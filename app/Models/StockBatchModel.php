<?php

namespace App\Models;

use CodeIgniter\Model;

class StockBatchModel extends Model
{
    protected $table = 'stock_batches';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'batch_number',
        'product_id',
        'branch_id',
        'supplier_id',
        'quantity_initial',
        'quantity_current',
        'unit_cost',
        'manufacture_date',
        'expiry_date',
        'received_date',
        'status',
        'purchase_order_id',
        'delivery_id',
        'notes',
        'created_by',
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
    
    protected $validationRules = [
        'batch_number' => 'required|is_unique[stock_batches.batch_number,id,{id}]',
        'product_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'quantity_initial' => 'required|integer',
        'quantity_current' => 'required|integer',
    ];

    /**
     * Get batches by product and branch (FIFO order)
     */
    public function getBatchesFIFO(int $productId, int $branchId, string $status = 'available'): array
    {
        return $this->where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->where('status', $status)
            ->where('quantity_current >', 0)
            ->orderBy('received_date', 'ASC')
            ->findAll();
    }

    /**
     * Get batches by product and branch (FEFO order - First Expire, First Out)
     */
    public function getBatchesFEFO(int $productId, int $branchId, string $status = 'available'): array
    {
        return $this->where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->where('status', $status)
            ->where('quantity_current >', 0)
            ->where('expiry_date IS NOT NULL', null, false)
            ->orderBy('expiry_date', 'ASC')
            ->findAll();
    }

    /**
     * Get total stock for a product at a branch
     */
    public function getTotalStock(int $productId, int $branchId): int
    {
        $result = $this->selectSum('quantity_current')
            ->where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->where('status', 'available')
            ->first();

        return (int)($result['quantity_current'] ?? 0);
    }

    /**
     * Generate unique batch number
     */
    public function generateBatchNumber(int $productId, int $branchId): string
    {
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $batchNumber = "BATCH-{$branchId}-{$productId}-{$date}-{$random}";

        // Ensure uniqueness
        $count = 1;
        $originalBatchNumber = $batchNumber;
        while ($this->where('batch_number', $batchNumber)->first()) {
            $batchNumber = $originalBatchNumber . '-' . $count++;
        }

        return $batchNumber;
    }

    /**
     * Get expiring batches (within specified days)
     */
    public function getExpiringBatches(int $days = 30): array
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));

        return $this->select('stock_batches.*, product_catalog.name AS product_name, branches.name AS branch_name')
            ->join('product_catalog', 'product_catalog.id = stock_batches.product_id')
            ->join('branches', 'branches.id = stock_batches.branch_id')
            ->where('stock_batches.status', 'available')
            ->where('stock_batches.quantity_current >', 0)
            ->where('stock_batches.expiry_date <=', $futureDate)
            ->orderBy('stock_batches.expiry_date', 'ASC')
            ->findAll();
    }

    /**
     * Mark expired batches
     */
    public function markExpiredBatches(): int
    {
        $today = date('Y-m-d');
        
        return $this->where('expiry_date <', $today)
            ->where('status', 'available')
            ->set('status', 'expired')
            ->update();
    }

    /**
     * Reduce batch quantity (for stock out)
     */
    public function reduceQuantity(int $batchId, int $quantity): bool
    {
        $batch = $this->find($batchId);
        if (!$batch || $batch['quantity_current'] < $quantity) {
            return false;
        }

        $newQuantity = $batch['quantity_current'] - $quantity;
        $updates = ['quantity_current' => $newQuantity];

        // Auto-mark as depleted if quantity reaches zero
        if ($newQuantity <= 0) {
            $updates['status'] = 'depleted';
        }

        return $this->update($batchId, $updates);
    }

    /**
     * Get batch with product and branch info
     */
    public function getBatchWithDetails(int $batchId): ?array
    {
        return $this->select('stock_batches.*, product_catalog.name AS product_name, product_catalog.sku, branches.name AS branch_name, suppliers.name AS supplier_name')
            ->join('product_catalog', 'product_catalog.id = stock_batches.product_id')
            ->join('branches', 'branches.id = stock_batches.branch_id')
            ->join('suppliers', 'suppliers.id = stock_batches.supplier_id', 'left')
            ->where('stock_batches.id', $batchId)
            ->first();
    }
}

