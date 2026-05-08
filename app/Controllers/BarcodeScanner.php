<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\BranchModel;
use Config\Database;

class BarcodeScanner extends BaseController
{
    protected $db;
    protected $productModel;
    protected $branchModel;
    
    public function __construct()
    {
        $this->db = Database::connect();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
    }
    
    /**
     * Scan barcode and get product information
     */
    public function scan()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }
        
        $barcode = trim($this->request->getPost('barcode') ?? $this->request->getGet('barcode') ?? '');
        
        if (empty($barcode)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Barcode is required']);
        }
        
        // Search by barcode (exclude soft-deleted items)
        $product = $this->db->table('products p')
            ->select('p.*, b.name as branch_name, c.name as category_name')
            ->join('branches b', 'b.id = p.branch_id', 'left')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.barcode', $barcode)
            ->where('p.deleted_at IS NULL') // Exclude deleted items
            ->get()
            ->getRowArray();
        
        if (!$product) {
            return $this->response->setJSON([
                'status' => 'not_found',
                'message' => 'Product with barcode not found',
                'barcode' => $barcode
            ]);
        }
        
        // Check branch access if user has branch restriction
        $userBranchId = $session->get('branch_id');
        if ($userBranchId && $product['branch_id'] != $userBranchId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Product belongs to different branch',
                'product' => $product
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'product' => $product
        ]);
    }
    
    /**
     * Create or update product with barcode
     */
    public function createOrUpdate()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }
        
        $barcode = trim($this->request->getPost('barcode') ?? '');
        $name = trim($this->request->getPost('name') ?? '');
        $stock = (int)($this->request->getPost('stock') ?? 0);
        $branchId = (int)($this->request->getPost('branch_id') ?? $session->get('branch_id') ?? 0);
        
        if (empty($barcode)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Barcode is required']);
        }
        
        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Product name is required']);
        }
        
        // Check if product with barcode exists
        $existingProduct = $this->db->table('products')
            ->where('barcode', $barcode)
            ->get()
            ->getRowArray();
        
        if ($existingProduct) {
            // Update existing product
            $updateData = [
                'name' => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($stock > 0) {
                $updateData['stock_qty'] = $stock;
            }
            
            $this->db->table('products')
                ->where('id', $existingProduct['id'])
                ->update($updateData);
            
            $product = $this->productModel->find($existingProduct['id']);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Product updated',
                'product' => $product,
                'action' => 'updated'
            ]);
        } else {
            // Create new product
            $userId = $session->get('user_id');
            $productData = [
                'barcode' => $barcode,
                'name' => $name,
                'stock_qty' => $stock,
                'branch_id' => $branchId,
                'unit' => $this->request->getPost('unit') ?? 'pcs',
                'price' => (float)($this->request->getPost('price') ?? 0),
                'min_stock' => (int)($this->request->getPost('min_stock') ?? 0),
                'status' => 'active',
                'created_by' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('products')->insert($productData);
            $productId = $this->db->insertID();
            $product = $this->productModel->find($productId);
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Product created',
                'product' => $product,
                'action' => 'created'
            ]);
        }
    }
}

