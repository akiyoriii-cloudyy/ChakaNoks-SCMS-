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

        // Create delivery
        $deliveryData = [
            'purchase_order_id' => (int)$purchaseOrderId,
            'supplier_id' => $po['supplier_id'],
            'branch_id' => $po['branch_id'],
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

        $status = $this->request->getPost('status');
        $actualDeliveryDate = $this->request->getPost('actual_delivery_date');

        if (!$status) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Status is required']);
        }

        $updated = $this->deliveryModel->updateDeliveryStatus(
            (int)$id,
            $status,
            $actualDeliveryDate,
            $session->get('id')
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

                    if ($deliveryItem) {
                        $db->table('delivery_items')
                            ->where('delivery_id', $id)
                            ->where('product_id', $productId)
                            ->update([
                                'received_quantity' => $receivedQty,
                                'condition_status' => $conditionStatus,
                                'notes' => $notes,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                        // Get product to check expiry
                        $product = $this->productModel->find($productId);
                        $expiryDate = $product['expiry'] ?? null;

                        // Record STOCK-IN transaction (NEW STOCK)
                        // This implements: STOCK-IN → NEW STOCK flow from diagram
                        $this->stockTransactionModel->recordStockIn(
                            $productId,
                            $receivedQty,
                            $id, // reference_id (delivery_id)
                            'delivery', // reference_type
                            $session->get('id'), // created_by
                            $expiryDate
                        );

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
            }

            // Update delivery status
            $this->deliveryModel->updateDeliveryStatus(
                (int)$id,
                'delivered',
                date('Y-m-d'),
                $session->get('id')
            );

            // Update purchase order status if all items received
            if (!empty($delivery['purchase_order_id'])) {
                $this->checkAndUpdatePOStatus($delivery['purchase_order_id']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Delivery received successfully. Stock updated (STOCK-IN → NEW STOCK)'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
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
}

