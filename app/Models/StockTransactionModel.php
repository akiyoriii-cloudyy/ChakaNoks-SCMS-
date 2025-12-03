<?php

namespace App\Models;

use CodeIgniter\Model;

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
     */
    public function recordStockIn(int $productId, int $quantity, ?int $referenceId = null, ?string $referenceType = null, ?int $createdBy = null, ?string $expiryDate = null): bool
    {
        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        
        if (!$product) {
            return false;
        }

        $stockBefore = (int)($product['stock_qty'] ?? 0);
        $stockAfter = $stockBefore + $quantity;
        $branchId = $product['branch_id'] ?? null;
        $unitCost = $product['price'] ?? $product['cost'] ?? null;

        // Check if this is NEW STOCK (recent delivery, not expired)
        $isNewStock = true;
        if ($expiryDate) {
            $expiry = strtotime($expiryDate);
            $daysUntilExpiry = ($expiry - time()) / 86400;
            // If expiring within 7 days, it's not "new" stock
            $isNewStock = $daysUntilExpiry > 7;
        }

        // Generate transaction number
        $transactionNumber = $this->generateTransactionNumber('IN');

        $data = [
            'transaction_number' => $transactionNumber,
            'product_id' => $productId,
            'branch_id' => $branchId,
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

        $inserted = $this->insert($data);

        if ($inserted) {
            // Update product stock
            $productModel->update($productId, ['stock_qty' => $stockAfter]);
        }

        return $inserted !== false;
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
}

