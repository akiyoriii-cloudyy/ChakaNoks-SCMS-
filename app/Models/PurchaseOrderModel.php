<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table      = 'purchase_orders';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'order_number',
        'purchase_request_id',
        'supplier_id',
        'branch_id',
        'status',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'total_amount',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'supplier_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'order_date' => 'required|valid_date',
    ];

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $prefix = 'PO';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $date . '-' . $random;
    }

    /**
     * Create a new purchase order
     */
    public function createOrder(array $data): ?int
    {
        // Generate order number if not provided
        if (empty($data['order_number'])) {
            $data['order_number'] = $this->generateOrderNumber();
        }

        // Ensure unique order number
        while ($this->where('order_number', $data['order_number'])->first()) {
            $data['order_number'] = $this->generateOrderNumber();
        }

        $data['status'] = $data['status'] ?? 'pending';
        $data['order_date'] = $data['order_date'] ?? date('Y-m-d');
        $data['total_amount'] = $data['total_amount'] ?? 0.00;

        $insertId = $this->insert($data);
        return $insertId ? $this->getInsertID() : null;
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus(string $status): array
    {
        return $this->where('status', $status)
            ->orderBy('order_date', 'DESC')
            ->findAll();
    }

    /**
     * Get orders by supplier
     */
    public function getOrdersBySupplier(int $supplierId, array $filters = []): array
    {
        $builder = $this->where('supplier_id', $supplierId);

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(order_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(order_date) <=', $filters['date_to']);
        }

        return $builder->orderBy('order_date', 'DESC')->findAll();
    }

    /**
     * Get order with items and related data
     */
    public function getOrderWithDetails(int $id): ?array
    {
        $order = $this->find($id);
        
        if (!$order) {
            return null;
        }

        // Get items
        $db = \Config\Database::connect();
        $items = $db->table('purchase_order_items')
            ->where('purchase_order_id', $id)
            ->get()
            ->getResultArray();

        // Get product details for each item
        $productModel = new \App\Models\ProductModel();
        foreach ($items as &$item) {
            $product = $productModel->find($item['product_id']);
            $item['product'] = $product;
        }

        $order['items'] = $items;

        // Get supplier info
        $supplierModel = new \App\Models\SupplierModel();
        $order['supplier'] = $supplierModel->find($order['supplier_id']);

        // Get branch info
        $branchModel = new \App\Models\BranchModel();
        $order['branch'] = $branchModel->find($order['branch_id']);

        // Get delivery info if exists
        $delivery = $db->table('deliveries')
            ->where('purchase_order_id', $id)
            ->get()
            ->getRowArray();
        $order['delivery'] = $delivery;

        return $order;
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $id, string $status, ?string $actualDeliveryDate = null): bool
    {
        $data = ['status' => $status];

        if ($status === 'delivered' && $actualDeliveryDate) {
            $data['actual_delivery_date'] = $actualDeliveryDate;
        }

        return $this->update($id, $data);
    }

    /**
     * Track order - get current status and timeline
     */
    public function trackOrder(int $id): ?array
    {
        $order = $this->getOrderWithDetails($id);
        
        if (!$order) {
            return null;
        }

        // Calculate delivery status
        $today = date('Y-m-d');
        $expected = $order['expected_delivery_date'] ?? null;
        
        $order['tracking'] = [
            'current_status' => $order['status'],
            'is_overdue' => $expected && $today > $expected && $order['status'] !== 'delivered',
            'days_until_delivery' => $expected ? (strtotime($expected) - strtotime($today)) / 86400 : null,
            'is_delayed' => $expected && $order['actual_delivery_date'] && 
                           strtotime($order['actual_delivery_date']) > strtotime($expected),
        ];

        return $order;
    }
}

