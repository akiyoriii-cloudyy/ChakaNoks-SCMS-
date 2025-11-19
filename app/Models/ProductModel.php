<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'category',
        'unit',
        'price',
        'stock_qty',
        'min_stock',
        'max_stock',
        'branch_address',
        'expiry',
        'status',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    /**
     * Add a product
     */
    public function addProduct(array $data)
    {
        try {
            log_message('debug', 'ProductModel::addProduct data: ' . json_encode($data));
            
            // Ensure timestamps are set
            $now = date('Y-m-d H:i:s');
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            
            $result = $this->insert($data);
            
            if ($result !== false) {
                $insertId = $this->getInsertID();
                log_message('debug', 'Product inserted successfully with ID: ' . $insertId);
                return $insertId;
            }
            
            $error = $this->db->error();
            log_message('error', 'Product insert failed. DB Error: ' . json_encode($error));
            return false;
            
        } catch (\Exception $e) {
            log_message('error', 'Product insert exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get inventory with filters
     */
    public function getInventory(array $filters = []): array
    {
        $builder = $this->builder();

        // Branch filter
        if (!empty($filters['branch_address']) && $filters['branch_address'] !== 'all') {
            $builder->where('branch_address', $filters['branch_address']);
        }

        // Status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('status', $filters['status']);
        }

        // Date filter
        if (!empty($filters['date'])) {
            $builder->where('DATE(created_at)', $filters['date']);
        }

        // Search filter
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('name', $filters['search'])
                ->orLike('category', $filters['search'])
                ->orLike('branch_address', $filters['search'])
                ->groupEnd();
        }

        $items = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
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