<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockTransactionModel;
use App\Models\BranchModel;
use App\Models\CategoryModel;
use Config\Database;
use Exception;

class Staff extends BaseController
{
    protected $db;
    protected $model;
    protected $stockTransactionModel;
    protected $branchModel;

    public function __construct()
    {
        $this->db    = Database::connect();
        $this->model = new ProductModel();
        $this->stockTransactionModel = new StockTransactionModel();
        $this->branchModel = new BranchModel();
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $session = session();

        // Auth check - allow inventory staff and managers
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return redirect()->to('/auth/login');
        }

        $role = strtolower($session->get('role'));
        $branchId = $session->get('branch_id');
        // Enforce branch scope - each branch only sees their own products
        $enforceBranchScope = $branchId && in_array($role, ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager']);

        // Filters
        $filters = [
            'search'         => $this->request->getGet('search'),
            'status'         => $this->request->getGet('status'),
            'date'           => $this->request->getGet('date'),
            'branch_id'      => $this->request->getGet('branch_id'),
        ];

        // Enforce branch filtering - users can only see products from their assigned branch
        if ($enforceBranchScope) {
            $filters['branch_id'] = $branchId;
        }

        // Get inventory items
        $items = $this->model->getInventory($filters);

        // Get branches from branches table
        $branches = $this->branchModel
            ->select('id, name, code, address')
            ->orderBy('name')
            ->findAll();

        // Get current section from URL or default to inventory
        $section = $this->request->getGet('section') ?? 'inventory';

        // Load pending deliveries for this branch (server-side)
        $pendingDeliveries = [];
        if ($branchId) {
            try {
                $activeStatuses = ['scheduled', 'in_transit', 'delayed', 'partial_delivery'];
                $deliveriesData = $this->db->table('deliveries d')
                    ->select('d.*, po.order_number, s.name as supplier_name')
                    ->join('purchase_orders po', 'po.id = d.purchase_order_id', 'left')
                    ->join('suppliers s', 's.id = d.supplier_id', 'left')
                    ->where('d.branch_id', $branchId)
                    ->whereIn('d.status', $activeStatuses)
                    ->orderBy('d.scheduled_date', 'ASC')
                    ->orderBy('d.created_at', 'DESC')
                    ->get()
                    ->getResultArray();

                foreach ($deliveriesData as $delivery) {
                    $pendingDeliveries[] = [
                        'id' => $delivery['id'],
                        'delivery_number' => $delivery['delivery_number'] ?? ('DLV-' . str_pad($delivery['id'], 5, '0', STR_PAD_LEFT)),
                        'purchase_order' => [
                            'id' => $delivery['purchase_order_id'],
                            'order_number' => $delivery['order_number'] ?? 'N/A',
                        ],
                        'supplier' => [
                            'id' => $delivery['supplier_id'],
                            'name' => $delivery['supplier_name'] ?? 'N/A',
                        ],
                        'status' => $delivery['status'] ?? 'scheduled',
                        'scheduled_date' => $delivery['scheduled_date'],
                        'actual_delivery_date' => $delivery['actual_delivery_date'],
                        'driver_name' => $delivery['driver_name'],
                        'vehicle_info' => $delivery['vehicle_info'],
                        'notes' => $delivery['notes'],
                    ];
                }
            } catch (Exception $e) {
                log_message('error', 'Error loading deliveries in dashboard: ' . $e->getMessage());
            }
        }

        return view('dashboards/staff', [
            'items'    => $items,
            'branches' => $branches,
            'filters'  => $filters,
            'branchScope' => [
                'enforced' => (bool)$enforceBranchScope,
                'branch_id' => $branchId,
                'role' => $role,
            ],
            'me'       => [
                'email' => $session->get('email'),
                'role'  => $session->get('role'),
                'branch_id' => $branchId,
            ],
            'currentSection' => $section,
            'pendingDeliveries' => $pendingDeliveries,
        ]);
    }

    /**
     * Inventory AJAX API: return items for purchase request form and other consumers
     */
    public function getItems()
    {
        $session = session();

        if (! $session->get('logged_in')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Not authorized',
            ]);
        }

        try {
            // Get ALL products from database
            $allProducts = $this->db->table('products')
                ->select('products.id, products.name, categories.name AS category, products.unit, products.stock_qty, products.min_stock, products.max_stock, products.price, products.branch_id, products.expiry, branches.name AS branch_name, branches.address AS branch_address, products.created_at')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->join('branches', 'branches.id = products.branch_id', 'left')
                ->orderBy('products.name', 'ASC')
                ->orderBy('products.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Remove duplicates based on product name (case-insensitive)
            // Keep the most recent product for each unique name
            $uniqueProducts = [];
            $seenNames = [];
            
            foreach ($allProducts as $product) {
                $productName = trim(strtolower($product['name'] ?? ''));
                
                // Skip if we've already seen this product name
                if (isset($seenNames[$productName])) {
                    continue;
                }
                
                // Mark this name as seen and add the product
                $seenNames[$productName] = true;
                
                // Ensure price field exists and is numeric
                $product['price'] = (float)($product['price'] ?? 0);
                $product['stock_qty'] = (int)($product['stock_qty'] ?? 0);
                $product['id'] = (int)($product['id'] ?? 0);
                
                // Remove created_at as it's not needed in response
                unset($product['created_at']);
                
                $uniqueProducts[] = $product;
            }

            // Sort by name again after deduplication
            usort($uniqueProducts, function($a, $b) {
                return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
            });

            log_message('debug', 'getItems() returning ' . count($uniqueProducts) . ' unique products (removed ' . (count($allProducts) - count($uniqueProducts)) . ' duplicates)');

            return $this->response->setJSON([
                'status' => 'success',
                'items'  => $uniqueProducts,
                'count'  => count($uniqueProducts),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'getItems() error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to load products: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Add new product
     */
    public function addProduct()
    {
        // Check authentication - allow inventory staff and managers
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized']);
        }

        // Get and validate input
        $name = trim($this->request->getPost('name'));
        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Product name is required']);
        }

        $stock = (int)($this->request->getPost('stock') ?? 0);
        if ($stock <= 0) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Stock quantity must be greater than 0']);
        }

        $branchId = (int)($this->request->getPost('branch_id') ?? 0);
        if (!$branchId && $session->get('branch_id')) {
            $branchId = (int)$session->get('branch_id');
        }

        if ($branchId <= 0) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Branch selection is required']);
        }

        $branch = $this->branchModel->find($branchId);
        if (!$branch) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Invalid branch selected']);
        }

        // Get or create category
        $categoryName = $this->request->getPost('category') ?? 'Chicken Parts';
        $categoryModel = new CategoryModel();
        $category = $categoryModel->where('name', $categoryName)->first();
        
        $categoryId = null;
        if ($category) {
            $categoryId = $category['id'];
        } else {
            // Create category if it doesn't exist
            $categoryModel->insert([
                'name' => $categoryName,
                'status' => 'active',
                'created_by' => $session->get('user_id') ?? null,
            ]);
            $categoryId = $categoryModel->getInsertID();
        }

        // Prepare data for insertion (normalized - no branch_address or category)
        $now = date('Y-m-d H:i:s');
        $payload = [
            'name'           => $name,
            'category_id'   => $categoryId,
            'unit'          => $this->request->getPost('unit') ?? 'pcs',
            'price'         => (float)($this->request->getPost('price') ?? 0),
            'stock_qty'     => $stock,
            'min_stock'     => (int)($this->request->getPost('min_stock') ?? 0),
            'max_stock'     => (int)($this->request->getPost('max_stock') ?? 0),
            'branch_id'     => $branchId,
            'expiry'        => $this->request->getPost('expiry') ?: null,
            'created_by'    => $session->get('user_id') ?? null,
            'status'        => 'active',
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        try {
            log_message('debug', 'Add product payload: ' . json_encode($payload));

            $builder = $this->db->table('products');
            $result = $builder->insert($payload, true);

            if ($result === false) {
                $error = $this->db->error();
                log_message('error', 'Add product insert failed: ' . json_encode($error));
                return $this->response->setJSON([
                    'status' => 'error',
                    'error'  => $error['message'] ?? 'Failed to insert product into database'
                ]);
            }

            $insertId = $this->db->insertID();

            $product = $this->model->find($insertId);

            return $this->response->setJSON([
                'status'  => 'success',
                'id'      => $insertId,
                'product' => $product
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'Add product exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'error'  => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update stock quantity
     */
    public function updateStock($id = null)
    {
        // Check authentication - allow inventory staff and managers
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing product id']);
        }

        $qty = (int)($this->request->getPost('stock_qty') ?? 0);
        $ok  = $this->model->updateStock((int)$id, $qty);

        if ($ok) {
            return $this->response->setJSON(['status' => 'success']);
        }

        $err = $this->db->error();
        return $this->response->setJSON(['status' => 'error', 'error' => $err['message'] ?? 'Update failed']);
    }

    /**
     * Receive delivery: increase stock by provided quantity
     */
    public function receiveDelivery($id = null)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Missing product id']);
        }

        $qty = (int)($this->request->getPost('quantity') ?? 0);
        if ($qty <= 0) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Quantity must be greater than 0']);
        }

        $product = $this->model->find((int)$id);
        if (!is_array($product)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Product not found']);
        }

        if (!$this->canAccessProduct($session, $product)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized to manage this branch inventory']);
        }

        if (!$this->canAccessProduct($session, $product)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized to manage this branch inventory']);
        }

        $newQty = max(0, (int)$product['stock_qty'] + $qty);
        $ok = $this->model->update((int)$id, [
            'stock_qty' => $newQty,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($ok) return $this->response->setJSON(['status' => 'success', 'stock_qty' => $newQty]);

        $err = $this->db->error();
        return $this->response->setJSON(['status' => 'error', 'error' => $err['message'] ?? 'Update failed']);
    }

    /**
     * Report damaged: decrease stock by provided quantity
     * Implements STOCK-OUT → EXPIRE? → OLDS flow from diagram
     */
    public function reportDamage($id = null)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Missing product id']);
        }

        $qty = (int)($this->request->getPost('quantity') ?? 0);
        if ($qty <= 0) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Quantity must be greater than 0']);
        }

        $reason = $this->request->getPost('reason') ?? 'Damaged/Expired';
        $product = $this->model->find((int)$id);
        if (!is_array($product)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Product not found']);
        }

        if (!$this->canAccessProduct($session, $product)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized to manage this branch inventory']);
        }

        // Record STOCK-OUT transaction (checks EXPIRE? → OLDS)
        // This implements: STOCK-OUT → EXPIRE? → OLDS flow from diagram
        $recorded = $this->stockTransactionModel->recordStockOut(
            (int)$id,
            $qty,
            null, // reference_id
            'damage_report', // reference_type
            $session->get('id'), // created_by
            $reason
        );

        if ($recorded) {
            $product = $this->model->find((int)$id); // Get updated product
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Damage reported. Stock updated (STOCK-OUT → EXPIRE? → OLDS)',
                'stock_qty' => (int)($product['stock_qty'] ?? 0)
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'error' => 'Failed to record stock transaction']);
    }

    /**
     * Check expiry status for a product
     */
    public function checkExpiry($id = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Missing product id']);
        }

        $product = $this->model->find((int)$id);
        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Product not found']);
        }

        if (!$this->canAccessProduct($session, $product)) {
            return $this->response->setJSON(['status' => 'error', 'error' => 'Not authorized to view this branch inventory']);
        }

        $expiry = $product['expiry'] ?? null;
        $status = 'No Expiry';
        $days   = null;
        if ($expiry) {
            $exp  = new \DateTime($expiry);
            $today = new \DateTime();
            $today->setTime(0,0,0);
            $diff = (int)$today->diff($exp)->format('%r%a');
            $days = $diff;
            if ($diff < 0) $status = 'Expired';
            elseif ($diff <= 7) $status = 'Expiring Soon';
            else $status = 'Good';
        }

        return $this->response->setJSON([
            'status' => 'success',
            'expiry' => $expiry,
            'days'   => $days,
            'state'  => $status,
        ]);
    }

    private function canAccessProduct($session, array $product): bool
    {
        $branchId = $session->get('branch_id');
        if (!$branchId) {
            return true;
        }

        $role = strtolower($session->get('role'));
        if (!in_array($role, ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return true;
        }

        if (empty($product['branch_id'])) {
            return true;
        }

        return (int)$product['branch_id'] === (int)$branchId;
    }

    /**
     * Get branch products for stock in/out/damaged sections
     * Returns only products from the user's assigned branch
     */
    public function getBranchProducts()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        if (!$branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch assignment missing']);
        }

        try {
            // Get only products from the user's assigned branch
            $products = $this->db->table('products')
                ->select('products.id, products.name, products.stock_qty, products.unit')
                ->where('products.branch_id', $branchId)
                ->orderBy('products.name', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'status' => 'success',
                'products' => $products
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error getting branch products: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error loading products']);
        }
    }

    /**
     * Record stock in
     */
    public function recordStockIn()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $productId = (int)($this->request->getPost('product_id') ?? 0);
        $quantity = (int)($this->request->getPost('quantity') ?? 0);
        $reference = $this->request->getPost('reference') ?? '';
        $notes = $this->request->getPost('notes') ?? '';

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid product or quantity']);
        }

        // Verify product belongs to branch
        $product = $this->model->find($productId);
        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Product not found']);
        }

        if (!$this->canAccessProduct($session, $product)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to manage this product']);
        }

        $userId = $session->get('user_id') ?? $session->get('id');
        $expiryDate = $product['expiry'] ?? null;

        // Record STOCK-IN transaction
        $stockInRecorded = $this->stockTransactionModel->recordStockIn(
            $productId,
            $quantity,
            null, // reference_id
            $reference ?: 'stock_in', // reference_type
            $userId, // created_by
            $expiryDate
        );

        if ($stockInRecorded) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Stock in recorded successfully'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to record stock in']);
    }

    /**
     * Record stock out
     */
    public function recordStockOut()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $productId = (int)($this->request->getPost('product_id') ?? 0);
        $quantity = (int)($this->request->getPost('quantity') ?? 0);
        $reason = $this->request->getPost('reason') ?? 'other';
        $notes = $this->request->getPost('notes') ?? '';

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid product or quantity']);
        }

        // Verify product belongs to branch
        $product = $this->model->find($productId);
        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Product not found']);
        }

        if (!$this->canAccessProduct($session, $product)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to manage this product']);
        }

        if ($product['stock_qty'] < $quantity) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Insufficient stock. Available: ' . $product['stock_qty']]);
        }

        $userId = $session->get('user_id') ?? $session->get('id');
        $finalNotes = $reason . ($notes ? ': ' . $notes : '');

        // Record STOCK-OUT transaction
        $stockOutRecorded = $this->stockTransactionModel->recordStockOut(
            $productId,
            $quantity,
            null, // reference_id
            'stock_out', // reference_type
            $userId, // created_by
            $finalNotes
        );

        if ($stockOutRecorded) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Stock out recorded successfully'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to record stock out']);
    }

    /**
     * Get deliveries for branch
     */
    public function getDeliveries()
    {
        $session = session();

        $allowedRoles = [
            'inventorystaff',
            'inventory_staff',
            'branch_manager',
            'manager',
            'central_admin',
            'superadmin',
        ];

        $role = strtolower((string) $session->get('role'));
        if (!$session->get('logged_in') || !in_array($role, $allowedRoles, true)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = (int) ($session->get('branch_id') ?? 0);
        $requestedBranchId = (int) ($this->request->getGet('branch_id') ?? 0);

        if ($requestedBranchId > 0) {
            $canSwitchBranch = in_array($role, ['branch_manager', 'manager', 'central_admin', 'superadmin'], true);
            if ($canSwitchBranch) {
                $branchId = $requestedBranchId;
            } elseif ($branchId !== $requestedBranchId) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to view this branch']);
            }
        }

        if (!$branchId) {
            $userId = (int) ($session->get('user_id') ?? $session->get('id') ?? 0);
            if ($userId) {
                $user = $this->db->table('users')
                    ->select('branch_id')
                    ->where('id', $userId)
                    ->get()
                    ->getRowArray();

                if (!empty($user['branch_id'])) {
                    $branchId = (int) $user['branch_id'];
                    $session->set('branch_id', $branchId);
                }
            }
        }

        if (!$branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch assignment missing']);
        }

        try {
            $activeStatuses = ['scheduled', 'in_transit', 'delayed', 'partial_delivery'];

            $deliveries = $this->db->table('deliveries d')
                ->select('d.*, po.order_number, s.name as supplier_name')
                ->join('purchase_orders po', 'po.id = d.purchase_order_id', 'left')
                ->join('suppliers s', 's.id = d.supplier_id', 'left')
                ->where('d.branch_id', $branchId)
                ->whereIn('d.status', $activeStatuses)
                ->orderBy('d.scheduled_date', 'ASC')
                ->orderBy('d.created_at', 'DESC')
                ->get()
                ->getResultArray();

            $formattedDeliveries = [];
            foreach ($deliveries as $delivery) {
                $formattedDeliveries[] = [
                    'id' => $delivery['id'],
                    'delivery_number' => $delivery['delivery_number'] ?? ('DLV-' . str_pad($delivery['id'], 5, '0', STR_PAD_LEFT)),
                    'purchase_order' => [
                        'id' => $delivery['purchase_order_id'],
                        'order_number' => $delivery['order_number'] ?? 'N/A',
                    ],
                    'supplier' => [
                        'id' => $delivery['supplier_id'],
                        'name' => $delivery['supplier_name'] ?? 'N/A',
                    ],
                    'status' => $delivery['status'] ?? 'scheduled',
                    'scheduled_date' => $delivery['scheduled_date'],
                    'actual_delivery_date' => $delivery['actual_delivery_date'],
                    'driver_name' => $delivery['driver_name'],
                    'vehicle_info' => $delivery['vehicle_info'],
                    'notes' => $delivery['notes'],
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'deliveries' => $formattedDeliveries,
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error getting deliveries: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error loading deliveries']);
        }
    }
}