<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockTransactionModel;
use App\Models\BranchModel;
use App\Models\CategoryModel;
use App\Libraries\NotificationService;
use Config\Database;
use Exception;

class Staff extends BaseController
{
    protected $db;
    protected $model;
    protected $stockTransactionModel;
    protected $branchModel;
    protected $notificationService;
    protected $deliveryModel;

    public function __construct()
    {
        $this->db    = Database::connect();
        $this->model = new ProductModel();
        $this->stockTransactionModel = new StockTransactionModel();
        $this->branchModel = new BranchModel();
        $this->notificationService = new NotificationService();
        $this->deliveryModel = new \App\Models\DeliveryModel();
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

        // Pagination
        $perPage = 10; // Maximum 10 records per page
        $currentPage = max(1, (int)$this->request->getGet('page') ?? 1);

        // Get inventory items with pagination
        $items = $this->model->getInventory($filters);
        $totalItems = count($items);
        
        // Manual pagination since getInventory returns array
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);
        
        // Create pager data manually
        $pager = (object)[
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'total' => $totalItems,
            'pageCount' => ceil($totalItems / $perPage),
        ];

        // Get branches from branches table
        $branches = $this->branchModel
            ->select('id, name, code, address')
            ->orderBy('name')
            ->findAll();

        // Get categories for Add Item form
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getActiveCategories();

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
            'items'    => $paginatedItems,
            'pager'    => $pager,
            'branches' => $branches,
            'categories' => $categories,
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
     * Get monthly items for report generation
     */
    public function getMonthlyItems()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        try {
            $month = $this->request->getGet('month'); // Format: YYYY-MM
            
            if (!$month) {
                // Use current month if not specified
                $month = date('Y-m');
            }
            
            // Parse month
            list($year, $monthNum) = explode('-', $month);
            $startDate = $year . '-' . $monthNum . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($startDate));
            
            // Get branch scope
            $role = $session->get('role');
            $branchId = $session->get('branch_id');
            $enforceBranchScope = !in_array($role, ['central_admin', 'superadmin']);
            
            // Get branch information
            $branchInfo = null;
            if ($branchId) {
                $branchModel = new \App\Models\BranchModel();
                $branchInfo = $branchModel->find($branchId);
            }
            
            // Build query - Get ALL products (not just created in that month)
            $builder = $this->db->table('products')
                ->select('products.*, categories.name AS category, branches.name AS branch_name, branches.address AS branch_address')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->join('branches', 'branches.id = products.branch_id', 'left')
                ->where('products.deleted_at IS NULL'); // Exclude deleted products
            
            // Enforce branch scope if needed
            if ($enforceBranchScope && $branchId) {
                $builder->where('products.branch_id', $branchId);
            }
            
            $items = $builder->orderBy('categories.name', 'ASC')
                ->orderBy('products.name', 'ASC')
                ->get()
                ->getResultArray();
            
            // Format items and calculate stock in/out for the month
            $formattedItems = [];
            foreach ($items as $item) {
                $productId = $item['id'];
                
                // Get stock in transactions for the month
                // Use COALESCE to check transaction_date first, then created_at if transaction_date is NULL
                $stockInQuery = "
                    SELECT SUM(quantity) as total_stock_in 
                    FROM stock_transactions 
                    WHERE product_id = ? 
                    AND transaction_type = 'stock_in'
                    AND (
                        (transaction_date IS NOT NULL AND DATE(transaction_date) >= ? AND DATE(transaction_date) <= ?)
                        OR 
                        (transaction_date IS NULL AND DATE(created_at) >= ? AND DATE(created_at) <= ?)
                    )
                ";
                
                $stockInResult = $this->db->query($stockInQuery, [
                    $productId,
                    date('Y-m-d', strtotime($startDate)),
                    date('Y-m-d', strtotime($endDate)),
                    date('Y-m-d', strtotime($startDate)),
                    date('Y-m-d', strtotime($endDate))
                ])->getRowArray();
                
                $stockIn = (int)($stockInResult['total_stock_in'] ?? 0);
                
                // Get stock out transactions for the month
                // Use COALESCE to check transaction_date first, then created_at if transaction_date is NULL
                $stockOutQuery = "
                    SELECT SUM(quantity) as total_stock_out 
                    FROM stock_transactions 
                    WHERE product_id = ? 
                    AND transaction_type = 'stock_out'
                    AND (
                        (transaction_date IS NOT NULL AND DATE(transaction_date) >= ? AND DATE(transaction_date) <= ?)
                        OR 
                        (transaction_date IS NULL AND DATE(created_at) >= ? AND DATE(created_at) <= ?)
                    )
                ";
                
                $stockOutResult = $this->db->query($stockOutQuery, [
                    $productId,
                    date('Y-m-d', strtotime($startDate)),
                    date('Y-m-d', strtotime($endDate)),
                    date('Y-m-d', strtotime($startDate)),
                    date('Y-m-d', strtotime($endDate))
                ])->getRowArray();
                
                $stockOut = (int)($stockOutResult['total_stock_out'] ?? 0);
                
                // Check if product was updated in this month
                $productUpdatedInMonth = false;
                if (isset($item['updated_at']) && $item['updated_at']) {
                    $updatedDate = date('Y-m-d', strtotime($item['updated_at']));
                    $startDateOnly = date('Y-m-d', strtotime($startDate));
                    $endDateOnly = date('Y-m-d', strtotime($endDate));
                    $productUpdatedInMonth = ($updatedDate >= $startDateOnly && $updatedDate <= $endDateOnly);
                }
                
                // Include items that have transactions in this month OR were updated in this month
                // This ensures the report shows all products with activity in the selected month
                if ($stockIn > 0 || $stockOut > 0 || $productUpdatedInMonth) {
                    $formattedItems[] = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'category' => $item['category'] ?? 'N/A',
                        'stock_qty' => (int)($item['stock_qty'] ?? 0),
                        'min_stock' => (int)($item['min_stock'] ?? 0),
                        'max_stock' => (int)($item['max_stock'] ?? 0),
                        'unit' => $item['unit'] ?? 'N/A',
                        'price' => (float)($item['price'] ?? 0),
                        'branch_name' => $item['branch_name'] ?? 'N/A',
                        'branch_address' => $item['branch_address'] ?? 'N/A',
                        'stock_in' => $stockIn,
                        'stock_out' => $stockOut,
                        'created_at' => $item['created_at'],
                        'updated_at' => $item['updated_at'],
                        'last_updated' => $item['updated_at'] // Add for display in report
                    ];
                }
            }
            
            // Get user info for notification
            $userId = $session->get('user_id') ?? $session->get('id');
            $userEmail = $session->get('email');
            
            // Log for debugging
            log_message('info', "Monthly report for month {$month}: Found " . count($formattedItems) . " products with activity (transactions or updates). Date range: {$startDate} to {$endDate}");
            
            return $this->response->setJSON([
                'status' => 'success',
                'items' => $formattedItems,
                'month' => $month,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'count' => count($formattedItems),
                'branch' => $branchInfo ? [
                    'id' => $branchInfo['id'],
                    'name' => $branchInfo['name'] ?? 'N/A',
                    'address' => $branchInfo['address'] ?? 'N/A',
                    'code' => $branchInfo['code'] ?? 'N/A'
                ] : null,
                'user' => [
                    'id' => $userId,
                    'email' => $userEmail
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching monthly items: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error fetching monthly items: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete an inventory item (soft delete)
     */
    public function deleteItem($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Item ID is required'
            ]);
        }
        
        try {
            $item = $this->model->find($id);
            
            if (!$item) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Item not found'
                ]);
            }
            
            // Check branch access
            $role = $session->get('role');
            $branchId = $session->get('branch_id');
            $enforceBranchScope = !in_array($role, ['central_admin', 'superadmin']);
            
            if ($enforceBranchScope && $branchId && (int)$item['branch_id'] !== (int)$branchId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Not authorized to delete this item'
                ]);
            }
            
            // Soft delete - set deleted_at timestamp
            $this->model->update($id, [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);
            
            log_message('info', "Item {$id} soft deleted by user {$session->get('user_id')}");
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Item deleted successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting item: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error deleting item: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Restore a deleted inventory item
     */
    public function restoreItem($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Item ID is required'
            ]);
        }
        
        try {
            $item = $this->model->find($id);
            
            if (!$item) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Item not found'
                ]);
            }
            
            // Check branch access
            $role = $session->get('role');
            $branchId = $session->get('branch_id');
            $enforceBranchScope = !in_array($role, ['central_admin', 'superadmin']);
            
            if ($enforceBranchScope && $branchId && (int)$item['branch_id'] !== (int)$branchId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Not authorized to restore this item'
                ]);
            }
            
            // Restore - clear deleted_at timestamp
            $this->model->update($id, [
                'deleted_at' => null
            ]);
            
            log_message('info', "Item {$id} restored by user {$session->get('user_id')}");
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Item restored successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error restoring item: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error restoring item: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get single item by ID (for barcode scanner)
     */
    public function getItem($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Item ID is required'
            ]);
        }
        
        try {
            // Get item with all related data
            $item = $this->db->table('products p')
                ->select('p.*, b.name AS branch_name, b.code AS branch_code, b.address AS branch_location, c.name AS category')
                ->join('branches b', 'b.id = p.branch_id', 'left')
                ->join('categories c', 'c.id = p.category_id', 'left')
                ->where('p.id', $id)
                ->where('p.deleted_at IS NULL') // Exclude deleted items
                ->get()
                ->getRowArray();
            
            if (!$item) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Item not found'
                ]);
            }
            
            // Check branch access
            $role = $session->get('role');
            $branchId = $session->get('branch_id');
            $enforceBranchScope = !in_array($role, ['central_admin', 'superadmin']);
            
            if ($enforceBranchScope && $branchId && (int)$item['branch_id'] !== (int)$branchId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Not authorized to view this item'
                ]);
            }
            
            // Process item data similar to getInventory
            $item['status'] = $this->model->calculateStatus($item);
            $item = $this->model->processTimestamps($item);
            $item['stock_qty'] = (int)($item['stock_qty'] ?? 0);
            $item['min_stock'] = (int)($item['min_stock'] ?? 0);
            $item['max_stock'] = (int)($item['max_stock'] ?? 0);
            $item['price'] = (float)($item['price'] ?? 0);
            $item['branch_label'] = $item['branch_name'] ?? ($item['branch_code'] ?? 'Unassigned');
            
            return $this->response->setJSON([
                'status' => 'success',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching item: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error fetching item: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate barcode for an item if it doesn't have one
     */
    public function generateBarcode($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Item ID is required'
            ]);
        }
        
        try {
            $item = $this->model->find($id);
            
            if (!$item) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Item not found'
                ]);
            }
            
            // Check branch access
            $role = $session->get('role');
            $branchId = $session->get('branch_id');
            $enforceBranchScope = !in_array($role, ['central_admin', 'superadmin']);
            
            if ($enforceBranchScope && $branchId && (int)$item['branch_id'] !== (int)$branchId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Not authorized to generate barcode for this item'
                ]);
            }
            
            // Check if barcode already exists
            if (!empty($item['barcode']) && trim($item['barcode']) !== '') {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Item already has a barcode',
                    'barcode' => $item['barcode']
                ]);
            }
            
            // Generate unique barcode: PROD-{ID}-{TIMESTAMP}
            $barcode = 'PROD-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '-' . date('YmdHis');
            
            // Ensure uniqueness
            $existing = $this->db->table('products')
                ->where('barcode', $barcode)
                ->get()
                ->getRowArray();
            
            if ($existing) {
                // If exists, add random suffix
                $barcode = $barcode . '-' . rand(1000, 9999);
            }
            
            // Update item with barcode using direct database update to avoid allowedFields restriction
            $updateResult = $this->db->table('products')
                ->where('id', $id)
                ->update([
                    'barcode' => $barcode,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            if ($updateResult === false) {
                $error = $this->db->error();
                log_message('error', 'Failed to update barcode: ' . json_encode($error));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update barcode: ' . ($error['message'] ?? 'Unknown error')
                ]);
            }
            
            log_message('info', "Barcode generated for item {$id}: {$barcode}");
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Barcode generated successfully',
                'barcode' => $barcode
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error generating barcode: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error generating barcode: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Notify central admin when monthly report is generated
     */
    public function notifyReportGenerated()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        try {
            $branchId = $this->request->getPost('branch_id');
            $branchName = $this->request->getPost('branch_name') ?? 'Unknown Branch';
            $branchAddress = $this->request->getPost('branch_address') ?? 'N/A';
            $month = $this->request->getPost('month') ?? date('F Y');
            $itemCount = $this->request->getPost('item_count') ?? 0;
            
            $userId = $session->get('user_id') ?? $session->get('id');
            $userEmail = $session->get('email') ?? 'Unknown User';
            
            // Create notification for central admin
            $notificationData = [
                'user_id' => null, // Broadcast to all central admins
                'role' => 'central_admin',
                'type' => 'info',
                'title' => 'Monthly Inventory Report Generated',
                'message' => "Branch: {$branchName} ({$branchAddress}) has generated a monthly inventory report for {$month}. Total items: {$itemCount}. Generated by: {$userEmail}",
                'link' => base_url('staff/dashboard'),
                'related_table' => 'branches',
                'related_id' => $branchId
            ];
            
            $notificationId = $this->notificationService->notify($notificationData, false, false);
            
            if ($notificationId) {
                log_message('info', "Monthly report notification created for branch {$branchName} (ID: {$branchId})");
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Notification sent to central admin',
                    'notification_id' => $notificationId
                ]);
            } else {
                log_message('warning', "Failed to create monthly report notification for branch {$branchName}");
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to create notification'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating report notification: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error creating notification: ' . $e->getMessage()
            ]);
        }
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
            
            // Update created_by if it's NULL
            if (empty($category['created_by']) || $category['created_by'] === null) {
                $userId = $session->get('user_id');
                if (!$userId) {
                    // Fallback to first inventory staff if session user_id is not available
                    $invUser = $this->db->table('users')
                        ->where('role', 'inventory_staff')
                        ->limit(1)
                        ->get()
                        ->getRowArray();
                    $userId = $invUser ? $invUser['id'] : null;
                }
                if ($userId) {
                    $categoryModel->update($category['id'], ['created_by' => $userId]);
                }
            }
        } else {
            // Create category if it doesn't exist
            $userId = $session->get('user_id');
            if (!$userId) {
                // Fallback to first inventory staff if session user_id is not available
                $invUser = $this->db->table('users')
                    ->where('role', 'inventory_staff')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
                $userId = $invUser ? $invUser['id'] : null;
            }
            
            $categoryModel->insert([
                'name' => $categoryName,
                'status' => 'active',
                'created_by' => $userId,
            ]);
            $categoryId = $categoryModel->getInsertID();
        }

        // Ensure created_by is set (fallback to inventory staff if session user_id is not available)
        $userId = $session->get('user_id');
        if (!$userId) {
            // Fallback to first inventory staff if session user_id is not available
            $invUser = $this->db->table('users')
                ->where('role', 'inventory_staff')
                ->limit(1)
                ->get()
                ->getRowArray();
            $userId = $invUser ? $invUser['id'] : null;
        }
        
        // Prepare data for insertion (normalized - no branch_address or category)
        $now = date('Y-m-d H:i:s');
        $payload = [
            'name'           => $name,
            'barcode'        => $this->request->getPost('barcode') ?: null,
            'category_id'   => $categoryId,
            'unit'          => $this->request->getPost('unit') ?? 'pcs',
            'price'         => (float)($this->request->getPost('price') ?? 0),
            'stock_qty'     => $stock,
            'min_stock'     => (int)($this->request->getPost('min_stock') ?? 0),
            'max_stock'     => (int)($this->request->getPost('max_stock') ?? 0),
            'branch_id'     => $branchId,
            'expiry'        => $this->request->getPost('expiry') ?: null,
            'created_by'    => $userId,
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
            
            // Get branch name for notification
            $branch = $this->branchModel->find($branchId);
            $product['branch_name'] = $branch['name'] ?? 'Unknown Branch';
            
            // Send notification
            $this->notificationService->notifyProductUpdate('created', $product, $userId);

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

        if ($ok) {
            // Check for low stock after update
            $updatedProduct = $this->model->find((int)$id);
            $branch = $this->branchModel->find($updatedProduct['branch_id']);
            $updatedProduct['branch_name'] = $branch['name'] ?? 'Unknown Branch';
            
            if ($updatedProduct['min_stock'] > 0 && $newQty <= $updatedProduct['min_stock']) {
                $this->notificationService->notifyLowStock($updatedProduct);
            }
            
            // Notify about stock update
            $this->notificationService->notifyProductUpdate('updated', $updatedProduct, $session->get('user_id'));
            
            return $this->response->setJSON(['status' => 'success', 'stock_qty' => $newQty]);
        }

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
     * Get branches for transfer (only 5 specific branches: LANANG, AGDAO, BUHANGIN, TORIL, MATINA)
     * Excludes current branch and Toril if user is Toril branch manager
     */
    public function getTransferBranches()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventorystaff', 'inventory_staff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $currentBranchId = $session->get('branch_id');
            
            // Get current branch info to check if it's Toril
            $currentBranch = null;
            if ($currentBranchId) {
                $currentBranch = $this->branchModel->find($currentBranchId);
            }
            
            // Only include these 5 specific branches (excluding franchise branches)
            $allowedBranchCodes = ['LANANG', 'AGDAO', 'BUHANGIN', 'TORIL', 'MATINA'];
            
            // Build query to get only the allowed branches
            $builder = $this->branchModel
                ->whereIn('code', $allowedBranchCodes)
                ->where('franchise_type', 'company_owned') // Ensure only company-owned branches
                ->orderBy('name', 'ASC');
            
            // Exclude current branch
            if ($currentBranchId) {
                $builder->where('id !=', $currentBranchId);
            }
            
            // If current branch is Toril, exclude Toril from the list
            if ($currentBranch && strtoupper($currentBranch['code'] ?? '') === 'TORIL') {
                $builder->where('code !=', 'TORIL');
            }
            
            $branches = $builder->findAll();
            
            return $this->response->setJSON([
                'status' => 'success',
                'branches' => $branches
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error getting transfer branches: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error loading branches']);
        }
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
        $transferBranchId = $this->request->getPost('transfer_branch_id') ? (int)$this->request->getPost('transfer_branch_id') : null;

        if ($productId <= 0 || $quantity <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid product or quantity']);
        }

        // If reason is transfer, validate transfer branch
        if (stripos($reason, 'transfer') !== false && !$transferBranchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please select a branch to transfer to']);
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
        
        // Build notes with transfer branch info if applicable
        $finalNotes = $reason;
        $transferBranch = null;
        if ($transferBranchId) {
            $transferBranch = $this->branchModel->find($transferBranchId);
            $transferBranchName = $transferBranch ? $transferBranch['name'] : 'Unknown Branch';
            $finalNotes .= ' to ' . $transferBranchName;
        }
        if ($notes) {
            $finalNotes .= ($finalNotes ? ': ' : '') . $notes;
        }

        // Start database transaction for transfer
        $this->db->transStart();

        // Record STOCK-OUT transaction at source branch
        $stockOutRecorded = $this->stockTransactionModel->recordStockOut(
            $productId,
            $quantity,
            null, // reference_id
            'stock_out', // reference_type
            $userId, // created_by
            $finalNotes
        );

        if (!$stockOutRecorded) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to record stock out']);
        }

        // If this is a transfer, create stock in at destination branch
        if ($transferBranchId && $transferBranch) {
            // Find or create product at destination branch
            $destinationProduct = $this->db->table('products')
                ->where('name', $product['name'])
                ->where('branch_id', $transferBranchId)
                ->where('deleted_at IS NULL')
                ->get()
                ->getRowArray();

            $destinationProductId = null;
            
            if ($destinationProduct) {
                // Product exists at destination branch, use it
                $destinationProductId = (int)$destinationProduct['id'];
            } else {
                // Create new product at destination branch
                $newProductData = [
                    'name' => $product['name'],
                    'branch_id' => $transferBranchId,
                    'category_id' => $product['category_id'] ?? null,
                    'price' => $product['price'] ?? 0,
                    'stock_qty' => 0, // Will be updated by stock-in
                    'unit' => $product['unit'] ?? 'pcs',
                    'min_stock' => $product['min_stock'] ?? 0,
                    'max_stock' => $product['max_stock'] ?? 0,
                    'barcode' => $product['barcode'] ?? null,
                    'expiry' => $product['expiry'] ?? null,
                    'status' => 'active',
                    'created_by' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $insertResult = $this->db->table('products')->insert($newProductData);
                if (!$insertResult) {
                    $this->db->transRollback();
                    $error = $this->db->error();
                    log_message('error', 'Failed to create product at destination branch: ' . json_encode($error));
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create product at destination branch']);
                }

                $destinationProductId = $this->db->insertID();
            }

            // Record STOCK-IN transaction at destination branch
            $transferNotes = 'Transfer from ' . ($this->branchModel->find($branchId)['name'] ?? 'Unknown Branch');
            if ($notes) {
                $transferNotes .= ': ' . $notes;
            }

            $stockInRecorded = $this->stockTransactionModel->recordStockIn(
                $destinationProductId,
                $quantity,
                null, // reference_id
                'branch_transfer', // reference_type
                $userId, // created_by
                $product['expiry'] ?? null
            );

            if (!$stockInRecorded) {
                $this->db->transRollback();
                log_message('error', 'Failed to record stock in at destination branch');
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to record stock in at destination branch']);
            }
        }

        // Complete transaction
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $transferBranchId ? 'Stock transferred successfully' : 'Stock out recorded successfully'
        ]);
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
                // Use trackDelivery to get full details including payment status
                $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
                if ($deliveryDetails) {
                    $formattedDeliveries[] = $deliveryDetails;
                } else {
                    // Fallback to basic data if trackDelivery fails
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