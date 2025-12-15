<?php
namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\DeliveryModel;
use App\Models\SupplierModel;
use App\Models\AuditTrailModel;
use Config\Database;

class LogisticsCoordinator extends BaseController
{
    protected $db;
    protected $purchaseOrderModel;
    protected $deliveryModel;
    protected $supplierModel;
    protected $auditTrailModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->deliveryModel = new DeliveryModel();
        $this->supplierModel = new SupplierModel();
        $this->auditTrailModel = new AuditTrailModel();
    }

    public function dashboard()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get approved POs that don't have deliveries yet (need scheduling)
        $approvedPOsRaw = $this->purchaseOrderModel->getOrdersByStatus('approved');
        $pendingPOs = [];
        
        // Get all PO IDs that already have deliveries
        $posWithDeliveries = $this->db->table('deliveries')
            ->select('purchase_order_id')
            ->get()
            ->getResultArray();
        $poIdsWithDeliveries = array_column($posWithDeliveries, 'purchase_order_id');
        
        foreach ($approvedPOsRaw as $po) {
            // Only include POs that don't have a delivery scheduled yet
            if (!in_array($po['id'], $poIdsWithDeliveries)) {
                $pendingPOs[] = $this->purchaseOrderModel->getOrderWithDetails($po['id']);
            }
        }
        
        // Get scheduled deliveries with full details
        $scheduledDeliveriesRaw = $this->db->table('deliveries')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date', 'ASC')
            ->get()
            ->getResultArray();
        
        $scheduledDeliveries = [];
        foreach ($scheduledDeliveriesRaw as $delivery) {
            $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
            if ($deliveryDetails) {
                $scheduledDeliveries[] = $deliveryDetails;
            }
        }

        // Get in-transit deliveries with full details
        $inTransitDeliveriesRaw = $this->db->table('deliveries')
            ->where('status', 'in_transit')
            ->orderBy('scheduled_date', 'ASC')
            ->get()
            ->getResultArray();
        
        $inTransitDeliveries = [];
        foreach ($inTransitDeliveriesRaw as $delivery) {
            $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
            if ($deliveryDetails) {
                $inTransitDeliveries[] = $deliveryDetails;
            }
        }

        return view('dashboards/logisticscoordinator', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'pendingPOs' => $pendingPOs,
            'scheduledDeliveries' => $scheduledDeliveries,
            'inTransitDeliveries' => $inTransitDeliveries,
            'currentPage' => 'dashboard'
        ]);
    }

    public function scheduleDelivery()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get approved POs that don't have deliveries yet (need scheduling)
        $approvedPOsRaw = $this->purchaseOrderModel->getOrdersByStatus('approved');
        $pendingPOs = [];
        
        // Get all PO IDs that already have deliveries
        $posWithDeliveries = $this->db->table('deliveries')
            ->select('purchase_order_id')
            ->get()
            ->getResultArray();
        $poIdsWithDeliveries = array_column($posWithDeliveries, 'purchase_order_id');
        
        foreach ($approvedPOsRaw as $po) {
            // Only include POs that don't have a delivery scheduled yet
            if (!in_array($po['id'], $poIdsWithDeliveries)) {
                $pendingPOs[] = $this->purchaseOrderModel->getOrderWithDetails($po['id']);
            }
        }

        return view('dashboards/logisticscoordinator', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'pendingPOs' => $pendingPOs,
            'scheduledDeliveries' => [],
            'inTransitDeliveries' => [],
            'currentPage' => 'schedule'
        ]);
    }

    public function trackOrders()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get all purchase orders with their tracking info
        $allOrders = $this->purchaseOrderModel->findAll();
        $ordersWithTracking = [];
        
        // Get delivery statuses for all POs
        $deliveries = $this->db->table('deliveries')
            ->select('purchase_order_id, status as delivery_status')
            ->get()
            ->getResultArray();
        
        $deliveryStatusMap = [];
        foreach ($deliveries as $delivery) {
            $deliveryStatusMap[$delivery['purchase_order_id']] = $delivery['delivery_status'];
        }
        
        foreach ($allOrders as $order) {
            $orderDetails = $this->purchaseOrderModel->getOrderWithDetails($order['id']);
            
            // Update status based on delivery status if delivery exists
            if (isset($deliveryStatusMap[$order['id']])) {
                $deliveryStatus = $deliveryStatusMap[$order['id']];
                // Map delivery status to PO status for display
                if ($deliveryStatus === 'delivered' && $orderDetails['status'] !== 'delivered') {
                    $orderDetails['status'] = 'delivered';
                } elseif ($deliveryStatus === 'partial_delivery' && $orderDetails['status'] !== 'partial') {
                    $orderDetails['status'] = 'partial';
                } elseif ($deliveryStatus === 'in_transit' && $orderDetails['status'] !== 'in_transit') {
                    $orderDetails['status'] = 'in_transit';
                }
            }
            
            $ordersWithTracking[] = $orderDetails;
        }

        return view('dashboards/logisticscoordinator', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'pendingPOs' => [],
            'scheduledDeliveries' => [],
            'inTransitDeliveries' => [],
            'allOrders' => $ordersWithTracking,
            'currentPage' => 'track'
        ]);
    }

    public function deliveries()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get all deliveries with full details, ordered by most recent first
        $allDeliveriesRaw = $this->db->table('deliveries')
            ->orderBy('created_at', 'DESC')
            ->orderBy('scheduled_date', 'DESC')
            ->get()
            ->getResultArray();
        
        $allDeliveries = [];
        foreach ($allDeliveriesRaw as $delivery) {
            $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
            if ($deliveryDetails) {
                $allDeliveries[] = $deliveryDetails;
            }
        }

        return view('dashboards/logisticscoordinator', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'pendingPOs' => [],
            'scheduledDeliveries' => [],
            'inTransitDeliveries' => [],
            'allDeliveries' => $allDeliveries,
            'currentPage' => 'deliveries'
        ]);
    }

    public function schedules()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get scheduled deliveries with full details, ordered by scheduled date
        $scheduledDeliveriesRaw = $this->db->table('deliveries')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        $scheduledDeliveries = [];
        foreach ($scheduledDeliveriesRaw as $delivery) {
            $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
            if ($deliveryDetails) {
                $scheduledDeliveries[] = $deliveryDetails;
            }
        }

        return view('dashboards/logisticscoordinator', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'pendingPOs' => [],
            'scheduledDeliveries' => $scheduledDeliveries,
            'inTransitDeliveries' => [],
            'currentPage' => 'schedules'
        ]);
    }

    /**
     * Reschedule a delivery
     */
    public function rescheduleDelivery($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        $scheduledDate = $this->request->getPost('scheduled_date');
        $driverName = $this->request->getPost('driver_name');
        $vehicleInfo = $this->request->getPost('vehicle_info');
        $notes = $this->request->getPost('notes');

        if (!$scheduledDate) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Scheduled date is required']);
        }

        // Get existing delivery
        $delivery = $this->deliveryModel->find((int)$id);
        if (!$delivery) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery not found']);
        }

        // Store old values for audit trail
        $oldValues = [
            'scheduled_date' => $delivery['scheduled_date'],
            'driver_name' => $delivery['driver_name'],
            'vehicle_info' => $delivery['vehicle_info'],
            'notes' => $delivery['notes']
        ];

        // Prepare update data
        $updateData = [
            'scheduled_date' => $scheduledDate,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($driverName !== null) {
            $updateData['driver_name'] = $driverName;
        }
        if ($vehicleInfo !== null) {
            $updateData['vehicle_info'] = $vehicleInfo;
        }
        if ($notes !== null) {
            $updateData['notes'] = $notes;
        }

        // Update delivery
        $updated = $this->deliveryModel->update((int)$id, $updateData);

        if ($updated) {
            // Log to audit trail
            $userId = $session->get('user_id') ?? $session->get('id');
            $newValues = array_merge($oldValues, $updateData);
            $this->auditTrailModel->logChange(
                'deliveries',
                (int)$id,
                'RESCHEDULE',
                $oldValues,
                $newValues,
                $userId ? (int)$userId : null
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Delivery rescheduled successfully'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to reschedule delivery']);
    }

    /**
     * Get driver contact information
     */
    public function getDriverContact($deliveryId = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$deliveryId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        $delivery = $this->deliveryModel->find((int)$deliveryId);
        if (!$delivery) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery not found']);
        }

        $driverName = $delivery['driver_name'] ?? null;
        $vehicleInfo = $delivery['vehicle_info'] ?? null;

        if (!$driverName) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No driver assigned to this delivery'
            ]);
        }

        // Get all deliveries for this driver to show schedule
        $driverDeliveries = $this->db->table('deliveries')
            ->where('driver_name', $driverName)
            ->where('status !=', 'delivered')
            ->where('status !=', 'cancelled')
            ->orderBy('scheduled_date', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'driver' => [
                'name' => $driverName,
                'vehicle_info' => $vehicleInfo,
                'active_deliveries' => count($driverDeliveries),
                'deliveries' => $driverDeliveries
            ]
        ]);
    }

    /**
     * Get driver schedule view
     */
    public function getDriverSchedule($driverName = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$driverName) {
            // Get driver name from request if not in URL
            $driverName = $this->request->getGet('driver_name');
        }

        if (!$driverName) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Driver name required']);
        }

        // Get date range filter
        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-d');
        $dateTo = $this->request->getGet('date_to') ?? date('Y-m-d', strtotime('+30 days'));

        // Get all deliveries for this driver
        $deliveries = $this->db->table('deliveries')
            ->where('driver_name', urldecode($driverName))
            ->where('DATE(scheduled_date) >=', $dateFrom)
            ->where('DATE(scheduled_date) <=', $dateTo)
            ->orderBy('scheduled_date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        // Enrich with full details
        $enrichedDeliveries = [];
        foreach ($deliveries as $delivery) {
            $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
            if ($deliveryDetails) {
                $enrichedDeliveries[] = $deliveryDetails;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'driver_name' => urldecode($driverName),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'deliveries' => $enrichedDeliveries,
            'total' => count($enrichedDeliveries)
        ]);
    }

    /**
     * Get order history (audit trail for purchase order)
     */
    public function getOrderHistory($orderId = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$orderId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order ID required']);
        }

        // Get order details
        $order = $this->purchaseOrderModel->getOrderWithDetails((int)$orderId);
        if (!$order) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order not found']);
        }

        // Get audit trail for this order
        $history = $this->auditTrailModel->getRecordHistory('purchase_orders', (int)$orderId);

        // Get related deliveries
        $deliveries = $this->db->table('deliveries')
            ->where('purchase_order_id', (int)$orderId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $enrichedDeliveries = [];
        foreach ($deliveries as $delivery) {
            $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
            if ($deliveryDetails) {
                $enrichedDeliveries[] = $deliveryDetails;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'order' => $order,
            'history' => $history,
            'deliveries' => $enrichedDeliveries
        ]);
    }

    /**
     * Get delivery history (audit trail for delivery)
     */
    public function getDeliveryHistory($deliveryId = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$deliveryId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery ID required']);
        }

        // Get delivery details
        $delivery = $this->deliveryModel->trackDelivery((int)$deliveryId);
        if (!$delivery) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delivery not found']);
        }

        // Get audit trail for this delivery
        $history = $this->auditTrailModel->getRecordHistory('deliveries', (int)$deliveryId);

        return $this->response->setJSON([
            'status' => 'success',
            'delivery' => $delivery,
            'history' => $history
        ]);
    }

    /**
     * Get all unique drivers
     */
    public function getAllDrivers()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        // Get all unique driver names with their delivery counts
        $drivers = $this->db->table('deliveries')
            ->select('driver_name, vehicle_info, COUNT(*) as delivery_count')
            ->where('driver_name IS NOT NULL')
            ->where('driver_name !=', '')
            ->groupBy('driver_name, vehicle_info')
            ->orderBy('driver_name', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'drivers' => $drivers
        ]);
    }

    /**
     * Get fleet management data (vehicles, drivers, maintenance)
     */
    public function getFleetManagementData()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $vehicles = [];
            $drivers = [];
            $maintenance = [];

            // Get vehicles if table exists
            if ($this->db->tableExists('vehicles')) {
                $vehicles = $this->db->table('vehicles')
                    ->orderBy('vehicle_number', 'ASC')
                    ->get()
                    ->getResultArray();
            }

            // Get drivers if table exists
            if ($this->db->tableExists('drivers')) {
                // Check if is_confirmed column exists, if not it will be added when confirming
                $drivers = $this->db->table('drivers')
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->getResultArray();
                
                // Ensure is_confirmed field exists in response (default to 0 if not set)
                foreach ($drivers as &$driver) {
                    if (!isset($driver['is_confirmed'])) {
                        $driver['is_confirmed'] = 0;
                    }
                }
            }

            // Get maintenance records if table exists
            if ($this->db->tableExists('maintenance_records')) {
                $maintenance = $this->db->table('maintenance_records')
                    ->orderBy('next_due_date', 'ASC')
                    ->get()
                    ->getResultArray();
            }

            return $this->response->setJSON([
                'status' => 'success',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'maintenance' => $maintenance
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error loading fleet data: ' . $e->getMessage(),
                'vehicles' => [],
                'drivers' => [],
                'maintenance' => []
            ]);
        }
    }

    /**
     * Confirm a driver
     */
    public function confirmDriver($driverId = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$driverId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Driver ID required']);
        }

        try {
            // Check if drivers table exists and has is_confirmed column
            $tableExists = $this->db->tableExists('drivers');
            
            if (!$tableExists) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Drivers table does not exist. Please create it first.'
                ]);
            }

            // Check if is_confirmed column exists, if not add it
            $columns = $this->db->getFieldNames('drivers');
            if (!in_array('is_confirmed', $columns)) {
                // Add is_confirmed column
                $this->db->query("ALTER TABLE drivers ADD COLUMN is_confirmed TINYINT(1) DEFAULT 0");
            }

            // Update driver confirmation status
            $updated = $this->db->table('drivers')
                ->where('id', $driverId)
                ->update([
                    'is_confirmed' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            if ($updated) {
                // Log the action
                if (class_exists('App\Models\AuditTrailModel')) {
                    $this->auditTrailModel->log([
                        'table_name' => 'drivers',
                        'record_id' => $driverId,
                        'action' => 'confirm',
                        'user_id' => $session->get('user_id'),
                        'changes' => json_encode(['is_confirmed' => 1])
                    ]);
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Driver confirmed successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Driver not found or update failed'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error confirming driver: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get pending purchase orders (for Schedule Delivery section)
     */
    public function getPendingPOs()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            // Get approved POs that don't have deliveries yet
            $approvedPOsRaw = $this->purchaseOrderModel->getOrdersByStatus('approved');
            $pendingPOs = [];
            
            // Get all PO IDs that already have deliveries
            $posWithDeliveries = $this->db->table('deliveries')
                ->select('purchase_order_id')
                ->get()
                ->getResultArray();
            $poIdsWithDeliveries = array_column($posWithDeliveries, 'purchase_order_id');
            
            foreach ($approvedPOsRaw as $po) {
                if (!in_array($po['id'], $poIdsWithDeliveries)) {
                    $pendingPOs[] = $this->purchaseOrderModel->getOrderWithDetails($po['id']);
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'pendingPOs' => $pendingPOs
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error loading pending POs: ' . $e->getMessage(),
                'pendingPOs' => []
            ]);
        }
    }

    /**
     * Get all purchase orders (for Track Orders section)
     */
    public function getAllOrders()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $searchTerm = $this->request->getGet('search');
            
            // Get all purchase orders
            $orders = $this->purchaseOrderModel->findAll();
            $ordersWithTracking = [];
            
            foreach ($orders as $order) {
                $orderDetails = $this->purchaseOrderModel->getOrderWithDetails($order['id']);
                
                // Apply search filter if provided
                if ($searchTerm) {
                    $searchLower = trim(strtolower($searchTerm));
                    $matches = false;
                    
                    // Search in order number
                    if (stripos($orderDetails['order_number'] ?? '', $searchTerm) !== false) {
                        $matches = true;
                    }
                    
                    // Search in supplier name
                    if (!$matches && isset($orderDetails['supplier']['name']) && 
                        stripos($orderDetails['supplier']['name'], $searchTerm) !== false) {
                        $matches = true;
                    }
                    
                    // Search in branch name
                    if (!$matches && isset($orderDetails['branch']['name']) && 
                        stripos($orderDetails['branch']['name'], $searchTerm) !== false) {
                        $matches = true;
                    }
                    
                    // Search in status
                    if (!$matches && isset($orderDetails['status']) && 
                        stripos($orderDetails['status'], $searchTerm) !== false) {
                        $matches = true;
                    }
                    
                    // If no match found, skip this order
                    if (!$matches) {
                        continue;
                    }
                }
                
                $ordersWithTracking[] = $orderDetails;
            }

            return $this->response->setJSON([
                'status' => 'success',
                'orders' => $ordersWithTracking
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error loading orders: ' . $e->getMessage(),
                'orders' => []
            ]);
        }
    }

    /**
     * Get all deliveries (for Deliveries section)
     */
    public function getAllDeliveries()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $statusFilter = $this->request->getGet('status');
            $dateFilter = $this->request->getGet('date');
            $driverFilter = $this->request->getGet('driver');
            
            // Build query
            $query = $this->db->table('deliveries');
            
            if ($statusFilter) {
                $query->where('status', $statusFilter);
            }
            
            if ($dateFilter) {
                $query->where('scheduled_date', $dateFilter);
            }
            
            if ($driverFilter) {
                $query->where('driver_name', $driverFilter);
            }
            
            $allDeliveriesRaw = $query
                ->orderBy('created_at', 'DESC')
                ->orderBy('scheduled_date', 'DESC')
                ->get()
                ->getResultArray();
            
            $allDeliveries = [];
            foreach ($allDeliveriesRaw as $delivery) {
                $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
                if ($deliveryDetails) {
                    $allDeliveries[] = $deliveryDetails;
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'deliveries' => $allDeliveries
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error loading deliveries: ' . $e->getMessage(),
                'deliveries' => []
            ]);
        }
    }

    /**
     * Get scheduled deliveries (for Schedules section)
     */
    public function getScheduledDeliveries()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $scheduledDeliveriesRaw = $this->db->table('deliveries')
                ->where('status', 'scheduled')
                ->orderBy('scheduled_date', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getResultArray();
            
            $scheduledDeliveries = [];
            foreach ($scheduledDeliveriesRaw as $delivery) {
                $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
                if ($deliveryDetails) {
                    $scheduledDeliveries[] = $deliveryDetails;
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'deliveries' => $scheduledDeliveries
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error loading scheduled deliveries: ' . $e->getMessage(),
                'deliveries' => []
            ]);
        }
    }

    /**
     * Get deliveries for a specific week (for calendar view)
     */
    public function getWeekDeliveries()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['logistics_coordinator', 'central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            
            if (!$startDate || !$endDate) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Start date and end date are required',
                    'deliveries' => []
                ]);
            }

            // Get all scheduled and in_transit deliveries for the week from ALL branches
            $deliveriesRaw = $this->db->table('deliveries')
                ->where('scheduled_date >=', $startDate)
                ->where('scheduled_date <=', $endDate)
                ->whereIn('status', ['scheduled', 'in_transit'])
                ->orderBy('scheduled_date', 'ASC')
                ->orderBy('scheduled_time', 'ASC')
                ->get()
                ->getResultArray();
            
            $deliveries = [];
            foreach ($deliveriesRaw as $delivery) {
                $deliveryDetails = $this->deliveryModel->trackDelivery($delivery['id']);
                if ($deliveryDetails) {
                    // Include branch information
                    $deliveries[] = [
                        'id' => $deliveryDetails['id'],
                        'delivery_number' => $deliveryDetails['delivery_number'] ?? 'N/A',
                        'scheduled_date' => $deliveryDetails['scheduled_date'] ?? null,
                        'scheduled_time' => $deliveryDetails['scheduled_time'] ?? null,
                        'status' => $deliveryDetails['status'] ?? 'scheduled',
                        'driver_name' => $deliveryDetails['driver_name'] ?? null,
                        'vehicle_info' => $deliveryDetails['vehicle_info'] ?? null,
                        'branch' => $deliveryDetails['branch'] ?? null,
                        'supplier' => $deliveryDetails['supplier'] ?? null,
                        'purchase_order' => $deliveryDetails['purchase_order'] ?? null
                    ];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'deliveries' => $deliveries
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error loading week deliveries: ' . $e->getMessage(),
                'deliveries' => []
            ]);
        }
    }
}
