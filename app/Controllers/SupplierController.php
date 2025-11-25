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
     * Get all suppliers (returns active suppliers by default for modal)
     */
    public function index()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $status = $this->request->getGet('status') ?? 'active';
        $suppliers = [];

        if ($status === 'active') {
            $suppliers = $this->supplierModel->getActiveSuppliers();
        } else if ($status === 'all') {
            $suppliers = $this->supplierModel->findAll();
        } else {
            // Default to active suppliers
            $suppliers = $this->supplierModel->getActiveSuppliers();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'suppliers' => $suppliers,
            'count' => count($suppliers)
        ]);
    }

    /**
     * Show suppliers list page
     */
    public function showSuppliersList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Fetch suppliers data server-side
        try {
            $suppliers = $this->supplierModel->getActiveSuppliers();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching suppliers: ' . $e->getMessage());
            $suppliers = [];
        }

        // Get pending approvals for sidebar badge
        $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
        $pendingRequests = $purchaseRequestModel->getPendingRequests();
        $dashboardData = [
            'purchaseRequests' => [
                'pending_approvals' => count($pendingRequests)
            ]
        ];

        return view('dashboards/suppliers_list', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'suppliers' => $suppliers,
            'data' => $dashboardData
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
        try {
            $session = session();
            
            if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
            }

            $suppliers = $this->supplierModel->getSuppliersWithPerformance();

            log_message('debug', 'Suppliers with performance: ' . count($suppliers) . ' suppliers found');

            return $this->response->setJSON([
                'status' => 'success',
                'suppliers' => $suppliers,
                'count' => count($suppliers)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getSuppliersWithPerformance: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to load suppliers: ' . $e->getMessage(),
                'suppliers' => []
            ]);
        }
    }

    /**
     * Seed suppliers into database with improved error handling
     */
    public function seedSuppliers()
    {
        $session = session();
        
        // Allow anyone to seed (for testing), but log it
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not logged in']);
        }

        try {
            $now = date('Y-m-d H:i:s');
            $suppliers = [
                ['name' => 'Magnolia Chicken', 'contact_person' => 'Sales Department', 'email' => 'sales@magnolia.com.ph', 'phone' => '(02) 8123-4567', 'address' => 'Magnolia Avenue, Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => "Ana's Breeders Farms Inc", 'contact_person' => 'Farm Manager', 'email' => 'info@anasbreeders.com.ph', 'phone' => '(049) 501-2345', 'address' => 'Tagaytay, Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Premium Feeds Corporation', 'contact_person' => 'Sales Officer', 'email' => 'sales@premiumfeeds.com.ph', 'phone' => '(02) 8456-7890', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'San Miguel Foods', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@sanmiguelfoods.com.ph', 'phone' => '(02) 8888-0000', 'address' => 'Mandaluyong City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'CDO Foodsphere Inc.', 'contact_person' => 'Sales Department', 'email' => 'sales@cdofoodsphere.com.ph', 'phone' => '(02) 8123-5678', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Excellence Poultry and Livestock Specialist Inc.', 'contact_person' => 'Operations Manager', 'email' => 'info@excellencepoultry.com.ph', 'phone' => '(049) 502-3456', 'address' => 'Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Rare Global Food Trading Corp.', 'contact_person' => 'Trading Manager', 'email' => 'trading@rareglobal.com.ph', 'phone' => '(02) 8234-5678', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'E&L Faster Food Imports Inc.', 'contact_person' => 'Import Manager', 'email' => 'imports@elfaster.com.ph', 'phone' => '(02) 8345-6789', 'address' => 'Port Area, Manila, Philippines', 'payment_terms' => 'Net 45', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Foster Foods Inc', 'contact_person' => 'Sales Coordinator', 'email' => 'sales@fosterfoods.com.ph', 'phone' => '(02) 8456-7890', 'address' => 'Taguig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Pilmico', 'contact_person' => 'Corporate Sales', 'email' => 'corporate@pilmico.com.ph', 'phone' => '(02) 8567-8901', 'address' => 'Laguna, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Consistent Frozen Solutions Corp.', 'contact_person' => 'Sales Manager', 'email' => 'sales@consistentfrozen.com.ph', 'phone' => '(02) 8678-9012', 'address' => 'Cavite, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'EcoSci Food', 'contact_person' => 'Business Development', 'email' => 'bd@ecoscifood.com.ph', 'phone' => '(02) 8789-0123', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'COD', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Advance Protein Inc.', 'contact_person' => 'Sales Officer', 'email' => 'sales@advanceprotein.com.ph', 'phone' => '(02) 8890-1234', 'address' => 'Bulacan, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Art Inc.', 'contact_person' => 'Account Manager', 'email' => 'accounts@artinc.com.ph', 'phone' => '(02) 8901-2345', 'address' => 'Manila, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Clarc Feedmill Inc.', 'contact_person' => 'Production Manager', 'email' => 'production@clarcfeedmill.com.ph', 'phone' => '(049) 503-4567', 'address' => 'Cavite, Philippines', 'payment_terms' => 'COD', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Kai Anya Foods Intl Corp', 'contact_person' => 'International Sales', 'email' => 'sales@kaianya.com.ph', 'phone' => '(02) 8012-3456', 'address' => 'Makati City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Hightower Incorporated', 'contact_person' => 'Sales Department', 'email' => 'sales@hightower.com.ph', 'phone' => '(02) 8123-4567', 'address' => 'Pasig City, Philippines', 'payment_terms' => 'Net 30', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'The Original Savory Escolta - Online', 'contact_person' => 'Online Manager', 'email' => 'online@savoryescolta.com.ph', 'phone' => '(02) 8234-5678', 'address' => 'Escolta, Manila, Philippines', 'payment_terms' => 'COD', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Fresco PH', 'contact_person' => 'Sales Team', 'email' => 'sales@fresco.com.ph', 'phone' => '(02) 8345-6789', 'address' => 'Quezon City, Philippines', 'payment_terms' => 'Net 15', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ];

            $inserted = 0;
            $errors = [];

            // Use raw database insert for maximum reliability
            foreach ($suppliers as $supplier) {
                try {
                    $existing = $this->db->table('suppliers')
                        ->where('name', $supplier['name'])
                        ->get()
                        ->getRow();

                    if (!$existing) {
                        $result = $this->db->table('suppliers')->insert($supplier);
                        if ($result) {
                            $inserted++;
                        } else {
                            $errors[] = "Failed to insert: {$supplier['name']}";
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error inserting {$supplier['name']}: " . $e->getMessage();
                }
            }

            $message = "Suppliers seeded successfully! Inserted: $inserted out of 19";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode("; ", $errors);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message,
                'inserted' => $inserted,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Seeding failed: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ]);
        }
    }
}

