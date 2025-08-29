<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\BranchModel;

class Products extends BaseController
{
    public function create()
    {
        // Get request data (JSON or POST)
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        if (!$data) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data provided'
            ])->setStatusCode(400);
        }

        // Validate required fields
        if (empty($data['name']) || empty($data['stock'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product name and stock are required'
            ])->setStatusCode(422);
        }

        // Handle branch lookup (optional)
        $branchId = null;
        if (!empty($data['branch'])) {
            $branchModel = new BranchModel();
            $branch = $branchModel->where('code', $data['branch'])->first();

            if ($branch) {
                $branchId = $branch['id'];
            }
        }

        // Insert product
        $productModel = new ProductModel();

        $newProduct = [
            'branch_id' => $branchId,
            'name'      => $data['name'],
            'stock'     => (int) $data['stock'],
            'expiry'    => $data['expiry'] ?? null,
            'unit'      => $data['unit'] ?? null,   // ✅ allow liters/kg/etc.
            'min_stock' => $data['min_stock'] ?? 0, // ✅ safe default
            'max_stock' => $data['max_stock'] ?? 0, // ✅ safe default
        ];

        if (!$productModel->insert($newProduct)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save product',
                'errors'  => $productModel->errors(),
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Product added successfully',
            'product_id' => $productModel->getInsertID()
        ]);
    }
}
