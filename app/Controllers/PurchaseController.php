<?php

namespace App\Controllers;

use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\AccountsPayableModel;
use App\Models\SupplierModel;
use Config\Database;

class PurchaseController extends BaseController
{
    protected $db;
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $productModel;
    protected $branchModel;
    protected $accountsPayableModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->accountsPayableModel = new AccountsPayableModel();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Create a new purchase request
     */
    public function createRequest()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        // Get user ID - validate it exists
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID not found in session']);
        }

        $branchId = $session->get('branch_id');
        if (!$branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch not assigned']);
        }

        $json = $this->request->getJSON(true);
        $items = $json['items'] ?? null;
        $branchName = $json['branch'] ?? null;
        $priority = $json['priority'] ?? 'normal';
        $notes = $json['notes'] ?? '';
        
        if (!$branchName) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch is required']);
        }

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Items are required']);
        }

        // Extract branch code from branch name (e.g., "AGDAO Branch, Davao City" -> "AGDAO")
        $branchCode = strtoupper(explode(' ', $branchName)[0]);
        
        // Get branch ID from database using branch code
        $branchRecord = $this->db->table('branches')
            ->where('code', $branchCode)
            ->get()
            ->getRow();
        
        if (!$branchRecord) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid branch selected']);
        }
        
        $branchId = $branchRecord->id;

        $totalAmount = 0;
        foreach ($items as $item) {
            $quantity = (float)($item['quantity'] ?? 0);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            $totalAmount += $quantity * $unitPrice;
        }

        try {
            // Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');
            
            // Generate request number
            $requestNumber = 'PR-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Insert purchase request
            $requestData = [
                'request_number' => $requestNumber,
                'branch_id' => (int)$branchId,
                'requested_by' => (int)$userId,
                'priority' => $priority,
                'total_amount' => (float)$totalAmount,
                'notes' => $notes,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $insertResult = $this->db->table('purchase_requests')->insert($requestData);
            
            if (!$insertResult) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $error = $this->db->error();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create purchase request', 'db_error' => $error]);
            }
            
            $requestId = $this->db->insertID();
            
            // Insert items
            $itemsInserted = 0;
            foreach ($items as $item) {
                $quantity = (int)($item['quantity'] ?? 0);
                $unitPrice = (float)($item['unit_price'] ?? 0);
                
                if ($quantity <= 0) {
                    log_message('debug', 'Skipping item with quantity <= 0');
                    continue;
                }
                
                // Get product_id from product name and category
                $productId = null;
                
                // If product_id is already provided, use it
                if (!empty($item['product_id'])) {
                    $productId = (int)$item['product_id'];
                } else {
                    // Look up product by name and category
                    $itemName = $item['item_name'] ?? '';
                    $categoryName = $item['category'] ?? '';
                    
                    if (empty($itemName)) {
                        log_message('warning', 'Item missing item_name, skipping');
                        continue;
                    }
                    
                    // Normalize item name for matching
                    $normalizedItemName = trim(preg_replace('/\s+/', ' ', $itemName));
                    $searchName = strtolower($normalizedItemName);
                    $categoryLower = strtolower(trim($categoryName));
                    
                    log_message('debug', 'Looking up product: "' . $itemName . '" (search: "' . $searchName . '") in category: "' . $categoryName . '"');
                    
                    // Get all products and search in PHP (more reliable than complex SQL)
                    $allProducts = $this->db->table('products')
                        ->select('products.*, categories.name as category_name')
                        ->join('categories', 'categories.id = products.category_id', 'left')
                        ->get()
                        ->getResultArray();
                    
                    $product = null;
                    
                    // Strategy 1: Exact name match with category
                    foreach ($allProducts as $p) {
                        $pName = strtolower(trim($p['name'] ?? ''));
                        $pCategory = strtolower(trim($p['category_name'] ?? ''));
                        
                        if ($pName === $searchName && (!$categoryLower || $pCategory === $categoryLower)) {
                            $product = $p;
                            log_message('debug', '✅ Found product ID ' . $p['id'] . ' for item: "' . $itemName . '" (exact match)');
                            break;
                        }
                    }
                    
                    // Strategy 2: Case-insensitive name match (without category)
                    if (!$product) {
                        foreach ($allProducts as $p) {
                            $pName = strtolower(trim($p['name'] ?? ''));
                            if ($pName === $searchName) {
                                $product = $p;
                                log_message('debug', '✅ Found product ID ' . $p['id'] . ' for item: "' . $itemName . '" (name match only)');
                                break;
                            }
                        }
                    }
                    
                    // Strategy 3: Partial match (product name contains search or vice versa)
                    if (!$product) {
                        foreach ($allProducts as $p) {
                            $pName = strtolower(trim($p['name'] ?? ''));
                            if (strpos($pName, $searchName) !== false || strpos($searchName, $pName) !== false) {
                                $product = $p;
                                log_message('debug', '✅ Found product ID ' . $p['id'] . ' for item: "' . $itemName . '" (partial match: "' . $p['name'] . '")');
                                break;
                            }
                        }
                    }
                    
                    // Strategy 4: First word match
                    if (!$product) {
                        $words = explode(' ', $searchName);
                        $firstWord = $words[0] ?? '';
                        if (strlen($firstWord) > 2) {
                            foreach ($allProducts as $p) {
                                $pName = strtolower(trim($p['name'] ?? ''));
                                if (strpos($pName, $firstWord) === 0) {
                                    $product = $p;
                                    log_message('debug', '✅ Found product ID ' . $p['id'] . ' for item: "' . $itemName . '" (first word match: "' . $p['name'] . '")');
                                    break;
                                }
                            }
                        }
                    }
                    
                    if ($product) {
                        $productId = (int)$product['id'];
                    } else {
                        log_message('warning', '❌ Product not found for item: "' . $itemName . '" in category: "' . $categoryName . '" - will create new');
                        
                        // Create a new product entry
                        $categoryId = null;
                        if (!empty($categoryName)) {
                            // Get category ID
                            $category = $this->db->table('categories')
                                ->where('name', trim($categoryName))
                                ->get()
                                ->getRowArray();
                            
                            if (!$category) {
                                // Try case-insensitive
                                foreach ($this->db->table('categories')->get()->getResultArray() as $cat) {
                                    if (strtolower(trim($cat['name'])) === $categoryLower) {
                                        $category = $cat;
                                        break;
                                    }
                                }
                            }
                            
                            if ($category) {
                                $categoryId = (int)$category['id'];
                            }
                        }
                        
                        // If still no category, use first category or create one
                        if (!$categoryId) {
                            $firstCategory = $this->db->table('categories')->limit(1)->get()->getRowArray();
                            $categoryId = $firstCategory ? (int)$firstCategory['id'] : null;
                        }
                        
                        if ($categoryId) {
                            // Create product
                            $newProductData = [
                                'name' => trim($itemName),
                                'category_id' => $categoryId,
                                'branch_id' => (int)$branchId,
                                'price' => $unitPrice,
                                'stock_qty' => 0,
                                'unit' => 'pcs',
                                'status' => 'active',
                                'created_by' => (int)$userId,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];
                            
                            $this->db->table('products')->insert($newProductData);
                            $productId = $this->db->insertID();
                            log_message('info', '✅ Created new product ID ' . $productId . ' for item: "' . $itemName . '"');
                        } else {
                            log_message('error', '❌ Could not create product - no category available');
                        }
                    }
                }
                
                if ($productId && $productId > 0) {
                    $insertResult = $this->db->table('purchase_request_items')->insert([
                        'purchase_request_id' => (int)$requestId,
                        'product_id' => (int)$productId,
                        'quantity' => (int)$quantity,
                        'unit_price' => (float)$unitPrice,
                        'subtotal' => (float)($quantity * $unitPrice),
                        'notes' => $item['notes'] ?? '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    if ($insertResult) {
                        $itemsInserted++;
                        log_message('debug', 'Inserted purchase request item: product_id=' . $productId . ', qty=' . $quantity);
                    } else {
                        $dbError = $this->db->error();
                        log_message('error', 'Failed to insert purchase request item. DB error: ' . json_encode($dbError));
                    }
                } else {
                    log_message('warning', 'Skipping item - could not determine product_id for: ' . ($item['item_name'] ?? 'unknown'));
                }
            }
            
            log_message('info', 'Inserted ' . $itemsInserted . ' items for purchase request ' . $requestId);
            
            if ($itemsInserted === 0) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                // Delete the purchase request if no items were inserted
                $this->db->table('purchase_requests')->where('id', $requestId)->delete();
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'No valid items were found. Please ensure products exist in inventory.',
                    'debug' => [
                        'items_received' => count($items),
                        'items_processed' => $itemsInserted
                    ]
                ]);
            }
            
            // Verify items were actually saved
            $savedItemsCount = $this->db->table('purchase_request_items')
                ->where('purchase_request_id', $requestId)
                ->countAllResults();
            
            log_message('info', 'Verified ' . $savedItemsCount . ' items saved in database for request ' . $requestId);
            
            if ($savedItemsCount === 0) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $this->db->table('purchase_requests')->where('id', $requestId)->delete();
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Failed to save items to database. Please try again.',
                    'debug' => [
                        'items_inserted' => $itemsInserted,
                        'items_in_db' => $savedItemsCount
                    ]
                ]);
            }
            
            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase request created successfully with ' . $savedItemsCount . ' items.',
                'request_id' => $requestId,
                'request_number' => $requestNumber,
                'branch' => $branchName,
                'items_received' => count($items),
                'items_saved' => $savedItemsCount,
                'total_amount' => $totalAmount
            ]);
        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Get purchase requests
     */
    public function getRequests()
    {
        try {
            $session = session();
            
            if (!$session->get('logged_in')) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
            }

            $userId = $session->get('user_id');
            $role = $session->get('role');
            $status = $this->request->getGet('status');
            $priority = $this->request->getGet('priority');

            $filters = [];
            if ($status) $filters['status'] = $status;
            if ($priority) $filters['priority'] = $priority;

            if (in_array($role, ['central_admin', 'superadmin'])) {
                // Central admin can see all requests
                $builder = $this->purchaseRequestModel;
                
                if (!empty($filters['status']) && $status !== 'all') {
                    $builder = $builder->where('status', $filters['status']);
                }
                if (!empty($filters['priority']) && $priority !== 'all') {
                    $builder = $builder->where('priority', $filters['priority']);
                }
                
                $requests = $builder->orderBy('created_at', 'DESC')->findAll();
            } else {
                // Branch managers see requests they created
                $builder = $this->purchaseRequestModel->where('requested_by', $userId);
                
                if (!empty($filters['status']) && $status !== 'all') {
                    $builder->where('status', $filters['status']);
                }
                if (!empty($filters['priority']) && $priority !== 'all') {
                    $builder->where('priority', $filters['priority']);
                }
                
                $requests = $builder->orderBy('created_at', 'DESC')->findAll();
            }

            // Get full details for each request
            $detailedRequests = [];
            foreach ($requests as $request) {
                try {
                    $detailed = $this->purchaseRequestModel->getRequestWithItems($request['id']);
                    if ($detailed) {
                        $detailedRequests[] = $detailed;
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error loading request ' . $request['id'] . ': ' . $e->getMessage());
                    // Continue with other requests
                }
            }

            log_message('debug', 'Purchase requests API: Returning ' . count($detailedRequests) . ' requests');

            return $this->response->setJSON([
                'status' => 'success',
                'requests' => $detailedRequests,
                'count' => count($detailedRequests)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Exception in getRequests: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to load purchase requests: ' . $e->getMessage(),
                'requests' => []
            ]);
        }
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
     * Approve purchase request and automatically create PO
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

        // Get delivery details from POST
        $driverName = $this->request->getPost('driver_name');
        $vehicleInfo = $this->request->getPost('vehicle_info');
        $scheduledDeliveryDate = $this->request->getPost('scheduled_delivery_date');

        try {
            // Step 1: Approve the purchase request
            $approved = $this->purchaseRequestModel->approveRequest((int)$id, $session->get('user_id'));

            if (!$approved) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve request']);
            }

            // Step 2: Get the approved request with items
            $requestWithItems = $this->purchaseRequestModel->getRequestWithItems((int)$id);

            if (!$requestWithItems) {
                log_message('error', 'Request ' . $id . ' not found after approval');
                return $this->response->setJSON(['status' => 'error', 'message' => 'Request not found']);
            }

            // Check if items exist
            if (empty($requestWithItems['items']) || !is_array($requestWithItems['items']) || count($requestWithItems['items']) === 0) {
                log_message('error', 'Request ' . $id . ' has no items. Items count: ' . (is_array($requestWithItems['items']) ? count($requestWithItems['items']) : 'not array'));
                
                // Check if items exist in database
                $itemsCheck = $this->db->table('purchase_request_items')
                    ->where('purchase_request_id', (int)$id)
                    ->countAllResults();
                
                log_message('error', 'Items in database for request ' . $id . ': ' . $itemsCheck);
                
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Request has no items. Please ensure the purchase request was created with valid products.',
                    'debug' => [
                        'request_id' => $id,
                        'items_in_db' => $itemsCheck,
                        'items_in_response' => is_array($requestWithItems['items']) ? count($requestWithItems['items']) : 'not array'
                    ]
                ]);
            }

            // Store items separately
            $requestItems = $requestWithItems['items'];

            // Step 3: Get supplier ID from selected_supplier_id or request or use default
            $request = $this->purchaseRequestModel->find((int)$id);
            $supplierId = null;
            
            // First check if supplier was already selected
            if (!empty($request['selected_supplier_id'])) {
                $supplierId = (int)$request['selected_supplier_id'];
            } 
            // Then check if supplier_id is provided in POST
            elseif ($this->request->getPost('supplier_id')) {
                $supplierId = (int)$this->request->getPost('supplier_id');
            }
            
            if ($supplierId) {
                // Validate that the supplier exists and is active
                $supplier = $this->db->table('suppliers')
                    ->where('id', $supplierId)
                    ->where('status', 'active')
                    ->get()
                    ->getRowArray();
                
                if (!$supplier) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Selected supplier not found or inactive']);
                }
            } else {
                // Fallback to first active supplier if none selected
                $suppliers = $this->db->table('suppliers')->where('status', 'active')->limit(1)->get()->getResultArray();
                
                if (empty($suppliers)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'No active suppliers available. Please select a supplier first.']);
                }

                $supplierId = (int)$suppliers[0]['id'];
            }

            // Step 4: Generate unique order number
            $prefix = 'PO';
            $date = date('Ymd');
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . '-' . $date . '-' . $random;

            // Step 5: Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            // Step 6: Create purchase order with pending status
            $poData = [
                'order_number' => $orderNumber,
                'purchase_request_id' => (int)$request['id'],
                'supplier_id' => (int)$supplierId,
                'branch_id' => (int)$request['branch_id'],
                'order_date' => date('Y-m-d'),
                'expected_delivery_date' => date('Y-m-d', strtotime('+7 days')),
                'total_amount' => (float)$request['total_amount'],
                'notes' => 'Auto-created from approved purchase request',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $poInsertResult = $this->db->table('purchase_orders')->insert($poData);

            if (!$poInsertResult) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $dbError = $this->db->error();
                log_message('error', 'Failed to auto-create PO. DB error: ' . json_encode($dbError));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Request approved but failed to create PO', 'db_error' => $dbError]);
            }

            $poId = $this->db->insertID();
            log_message('info', 'Auto-created purchase order with ID: ' . $poId);

            // Step 7: Create purchase order items from request items
            $itemsInserted = 0;
            foreach ($requestItems as $item) {
                $itemData = [
                    'purchase_order_id' => (int)$poId,
                    'product_id' => (int)$item['product_id'],
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => (float)$item['unit_price'],
                    'subtotal' => (float)$item['subtotal'],
                    'received_quantity' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $itemInserted = $this->db->table('purchase_order_items')->insert($itemData);
                if ($itemInserted) {
                    $itemsInserted++;
                    log_message('debug', 'Inserted PO item: product_id=' . $item['product_id'] . ', qty=' . $item['quantity']);
                } else {
                    $dbError = $this->db->error();
                    log_message('error', 'Failed to insert PO item. DB error: ' . json_encode($dbError));
                }
            }

            // Step 8: Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');

            log_message('info', 'Inserted ' . $itemsInserted . ' items for auto-created purchase order ' . $poId);

            // Step 9: Update request status to converted_to_po
            $this->purchaseRequestModel->updateStatus((int)$id, 'converted_to_po');

            // Step 10: Auto-create delivery record with delivery details
            $deliveryDetails = [
                'driver_name' => $driverName,
                'vehicle_info' => $vehicleInfo,
                'scheduled_delivery_date' => $scheduledDeliveryDate
            ];
            $this->autoCreateDelivery($poId, $poData, $deliveryDetails);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase request approved! Purchase Order automatically created with ' . $itemsInserted . ' items. Status: Pending supplier approval.',
                'po_id' => $poId,
                'items_count' => $itemsInserted
            ]);
        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            log_message('error', 'Exception in approveRequest: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Accept/Select supplier for purchase request (without approving)
     */
    public function acceptSupplier($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request ID required']);
        }

        $supplierId = $this->request->getPost('supplier_id');
        
        if (!$supplierId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier ID required']);
        }

        // Validate that the supplier exists and is active
        $supplier = $this->db->table('suppliers')
            ->where('id', (int)$supplierId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();
        
        if (!$supplier) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Selected supplier not found or inactive']);
        }

        // Update purchase request with selected supplier
        $updated = $this->purchaseRequestModel->update((int)$id, [
            'selected_supplier_id' => (int)$supplierId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Supplier selected successfully',
                'supplier_name' => $supplier['name']
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to select supplier']);
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
        $rejected = $this->purchaseRequestModel->rejectRequest((int)$id, $session->get('user_id'), $reason);

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

        if (!$request) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request not found']);
        }

        if ($request['status'] !== 'approved') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request must be approved first. Current status: ' . $request['status']]);
        }

        if (empty($request['items'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Request has no items']);
        }

        try {
            // Disable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            // Create purchase order with pending status (waiting for supplier approval)
            $poData = [
                'purchase_request_id' => (int)$request['id'],
                'supplier_id' => (int)$supplierId,
                'branch_id' => (int)$request['branch_id'],
                'order_date' => date('Y-m-d'),
                'expected_delivery_date' => $expectedDeliveryDate ?: date('Y-m-d', strtotime('+7 days')),
                'total_amount' => (float)$request['total_amount'],
                'notes' => $notes,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $poInsertResult = $this->db->table('purchase_orders')->insert($poData);

            if (!$poInsertResult) {
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                $dbError = $this->db->error();
                log_message('error', 'Failed to insert purchase order. DB error: ' . json_encode($dbError));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create purchase order', 'db_error' => $dbError]);
            }

            $poId = $this->db->insertID();
            log_message('info', 'Purchase order created with ID: ' . $poId);

            // Create purchase order items from request items
            $itemsInserted = 0;
            foreach ($request['items'] as $item) {
                $itemData = [
                    'purchase_order_id' => (int)$poId,
                    'product_id' => (int)$item['product_id'],
                    'quantity' => (int)$item['quantity'],
                    'unit_price' => (float)$item['unit_price'],
                    'subtotal' => (float)$item['subtotal'],
                    'received_quantity' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $itemInserted = $this->db->table('purchase_order_items')->insert($itemData);
                if ($itemInserted) {
                    $itemsInserted++;
                    log_message('debug', 'Inserted PO item: product_id=' . $item['product_id'] . ', qty=' . $item['quantity']);
                } else {
                    $dbError = $this->db->error();
                    log_message('error', 'Failed to insert PO item. DB error: ' . json_encode($dbError));
                }
            }

            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');

            log_message('info', 'Inserted ' . $itemsInserted . ' items for purchase order ' . $poId);

            // Update request status
            $this->purchaseRequestModel->updateStatus((int)$id, 'converted_to_po');

            // Auto-create delivery record when PO is created (with delivery details if provided)
            $deliveryDetails = [
                'driver_name' => $this->request->getPost('driver_name'),
                'vehicle_info' => $this->request->getPost('vehicle_info'),
                'scheduled_delivery_date' => $expectedDeliveryDate
            ];
            $this->autoCreateDelivery($poId, $poData, $deliveryDetails);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Purchase order created successfully with ' . $itemsInserted . ' items. Status: Pending supplier approval. Delivery record created.',
                'po_id' => $poId,
                'items_count' => $itemsInserted
            ]);
        } catch (\Exception $e) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            log_message('error', 'Exception in convertToPO: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Auto-create delivery record when PO is approved
     */
    private function autoCreateDelivery(int $poId, array $poData, array $deliveryDetails = []): void
    {
        try {
            log_message('debug', 'autoCreateDelivery called for PO ' . $poId);
            log_message('debug', 'poData: ' . json_encode($poData));
            log_message('debug', 'deliveryDetails: ' . json_encode($deliveryDetails));
            
            // Create delivery record automatically with delivery details
            $session = session();
            $userId = $session->get('user_id') ?? $session->get('id');
            
            // Ensure scheduled_date has a valid value
            $scheduledDate = null;
            if (!empty($deliveryDetails['scheduled_delivery_date'])) {
                $scheduledDate = $deliveryDetails['scheduled_delivery_date'];
            } elseif (!empty($poData['expected_delivery_date'])) {
                $scheduledDate = $poData['expected_delivery_date'];
            } else {
                // Default to 3 days from now
                $scheduledDate = date('Y-m-d', strtotime('+3 days'));
            }
            
            // Ensure supplier_id and branch_id are valid
            $supplierId = (int)($poData['supplier_id'] ?? 0);
            $branchId = (int)($poData['branch_id'] ?? 0);
            
            if (!$supplierId) {
                // Get default supplier
                $defaultSupplier = $this->db->table('suppliers')
                    ->where('status', 'active')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
                $supplierId = $defaultSupplier ? (int)$defaultSupplier['id'] : 0;
                log_message('debug', 'Using default supplier ID: ' . $supplierId);
            }
            
            if (!$branchId) {
                // Get default branch
                $defaultBranch = $this->db->table('branches')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
                $branchId = $defaultBranch ? (int)$defaultBranch['id'] : 0;
                log_message('debug', 'Using default branch ID: ' . $branchId);
            }
            
            if (!$supplierId || !$branchId) {
                log_message('error', 'Cannot create delivery - missing supplier_id or branch_id');
                return;
            }
            
            // Generate delivery number
            $deliveryNumber = 'DEL-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Use direct database insert to bypass model validation issues
            $deliveryData = [
                'delivery_number' => $deliveryNumber,
                'purchase_order_id' => $poId,
                'supplier_id' => $supplierId,
                'branch_id' => $branchId,
                'scheduled_by' => $userId ? (int)$userId : null,
                'scheduled_date' => $scheduledDate,
                'driver_name' => $deliveryDetails['driver_name'] ?? null,
                'vehicle_info' => $deliveryDetails['vehicle_info'] ?? null,
                'status' => 'scheduled',
                'notes' => 'Auto-created from Purchase Order #' . $poId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('debug', 'Inserting delivery: ' . json_encode($deliveryData));
            
            // Insert directly using database builder
            $inserted = $this->db->table('deliveries')->insert($deliveryData);
            
            if ($inserted) {
                $deliveryId = $this->db->insertID();
                log_message('info', '✅ Delivery record created with ID ' . $deliveryId . ' for PO ' . $poId);
                
                // Create delivery items from PO items
                $poItems = $this->db->table('purchase_order_items')
                    ->where('purchase_order_id', $poId)
                    ->get()
                    ->getResultArray();
                
                foreach ($poItems as $poItem) {
                    $this->db->table('delivery_items')->insert([
                        'delivery_id' => $deliveryId,
                        'product_id' => $poItem['product_id'],
                        'expected_quantity' => $poItem['quantity'],
                        'received_quantity' => 0,
                        'condition_status' => 'good',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                
                log_message('info', '✅ Created ' . count($poItems) . ' delivery items for delivery ' . $deliveryId);
            } else {
                $error = $this->db->error();
                log_message('error', '❌ Failed to insert delivery: ' . json_encode($error));
            }
        } catch (\Exception $e) {
            log_message('error', '❌ Exception in autoCreateDelivery: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        }
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
        $productFilters = [];
        if ($branchId) {
            $productFilters['branch_id'] = $branchId;
        }
        $products = $this->productModel->getInventory($productFilters);

        // Get categories for filter
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->getActiveCategories();

        return view('dashboards/purchase_request_form', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'products' => $products,
            'categories' => $categories
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

        $userId = $session->get('user_id');
        $role = $session->get('role');
        
        // Get requests based on user role
        if (in_array($role, ['central_admin', 'superadmin'])) {
            // Central admin sees all requests
            $requests = $this->purchaseRequestModel->findAll();
        } else {
            // Branch managers see requests they created
            $requests = $this->purchaseRequestModel->where('requested_by', $userId)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        // Get full details for each request
        foreach ($requests as &$request) {
            $request = $this->purchaseRequestModel->getRequestWithItems($request['id']);
        }

        // Get pending approvals for sidebar badge (only for central admin)
        $dashboardData = ['purchaseRequests' => ['pending_approvals' => 0]];
        if (in_array($role, ['central_admin', 'superadmin'])) {
            $pendingRequests = $this->purchaseRequestModel->getPendingRequests();
            $dashboardData['purchaseRequests']['pending_approvals'] = count($pendingRequests);
        }

        return view('dashboards/purchase_requests_list', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'requests' => $requests,
            'data' => $dashboardData
        ]);
    }

    /**
     * Get a single purchase order with details
     */
    public function getPurchaseOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        try {
            $order = $this->purchaseOrderModel->getOrderWithDetails((int)$id);

            if (!$order) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching purchase order: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to fetch purchase order']);
        }
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

    /**
     * Get all purchase orders with supplier and branch info
     */
    public function getPurchaseOrdersList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $orders = $this->db->table('purchase_orders')
                ->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->orderBy('purchase_orders.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Format the response
            $formattedOrders = [];
            foreach ($orders as $order) {
                $formattedOrders[] = [
                    'id' => $order['id'],
                    'order_number' => $order['order_number'] ?? ('PO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)),
                    'purchase_request_id' => $order['purchase_request_id'],
                    'supplier' => [
                        'id' => $order['supplier_id'], 
                        'name' => $order['supplier_name'] ?? 'N/A'
                    ],
                    'branch' => [
                        'id' => $order['branch_id'], 
                        'name' => $order['branch_name'] ?? 'N/A'
                    ],
                    'status' => $order['status'] ?? 'pending',
                    'total_amount' => (float)($order['total_amount'] ?? 0),
                    'expected_delivery_date' => $order['expected_delivery_date'],
                    'actual_delivery_date' => $order['actual_delivery_date'],
                    'order_date' => $order['order_date'],
                    'created_at' => $order['created_at'],
                    'approved_by' => $order['approved_by'],
                    'approved_at' => $order['approved_at']
                ];
            }

            log_message('debug', 'Purchase orders API: Returning ' . count($formattedOrders) . ' orders');

            return $this->response->setJSON([
                'status' => 'success',
                'orders' => $formattedOrders,
                'count' => count($formattedOrders)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching purchase orders: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch purchase orders: ' . $e->getMessage(),
                'orders' => []
            ]);
        }
    }

    /**
     * Show purchase orders list page
     */
    public function showPurchaseOrdersList()
    {
        $session = session();
        
        // Allow central admin, branch managers, and staff to view purchase orders
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin', 'branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return redirect()->to('/auth/login');
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');
        
        // Fetch purchase orders data server-side
        try {
            $builder = $this->db->table('purchase_orders')
                ->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left');
            
            // Branch managers and staff only see their branch's purchase orders
            if (!in_array($role, ['central_admin', 'superadmin']) && $branchId) {
                $builder->where('purchase_orders.branch_id', $branchId);
            }
            
            $orders = $builder->orderBy('purchase_orders.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Format the data
            $formattedOrders = [];
            foreach ($orders as $order) {
                $formattedOrders[] = [
                    'id' => $order['id'],
                    'order_number' => $order['order_number'] ?? ('PO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)),
                    'purchase_request_id' => $order['purchase_request_id'],
                    'supplier' => [
                        'id' => $order['supplier_id'], 
                        'name' => $order['supplier_name'] ?? 'N/A'
                    ],
                    'branch' => [
                        'id' => $order['branch_id'], 
                        'name' => $order['branch_name'] ?? 'N/A'
                    ],
                    'status' => $order['status'] ?? 'pending',
                    'total_amount' => (float)($order['total_amount'] ?? 0),
                    'expected_delivery_date' => $order['expected_delivery_date'],
                    'actual_delivery_date' => $order['actual_delivery_date'],
                    'order_date' => $order['order_date'],
                    'created_at' => $order['created_at'],
                    'approved_by' => $order['approved_by'],
                    'approved_at' => $order['approved_at']
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching purchase orders: ' . $e->getMessage());
            $formattedOrders = [];
        }

        // Get pending approvals for sidebar badge
        $pendingRequests = $this->purchaseRequestModel->getPendingRequests();
        $dashboardData = [
            'purchaseRequests' => [
                'pending_approvals' => count($pendingRequests)
            ]
        ];

        return view('dashboards/purchase_orders_list', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'orders' => $formattedOrders,
            'data' => $dashboardData
        ]);
    }

    /**
     * Update purchase order details (supplier and delivery date)
     */
    public function updatePurchaseOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        try {
            $supplierId = $this->request->getPost('supplier_id');
            $expectedDeliveryDate = $this->request->getPost('expected_delivery_date');

            if (!$supplierId || !$expectedDeliveryDate) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier and delivery date are required']);
            }

            $order = $this->purchaseOrderModel->find((int)$id);

            if (!$order) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
            }

            // Update PO with new supplier and delivery date
            $updateData = [
                'supplier_id' => (int)$supplierId,
                'expected_delivery_date' => $expectedDeliveryDate,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $updated = $this->db->table('purchase_orders')
                ->where('id', (int)$id)
                ->update($updateData);

            if ($updated) {
                log_message('info', 'Purchase order ' . $id . ' updated by user ' . $session->get('user_id'));
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Purchase order updated successfully!'
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update purchase order']);
        } catch (\Exception $e) {
            log_message('error', 'Exception in updatePurchaseOrder: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve a purchase order (supplier approval)
     */
    public function approvePurchaseOrder($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        try {
            $order = $this->purchaseOrderModel->find((int)$id);

            if (!$order) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
            }

            // Update PO status to approved using direct database query
            $updateData = [
                'status' => 'approved',
                'approved_by' => (int)$session->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Use direct database update for reliability
            $updated = $this->db->table('purchase_orders')
                ->where('id', (int)$id)
                ->update($updateData);

            if ($updated) {
                log_message('info', 'Purchase order ' . $id . ' approved by user ' . $session->get('user_id'));
                
                // Create accounts payable entry
                $this->createAccountsPayableEntry((int)$id, $order, $session->get('user_id'));
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Purchase order approved successfully! Accounts payable entry created.'
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to approve purchase order']);
        } catch (\Exception $e) {
            log_message('error', 'Exception in approvePurchaseOrder: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Create accounts payable entry when purchase order is approved
     */
    private function createAccountsPayableEntry(int $purchaseOrderId, array $order, int $createdBy): bool
    {
        try {
            // Check if accounts payable entry already exists
            $existing = $this->accountsPayableModel
                ->where('purchase_order_id', $purchaseOrderId)
                ->first();
            
            if ($existing) {
                log_message('info', 'Accounts payable entry already exists for PO ' . $purchaseOrderId);
                return true;
            }

            // Get supplier to get payment terms
            $supplier = $this->supplierModel->find($order['supplier_id']);
            $paymentTerms = $supplier['payment_terms'] ?? 'Net 30';
            
            // Calculate due date based on payment terms
            $invoiceDate = date('Y-m-d'); // Use approval date as invoice date
            $dueDate = $this->accountsPayableModel->calculateDueDate($paymentTerms, $invoiceDate);
            
            // Generate invoice number
            $invoiceNumber = $this->accountsPayableModel->generateInvoiceNumber();
            
            // Create accounts payable entry
            $apData = [
                'purchase_order_id' => $purchaseOrderId,
                'supplier_id' => (int)$order['supplier_id'],
                'branch_id' => (int)$order['branch_id'],
                'invoice_number' => $invoiceNumber, // Auto-generated invoice number
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'amount' => (float)$order['total_amount'],
                'paid_amount' => 0.00,
                'balance' => (float)$order['total_amount'],
                'payment_status' => 'unpaid',
                'payment_date' => null,
                'payment_method' => null,
                'payment_reference' => null,
                'notes' => 'Auto-created from approved purchase order: ' . $order['order_number'],
                'created_by' => $createdBy,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $apId = $this->accountsPayableModel->insert($apData);
            
            if ($apId) {
                log_message('info', 'Accounts payable entry created with ID: ' . $apId . ' for PO ' . $purchaseOrderId);
                return true;
            } else {
                log_message('error', 'Failed to create accounts payable entry for PO ' . $purchaseOrderId);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception creating accounts payable entry: ' . $e->getMessage());
            return false;
        }
    }
}

