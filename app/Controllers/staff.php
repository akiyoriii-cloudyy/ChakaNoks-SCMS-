<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockTransactionModel;
use App\Models\BranchModel;
use App\Models\CategoryModel;
use Config\Database;

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
        $enforceBranchScope = $branchId && in_array($role, ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager']);

        // Filters
        $filters = [
            'search'         => $this->request->getGet('search'),
            'status'         => $this->request->getGet('status'),
            'date'           => $this->request->getGet('date'),
            'branch_id'      => $this->request->getGet('branch_id'),
        ];

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
            ],
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
            // Get ALL products for purchase request dropdown (no branch restriction)
            // Normalized: Use JOINs to get category and branch information
            $items = $this->db->table('products')
                ->select('products.id, products.name, categories.name AS category, products.unit, products.stock_qty, products.min_stock, products.max_stock, products.price, products.branch_id, products.expiry, branches.name AS branch_name, branches.address AS branch_address')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->join('branches', 'branches.id = products.branch_id', 'left')
                ->orderBy('products.name', 'ASC')
                ->get()
                ->getResultArray();

            // Ensure price field exists and is numeric
            foreach ($items as &$item) {
                $item['price'] = (float)($item['price'] ?? 0);
                $item['stock_qty'] = (int)($item['stock_qty'] ?? 0);
            }

            log_message('debug', 'getItems() returning ' . count($items) . ' products');

            return $this->response->setJSON([
                'status' => 'success',
                'items'  => $items,
                'count'  => count($items),
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
}