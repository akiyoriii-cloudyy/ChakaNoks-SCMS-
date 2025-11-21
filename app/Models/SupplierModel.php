<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table      = 'suppliers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'payment_terms',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[150]',
        'email' => 'permit_empty|valid_email|max_length[100]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Supplier name is required',
            'min_length' => 'Supplier name must be at least 3 characters',
        ],
        'email' => [
            'valid_email' => 'Please provide a valid email address',
        ],
    ];

    /**
     * Get all active suppliers
     */
    public function getActiveSuppliers(): array
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Get supplier by ID with related data
     */
    public function getSupplierById(int $id): ?array
    {
        $supplier = $this->find($id);
        
        if (!$supplier) {
            return null;
        }

        // Get purchase orders count
        $db = \Config\Database::connect();
        $supplier['total_orders'] = $db->table('purchase_orders')
            ->where('supplier_id', $id)
            ->countAllResults(false);

        $supplier['pending_orders'] = $db->table('purchase_orders')
            ->where('supplier_id', $id)
            ->whereIn('status', ['pending', 'approved', 'sent_to_supplier', 'in_transit'])
            ->countAllResults(false);

        return $supplier;
    }

    /**
     * Get suppliers with performance metrics
     */
    public function getSuppliersWithPerformance(): array
    {
        $suppliers = $this->findAll();
        $db = \Config\Database::connect();

        foreach ($suppliers as &$supplier) {
            // Get delivery statistics
            $deliveries = $db->table('deliveries')
                ->where('supplier_id', $supplier['id'])
                ->get()
                ->getResultArray();

            $totalDeliveries = count($deliveries);
            $onTimeDeliveries = 0;
            $delayedDeliveries = 0;

            foreach ($deliveries as $delivery) {
                if ($delivery['status'] === 'delivered') {
                    if ($delivery['actual_delivery_date'] && $delivery['scheduled_date']) {
                        $actual = strtotime($delivery['actual_delivery_date']);
                        $scheduled = strtotime($delivery['scheduled_date']);
                        if ($actual <= $scheduled) {
                            $onTimeDeliveries++;
                        } else {
                            $delayedDeliveries++;
                        }
                    }
                }
            }

            $supplier['total_deliveries'] = $totalDeliveries;
            $supplier['on_time_rate'] = $totalDeliveries > 0 
                ? round(($onTimeDeliveries / $totalDeliveries) * 100, 2) 
                : 0;
            $supplier['delayed_deliveries'] = $delayedDeliveries;
        }

        return $suppliers;
    }
}

