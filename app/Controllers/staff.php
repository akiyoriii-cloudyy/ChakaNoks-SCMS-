<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Config\Database;

class Staff extends BaseController
{
    protected $db;
    protected $model;

    public function __construct()
    {
        $this->db    = Database::connect();
        $this->model = new ProductModel();
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
}