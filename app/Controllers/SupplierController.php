<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use Config\Database;

class SupplierController extends BaseController
{
    protected $db;
    protected $supplierModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Get all suppliers
     */
    public function index()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $status = $this->request->getGet('status');
        $suppliers = [];

        if ($status === 'active') {
            $suppliers = $this->supplierModel->getActiveSuppliers();
        } else {
            $suppliers = $this->supplierModel->findAll();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Get supplier by ID
     */
    public function getSupplier($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier ID required']);
        }

        $supplier = $this->supplierModel->getSupplierById((int)$id);

        if (!$supplier) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier not found']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'supplier' => $supplier
        ]);
    }

    /**
     * Create new supplier
     */
    public function create()
    {
        $session = session();
        
        // Only central admin and superadmin can create suppliers
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'payment_terms' => $this->request->getPost('payment_terms'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        if (!$this->supplierModel->insert($data)) {
            $errors = $this->supplierModel->errors();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Supplier created successfully',
            'supplier_id' => $this->supplierModel->getInsertID()
        ]);
    }

    /**
     * Update supplier
     */
    public function update($id = null)
    {
        $session = session();
        
        // Only central admin and superadmin can update suppliers
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier ID required']);
        }

        $data = [];
        $fields = ['name', 'contact_person', 'email', 'phone', 'address', 'payment_terms', 'status'];
        
        foreach ($fields as $field) {
            if ($this->request->getPost($field) !== null) {
                $data[$field] = $this->request->getPost($field);
            }
        }

        if (empty($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No data to update']);
        }

        if (!$this->supplierModel->update((int)$id, $data)) {
            $errors = $this->supplierModel->errors();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Update failed',
                'errors' => $errors
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Supplier updated successfully'
        ]);
    }

    /**
     * Delete supplier (soft delete by setting status to inactive)
     */
    public function delete($id = null)
    {
        $session = session();
        
        // Only central admin and superadmin can delete suppliers
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier ID required']);
        }

        // Soft delete by setting status to inactive
        $updated = $this->supplierModel->update((int)$id, ['status' => 'inactive']);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Supplier deactivated successfully'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to deactivate supplier']);
    }

    /**
     * Get suppliers with performance metrics
     */
    public function getSuppliersWithPerformance()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $suppliers = $this->supplierModel->getSuppliersWithPerformance();

        return $this->response->setJSON([
            'status' => 'success',
            'suppliers' => $suppliers
        ]);
    }
}

