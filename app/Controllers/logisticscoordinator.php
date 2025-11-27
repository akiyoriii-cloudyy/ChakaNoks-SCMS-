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

        // Get pending POs that need delivery scheduling
        $pendingPOsRaw = $this->purchaseOrderModel->getOrdersByStatus('approved');
        $pendingPOs = [];
        foreach ($pendingPOsRaw as $po) {
            $pendingPOs[] = $this->purchaseOrderModel->getOrderWithDetails($po['id']);
        }
        
        // Get scheduled deliveries with full details
        $scheduledDeliveriesRaw = $this->db->table('deliveries')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date', 'ASC')
            ->get()
            ->getResultArray();
        
        $scheduledDeliveries = [];
        foreach ($scheduledDeliveriesRaw as $delivery) {
            $scheduledDeliveries[] = $this->deliveryModel->trackDelivery($delivery['id']);
        }

        // Get in-transit deliveries with full details
        $inTransitDeliveriesRaw = $this->db->table('deliveries')
            ->where('status', 'in_transit')
            ->orderBy('scheduled_date', 'ASC')
            ->get()
            ->getResultArray();
        
        $inTransitDeliveries = [];
        foreach ($inTransitDeliveriesRaw as $delivery) {
            $inTransitDeliveries[] = $this->deliveryModel->trackDelivery($delivery['id']);
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

        // Get pending POs that need delivery scheduling
        $pendingPOsRaw = $this->purchaseOrderModel->getOrdersByStatus('approved');
        $pendingPOs = [];
        foreach ($pendingPOsRaw as $po) {
            $pendingPOs[] = $this->purchaseOrderModel->getOrderWithDetails($po['id']);
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
        foreach ($allOrders as $order) {
            $ordersWithTracking[] = $this->purchaseOrderModel->getOrderWithDetails($order['id']);
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

        // Get all deliveries with full details
        $allDeliveriesRaw = $this->db->table('deliveries')
            ->orderBy('scheduled_date', 'DESC')
            ->get()
            ->getResultArray();
        
        $allDeliveries = [];
        foreach ($allDeliveriesRaw as $delivery) {
            $allDeliveries[] = $this->deliveryModel->trackDelivery($delivery['id']);
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

        // Get scheduled deliveries with full details
        $scheduledDeliveriesRaw = $this->db->table('deliveries')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date', 'ASC')
            ->get()
            ->getResultArray();
        
        $scheduledDeliveries = [];
        foreach ($scheduledDeliveriesRaw as $delivery) {
            $scheduledDeliveries[] = $this->deliveryModel->trackDelivery($delivery['id']);
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
