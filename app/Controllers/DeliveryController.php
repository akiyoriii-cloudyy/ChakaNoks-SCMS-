<?php

namespace App\Controllers;

use App\Models\DeliveryModel;
use App\Models\PurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\StockTransactionModel;
use Config\Database;

class DeliveryController extends BaseController
{
    protected $db;
    protected $deliveryModel;
    protected $purchaseOrderModel;
    protected $productModel;
    protected $stockTransactionModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->deliveryModel = new DeliveryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->productModel = new ProductModel();
        $this->stockTransactionModel = new StockTransactionModel();
    }

    /**
     * Schedule a delivery
     */
    public function scheduleDelivery()
    {
        $session = session();
        
        // Only logistics coordinator and central admin can schedule deliveries
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $purchaseOrderId = $this->request->getPost('purchase_order_id');
        $scheduledDate = $this->request->getPost('scheduled_date');
        $driverName = $this->request->getPost('driver_name');
        $vehicleInfo = $this->request->getPost('vehicle_info');
        $notes = $this->request->getPost('notes') ?? '';

        if (!$purchaseOrderId || !$scheduledDate) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order ID and scheduled date are required']);
        }

        // Get purchase order details
        $po = $this->purchaseOrderModel->getOrderWithDetails((int)$purchaseOrderId);

        if (!$po) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Purchase order not found']);
        }

        // Get user ID from session
        $userId = $session->get('user_id') ?? $session->get('id');

        // Create delivery
        $deliveryData = [
            'purchase_order_id' => (int)$purchaseOrderId,
            'supplier_id' => $po['supplier_id'],
            'branch_id' => $po['branch_id'],
            'scheduled_by' => $userId ? (int)$userId : null,
            'scheduled_date' => $scheduledDate,
            'driver_name' => $driverName,
            'vehicle_info' => $vehicleInfo,
            'notes' => $notes,
            'status' => 'scheduled'
        ];

        $deliveryId = $this->deliveryModel->scheduleDelivery($deliveryData);

        if ($deliveryId) {
            // Update PO status to in_transit
            $this->purchaseOrderModel->updateOrderStatus((int)$purchaseOrderId, 'in_transit');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Delivery scheduled successfully',
                'delivery_id' => $deliveryId
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to schedule delivery']);
    }

    /**
     * Update delivery status
     */
    public function updateDeliveryStatus($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin', 'inventory_staff', 'inventorystaff'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        // Handle both JSON and form data
        $jsonData = $this->request->getJSON(true);
        if ($jsonData) {
            $status = $jsonData['status'] ?? null;
            $actualDeliveryDate = $jsonData['actual_delivery_date'] ?? null;
        } else {
            $status = $this->request->getPost('status');
            $actualDeliveryDate = $this->request->getPost('actual_delivery_date');
        }

        if (!$status) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Status is required']);
        }

        $userId = $session->get('user_id') ?? $session->get('id');
        $updated = $this->deliveryModel->updateDeliveryStatus(
            (int)$id,
            $status,
            $actualDeliveryDate,
            $userId ? (int)$userId : null
        );

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Delivery status updated'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update delivery status']);
    }

    /**
     * Receive delivery (inventory staff)
     * This integrates with StockTransactionModel - STOCK-IN → NEW STOCK
     */
    public function receiveDelivery($id = null)
    {
        $session = session();
        
        // Only inventory staff and branch managers can receive deliveries
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['inventory_staff', 'inventorystaff', 'branch_manager', 'manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        // Get delivery with items
        $delivery = $this->deliveryModel->trackDelivery((int)$id);

        if (!$delivery) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery not found']);
        }

        // Get received quantities from POST
        $receivedItems = $this->request->getPost('items'); // Array of {product_id, received_quantity, condition_status, notes}

        if (empty($receivedItems) || !is_array($receivedItems)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Received items are required']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Get delivery branch ID
        $deliveryBranchId = (int)($delivery['branch_id'] ?? 0);
        $userId = $session->get('user_id') ?? $session->get('id');
        $stockInCount = 0;
        $errors = [];

        try {
            foreach ($receivedItems as $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $receivedQty = (int)($item['received_quantity'] ?? 0);
                $conditionStatus = $item['condition_status'] ?? 'good';
                $notes = $item['notes'] ?? '';

                if ($productId > 0 && $receivedQty > 0) {
                    // Update delivery item
                    $deliveryItem = $db->table('delivery_items')
                        ->where('delivery_id', $id)
                        ->where('product_id', $productId)
                        ->get()
                        ->getRowArray();

                    if (!$deliveryItem) {
                        $errors[] = "Delivery item not found for product ID: {$productId}";
                        continue;
                    }

                    // Update delivery item received quantity and condition
                    $updateResult = $db->table('delivery_items')
                            ->where('delivery_id', $id)
                            ->where('product_id', $productId)
                            ->update([
                                'received_quantity' => $receivedQty,
                                'condition_status' => $conditionStatus,
                                'notes' => $notes,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                    if (!$updateResult) {
                        $errors[] = "Failed to update delivery item for product ID: {$productId}";
                        continue;
                    }

                        // Get original product
                        $originalProduct = $this->productModel->find($productId);
                        if (!$originalProduct) {
                        $errors[] = "Product not found: {$productId}";
                            continue;
                        }

                        $expiryDate = $originalProduct['expiry'] ?? null;
                        
                        // Check if product belongs to the delivery's branch
                        $targetProductId = $productId;
                        if ((int)$originalProduct['branch_id'] !== $deliveryBranchId) {
                            // Product is from a different branch - find or create for this branch
                            $existingProduct = $db->table('products')
                                ->where('name', $originalProduct['name'])
                                ->where('branch_id', $deliveryBranchId)
                                ->get()
                                ->getRowArray();

                            if ($existingProduct) {
                                // Use existing product in this branch
                                $targetProductId = (int)$existingProduct['id'];
                            } else {
                                // Create new product for this branch
                                $newProductData = [
                                    'name' => $originalProduct['name'],
                                    'branch_id' => $deliveryBranchId,
                                'category_id' => $originalProduct['category_id'] ?? null,
                                'price' => $originalProduct['price'] ?? 0,
                                    'stock_qty' => 0, // Will be updated by stock-in
                                'unit' => $originalProduct['unit'] ?? 'pcs',
                                'min_stock' => $originalProduct['min_stock'] ?? 0,
                                'max_stock' => $originalProduct['max_stock'] ?? 0,
                                    'expiry' => $expiryDate,
                                    'status' => 'active',
                                'created_by' => $userId,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ];
                                
                            $insertResult = $db->table('products')->insert($newProductData);
                            if (!$insertResult) {
                                $dbError = $db->error();
                                $errors[] = "Failed to create product for branch: " . ($dbError['message'] ?? 'Unknown error');
                                continue;
                            }
                            
                                $targetProductId = $db->insertID();
                                log_message('info', "Created new product ID {$targetProductId} for branch {$deliveryBranchId} from delivery {$id}");
                            }
                        }

                        // Record STOCK-IN transaction for the TARGET product (in delivery's branch)
                    $stockInResult = $this->stockTransactionModel->recordStockIn(
                            $targetProductId,
                            $receivedQty,
                            $id, // reference_id (delivery_id)
                            'delivery', // reference_type
                        $userId, // created_by
                            $expiryDate
                        );

                    if (!$stockInResult) {
                        $errors[] = "Failed to record stock-in for product ID: {$targetProductId}";
                        log_message('error', "Failed to record stock-in for product {$targetProductId} in delivery {$id}");
                        continue;
                    }

                    $stockInCount++;

                        // Update purchase order item received quantity
                        if (!empty($delivery['purchase_order_id'])) {
                            $poItem = $db->table('purchase_order_items')
                                ->where('purchase_order_id', $delivery['purchase_order_id'])
                                ->where('product_id', $productId)
                                ->get()
                                ->getRowArray();

                            if ($poItem) {
                                $newReceivedQty = ($poItem['received_quantity'] ?? 0) + $receivedQty;
                                $db->table('purchase_order_items')
                                    ->where('purchase_order_id', $delivery['purchase_order_id'])
                                    ->where('product_id', $productId)
                                    ->update(['received_quantity' => $newReceivedQty]);
                            }
                        }
                    }
                }

            // If no items were processed successfully, rollback
            if ($stockInCount === 0 && !empty($errors)) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to process any items: ' . implode('; ', $errors)
                ]);
            }

            // Update delivery status to 'received' (not 'delivered') - inventory/manager has verified receipt
            // Logistics will mark as 'delivered' later if needed
            $updateData = [
                'status' => 'received',
                'received_by' => $userId,
                'received_at' => date('Y-m-d H:i:s'),
                'actual_delivery_date' => date('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $updateResult = $this->deliveryModel->update((int)$id, $updateData);
            if (!$updateResult) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update delivery status'
                ]);
            }

            // Log to audit trail
            $auditTrailModel = new \App\Models\AuditTrailModel();
            $oldDelivery = $this->deliveryModel->find((int)$id);
            $auditTrailModel->logChange(
                'deliveries',
                (int)$id,
                'RECEIVED',
                ['status' => $delivery['status']],
                ['status' => 'received', 'received_by' => $userId, 'received_at' => date('Y-m-d H:i:s')],
                $userId
            );

            // Update purchase order status if all items received
            if (!empty($delivery['purchase_order_id'])) {
                $this->checkAndUpdatePOStatus($delivery['purchase_order_id']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                $dbError = $db->error();
                log_message('error', 'Transaction failed: ' . json_encode($dbError));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Transaction failed: ' . ($dbError['message'] ?? 'Unknown database error')
                ]);
            }

            $message = "Delivery received successfully. Stock updated for {$stockInCount} item(s).";
            if (!empty($errors)) {
                $message .= " Warnings: " . implode('; ', $errors);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error receiving delivery: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check and update PO status based on received quantities
     */
    private function checkAndUpdatePOStatus(int $poId): void
    {
        $poItems = $this->db->table('purchase_order_items')
            ->where('purchase_order_id', $poId)
            ->get()
            ->getResultArray();

        $allReceived = true;
        $partialReceived = false;

        foreach ($poItems as $item) {
            $received = (int)($item['received_quantity'] ?? 0);
            $expected = (int)($item['quantity'] ?? 0);

            if ($received < $expected) {
                $allReceived = false;
                if ($received > 0) {
                    $partialReceived = true;
                }
            }
        }

        if ($allReceived) {
            $this->purchaseOrderModel->updateOrderStatus($poId, 'delivered');
        } elseif ($partialReceived) {
            $this->purchaseOrderModel->updateOrderStatus($poId, 'partial');
        }
    }

    /**
     * Track delivery
     */
    public function trackDelivery($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        $delivery = $this->deliveryModel->trackDelivery((int)$id);

        if (!$delivery) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery not found']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'delivery' => $delivery
        ]);
    }

    /**
     * Get deliveries by branch
     */
    public function getDeliveriesByBranch()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $status = $this->request->getGet('status');

        if (!$branchId && !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Branch not assigned']);
        }

        $filters = [];
        if ($status) $filters['status'] = $status;

        $deliveries = $this->deliveryModel->getDeliveriesByBranch($branchId, $filters);

        return $this->response->setJSON([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }

    /**
     * Get deliveries by supplier
     */
    public function getDeliveriesBySupplier($supplierId = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$supplierId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Supplier ID required']);
        }

        $status = $this->request->getGet('status');
        $filters = [];
        if ($status) $filters['status'] = $status;

        $deliveries = $this->deliveryModel->getDeliveriesBySupplier((int)$supplierId, $filters);

        return $this->response->setJSON([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }

    /**
     * Confirm delivery - Branch Manager confirms delivery receipt
     */
    public function confirmDelivery($id = null)
    {
        $session = session();
        
        // Only branch managers can confirm deliveries
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        $actualDeliveryDate = $this->request->getPost('actual_delivery_date');
        $receivedByText = $this->request->getPost('received_by'); // This is text/email from form
        $receivedAt = $this->request->getPost('received_at');

        if (!$actualDeliveryDate || !$receivedByText || !$receivedAt) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'All fields are required']);
        }

        // Get user ID from session (received_by must be user ID, not email)
        $userId = $session->get('user_id') ?? $session->get('id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID not found in session']);
        }

        // Get delivery
        $delivery = $this->deliveryModel->find((int)$id);

        if (!$delivery) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery not found']);
        }

        // Verify branch access
        $branchId = $session->get('branch_id');
        if ($delivery['branch_id'] != $branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'You can only confirm deliveries for your branch']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get delivery with items
            $delivery = $this->deliveryModel->trackDelivery((int)$id);
            
            if (!$delivery || empty($delivery['items'])) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery items not found']);
            }

            // Store old values for audit trail
            $oldStatus = $delivery['status'] ?? 'scheduled';

            // Update delivery with confirmation details
            // Status should be 'received' (not 'delivered') - manager/inventory verifies receipt
            // Logistics will mark as 'delivered' separately if needed
            $updateData = [
                'actual_delivery_date' => $actualDeliveryDate,
                'received_by' => (int)$userId, // Use user ID from session
                'received_at' => $receivedAt,
                'status' => 'received', // Changed from 'delivered' to 'received'
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $updateResult = $this->deliveryModel->update((int)$id, $updateData);
            if (!$updateResult) {
                $db->transRollback();
                $dbError = $db->error();
                log_message('error', 'Failed to update delivery status: ' . json_encode($dbError));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update delivery status: ' . ($dbError['message'] ?? 'Unknown error')
                ]);
            }

            // Record STOCK-IN for all delivery items
            $stockInCount = 0;
            $errors = [];
            
            foreach ($delivery['items'] as $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $expectedQty = (int)($item['expected_quantity'] ?? 0);
                $receivedQty = (int)($item['received_quantity'] ?? 0);
                
                // Use received quantity if available, otherwise use expected quantity
                $quantityToStock = $receivedQty > 0 ? $receivedQty : $expectedQty;
                
                if ($productId > 0 && $quantityToStock > 0) {
                    // Get product to check if it exists and belongs to branch
                    $product = $this->productModel->find($productId);
                    
                    if (!$product) {
                        $errors[] = "Product not found: {$productId}";
                        log_message('warning', 'Product ' . $productId . ' not found when confirming delivery ' . $id);
                        continue;
                    }
                    
                    // Check if product belongs to this branch, if not, find or create for this branch
                    $targetProductId = $productId;
                    if (empty($product['branch_id']) || (int)$product['branch_id'] != $branchId) {
                        // Product is from a different branch - find or create for this branch
                        $existingProduct = $db->table('products')
                            ->where('name', $product['name'])
                            ->where('branch_id', $branchId)
                            ->get()
                            ->getRowArray();

                        if ($existingProduct) {
                            // Use existing product in this branch
                            $targetProductId = (int)$existingProduct['id'];
                            // Reload to ensure we have latest data
                            $targetProduct = $this->productModel->find($targetProductId);
                            if (!$targetProduct) {
                                $errors[] = "Target product not found: {$targetProductId}";
                                log_message('error', "Target product {$targetProductId} not found in delivery {$id}");
                                continue;
                            }
                        } else {
                            // Create new product for this branch
                            $newProductData = [
                                'name' => $product['name'],
                                'branch_id' => $branchId,
                                'category_id' => $product['category_id'] ?? null,
                                'price' => $product['price'] ?? 0,
                                'stock_qty' => 0, // Will be updated by stock-in
                                'unit' => $product['unit'] ?? 'pcs',
                                'min_stock' => $product['min_stock'] ?? 0,
                                'max_stock' => $product['max_stock'] ?? 0,
                                'expiry' => $product['expiry'] ?? null,
                                'status' => 'active',
                                'created_by' => $userId,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            
                            $insertResult = $db->table('products')->insert($newProductData);
                            if (!$insertResult) {
                                $dbError = $db->error();
                                $errors[] = "Failed to create product for branch: " . ($dbError['message'] ?? 'Unknown error');
                                log_message('error', 'Failed to create product for branch: ' . json_encode($dbError));
                                continue;
                            }
                            
                            $targetProductId = $db->insertID();
                            if (!$targetProductId || $targetProductId <= 0) {
                                $errors[] = "Failed to get new product ID after creation";
                                log_message('error', 'Failed to get new product ID after creation');
                                continue;
                            }
                            
                            log_message('info', 'Created new product ID ' . $targetProductId . ' for branch ' . $branchId . ' during delivery confirmation');
                            
                            // Reload the newly created product to ensure we have all fields
                            $targetProduct = $this->productModel->find($targetProductId);
                            if (!$targetProduct) {
                                $errors[] = "Failed to load newly created product: {$targetProductId}";
                                log_message('error', "Failed to load newly created product {$targetProductId}");
                                continue;
                            }
                        }
                    } else {
                        // Product belongs to this branch - use it directly
                        $targetProduct = $product;
                        $targetProductId = $productId;
                    }
                    
                    // Verify target product exists and has required data
                    if (!$targetProduct) {
                        $errors[] = "Target product not found after processing: {$targetProductId}";
                        log_message('error', "Target product {$targetProductId} not found after processing in delivery {$id}");
                        continue;
                    }
                    
                    // Ensure target product has branch_id set
                    if (empty($targetProduct['branch_id'])) {
                        $updateResult = $this->productModel->update($targetProductId, ['branch_id' => $branchId]);
                        if ($updateResult) {
                            $targetProduct['branch_id'] = $branchId;
                            log_message('info', "Updated product {$targetProductId} branch_id to {$branchId}");
                        } else {
                            $errors[] = "Failed to update product branch_id: {$targetProductId}";
                            log_message('error', "Failed to update product {$targetProductId} branch_id to {$branchId}");
                            continue;
                        }
                    }
                    
                    $expiryDate = $targetProduct['expiry'] ?? $product['expiry'] ?? null;
                    
                    // Record STOCK-IN transaction (NEW STOCK)
                    $stockInRecorded = $this->stockTransactionModel->recordStockIn(
                        $targetProductId,
                        $quantityToStock,
                        $id, // reference_id (delivery_id)
                        'delivery', // reference_type
                        $userId, // created_by
                        $expiryDate
                    );
                    
                    if ($stockInRecorded) {
                        $stockInCount++;
                        
                        // Update delivery item received quantity if not already set
                        if ($receivedQty == 0) {
                            $db->table('delivery_items')
                                ->where('delivery_id', $id)
                                ->where('product_id', $productId)
                                ->update([
                                    'received_quantity' => $quantityToStock,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
                        }
                        
                        // Update purchase order item received quantity
                        if (!empty($delivery['purchase_order_id'])) {
                            $poItem = $db->table('purchase_order_items')
                                ->where('purchase_order_id', $delivery['purchase_order_id'])
                                ->where('product_id', $productId)
                                ->get()
                                ->getRowArray();

                            if ($poItem) {
                                $newReceivedQty = ($poItem['received_quantity'] ?? 0) + $quantityToStock;
                                $db->table('purchase_order_items')
                                    ->where('purchase_order_id', $delivery['purchase_order_id'])
                                    ->where('product_id', $productId)
                                    ->update(['received_quantity' => $newReceivedQty]);
                            }
                        }
                    } else {
                        // Get more detailed error from the model
                        $dbError = $this->db->error();
                        $errorMsg = $dbError['message'] ?? 'Unknown error';
                        $errors[] = "Failed to record stock-in for product ID: {$targetProductId} ({$errorMsg})";
                        log_message('error', "Failed to record stock-in for product {$targetProductId} in delivery {$id}. Product data: " . json_encode($targetProduct));
                    }
                }
            }

            // If no items were processed successfully, rollback
            if ($stockInCount === 0 && !empty($errors)) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to process any items: ' . implode('; ', $errors)
                ]);
            }

            // Log to audit trail
            $auditTrailModel = new \App\Models\AuditTrailModel();
            $auditTrailModel->logChange(
                'deliveries',
                (int)$id,
                'CONFIRMED',
                ['status' => $oldStatus],
                ['status' => 'received', 'received_by' => $userId, 'received_at' => $receivedAt],
                $userId
            );

            // Update purchase order status if all items received
            if (!empty($delivery['purchase_order_id'])) {
                $this->checkAndUpdatePOStatus($delivery['purchase_order_id']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                $dbError = $db->error();
                log_message('error', 'Transaction failed: ' . json_encode($dbError));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Transaction failed: ' . ($dbError['message'] ?? 'Unknown database error')
                ]);
            }

            $message = "Delivery confirmed successfully. Stock-IN recorded for {$stockInCount} item(s).";
            if (!empty($errors)) {
                $message .= " Warnings: " . implode('; ', $errors);
            }

            log_message('info', 'Delivery ' . $id . ' confirmed by branch manager. Stock-IN recorded for ' . $stockInCount . ' items.');
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error confirming delivery: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show deliveries list page
     */
    public function showDeliveriesList()
    {
        $session = session();
        
        // Allow central admin, branch managers, staff, and logistics coordinators to view deliveries
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin', 'logistics_coordinator', 'branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return redirect()->to('/auth/login');
        }

        // Fetch deliveries data server-side
        $branchId = $session->get('branch_id');
        $role = $session->get('role');
        
        try {
            // For central admin, get all deliveries; for others, filter by branch
            $builder = $this->db->table('deliveries')
                ->select('deliveries.*, purchase_orders.order_number, suppliers.name as supplier_name, branches.name as branch_name')
                ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id', 'left')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = deliveries.branch_id', 'left');
            
            if ($branchId && !in_array($role, ['central_admin', 'superadmin'])) {
                $builder->where('deliveries.branch_id', $branchId);
            }
            
            $deliveries = $builder->orderBy('deliveries.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Format the data
            $formattedDeliveries = [];
            foreach ($deliveries as $delivery) {
                $formattedDeliveries[] = [
                    'id' => $delivery['id'],
                    'delivery_number' => $delivery['delivery_number'] ?? ('DLV-' . str_pad($delivery['id'], 5, '0', STR_PAD_LEFT)),
                    'purchase_order' => [
                        'id' => $delivery['purchase_order_id'],
                        'order_number' => $delivery['order_number'] ?? 'N/A'
                    ],
                    'supplier' => [
                        'id' => $delivery['supplier_id'] ?? null,
                        'name' => $delivery['supplier_name'] ?? 'N/A'
                    ],
                    'branch' => [
                        'id' => $delivery['branch_id'],
                        'name' => $delivery['branch_name'] ?? 'N/A'
                    ],
                    'status' => $delivery['status'] ?? 'scheduled',
                    'scheduled_date' => $delivery['scheduled_date'],
                    'actual_delivery_date' => $delivery['actual_delivery_date'],
                    'driver_name' => $delivery['driver_name'],
                    'vehicle_info' => $delivery['vehicle_info'],
                    'created_at' => $delivery['created_at']
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching deliveries: ' . $e->getMessage());
            $formattedDeliveries = [];
        }

        // Get pending approvals for sidebar badge (only for central admin)
        $dashboardData = ['purchaseRequests' => ['pending_approvals' => 0]];
        if (in_array($role, ['central_admin', 'superadmin'])) {
            $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
            $pendingRequests = $purchaseRequestModel->getPendingRequests();
            $dashboardData['purchaseRequests']['pending_approvals'] = count($pendingRequests);
        }

        return view('dashboards/deliveries_list', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'deliveries' => $formattedDeliveries,
            'data' => $dashboardData
        ]);
    }
}

