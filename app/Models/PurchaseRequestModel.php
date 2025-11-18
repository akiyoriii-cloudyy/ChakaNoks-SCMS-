<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestModel extends Model
{
    protected $table      = 'purchase_requests';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'request_number',
        'branch_id',
        'requested_by',
        'status',
        'priority',
        'total_amount',
        'notes',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'branch_id' => 'required|integer',
        'requested_by' => 'required|integer',
        'status' => 'permit_empty|in_list[pending,approved,rejected,converted_to_po,cancelled]',
    ];

    /**
     * Generate unique request number
     */
    private function generateRequestNumber(): string
    {
        $prefix = 'PR';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $date . '-' . $random;
    }

    /**
     * Create a new purchase request
     */
    public function createRequest(array $data): ?int
    {
        // Generate request number if not provided
        if (empty($data['request_number'])) {
            $data['request_number'] = $this->generateRequestNumber();
        }

        // Ensure unique request number
        while ($this->where('request_number', $data['request_number'])->first()) {
            $data['request_number'] = $this->generateRequestNumber();
        }

        $data['status'] = $data['status'] ?? 'pending';
        $data['priority'] = $data['priority'] ?? 'normal';
        $data['total_amount'] = $data['total_amount'] ?? 0.00;

        $insertId = $this->insert($data);
        return $insertId ? $this->getInsertID() : null;
    }

    /**
     * Get requests by branch
     */
    public function getRequestsByBranch(int $branchId, array $filters = []): array
    {
        $builder = $this->where('branch_id', $branchId);

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $builder->where('priority', $filters['priority']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('DATE(created_at) <=', $filters['date_to']);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get pending requests
     */
    public function getPendingRequests(): array
    {
        return $this->where('status', 'pending')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Get request with items
     */
    public function getRequestWithItems(int $id): ?array
    {
        $request = $this->find($id);
        
        if (!$request) {
            return null;
        }

        // Get items
        $db = \Config\Database::connect();
        $items = $db->table('purchase_request_items')
            ->where('purchase_request_id', $id)
            ->get()
            ->getResultArray();

        // Get product details for each item
        $productModel = new \App\Models\ProductModel();
        foreach ($items as &$item) {
            $product = $productModel->find($item['product_id']);
            $item['product'] = $product;
        }

        $request['items'] = $items;

        // Get branch info
        $branchModel = new \App\Models\BranchModel();
        $request['branch'] = $branchModel->find($request['branch_id']);

        // Get requester info
        $userModel = new \App\Models\UserModel();
        $request['requester'] = $userModel->find($request['requested_by']);

        return $request;
    }

    /**
     * Update request status
     */
    public function updateStatus(int $id, string $status, ?int $approvedBy = null, ?string $rejectionReason = null): bool
    {
        $data = ['status' => $status];

        if ($status === 'approved' && $approvedBy) {
            $data['approved_by'] = $approvedBy;
            $data['approved_at'] = date('Y-m-d H:i:s');
        }

        if ($status === 'rejected' && $rejectionReason) {
            $data['rejection_reason'] = $rejectionReason;
        }

        return $this->update($id, $data);
    }

    /**
     * Approve request
     */
    public function approveRequest(int $id, int $approvedBy): bool
    {
        return $this->updateStatus($id, 'approved', $approvedBy);
    }

    /**
     * Reject request
     */
    public function rejectRequest(int $id, int $approvedBy, string $reason): bool
    {
        return $this->updateStatus($id, 'rejected', $approvedBy, $reason);
    }
}

