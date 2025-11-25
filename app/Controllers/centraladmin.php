<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use App\Models\DeliveryModel;
use App\Models\BranchModel;
use App\Models\StockTransactionModel;
use Config\Database;

class CentralAdmin extends BaseController
{
    protected $db;
    protected $productModel;
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $supplierModel;
    protected $deliveryModel;
    protected $branchModel;
    protected $stockTransactionModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->productModel = new ProductModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->deliveryModel = new DeliveryModel();
        $this->branchModel = new BranchModel();
        $this->stockTransactionModel = new StockTransactionModel();
    }

    public function dashboard()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get dashboard data
        $dashboardData = $this->getDashboardData();

        $activeTab = $this->request->getGet('tab') ?: 'dashboard';

        return view('dashboards/centraladmin', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'data' => $dashboardData,
            'activeTab' => $activeTab,
        ]);
    }

    public function suppliersPage()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        return redirect()->to(base_url('centraladmin/dashboard?tab=suppliers'));
    }

    public function deliveriesPage()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        return redirect()->to(base_url('centraladmin/dashboard?tab=deliveries'));
    }

    public function reportsPage()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get dashboard data for reports
        $dashboardData = $this->getDashboardData();

        return view('dashboards/reports', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'data' => $dashboardData,
        ]);
    }

    /**
     * Get all dashboard data
     */
    private function getDashboardData(): array
    {
        return [
            'inventory' => $this->getInventorySummary(),
            'suppliers' => $this->getSupplierReports(),
            'branches' => $this->getBranchInventoryOverview(),
            'purchaseRequests' => $this->getPurchaseRequestSummary(),
            'deliveries' => $this->getDeliveryTracking(),
            'pendingRequests' => $this->purchaseRequestModel->getPendingRequests(),
        ];
    }

    /**
     * Real-time inventory summary widget
     */
    private function getInventorySummary(): array
    {
        $allProducts = $this->productModel->getInventory();
        
        $summary = [
            'total_items' => count($allProducts),
            'total_stock_value' => 0,
            'low_stock_count' => 0,
            'critical_items_count' => 0,
            'expired_count' => 0,
            'expiring_soon_count' => 0,
        ];

        foreach ($allProducts as $product) {
            $status = $product['status'] ?? 'Good';
            $stockQty = (int)($product['stock_qty'] ?? 0);
            $unitPrice = (float)($product['price'] ?? 0);
            
            $summary['total_stock_value'] += $stockQty * ($unitPrice > 0 ? $unitPrice : 150); // Default price if not set

            if (in_array($status, ['Low Stock', 'Expiring Soon'])) {
                $summary['low_stock_count']++;
            }
            
            if (in_array($status, ['Critical', 'Out of Stock'])) {
                $summary['critical_items_count']++;
            }
            
            if ($status === 'Expired') {
                $summary['expired_count']++;
            }
            
            if ($status === 'Expiring Soon') {
                $summary['expiring_soon_count']++;
            }
        }

        return $summary;
    }

    /**
     * Supplier reports widget
     */
    private function getSupplierReports(): array
    {
        $activeSuppliers = $this->supplierModel->getActiveSuppliers();
        $allSuppliers = $this->supplierModel->findAll();
        
        // Get pending orders
        $pendingOrders = $this->purchaseOrderModel->getOrdersByStatus('pending');
        $inTransitOrders = $this->purchaseOrderModel->getOrdersByStatus('in_transit');
        
        // Get delivery performance
        $deliveries = $this->db->table('deliveries')
            ->where('status', 'delivered')
            ->get()
            ->getResultArray();
        
        $onTimeDeliveries = 0;
        $delayedDeliveries = 0;
        
        foreach ($deliveries as $delivery) {
            if ($delivery['actual_delivery_date'] && $delivery['scheduled_date']) {
                $actual = strtotime($delivery['actual_delivery_date']);
                $scheduled = strtotime($delivery['scheduled_date']);
                if ($actual <= $scheduled) {
                    $onTimeDeliveries++;
                } else {
                    $delayedDeliveries++;
                }
            }
        }
        
        $totalDeliveries = count($deliveries);
        $onTimeRate = $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100, 2) : 0;

        return [
            'active_suppliers' => count($activeSuppliers),
            'total_suppliers' => count($allSuppliers),
            'pending_orders' => count($pendingOrders),
            'in_transit_orders' => count($inTransitOrders),
            'on_time_delivery_rate' => $onTimeRate,
            'total_deliveries' => $totalDeliveries,
            'on_time_deliveries' => $onTimeDeliveries,
            'delayed_deliveries' => $delayedDeliveries,
        ];
    }

    /**
     * Branch inventory overview table/grid
     */
    private function getBranchInventoryOverview(): array
    {
        // Get all branches ordered by name, excluding Central Office
        $branches = $this->db->table('branches')
            ->where('code !=', 'CENTRAL')
            ->where('name !=', 'Central Office')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
        
        $overview = [];

        foreach ($branches as $branch) {
            // All branches here are already filtered (Central excluded)

            // Get products for this branch
            $branchProducts = $this->db->table('products')
                ->where('branch_id', $branch['id'])
                ->get()
                ->getResultArray();

            $lowStockCount = 0;
            $criticalCount = 0;
            $totalProducts = count($branchProducts);

            foreach ($branchProducts as $product) {
                // Calculate status using the same logic as ProductModel
                $stock = (int)($product['stock_qty'] ?? 0);
                $minStock = (int)($product['min_stock'] ?? 0);
                $expiry = $product['expiry'] ?? null;
                
                $status = 'Good';
                if ($expiry) {
                    $expiryDate = new \DateTime($expiry);
                    $today = new \DateTime();
                    $today->setTime(0, 0, 0);
                    if ($expiryDate < $today) {
                        $status = 'Expired';
                    } elseif (($expiryDate->diff($today)->days) <= 7) {
                        $status = 'Expiring Soon';
                    }
                }
                
                if ($stock <= 0) {
                    $status = 'Out of Stock';
                } elseif ($stock <= $minStock) {
                    $status = 'Critical';
                } elseif ($status === 'Good' && $stock < ($minStock * 1.3)) {
                    $status = 'Low Stock';
                }
                
                if (in_array($status, ['Low Stock', 'Expiring Soon'])) {
                    $lowStockCount++;
                }
                if (in_array($status, ['Critical', 'Out of Stock', 'Expired'])) {
                    $criticalCount++;
                }
            }

            $overview[] = [
                'branch_id' => $branch['id'],
                'branch_name' => $branch['name'],
                'branch_code' => $branch['code'],
                'branch_address' => $branch['address'] ?? '',
                'total_products' => $totalProducts,
                'low_stock' => $lowStockCount,
                'low_stock_items' => $lowStockCount,
                'critical_alerts' => $criticalCount,
            ];
        }

        // Sort by total_products descending, then by name ascending
        usort($overview, function($a, $b) {
            if ($b['total_products'] != $a['total_products']) {
                return $b['total_products'] - $a['total_products'];
            }
            return strcmp($a['branch_name'], $b['branch_name']);
        });
        
        // Limit to 5 branches only (ensures Toril is included if it has products)
        return array_slice($overview, 0, 5);
    }

    /**
     * Purchase request summary widget
     */
    private function getPurchaseRequestSummary(): array
    {
        $pendingRequests = $this->purchaseRequestModel->getPendingRequests();
        
        $today = date('Y-m-d');
        $approvedToday = $this->db->table('purchase_requests')
            ->where('status', 'approved')
            ->where('DATE(approved_at)', $today)
            ->get()
            ->getResultArray();
        
        $rejectedRequests = $this->db->table('purchase_requests')
            ->where('status', 'rejected')
            ->get()
            ->getResultArray();
        
        $totalValue = 0;
        foreach ($pendingRequests as $request) {
            $totalValue += (float)($request['total_amount'] ?? 0);
        }

        return [
            'pending_approvals' => count($pendingRequests),
            'approved_today' => count($approvedToday),
            'rejected_requests' => count($rejectedRequests),
            'total_pending_value' => $totalValue,
        ];
    }

    /**
     * Delivery tracking widget
     */
    private function getDeliveryTracking(): array
    {
        $scheduled = $this->db->table('deliveries')
            ->where('status', 'scheduled')
            ->countAllResults(false);
        
        $inTransit = $this->db->table('deliveries')
            ->where('status', 'in_transit')
            ->countAllResults(false);
        
        $today = date('Y-m-d');
        $completedToday = $this->db->table('deliveries')
            ->where('status', 'delivered')
            ->where('DATE(actual_delivery_date)', $today)
            ->countAllResults(false);
        
        // Get delayed deliveries (scheduled date passed but not delivered)
        $delayed = $this->db->table('deliveries')
            ->where('scheduled_date <', $today)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->countAllResults(false);

        return [
            'scheduled_deliveries' => $scheduled,
            'in_transit_deliveries' => $inTransit,
            'completed_today' => $completedToday,
            'delayed_deliveries' => $delayed,
        ];
    }

    /**
     * Detailed deliveries list for central admin Deliveries tab
     */
    public function getDeliveriesList()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $status = $this->request->getGet('status');

        $builder = $this->db->table('deliveries d')
            ->select('d.*, s.name AS supplier_name, b.name AS branch_name, po.order_number')
            ->join('suppliers s', 's.id = d.supplier_id', 'left')
            ->join('branches b', 'b.id = d.branch_id', 'left')
            ->join('purchase_orders po', 'po.id = d.purchase_order_id', 'left');

        if (!empty($status) && $status !== 'all') {
            $builder->where('d.status', $status);
        }

        try {
            $deliveries = $builder
                ->orderBy('d.scheduled_date', 'DESC')
                ->limit(100)
                ->get()
                ->getResultArray();

            log_message('debug', 'Deliveries list: ' . count($deliveries) . ' deliveries found');

            return $this->response->setJSON([
                'status' => 'success',
                'deliveries' => $deliveries,
                'count' => count($deliveries)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getDeliveriesList: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to load deliveries: ' . $e->getMessage(),
                'deliveries' => []
            ]);
        }
    }

    /**
     * API endpoint for real-time dashboard updates
     */
    public function getDashboardDataAPI()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $data = $this->getDashboardData();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
