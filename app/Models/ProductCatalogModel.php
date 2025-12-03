<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCatalogModel extends Model
{
    protected $table = 'product_catalog';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'sku',
        'name',
        'category_id',
        'unit',
        'standard_cost',
        'selling_price',
        'min_order_qty',
        'reorder_point',
        'description',
        'status',
        'created_by',
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
    
    protected $validationRules = [
        'sku' => 'required|is_unique[product_catalog.sku,id,{id}]',
        'name' => 'required|max_length[150]',
        'unit' => 'required',
    ];

    /**
     * Get product catalog with category info
     */
    public function getProductsWithCategory(array $filters = []): array
    {
        $builder = $this->builder();
        $builder->select('product_catalog.*, categories.name AS category_name');
        $builder->join('categories', 'categories.id = product_catalog.category_id', 'left');

        // Status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('product_catalog.status', $filters['status']);
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $builder->where('product_catalog.category_id', $filters['category_id']);
        }

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('product_catalog.name', $filters['search'])
                ->orLike('product_catalog.sku', $filters['search'])
                ->orLike('categories.name', $filters['search'])
                ->groupEnd();
        }

        return $builder->orderBy('product_catalog.name', 'ASC')->get()->getResultArray();
    }

    /**
     * Get product with total stock across all branches
     */
    public function getProductWithStock(int $productId): ?array
    {
        $product = $this->find($productId);
        if (!$product) {
            return null;
        }

        // Get total stock from batches
        $stockInfo = $this->db->query("
            SELECT 
                SUM(quantity_current) AS total_stock,
                COUNT(DISTINCT branch_id) AS branches_with_stock,
                MIN(expiry_date) AS earliest_expiry
            FROM stock_batches
            WHERE product_id = ? AND status = 'available'
        ", [$productId])->getRowArray();

        $product['total_stock'] = $stockInfo['total_stock'] ?? 0;
        $product['branches_with_stock'] = $stockInfo['branches_with_stock'] ?? 0;
        $product['earliest_expiry'] = $stockInfo['earliest_expiry'];

        return $product;
    }

    /**
     * Generate unique SKU
     */
    public function generateSKU(string $name, ?int $categoryId = null): string
    {
        $categoryCode = $categoryId ? sprintf('C%02d', $categoryId) : 'C00';
        $nameHash = strtoupper(substr(md5($name . time()), 0, 6));
        $sku = $categoryCode . '-' . $nameHash;

        // Ensure uniqueness
        $count = 1;
        $originalSKU = $sku;
        while ($this->where('sku', $sku)->first()) {
            $sku = $originalSKU . '-' . $count++;
        }

        return $sku;
    }

    /**
     * Get products needing reorder (stock below reorder point)
     */
    public function getProductsNeedingReorder(): array
    {
        return $this->db->query("
            SELECT 
                pc.*,
                COALESCE(SUM(sb.quantity_current), 0) AS total_stock,
                pc.reorder_point
            FROM product_catalog pc
            LEFT JOIN stock_batches sb ON sb.product_id = pc.id AND sb.status = 'available'
            WHERE pc.status = 'active' AND pc.reorder_point IS NOT NULL
            GROUP BY pc.id
            HAVING total_stock < pc.reorder_point
            ORDER BY (pc.reorder_point - total_stock) DESC
        ")->getResultArray();
    }
}

