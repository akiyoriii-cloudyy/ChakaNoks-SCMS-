<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ProductCatalogModel;

class StockTransactionModel extends Model
{
    protected $table      = 'stock_transactions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'branch_id',
        'transaction_type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'stock_before',
        'stock_after',
        'batch_id',
        'batch_qty_before',
        'batch_qty_after',
        'branch_total_before',
        'branch_total_after',
        'transaction_number',
        'transaction_date',
        'is_new_stock',
        'is_expired',
        'is_old_stock',
        'expiry_date',
        'reason',
        'notes',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    /**
     * Generate unique transaction number
     */
    public function generateTransactionNumber(string $type = 'TRANS'): string
    {
        $date = date('Ymd');
        $time = date('His');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        $transactionNumber = "{$type}-{$date}-{$time}-{$random}";
        
        // Ensure uniqueness
        $count = 1;
        while ($this->where('transaction_number', $transactionNumber)->first()) {
            $transactionNumber = "{$type}-{$date}-{$time}-" . str_pad($random + $count, 3, '0', STR_PAD_LEFT);
            $count++;
        }
        
        return $transactionNumber;
    }

    /**
     * Record STOCK-IN transaction (NEW STOCK)
     * Note: productId can be from products table, but we need catalog_id for stock_transactions
     */
    public function recordStockIn(int $productId, int $quantity, ?int $referenceId = null, ?string $referenceType = null, ?int $createdBy = null, ?string $expiryDate = null): bool
    {
        try {
        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        
        if (!$product) {
                log_message('error', "StockTransactionModel::recordStockIn - Product not found: {$productId}");
                return false;
            }

            // Get or create product_catalog entry for this product
            $catalogModel = new ProductCatalogModel();
            $catalogProduct = $this->getOrCreateCatalogProduct($product, $catalogModel, $createdBy);
            
            if (!$catalogProduct) {
                log_message('error', "StockTransactionModel::recordStockIn - Failed to get/create catalog product for product: {$productId}");
            return false;
        }
            
            $catalogId = $catalogProduct['id'];

        $stockBefore = (int)($product['stock_qty'] ?? 0);
        $stockAfter = $stockBefore + $quantity;
        $branchId = $product['branch_id'] ?? null;
        $unitCost = $product['price'] ?? $product['cost'] ?? null;

            // Validate required fields
            if ($quantity <= 0) {
                log_message('error', "StockTransactionModel::recordStockIn - Invalid quantity: {$quantity} for product {$productId}");
                return false;
            }

        // Check if this is NEW STOCK (recent delivery, not expired)
        $isNewStock = true;
        if ($expiryDate) {
            $expiry = strtotime($expiryDate);
                if ($expiry !== false) {
            $daysUntilExpiry = ($expiry - time()) / 86400;
            // If expiring within 7 days, it's not "new" stock
            $isNewStock = $daysUntilExpiry > 7;
                }
        }

        // Generate transaction number
        $transactionNumber = $this->generateTransactionNumber('IN');

        $data = [
            'transaction_number' => $transactionNumber,
                'product_id' => $catalogId, // Use catalog ID, not products table ID
                'branch_id' => $branchId, // Can be null
            'transaction_type' => 'stock_in',
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'transaction_date' => date('Y-m-d H:i:s'),
            'is_new_stock' => $isNewStock ? 1 : 0,  // Convert boolean to integer
            'is_expired' => 0,                        // Always 0 for stock_in
            'is_old_stock' => 0,                      // Always 0 for stock_in
            'expiry_date' => $expiryDate,
            'created_by' => $createdBy,
        ];

            // Keep all values - null is allowed for nullable fields
            // Only ensure required fields are present

        $inserted = $this->insert($data);

            if ($inserted === false) {
                $error = $this->db->error();
                log_message('error', "StockTransactionModel::recordStockIn - Insert failed for product {$productId} (catalog: {$catalogId}): " . json_encode($error));
                log_message('error', "StockTransactionModel::recordStockIn - Data attempted: " . json_encode($data));
                return false;
            }

            // Update product stock
            $updateResult = $productModel->update($productId, ['stock_qty' => $stockAfter]);
            if (!$updateResult) {
                $updateError = $productModel->db->error();
                log_message('error', "StockTransactionModel::recordStockIn - Failed to update product stock for product {$productId}: " . json_encode($updateError));
                // Don't return false here - transaction was recorded, just stock update failed
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', "StockTransactionModel::recordStockIn - Exception for product {$productId}: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Record STOCK-OUT transaction (check EXPIRE? → OLDS)
     */
    public function recordStockOut(int $productId, int $quantity, ?int $referenceId = null, ?string $referenceType = null, ?int $createdBy = null, ?string $reason = null): bool
    {
        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        
        if (!$product) {
            return false;
        }

        $stockBefore = (int)($product['stock_qty'] ?? 0);
        $stockAfter = max(0, $stockBefore - $quantity);
        $branchId = $product['branch_id'] ?? null;
        $unitCost = $product['price'] ?? $product['cost'] ?? null;

        // Check EXPIRE? - is the stock expired?
        $isExpired = false;
        $isOldStock = false;
        $expiryDate = $product['expiry'] ?? null;

        if ($expiryDate) {
            $expiry = strtotime($expiryDate);
            $today = strtotime(date('Y-m-d'));
            
            if ($expiry < $today) {
                $isExpired = true;
                $isOldStock = true; // Expired stock is old stock
            } elseif (($expiry - $today) / 86400 <= 7) {
                // Expiring soon (within 7 days) - consider as old stock
                $isOldStock = true;
            }
        }

        // If reason contains "expired" or "old", mark accordingly
        if ($reason && (stripos($reason, 'expired') !== false || stripos($reason, 'old') !== false)) {
            $isOldStock = true;
            if (stripos($reason, 'expired') !== false) {
                $isExpired = true;
            }
        }

        // Generate transaction number
        $transactionNumber = $this->generateTransactionNumber('OUT');

        $data = [
            'transaction_number' => $transactionNumber,
            'product_id' => $productId,
            'branch_id' => $branchId,
            'transaction_type' => 'stock_out',
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'transaction_date' => date('Y-m-d H:i:s'),
            'is_new_stock' => 0,                      // Always 0 for stock_out
            'is_expired' => $isExpired ? 1 : 0,       // Convert boolean to integer
            'is_old_stock' => $isOldStock ? 1 : 0,    // Convert boolean to integer
            'expiry_date' => $expiryDate,
            'reason' => $reason,
            'notes' => $reason,
            'created_by' => $createdBy,
        ];

        $inserted = $this->insert($data);

        if ($inserted) {
            // Update product stock
            $productModel->update($productId, ['stock_qty' => $stockAfter]);
        }

        return $inserted !== false;
    }

    /**
     * Get stock transactions for a product
     */
    public function getProductTransactions(int $productId, ?string $type = null): array
    {
        $builder = $this->where('product_id', $productId);
        
        if ($type) {
            $builder->where('transaction_type', $type);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get NEW STOCK transactions
     */
    public function getNewStockTransactions(): array
    {
        return $this->where('is_new_stock', true)
            ->where('transaction_type', 'stock_in')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get OLD STOCK transactions (expired or expiring soon)
     */
    public function getOldStockTransactions(): array
    {
        return $this->where('is_old_stock', true)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get expired stock transactions
     */
    public function getExpiredStockTransactions(): array
    {
        return $this->where('is_expired', true)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get or create product_catalog entry for a product
     * Matches by name and category_id
     */
    private function getOrCreateCatalogProduct(array $product, ProductCatalogModel $catalogModel, ?int $createdBy = null): ?array
    {
        $name = $product['name'] ?? '';
        $categoryId = $product['category_id'] ?? null;
        
        if (empty($name)) {
            log_message('error', "StockTransactionModel::getOrCreateCatalogProduct - Product name is empty");
            return null;
        }

        // Try to find existing catalog entry by name and category
        $catalogProduct = $catalogModel
            ->where('name', $name)
            ->where('category_id', $categoryId)
            ->first();

        if ($catalogProduct) {
            return $catalogProduct;
        }

        // Create new catalog entry
        $sku = $catalogModel->generateSKU($name, $categoryId);
        $unit = $product['unit'] ?? 'pcs';
        $price = $product['price'] ?? 0;
        
        $catalogData = [
            'sku' => $sku,
            'name' => $name,
            'category_id' => $categoryId,
            'unit' => $unit,
            'selling_price' => $price,
            'standard_cost' => $price * 0.7, // Estimate 70% cost
            'status' => 'active',
            'created_by' => $createdBy,
        ];

        $catalogId = $catalogModel->insert($catalogData);
        
        if ($catalogId) {
            $newCatalogProduct = $catalogModel->find($catalogId);
            log_message('info', "Created new product_catalog entry ID {$catalogId} for product: {$name}");
            return $newCatalogProduct;
        }

        $error = $catalogModel->db->error();
        log_message('error', "Failed to create product_catalog entry: " . json_encode($error));
        return null;
    }
}

