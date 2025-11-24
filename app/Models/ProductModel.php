<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'branch_id',
        'name',
        'category_id',
        'unit',
        'price',
        'stock_qty',
        'min_stock',
        'max_stock',
        'expiry',
        'status',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    /**
     * Get inventory with filters
     */
    public function getInventory(array $filters = []): array
    {
        $builder = $this->builder();
        $builder->select('products.*, branches.name AS branch_name, branches.code AS branch_code, branches.address AS branch_location, categories.name AS category');
        $builder->join('branches', 'branches.id = products.branch_id', 'left');
        $builder->join('categories', 'categories.id = products.category_id', 'left');

        // Branch filter (by ID only - normalized)
        if (!empty($filters['branch_id']) && $filters['branch_id'] !== 'all') {
            $builder->where('products.branch_id', (int)$filters['branch_id']);
        }

        // Status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('products.status', $filters['status']);
        }

        // Date filter
        if (!empty($filters['date'])) {
            $builder->where('DATE(products.created_at)', $filters['date']);
        }

        // Search filter
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('products.name', $filters['search'])
                ->orLike('categories.name', $filters['search'])
                ->orLike('branches.name', $filters['search'])
                ->orLike('branches.address', $filters['search'])
                ->groupEnd();
        }

        $items = $builder->orderBy('products.created_at', 'DESC')->get()->getResultArray();
        
        // Process each item for accurate data
        foreach ($items as &$item) {
            // Calculate accurate status
            $item['status'] = $this->calculateStatus($item);
            
            // Process timestamps for accuracy
            $item = $this->processTimestamps($item);
            
            // Ensure proper data types
            $item['stock_qty'] = (int)($item['stock_qty'] ?? 0);
            $item['min_stock'] = (int)($item['min_stock'] ?? 0);
            $item['max_stock'] = (int)($item['max_stock'] ?? 0);
            $item['price'] = (float)($item['price'] ?? 0);

            // Provide a consistent branch label for UI (normalized - from branches table)
            $item['branch_label'] = $item['branch_name'] ?? ($item['branch_code'] ?? 'Unassigned');
        }
        
        return $items;
    }

    /**
     * Process timestamps to ensure accuracy
     */
    private function processTimestamps(array $item): array
    {
        $currentTime = date('Y-m-d H:i:s');
        
        // If no created_at, set it to current time
        if (empty($item['created_at'])) {
            $item['created_at'] = $currentTime;
        }
        
        // If no updated_at, use created_at
        if (empty($item['updated_at'])) {
            $item['updated_at'] = $item['created_at'];
        }
        
        // Format timestamps for display
        $item['created_at_formatted'] = date('Y-m-d H:i:s', strtotime($item['created_at']));
        $item['updated_at_formatted'] = date('Y-m-d H:i:s', strtotime($item['updated_at']));
        
        // Calculate time ago for display
        $item['created_ago'] = $this->timeAgo($item['created_at']);
        $item['updated_ago'] = $this->timeAgo($item['updated_at']);
        
        return $item;
    }

    /**
     * Calculate time ago string
     */
    private function timeAgo($datetime): string
    {
        if (empty($datetime)) return 'Unknown';
        
        $time = strtotime($datetime);
        if ($time === false) return 'Invalid date';
        
        $diff = time() - $time;
        
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hour ago';
        if ($diff < 604800) return floor($diff / 86400) . ' day ago';
        
        return date('M j, Y', $time);
    }

    /**
     * Calculate item status based on stock and expiry
     */
    private function calculateStatus(array $item): string
    {
        $stock = (int)($item['stock_qty'] ?? 0);
        $minStock = (int)($item['min_stock'] ?? 0);
        $maxStock = (int)($item['max_stock'] ?? 0);
        $expiry = $item['expiry'] ?? null;
        
        // Check if expired
        if ($expiry) {
            $expiryDate = new \DateTime($expiry);
            $today = new \DateTime();
            $today->setTime(0, 0, 0);
            
            if ($expiryDate < $today) {
                return 'Expired';
            }
            
            // Check if expiring soon (within 7 days)
            $daysUntilExpiry = $today->diff($expiryDate)->days;
            if ($daysUntilExpiry <= 7) {
                return 'Expiring Soon';
            }
        }
        
        // Check stock levels with improved logic
        if ($stock <= 0) return 'Out of Stock';
        
        // If min and max are the same, treat as good if stock equals that value
        if ($minStock == $maxStock) {
            if ($stock == $minStock) return 'Good';
            if ($stock < $minStock) return 'Critical';
            return 'Good'; // Stock is above the set level
        }
        
        // Calculate the range between min and max
        $range = $maxStock - $minStock;
        
        // If stock is at or below minimum, it's critical
        if ($stock <= $minStock) return 'Critical';
        
        // If stock is in the lower 30% of the range, it's low stock
        if ($stock < $minStock + ($range * 0.3)) return 'Low Stock';
        
        // If stock is at or near maximum, it's good
        if ($stock >= $maxStock * 0.8) return 'Good';
        
        // If stock is in the middle range, it's good
        return 'Good';
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $id, int $qty): bool
    {
        return $this->update($id, [
            'stock_qty' => $qty,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}