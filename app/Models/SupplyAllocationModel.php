<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplyAllocationModel extends Model
{
    protected $table = 'supply_allocations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id',
        'product_id',
        'allocated_qty',
        'unit_price',
        'total_amount',
        'status',
        'allocation_date',
        'delivery_date',
        'notes',
        'created_by'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Override insert to set defaults and calculate total_amount
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            // Set defaults
            $data['status'] = $data['status'] ?? 'pending';
            $data['allocation_date'] = $data['allocation_date'] ?? date('Y-m-d');
            
            // Calculate total_amount if not provided
            if (empty($data['total_amount']) && !empty($data['allocated_qty']) && !empty($data['unit_price'])) {
                $data['total_amount'] = $data['allocated_qty'] * $data['unit_price'];
            }
        }
        
        return parent::insert($data, $returnID);
    }

    public function getAllocationsWithDetails()
    {
        return $this->db->table('supply_allocations sa')
            ->select('sa.*, b.name as branch_name, b.code as branch_code, p.name as product_name')
            ->join('branches b', 'b.id = sa.branch_id', 'left')
            ->join('products p', 'p.id = sa.product_id', 'left')
            ->orderBy('sa.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAllocationStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'pending' => $this->where('status', 'pending')->countAllResults(false),
            'approved' => $this->where('status', 'approved')->countAllResults(false),
            'shipped' => $this->where('status', 'shipped')->countAllResults(false),
            'delivered' => $this->where('status', 'delivered')->countAllResults(false),
            'total_value' => $this->selectSum('total_amount')->get()->getRow()->total_amount ?? 0,
        ];
        return $stats;
    }

    public function getPendingAllocations()
    {
        return $this->db->table('supply_allocations sa')
            ->select('sa.*, b.name as branch_name, b.code as branch_code, p.name as product_name')
            ->join('branches b', 'b.id = sa.branch_id', 'left')
            ->join('products p', 'p.id = sa.product_id', 'left')
            ->where('sa.status', 'pending')
            ->orderBy('sa.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}

