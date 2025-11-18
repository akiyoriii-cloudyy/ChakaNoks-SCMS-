<?php

namespace App\Controllers;

use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use Config\Database;

class PurchaseController extends BaseController
{
    protected $db;
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $productModel;
    protected $branchModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
    }

    /**
     * Create a new purchase request
     */
    public function createRequest()
    {
        $session = session();
        
        // Check authorization - branch manager or inventory staff
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        if (!$branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch not assigned']);
        }

        $items = $this->request->getPost('items'); // Array of {product_id, quantity, unit_price, notes}
        $priority = $this->request->getPost('priority') ?? 'normal';
        $notes = $this->request->getPost('notes') ?? '';

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Items are required']);
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($items as $item) {
            $quantity = (float)($item['quantity'] ?? 0);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            $totalAmount += $quantity * $unitPrice;
        }

        // Create purchase request
        $requestData = [
            'branch_id' => $branchId,
            'requested_by' => $session->get('id'),
            'priority' => $priority,
            'total_amount' => $totalAmount,
            'notes' => $notes,
            'status' => 'pending'
        ];

        $requestId = $this->purchaseRequestModel->createRequest($requestData);

        if (!$requestId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create purchase request']);
        }

        // Create purchase request items
        foreach ($items as $item) {
            $productId = (int)($item['product_id'] ?? 0);
            $quantity = (int)($item['quantity'] ?? 0);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            $subtotal = $quantity * $unitPrice;
            $itemNotes = $item['notes'] ?? '';

            if ($productId > 0 && $quantity > 0) {
                $this->db->table('purchase_request_items')->insert([
                    'purchase_request_id' => $requestId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'notes' => $itemNotes,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Purchase request created successfully',
            'request_id' => $requestId
        ]);
    }

    /**
     * Get purchase requests (for branch manager or central admin)
     */
    public function getRequests()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');
        $status = $this->request->getGet('status');
        $priority = $this->request->getGet('priority');

        $filters = [];
        if ($status) $filters['status'] = $status;
        if ($priority) $filters['priority'] = $priority;

        if (in_array($role, ['central_admin', 'superadmin'])) {
            // Central admin can see all requests
            $requests = $this->purchaseRequestModel->findAll();
        } else {
            // Branch manager sees only their branch requests
            $requests = $this->purchaseRequestModel->getRequestsByBranch($branchId, $filters);
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
     * Approve purchase request
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

        $approved = $this->purchaseRequestModel->approveRequest((int)$id, $session->get('id'));

        if ($approved) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase request approved'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve request']);
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
        $rejected = $this->purchaseRequestModel->rejectRequest((int)$id, $session->get('id'), $reason);

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

        if (!$request || $request['status'] !== 'approved') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request not found or not approved']);
        }

        // Create purchase order
        $poData = [
            'purchase_request_id' => $request['id'],
            'supplier_id' => (int)$supplierId,
            'branch_id' => $request['branch_id'],
            'order_date' => date('Y-m-d'),
            'expected_delivery_date' => $expectedDeliveryDate ?: date('Y-m-d', strtotime('+7 days')),
            'total_amount' => $request['total_amount'],
            'approved_by' => $session->get('id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'notes' => $notes,
            'status' => 'approved'
        ];

        $poId = $this->purchaseOrderModel->createOrder($poData);

        if (!$poId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create purchase order']);
        }

        // Create purchase order items from request items
        foreach ($request['items'] as $item) {
            $this->db->table('purchase_order_items')->insert([
                'purchase_order_id' => $poId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
                'received_quantity' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Update request status
        $this->purchaseRequestModel->updateStatus((int)$id, 'converted_to_po');

        // Auto-create delivery record when PO is created (int-2: Connect purchasing to supplier)
        $this->autoCreateDelivery($poId, $poData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Purchase order created successfully. Delivery record created.',
            'po_id' => $poId
        ]);
    }

    /**
     * Auto-create delivery record when PO is approved (int-2)
     */
    private function autoCreateDelivery(int $poId, array $poData): void
    {
        $deliveryModel = new \App\Models\DeliveryModel();
        
        // Create delivery record automatically
        $deliveryData = [
            'purchase_order_id' => $poId,
            'supplier_id' => $poData['supplier_id'],
            'branch_id' => $poData['branch_id'],
            'scheduled_date' => $poData['expected_delivery_date'],
            'status' => 'scheduled',
            'notes' => 'Auto-created from Purchase Order'
        ];

        $deliveryModel->scheduleDelivery($deliveryData);
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
        $products = $this->productModel->getInventory(['branch_address' => 'all']);

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

        $branchId = $session->get('branch_id');
        $role = $session->get('role');
        
        if (in_array($role, ['central_admin', 'superadmin'])) {
            $requests = $this->purchaseRequestModel->findAll();
        } else {
            $requests = $this->purchaseRequestModel->getRequestsByBranch($branchId);
        }

        // Get full details
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
}

