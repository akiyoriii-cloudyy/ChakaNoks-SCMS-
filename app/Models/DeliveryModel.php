<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table      = 'deliveries';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'delivery_number',
        'purchase_order_id',
        'supplier_id',
        'branch_id',
        'scheduled_by',
        'status',
        'scheduled_date',
        'actual_delivery_date',
        'driver_name',
        'vehicle_info',
        'received_by',
        'received_at',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'purchase_order_id' => 'required|integer',
        'supplier_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'scheduled_date' => 'required|valid_date',
    ];

    /**
     * Generate unique delivery number
     */
    private function generateDeliveryNumber(): string
    {
        $prefix = 'DEL';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $date . '-' . $random;
    }

    /**
     * Override insert to auto-generate delivery_number
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && empty($data['delivery_number'])) {
            $data['delivery_number'] = $this->generateDeliveryNumber();
            
            // Ensure unique delivery number
            while ($this->where('delivery_number', $data['delivery_number'])->first()) {
                $data['delivery_number'] = $this->generateDeliveryNumber();
            }
        }
        
        // Set defaults
        if (is_array($data)) {
            $data['status'] = $data['status'] ?? 'scheduled';
            $data['scheduled_date'] = $data['scheduled_date'] ?? date('Y-m-d');
        }
        
        return parent::insert($data, $returnID);
    }

    /**
     * Schedule a delivery
     */
    public function scheduleDelivery(array $data): ?int
    {
        // insert() method now handles delivery_number generation automatically
        $insertId = $this->insert($data);
        
        if ($insertId) {
            $deliveryId = $this->getInsertID();
            
            // Create delivery items from purchase order items
            if (!empty($data['purchase_order_id'])) {
                $this->createDeliveryItemsFromPO($deliveryId, $data['purchase_order_id']);
            }
            
            return $deliveryId;
        }

        return null;
    }

    /**
     * Create delivery items from purchase order
     */
    private function createDeliveryItemsFromPO(int $deliveryId, int $purchaseOrderId): void
    {
        $db = \Config\Database::connect();
        
        // Get PO items
        $poItems = $db->table('purchase_order_items')
            ->where('purchase_order_id', $purchaseOrderId)
            ->get()
            ->getResultArray();

        // Create delivery items
        foreach ($poItems as $poItem) {
            $db->table('delivery_items')->insert([
                'delivery_id' => $deliveryId,
                'product_id' => $poItem['product_id'],
                'expected_quantity' => $poItem['quantity'],
                'received_quantity' => 0,
                'condition_status' => 'good',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Update delivery status
     */
    public function updateDeliveryStatus(int $id, string $status, ?string $actualDeliveryDate = null, ?int $receivedBy = null): bool
    {
        // Get old values for audit trail
        $oldDelivery = $this->find($id);
        if (!$oldDelivery) {
            return false;
        }

        $data = ['status' => $status];

        if ($status === 'delivered' || $status === 'partial_delivery') {
            if ($actualDeliveryDate) {
                $data['actual_delivery_date'] = $actualDeliveryDate;
            } else {
                $data['actual_delivery_date'] = date('Y-m-d');
            }
            
            if ($receivedBy) {
                $data['received_by'] = $receivedBy;
                $data['received_at'] = date('Y-m-d H:i:s');
            }
        }

        $updated = $this->update($id, $data);

        // Log to audit trail if update successful
        if ($updated) {
            $auditTrailModel = new \App\Models\AuditTrailModel();
            $newDelivery = $this->find($id);
            
            $oldValues = [
                'status' => $oldDelivery['status'],
                'actual_delivery_date' => $oldDelivery['actual_delivery_date'],
                'received_by' => $oldDelivery['received_by'],
                'received_at' => $oldDelivery['received_at']
            ];
            
            $newValues = [
                'status' => $newDelivery['status'],
                'actual_delivery_date' => $newDelivery['actual_delivery_date'],
                'received_by' => $newDelivery['received_by'],
                'received_at' => $newDelivery['received_at']
            ];

            $auditTrailModel->logChange(
                'deliveries',
                $id,
                'STATUS_UPDATE',
                $oldValues,
                $newValues,
                $receivedBy
            );
        }

        return $updated;
    }

    /**
     * Track delivery - get current status and details
     */
    public function trackDelivery(int $id): ?array
    {
        $delivery = $this->find($id);
        
        if (!$delivery) {
            return null;
        }

        // Get delivery items
        $db = \Config\Database::connect();
        $items = $db->table('delivery_items')
            ->where('delivery_id', $id)
            ->get()
            ->getResultArray();

        // Get product details
        $productModel = new \App\Models\ProductModel();
        foreach ($items as &$item) {
            $product = $productModel->find($item['product_id']);
            $item['product'] = $product;
        }

        $delivery['items'] = $items;

        // Get purchase order info
        $poModel = new \App\Models\PurchaseOrderModel();
        $delivery['purchase_order'] = $poModel->find($delivery['purchase_order_id']);

        // Get supplier info
        $supplierModel = new \App\Models\SupplierModel();
        $delivery['supplier'] = $supplierModel->find($delivery['supplier_id']);

        // Get branch info
        $branchModel = new \App\Models\BranchModel();
        $delivery['branch'] = $branchModel->find($delivery['branch_id']);

        // Get user info for scheduled_by
        if (!empty($delivery['scheduled_by'])) {
            $userModel = new \App\Models\UserModel();
            $scheduledByUser = $userModel->find($delivery['scheduled_by']);
            $delivery['scheduled_by_user'] = $scheduledByUser ? [
                'id' => $scheduledByUser['id'],
                'email' => $scheduledByUser['email'] ?? null,
                'username' => $scheduledByUser['username'] ?? null,
                'role' => $scheduledByUser['role'] ?? null
            ] : null;
        } else {
            $delivery['scheduled_by_user'] = null;
        }

        // Get user info for received_by
        if (!empty($delivery['received_by'])) {
            $userModel = new \App\Models\UserModel();
            $receivedByUser = $userModel->find($delivery['received_by']);
            $delivery['received_by_user'] = $receivedByUser ? [
                'id' => $receivedByUser['id'],
                'email' => $receivedByUser['email'] ?? null,
                'username' => $receivedByUser['username'] ?? null,
                'role' => $receivedByUser['role'] ?? null
            ] : null;
        } else {
            $delivery['received_by_user'] = null;
        }

        // Calculate tracking info
        $today = date('Y-m-d');
        $scheduled = $delivery['scheduled_date'];
        
        $delivery['tracking'] = [
            'current_status' => $delivery['status'],
            'is_overdue' => $scheduled && $today > $scheduled && !in_array($delivery['status'], ['delivered', 'partial_delivery']),
            'days_until_delivery' => $scheduled ? (strtotime($scheduled) - strtotime($today)) / 86400 : null,
            'is_delayed' => $scheduled && $delivery['actual_delivery_date'] && 
                           strtotime($delivery['actual_delivery_date']) > strtotime($scheduled),
        ];

        return $delivery;
    }

    /**
     * Get deliveries by branch
     */
    public function getDeliveriesByBranch(int $branchId, array $filters = []): array
    {
        $builder = $this->where('branch_id', $branchId);

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(scheduled_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(scheduled_date) <=', $filters['date_to']);
        }

        return $builder->orderBy('scheduled_date', 'DESC')->findAll();
    }

    /**
     * Get deliveries by supplier
     */
    public function getDeliveriesBySupplier(int $supplierId, array $filters = []): array
    {
        $builder = $this->where('supplier_id', $supplierId);

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(scheduled_date) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(scheduled_date) <=', $filters['date_to']);
        }

        return $builder->orderBy('scheduled_date', 'DESC')->findAll();
    }
}

