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
        'rejection_reason',
        'selected_supplier_id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [];
    
    protected $skipValidation = false;

    /**
     * Generate unique request number
     */
    private function generateRequestNumber(): string
    {
        $prefix = 'PR';
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $requestNumber = $prefix . '-' . $date . '-' . $random;
        log_message('debug', 'Generated request number: ' . $requestNumber);
        
        // Check if request number already exists
        $db = \Config\Database::connect();
        $exists = $db->table('purchase_requests')
            ->where('request_number', $requestNumber)
            ->countAllResults() > 0;
        
        if ($exists) {
            log_message('debug', 'Request number ' . $requestNumber . ' already exists, regenerating...');
            return $this->generateRequestNumber();
        }
        
        return $requestNumber;
    }

    /**
     * Create a new purchase request
     */
    public function createRequest(array $data): ?int
    {
        log_message('debug', 'PurchaseRequestModel::createRequest() called with data: ' . json_encode($data));
        
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
        $data['total_amount'] = (float)($data['total_amount'] ?? 0.00);
        
        // Ensure timestamps are set
        if (empty($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (empty($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        log_message('debug', 'Final data to insert: ' . json_encode($data));
        
        try {
            // Use direct database insert to bypass model validation
            $db = \Config\Database::connect();
            
            // Temporarily disable foreign key checks
            $db->query('SET FOREIGN_KEY_CHECKS=0');
            
            $result = $db->table('purchase_requests')->insert($data);
            
            // Re-enable foreign key checks
            $db->query('SET FOREIGN_KEY_CHECKS=1');
            
            if ($result === false) {
                $dbError = $db->error();
                log_message('error', 'Direct insert failed. DB error: ' . json_encode($dbError));
                return null;
            }
            
            $id = $db->insertID();
            log_message('info', 'Purchase request created successfully with ID: ' . $id);
            return $id;
        } catch (\Exception $e) {
            log_message('error', 'Exception in createRequest: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Get requests by branch
     */
    public function getRequestsByBranch(int $branchId, array $filters = []): array
    {
        log_message('debug', 'Getting requests for branch ' . $branchId . ' with filters: ' . json_encode($filters));
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

        $requests = $builder->orderBy('created_at', 'DESC')->findAll();
        log_message('debug', 'Found ' . count($requests) . ' requests for branch ' . $branchId);
        return $requests;
    }

    /**
     * Get pending requests
     */
    public function getPendingRequests(): array
    {
        log_message('debug', 'Getting pending requests');
        $requests = $this->where('status', 'pending')
            ->orderBy('priority', 'DESC')
            ->orderBy('created_at', 'ASC')
            ->findAll();
        log_message('debug', 'Found ' . count($requests) . ' pending requests');
        return $requests;
    }

    /**
     * Get request with items
     */
    public function getRequestWithItems(int $id): ?array
    {
        log_message('debug', 'Getting request ' . $id . ' with items');
        $request = $this->find($id);
        
        if (!$request) {
            log_message('warn', 'Request ' . $id . ' not found');
            return null;
        }

        // Get items
        $db = \Config\Database::connect();
        $items = $db->table('purchase_request_items')
            ->where('purchase_request_id', $id)
            ->get()
            ->getResultArray();
        
        log_message('debug', 'Found ' . count($items) . ' items for request ' . $id);
        
        if (empty($items)) {
            log_message('warning', 'Request ' . $id . ' has no items in purchase_request_items table');
            $request['items'] = [];
            return $request;
        }

        // Get product details for each item
        $productModel = new \App\Models\ProductModel();
        $validItems = [];
        foreach ($items as $item) {
            $product = $productModel->find($item['product_id']);
            if ($product) {
                $item['product'] = $product;
                $validItems[] = $item;
                log_message('debug', 'Loaded product ' . $item['product_id'] . ' (' . ($product['name'] ?? 'unknown') . ') for item');
            } else {
                log_message('warning', 'Product ID ' . $item['product_id'] . ' not found for purchase request item ' . $item['id']);
                // Still include the item even if product is missing (product might have been deleted)
                $item['product'] = null;
                $validItems[] = $item;
            }
        }

        $request['items'] = $validItems;

        // Get branch info
        $branchModel = new \App\Models\BranchModel();
        $request['branch'] = $branchModel->find($request['branch_id']);
        log_message('debug', 'Loaded branch ' . $request['branch_id']);

        // Get requester info
        $userModel = new \App\Models\UserModel();
        $request['requester'] = $userModel->find($request['requested_by']);
        log_message('debug', 'Loaded requester ' . $request['requested_by']);

        log_message('info', 'Request ' . $id . ' loaded successfully with ' . count($items) . ' items');
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

        try {
            $result = $this->update($id, $data);
            log_message('debug', 'Updated request ' . $id . ' status to: ' . $status);
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error updating request status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Approve request
     */
    public function approveRequest(int $id, int $approvedBy): bool
    {
        log_message('debug', 'Approving request ' . $id . ' by user ' . $approvedBy);
        return $this->updateStatus($id, 'approved', $approvedBy);
    }

    /**
     * Reject request
     */
    public function rejectRequest(int $id, int $approvedBy, string $reason): bool
    {
        log_message('debug', 'Rejecting request ' . $id . ' by user ' . $approvedBy . ' with reason: ' . $reason);
        return $this->updateStatus($id, 'rejected', $approvedBy, $reason);
    }
}

