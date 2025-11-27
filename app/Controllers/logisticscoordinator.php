<?php
namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\DeliveryModel;
use App\Models\SupplierModel;
use Config\Database;

class LogisticsCoordinator extends BaseController
{
    protected $db;
    protected $purchaseOrderModel;
    protected $deliveryModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->deliveryModel = new DeliveryModel();
        $this->supplierModel = new SupplierModel();
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
}
