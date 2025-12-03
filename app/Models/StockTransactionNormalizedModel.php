<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransactionNormalizedModel extends Model
{
    protected $table = 'stock_transactions';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'transaction_number',
        'batch_id',
        'product_id',
        'branch_id',
        'transaction_type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'batch_qty_before',
        'batch_qty_after',
        'branch_total_before',
        'branch_total_after',
        'reason',
        'notes',
        'created_by',
        'approved_by',
        'transaction_date',
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
    
    protected $validationRules = [
        'transaction_number' => 'required|is_unique[stock_transactions.transaction_number,id,{id}]',
        'batch_id' => 'required|integer',
        'product_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'transaction_type' => 'required',
        'quantity' => 'required|integer',
    ];

    /**
     * Record a stock-in transaction
     */
    public function recordStockIn(array $data): bool
    {
        $batchModel = new StockBatchModel();
        $batch = $batchModel->find($data['batch_id']);

        if (!$batch) {
            return false;
        }

        // Calculate stock levels
        $batchQtyBefore = $batch['quantity_current'];
        $batchQtyAfter = $batchQtyBefore + $data['quantity'];

        // Get branch total before
        $branchTotalBefore = $batchModel->getTotalStock($batch['product_id'], $batch['branch_id']);
        $branchTotalAfter = $branchTotalBefore + $data['quantity'];

        // Generate transaction number
        $transactionNumber = $this->generateTransactionNumber('IN');

        // Create transaction record
        $transactionData = array_merge($data, [
            'transaction_number' => $transactionNumber,
            'transaction_type' => 'stock_in',
            'product_id' => $batch['product_id'],
            'branch_id' => $batch['branch_id'],
            'batch_qty_before' => $batchQtyBefore,
            'batch_qty_after' => $batchQtyAfter,
            'branch_total_before' => $branchTotalBefore,
            'branch_total_after' => $branchTotalAfter,
            'transaction_date' => date('Y-m-d H:i:s'),
        ]);

        // Start transaction
        $this->db->transStart();

        // Insert transaction
        $this->insert($transactionData);

        // Update batch quantity
        $batchModel->update($batch['id'], [
            'quantity_current' => $batchQtyAfter,
            'status' => 'available',
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Record a stock-out transaction (FIFO/FEFO)
     */
    public function recordStockOut(int $productId, int $branchId, int $quantity, array $data = []): bool
    {
        $batchModel = new StockBatchModel();
        
        // Get available batches (FEFO if expiry dates exist, otherwise FIFO)
        $batches = $batchModel->getBatchesFEFO($productId, $branchId);
        if (empty($batches)) {
            $batches = $batchModel->getBatchesFIFO($productId, $branchId);
        }

        if (empty($batches)) {
            return false; // No stock available
        }

        // Calculate total available
        $totalAvailable = array_sum(array_column($batches, 'quantity_current'));
        if ($totalAvailable < $quantity) {
            return false; // Insufficient stock
        }

        $remainingQty = $quantity;
        $branchTotalBefore = $totalAvailable;

        $this->db->transStart();

        // Deduct from batches
        foreach ($batches as $batch) {
            if ($remainingQty <= 0) {
                break;
            }

            $deductQty = min($remainingQty, $batch['quantity_current']);
            $batchQtyBefore = $batch['quantity_current'];
            $batchQtyAfter = $batchQtyBefore - $deductQty;

            // Generate transaction number
            $transactionNumber = $this->generateTransactionNumber('OUT');

            // Record transaction
            $transactionData = array_merge($data, [
                'transaction_number' => $transactionNumber,
                'batch_id' => $batch['id'],
                'product_id' => $productId,
                'branch_id' => $branchId,
                'transaction_type' => 'stock_out',
                'quantity' => -$deductQty, // Negative for stock out
                'batch_qty_before' => $batchQtyBefore,
                'batch_qty_after' => $batchQtyAfter,
                'branch_total_before' => $branchTotalBefore,
                'branch_total_after' => $branchTotalBefore - $deductQty,
                'transaction_date' => date('Y-m-d H:i:s'),
            ]);

            $this->insert($transactionData);

            // Update batch
            $batchModel->reduceQuantity($batch['id'], $deductQty);

            $remainingQty -= $deductQty;
            $branchTotalBefore -= $deductQty;
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Generate unique transaction number
     */
    public function generateTransactionNumber(string $type = 'TRANS'): string
    {
        $date = date('Ymd');
        $time = date('His');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        return "{$type}-{$date}-{$time}-{$random}";
    }

    /**
     * Get transactions with details
     */
    public function getTransactionsWithDetails(array $filters = []): array
    {
        $builder = $this->builder();
        $builder->select('stock_transactions.*, product_catalog.name AS product_name, product_catalog.sku, branches.name AS branch_name, users.email AS created_by_email')
            ->join('product_catalog', 'product_catalog.id = stock_transactions.product_id')
            ->join('branches', 'branches.id = stock_transactions.branch_id')
            ->join('users', 'users.id = stock_transactions.created_by', 'left');

        if (!empty($filters['product_id'])) {
            $builder->where('stock_transactions.product_id', $filters['product_id']);
        }

        if (!empty($filters['branch_id'])) {
            $builder->where('stock_transactions.branch_id', $filters['branch_id']);
        }

        if (!empty($filters['transaction_type'])) {
            $builder->where('stock_transactions.transaction_type', $filters['transaction_type']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(stock_transactions.transaction_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(stock_transactions.transaction_date) <=', $filters['date_to']);
        }

        return $builder->orderBy('stock_transactions.transaction_date', 'DESC')->get()->getResultArray();
    }
}

