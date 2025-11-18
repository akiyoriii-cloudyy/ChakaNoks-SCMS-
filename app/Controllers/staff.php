<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockTransactionModel;
use Config\Database;

class Staff extends BaseController
{
    protected $db;
    protected $model;
    protected $stockTransactionModel;

    public function __construct()
    {
        $this->db    = Database::connect();
        $this->model = new ProductModel();
        $this->stockTransactionModel = new StockTransactionModel();
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

        // Filters
        $filters = [
            'search'         => $this->request->getGet('search'),
            'branch_address' => $this->request->getGet('branch_address'),
            'status'         => $this->request->getGet('status'),
            'date'           => $this->request->getGet('date'),
        ];

        // Get inventory items
        $items = $this->model->getInventory($filters);

        // Get branches from products table
        $branches = $this->db->table('products')
            ->distinct()
            ->select('branch_address')
            ->where('branch_address IS NOT NULL')
            ->where('branch_address !=', '')
            ->orderBy('branch_address')
            ->get()
            ->getResultArray();

        return view('dashboards/staff', [
            'items'    => $items,
            'branches' => $branches,
            'filters'  => $filters,
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

        $filters = [
            'branch_address' => $this->request->getGet('branch_address') ?? 'all',
            'status'         => $this->request->getGet('status') ?? null,
            'search'         => $this->request->getGet('search') ?? null,
        ];

        $items = $this->model->getInventory($filters);

        return $this->response->setJSON([
            'status' => 'success',
            'items'  => $items,
        ]);
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

        $branchAddress = trim($this->request->getPost('branch_address'));
        if ($branchAddress === 'Unknown Branch') {
            $branchAddress = null;
        }

        // Prepare data for insertion
        $payload = [
            'name'           => $name,
            'category'       => $this->request->getPost('category') ?? 'Chicken Parts',
            'unit'           => $this->request->getPost('unit') ?? 'pcs',
            'stock_qty'      => $stock,
            'min_stock'      => (int)($this->request->getPost('min_stock') ?? 0),
            'max_stock'      => (int)($this->request->getPost('max_stock') ?? 0),
            'branch_address' => $branchAddress,
            'expiry'         => $this->request->getPost('expiry') ?: null,
            'created_by'     => $session->get('id') ?? 1,
        ];

        try {
            log_message('debug', 'Add product payload: ' . json_encode($payload));
            
            $insertId = $this->model->addProduct($payload);
            
            if ($insertId) {
                return $this->response->setJSON(['status' => 'success', 'id' => $insertId]);
            } else {
                $error = $this->db->error();
                return $this->response->setJSON([
                    'status' => 'error',
                    'error'  => $error['message'] ?? 'Failed to insert product'
                ]);
            }
            
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
}