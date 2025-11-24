<?php

namespace App\Controllers;

use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\AccountsPayableModel;
use App\Models\SupplierModel;
use Config\Database;

class PurchaseController extends BaseController
{
    protected $db;
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $productModel;
    protected $branchModel;
    protected $accountsPayableModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->accountsPayableModel = new AccountsPayableModel();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Create a new purchase request
     */
    public function createRequest()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        // Get user ID - validate it exists
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID not found in session']);
        }

        $branchId = $session->get('branch_id');
        if (!$branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch not assigned']);
        }

        $json = $this->request->getJSON(true);
        $items = $json['items'] ?? null;
        $branchName = $json['branch'] ?? null;
        $priority = $json['priority'] ?? 'normal';
        $notes = $json['notes'] ?? '';
        
        if (!$branchName) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch is required']);
        }

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Items are required']);
        }

        // Extract branch code from branch name (e.g., "AGDAO Branch, Davao City" -> "AGDAO")
        $branchCode = strtoupper(explode(' ', $branchName)[0]);
        
        // Get branch ID from database using branch code
        $branchRecord = $this->db->table('branches')
            ->where('code', $branchCode)
            ->get()
            ->getRow();
        
        if (!$branchRecord) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid branch selected']);
        }
        
        $branchId = $branchRecord->id;

        $totalAmount = 0;
        foreach ($items as $item) {
            $quantity = (float)($item['quantity'] ?? 0);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            $totalAmount += $quantity * $unitPrice;
        }

        try {
            // Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');
            
            // Generate request number
            $requestNumber = 'PR-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Insert purchase request
            $requestData = [
                'request_number' => $requestNumber,
                'branch_id' => (int)$branchId,
                'requested_by' => (int)$userId,
                'priority' => $priority,
                'total_amount' => (float)$totalAmount,
                'notes' => $notes,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $insertResult = $this->db->table('purchase_requests')->insert($requestData);
            
            if (!$insertResult) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $error = $this->db->error();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create purchase request', 'db_error' => $error]);
            }
            
            $requestId = $this->db->insertID();
            
            // Insert items
            foreach ($items as $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $unitPrice = (float)($item['unit_price'] ?? 0);
                
                if ($productId > 0 && $quantity > 0) {
                    $this->db->table('purchase_request_items')->insert([
                        'purchase_request_id' => (int)$requestId,
                        'product_id' => (int)$productId,
                        'quantity' => (int)$quantity,
                        'unit_price' => (float)$unitPrice,
                        'subtotal' => (float)($quantity * $unitPrice),
                        'notes' => $item['notes'] ?? '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            
            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase request created successfully',
                'request_id' => $requestId,
                'request_number' => $requestNumber,
                'branch' => $branchName,
                'items_count' => count($items),
                'total_amount' => $totalAmount
            ]);
        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Get purchase requests
     */
    public function getRequests()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $userId = $session->get('user_id');
        $role = $session->get('role');
        $status = $this->request->getGet('status');
        $priority = $this->request->getGet('priority');

        $filters = [];
        if ($status) $filters['status'] = $status;
        if ($priority) $filters['priority'] = $priority;

        if (in_array($role, ['central_admin', 'superadmin'])) {
            // Central admin can see all requests
            $requests = $this->purchaseRequestModel->findAll();
        } else {
            // Branch managers see requests they created
            $builder = $this->purchaseRequestModel->where('requested_by', $userId);
            
            if (!empty($filters['status'])) {
                $builder->where('status', $filters['status']);
            }
            if (!empty($filters['priority'])) {
                $builder->where('priority', $filters['priority']);
            }
            
            $requests = $builder->orderBy('created_at', 'DESC')->findAll();
        }

        // Get full details for each request
        foreach ($requests as &$request) {
            $request = $this->purchaseRequestModel->getRequestWithItems($request['id']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'requests' => $requests
        ]);
    }

    /**
     * Get pending requests (for central admin)
     */
    public function getPendingRequests()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $requests = $this->purchaseRequestModel->getPendingRequests();

        // Get full details
        foreach ($requests as &$request) {
            $request = $this->purchaseRequestModel->getRequestWithItems($request['id']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'requests' => $requests
        ]);
    }

    /**
     * Approve purchase request and automatically create PO
     */
    public function approveRequest($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request ID required']);
        }

        try {
            // Step 1: Approve the purchase request
            $approved = $this->purchaseRequestModel->approveRequest((int)$id, $session->get('user_id'));

            if (!$approved) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve request']);
            }

            // Step 2: Get the approved request with items
            $requestWithItems = $this->purchaseRequestModel->getRequestWithItems((int)$id);

            if (!$requestWithItems || empty($requestWithItems['items'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Request has no items']);
            }

            // Store items separately
            $requestItems = $requestWithItems['items'];

            // Step 3: Get supplier ID from selected_supplier_id or request or use default
            $request = $this->purchaseRequestModel->find((int)$id);
            $supplierId = null;
            
            // First check if supplier was already selected
            if (!empty($request['selected_supplier_id'])) {
                $supplierId = (int)$request['selected_supplier_id'];
            } 
            // Then check if supplier_id is provided in POST
            elseif ($this->request->getPost('supplier_id')) {
                $supplierId = (int)$this->request->getPost('supplier_id');
            }
            
            if ($supplierId) {
                // Validate that the supplier exists and is active
                $supplier = $this->db->table('suppliers')
                    ->where('id', $supplierId)
                    ->where('status', 'active')
                    ->get()
                    ->getRowArray();
                
                if (!$supplier) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Selected supplier not found or inactive']);
                }
            } else {
                // Fallback to first active supplier if none selected
                $suppliers = $this->db->table('suppliers')->where('status', 'active')->limit(1)->get()->getResultArray();
                
                if (empty($suppliers)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'No active suppliers available. Please select a supplier first.']);
                }

                $supplierId = (int)$suppliers[0]['id'];
            }

            // Step 4: Generate unique order number
            $prefix = 'PO';
            $date = date('Ymd');
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . '-' . $date . '-' . $random;

            // Step 5: Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            // Step 6: Create purchase order with pending status
            $poData = [
                'order_number' => $orderNumber,
                'purchase_request_id' => (int)$request['id'],
                'supplier_id' => (int)$supplierId,
                'branch_id' => (int)$request['branch_id'],
                'order_date' => date('Y-m-d'),
                'expected_delivery_date' => date('Y-m-d', strtotime('+7 days')),
                'total_amount' => (float)$request['total_amount'],
                'notes' => 'Auto-created from approved purchase request',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $poInsertResult = $this->db->table('purchase_orders')->insert($poData);

            if (!$poInsertResult) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $dbError = $this->db->error();
                log_message('error', 'Failed to auto-create PO. DB error: ' . json_encode($dbError));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Request approved but failed to create PO', 'db_error' => $dbError]);
            }

            $poId = $this->db->insertID();
            log_message('info', 'Auto-created purchase order with ID: ' . $poId);

            // Step 7: Create purchase order items from request items
            $itemsInserted = 0;
            foreach ($requestItems as $item) {
                $itemData = [
                    'purchase_order_id' => (int)$poId,
                    'product_id' => (int)$item['product_id'],
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => (float)$item['unit_price'],
                    'subtotal' => (float)$item['subtotal'],
                    'received_quantity' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $itemInserted = $this->db->table('purchase_order_items')->insert($itemData);
                if ($itemInserted) {
                    $itemsInserted++;
                    log_message('debug', 'Inserted PO item: product_id=' . $item['product_id'] . ', qty=' . $item['quantity']);
                } else {
                    $dbError = $this->db->error();
                    log_message('error', 'Failed to insert PO item. DB error: ' . json_encode($dbError));
                }
            }

            // Step 8: Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');

            log_message('info', 'Inserted ' . $itemsInserted . ' items for auto-created purchase order ' . $poId);

            // Step 9: Update request status to converted_to_po
            $this->purchaseRequestModel->updateStatus((int)$id, 'converted_to_po');

            // Step 10: Auto-create delivery record
            $this->autoCreateDelivery($poId, $poData);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase request approved! Purchase Order automatically created with ' . $itemsInserted . ' items. Status: Pending supplier approval.',
                'po_id' => $poId,
                'items_count' => $itemsInserted
            ]);
        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            log_message('error', 'Exception in approveRequest: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Accept/Select supplier for purchase request (without approving)
     */
    public function acceptSupplier($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request ID required']);
        }

        $supplierId = $this->request->getPost('supplier_id');
        
        if (!$supplierId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier ID required']);
        }

        // Validate that the supplier exists and is active
        $supplier = $this->db->table('suppliers')
            ->where('id', (int)$supplierId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();
        
        if (!$supplier) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Selected supplier not found or inactive']);
        }

        // Update purchase request with selected supplier
        $updated = $this->purchaseRequestModel->update((int)$id, [
            'selected_supplier_id' => (int)$supplierId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Supplier selected successfully',
                'supplier_name' => $supplier['name']
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to select supplier']);
    }

    /**
     * Reject purchase request
     */
    public function rejectRequest($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request ID required']);
        }

        $reason = $this->request->getPost('reason') ?? 'No reason provided';
        $rejected = $this->purchaseRequestModel->rejectRequest((int)$id, $session->get('user_id'), $reason);

        if ($rejected) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase request rejected'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to reject request']);
    }

    /**
     * Convert approved purchase request to purchase order
     */
    public function convertToPO($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request ID required']);
        }

        $supplierId = $this->request->getPost('supplier_id');
        $expectedDeliveryDate = $this->request->getPost('expected_delivery_date');
        $notes = $this->request->getPost('notes') ?? '';

        if (!$supplierId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier is required']);
        }

        // Get purchase request with items
        $request = $this->purchaseRequestModel->getRequestWithItems((int)$id);

        if (!$request) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request not found']);
        }

        if ($request['status'] !== 'approved') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request must be approved first. Current status: ' . $request['status']]);
        }

        if (empty($request['items'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request has no items']);
        }

        try {
            // Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            // Create purchase order with pending status (waiting for supplier approval)
            $poData = [
                'purchase_request_id' => (int)$request['id'],
                'supplier_id' => (int)$supplierId,
                'branch_id' => (int)$request['branch_id'],
                'order_date' => date('Y-m-d'),
                'expected_delivery_date' => $expectedDeliveryDate ?: date('Y-m-d', strtotime('+7 days')),
                'total_amount' => (float)$request['total_amount'],
                'notes' => $notes,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $poInsertResult = $this->db->table('purchase_orders')->insert($poData);

            if (!$poInsertResult) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $dbError = $this->db->error();
                log_message('error', 'Failed to insert purchase order. DB error: ' . json_encode($dbError));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create purchase order', 'db_error' => $dbError]);
            }

            $poId = $this->db->insertID();
            log_message('info', 'Purchase order created with ID: ' . $poId);

            // Create purchase order items from request items
            $itemsInserted = 0;
            foreach ($request['items'] as $item) {
                $itemData = [
                    'purchase_order_id' => (int)$poId,
                    'product_id' => (int)$item['product_id'],
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => (float)$item['unit_price'],
                    'subtotal' => (float)$item['subtotal'],
                    'received_quantity' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $itemInserted = $this->db->table('purchase_order_items')->insert($itemData);
                if ($itemInserted) {
                    $itemsInserted++;
                    log_message('debug', 'Inserted PO item: product_id=' . $item['product_id'] . ', qty=' . $item['quantity']);
                } else {
                    $dbError = $this->db->error();
                    log_message('error', 'Failed to insert PO item. DB error: ' . json_encode($dbError));
                }
            }

            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');

            log_message('info', 'Inserted ' . $itemsInserted . ' items for purchase order ' . $poId);

            // Update request status
            $this->purchaseRequestModel->updateStatus((int)$id, 'converted_to_po');

            // Auto-create delivery record when PO is created
            $this->autoCreateDelivery($poId, $poData);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase order created successfully with ' . $itemsInserted . ' items. Status: Pending supplier approval. Delivery record created.',
                'po_id' => $poId,
                'items_count' => $itemsInserted
            ]);
        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            log_message('error', 'Exception in convertToPO: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Auto-create delivery record when PO is approved (int-2)
     */
    private function autoCreateDelivery(int $poId, array $poData): void
    {
        try {
            $deliveryModel = new \App\Models\DeliveryModel();
            
            // Create delivery record automatically
            $deliveryData = [
                'purchase_order_id' => $poId,
                'supplier_id' => $poData['supplier_id'],
                'branch_id' => $poData['branch_id'],
                'scheduled_date' => $poData['expected_delivery_date'],
                'status' => 'scheduled',
                'notes' => 'Auto-created from Purchase Order',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $deliveryModel->scheduleDelivery($deliveryData);
            log_message('info', 'Delivery record created for PO ' . $poId);
        } catch (\Exception $e) {
            log_message('error', 'Failed to create delivery record: ' . $e->getMessage());
        }
    }

    /**
     * Get purchase request details
     */
    public function getRequest($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request ID required']);
        }

        $request = $this->purchaseRequestModel->getRequestWithItems((int)$id);

        if (!$request) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request not found']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'request' => $request
        ]);
    }

    /**
     * Show purchase request form
     */
    public function showForm()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return redirect()->to('/auth/login');
        }

        // Get products for this branch
        $branchId = $session->get('branch_id');
        $productFilters = [];
        if ($branchId) {
            $productFilters['branch_id'] = $branchId;
        }
        $products = $this->productModel->getInventory($productFilters);

        return view('dashboards/purchase_request_form', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'products' => $products
        ]);
    }

    /**
     * Show purchase requests list
     */
    public function showRequests()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = $session->get('user_id');
        $role = $session->get('role');
        
        // Get requests based on user role
        if (in_array($role, ['central_admin', 'superadmin'])) {
            // Central admin sees all requests
            $requests = $this->purchaseRequestModel->findAll();
        } else {
            // Branch managers see requests they created
            $requests = $this->purchaseRequestModel->where('requested_by', $userId)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        // Get full details for each request
        foreach ($requests as &$request) {
            $request = $this->purchaseRequestModel->getRequestWithItems($request['id']);
        }

        return view('dashboards/purchase_requests_list', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'requests' => $requests
        ]);
    }

    /**
     * Get a single purchase order with details
     */
    public function getPurchaseOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        try {
            $order = $this->purchaseOrderModel->getOrderWithDetails((int)$id);

            if (!$order) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching purchase order: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to fetch purchase order']);
        }
    }

    /**
     * Track purchase order
     */
    public function trackOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        $order = $this->purchaseOrderModel->trackOrder((int)$id);

        if (!$order) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order not found']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'order' => $order
        ]);
    }

    /**
     * Get all purchase orders with supplier and branch info
     */
    public function getPurchaseOrdersList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $orders = $this->db->table('purchase_orders')
                ->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->orderBy('purchase_orders.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Format the response
            $formattedOrders = [];
            foreach ($orders as $order) {
                $formattedOrders[] = [
                    'id' => $order['id'],
                    'order_number' => $order['order_number'] ?? ('PO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)),
                    'purchase_request_id' => $order['purchase_request_id'],
                    'supplier' => [
                        'id' => $order['supplier_id'], 
                        'name' => $order['supplier_name'] ?? 'N/A'
                    ],
                    'branch' => [
                        'id' => $order['branch_id'], 
                        'name' => $order['branch_name'] ?? 'N/A'
                    ],
                    'status' => $order['status'] ?? 'pending',
                    'total_amount' => (float)($order['total_amount'] ?? 0),
                    'expected_delivery_date' => $order['expected_delivery_date'],
                    'actual_delivery_date' => $order['actual_delivery_date'],
                    'order_date' => $order['order_date'],
                    'created_at' => $order['created_at'],
                    'approved_by' => $order['approved_by'],
                    'approved_at' => $order['approved_at']
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'orders' => $formattedOrders,
                'count' => count($formattedOrders)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching purchase orders: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to fetch purchase orders']);
        }
    }

    /**
     * Update purchase order details (supplier and delivery date)
     */
    public function updatePurchaseOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        try {
            $supplierId = $this->request->getPost('supplier_id');
            $expectedDeliveryDate = $this->request->getPost('expected_delivery_date');

            if (!$supplierId || !$expectedDeliveryDate) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier and delivery date are required']);
            }

            $order = $this->purchaseOrderModel->find((int)$id);

            if (!$order) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
            }

            // Update PO with new supplier and delivery date
            $updateData = [
                'supplier_id' => (int)$supplierId,
                'expected_delivery_date' => $expectedDeliveryDate,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $updated = $this->db->table('purchase_orders')
                ->where('id', (int)$id)
                ->update($updateData);

            if ($updated) {
                log_message('info', 'Purchase order ' . $id . ' updated by user ' . $session->get('user_id'));
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Purchase order updated successfully!'
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update purchase order']);
        } catch (\Exception $e) {
            log_message('error', 'Exception in updatePurchaseOrder: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve a purchase order (supplier approval)
     */
    public function approvePurchaseOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        try {
            $order = $this->purchaseOrderModel->find((int)$id);

            if (!$order) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
            }

            // Update PO status to approved using direct database query
            $updateData = [
                'status' => 'approved',
                'approved_by' => (int)$session->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Use direct database update for reliability
            $updated = $this->db->table('purchase_orders')
                ->where('id', (int)$id)
                ->update($updateData);

            if ($updated) {
                log_message('info', 'Purchase order ' . $id . ' approved by user ' . $session->get('user_id'));
                
                // Create accounts payable entry
                $this->createAccountsPayableEntry((int)$id, $order, $session->get('user_id'));
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Purchase order approved successfully! Accounts payable entry created.'
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve purchase order']);
        } catch (\Exception $e) {
            log_message('error', 'Exception in approvePurchaseOrder: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Create accounts payable entry when purchase order is approved
     */
    private function createAccountsPayableEntry(int $purchaseOrderId, array $order, int $createdBy): bool
    {
        try {
            // Check if accounts payable entry already exists
            $existing = $this->accountsPayableModel
                ->where('purchase_order_id', $purchaseOrderId)
                ->first();
            
            if ($existing) {
                log_message('info', 'Accounts payable entry already exists for PO ' . $purchaseOrderId);
                return true;
            }

            // Get supplier to get payment terms
            $supplier = $this->supplierModel->find($order['supplier_id']);
            $paymentTerms = $supplier['payment_terms'] ?? 'Net 30';
            
            // Calculate due date based on payment terms
            $invoiceDate = date('Y-m-d'); // Use approval date as invoice date
            $dueDate = $this->accountsPayableModel->calculateDueDate($paymentTerms, $invoiceDate);
            
            // Create accounts payable entry
            $apData = [
                'purchase_order_id' => $purchaseOrderId,
                'supplier_id' => (int)$order['supplier_id'],
                'branch_id' => (int)$order['branch_id'],
                'invoice_number' => null, // Can be updated later when invoice is received
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'amount' => (float)$order['total_amount'],
                'paid_amount' => 0.00,
                'balance' => (float)$order['total_amount'],
                'payment_status' => 'unpaid',
                'payment_date' => null,
                'payment_method' => null,
                'payment_reference' => null,
                'notes' => 'Auto-created from approved purchase order: ' . $order['order_number'],
                'created_by' => $createdBy,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $apId = $this->accountsPayableModel->insert($apData);
            
            if ($apId) {
                log_message('info', 'Accounts payable entry created with ID: ' . $apId . ' for PO ' . $purchaseOrderId);
                return true;
            } else {
                log_message('error', 'Failed to create accounts payable entry for PO ' . $purchaseOrderId);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception creating accounts payable entry: ' . $e->getMessage());
            return false;
        }
    }
}

