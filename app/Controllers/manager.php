<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use App\Models\AuditTrailModel;
use Config\Database;
use Exception;

class Manager extends BaseController
{
    protected $db;
    protected $model;
    protected $supplierModel;
    protected $userModel;
    protected $auditTrailModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->model = new ProductModel();
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
        $this->auditTrailModel = new AuditTrailModel();
    }

    public function dashboard()
    {
        $session = session();

        // Auth check
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return redirect()->to('/auth/login');
        }

        $branchId = $session->get('branch_id');
        if (empty($branchId)) {
            $session->setFlashdata('error', 'Branch assignment missing. Please contact the central admin.');
            return redirect()->back();
        }

        // Get dashboard data
        $dashboardData = $this->getDashboardData((int)$branchId);
        
        $activeTab = $this->request->getGet('tab') ?: 'dashboard';

        return view('dashboards/manager', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'branch_id' => $branchId,
                'user_id' => $session->get('user_id'),
            ],
            'data' => array_merge($dashboardData, ['users' => $this->getUsersData($branchId)]),
            'activeTab' => $activeTab,
        ]);
    }

    private function getDashboardData(int $branchId): array
    {
        return [
            'inventory' => $this->getInventorySummary($branchId),
            'sales' => $this->getSalesData(),
            'suppliers' => $this->getSupplierReports($branchId),
            'deliveries' => $this->getDeliveryTracking($branchId),
            'purchaseRequests' => $this->getPurchaseRequests($branchId),
            'operations' => $this->getOperationalData($branchId),
        ];
    }

    private function getInventorySummary(int $branchId)
    {
        try {
            // Get only products from the current branch
            $items = $this->model->getInventory(['branch_id' => $branchId]);
            
            $summary = [
                'total_items' => count($items),
                'critical_items' => 0,
                'low_stock_items' => 0,
                'good_stock_items' => 0,
                'expired_items' => 0,
                'expiring_soon' => 0,
                'total_value' => 0
            ];

            foreach ($items as $item) {
                $stock_qty = (int)($item['stock_qty'] ?? 0);
                $price = (float)($item['price'] ?? 0);
                $status = trim($item['status'] ?? 'Good');
                
                // Use the calculated status from ProductModel (normalized database)
                if (in_array($status, ['Critical', 'Out of Stock', 'Expired'])) {
                    $summary['critical_items']++;
                } elseif (in_array($status, ['Low Stock', 'Expiring Soon'])) {
                    $summary['low_stock_items']++;
                } else {
                    // Default to good stock for 'Good' status or any other status
                    $summary['good_stock_items']++;
                }
                
                // Check expiry status
                if ($status === 'Expired') {
                    $summary['expired_items']++;
                } elseif ($status === 'Expiring Soon') {
                    $summary['expiring_soon']++;
                }
                
                // Calculate total value using actual product price from database
                // Use actual price if available, otherwise use a default
                $itemPrice = $price > 0 ? $price : 150;
                $summary['total_value'] += $stock_qty * $itemPrice;
            }

            return $summary;
        } catch (Exception $e) {
            log_message('error', 'Error in getInventorySummary: ' . $e->getMessage());
            return [
                'total_items' => 0,
                'critical_items' => 0,
                'low_stock_items' => 0,
                'good_stock_items' => 0,
                'expired_items' => 0,
                'expiring_soon' => 0,
                'total_value' => 0
            ];
        }
    }

    private function getSalesData()
    {
        try {
            $today = date('Y-m-d');
            $weekAgo = date('Y-m-d', strtotime('-7 days'));
            $monthAgo = date('Y-m-d', strtotime('-30 days'));
            
            $todayAmount = 0;
            $weekAmount = 0;
            $monthAmount = 0;
            $growthRate = 0;
            $hourlyData = [];
            
            // Check if sales table exists
            $tables = $this->db->listTables();
            
            if (in_array('sales', $tables)) {
                // Use sales table if it exists
                $todaySales = $this->db->table('sales')
                    ->selectSum('total_amount')
                    ->where('DATE(created_at)', $today)
                    ->get()
                    ->getRow();
                
                $weekSales = $this->db->table('sales')
                    ->selectSum('total_amount')
                    ->where('created_at >=', $weekAgo)
                    ->get()
                    ->getRow();
                
                $monthSales = $this->db->table('sales')
                    ->selectSum('total_amount')
                    ->where('created_at >=', $monthAgo)
                    ->get()
                    ->getRow();
                
                $todayAmount = (float)($todaySales->total_amount ?? 0);
                $weekAmount = (float)($weekSales->total_amount ?? 0);
                $monthAmount = (float)($monthSales->total_amount ?? 0);
                
                $yesterdayAmount = $this->db->table('sales')
                    ->selectSum('total_amount')
                    ->where('DATE(created_at)', date('Y-m-d', strtotime('-1 day')))
                    ->get()
                    ->getRow();
                $yesterdayAmount = (float)($yesterdayAmount->total_amount ?? 1);
                
                $growthRate = $yesterdayAmount > 0 ? round((($todayAmount - $yesterdayAmount) / $yesterdayAmount) * 100, 1) : 0;
                
                // Hourly breakdown
                for ($hour = 6; $hour <= 21; $hour += 3) {
                    $startTime = sprintf('%02d:00:00', $hour);
                    $endTime = sprintf('%02d:59:59', $hour + 2);
                    $sales = $this->db->table('sales')
                        ->selectSum('total_amount')
                        ->where('DATE(created_at)', $today)
                        ->where('TIME(created_at) >=', $startTime)
                        ->where('TIME(created_at) <=', $endTime)
                        ->get()
                        ->getRow();
                    $hourlyData[] = [
                        'hour' => sprintf('%dAM', $hour),
                        'sales' => (int)($sales->total_amount ?? 0)
                    ];
                }
            } else {
                // Use mock data if sales table doesn't exist
                $todayAmount = 125000;
                $weekAmount = 875000;
                $monthAmount = 3200000;
                $growthRate = 12.5;
                $hourlyData = [
                    ['hour' => '6AM', 'sales' => 15000],
                    ['hour' => '9AM', 'sales' => 25000],
                    ['hour' => '12PM', 'sales' => 45000],
                    ['hour' => '3PM', 'sales' => 20000],
                    ['hour' => '6PM', 'sales' => 35000],
                    ['hour' => '9PM', 'sales' => 15000]
                ];
            }
            
            return [
                'today_sales' => $todayAmount,
                'week_sales' => $weekAmount,
                'month_sales' => $monthAmount,
                'growth_rate' => $growthRate,
                'hourly_sales' => $hourlyData
            ];
        } catch (Exception $e) {
            // Fallback to mock data on any error
            return [
                'today_sales' => 125000,
                'week_sales' => 875000,
                'month_sales' => 3200000,
                'growth_rate' => 12.5,
                'hourly_sales' => [
                    ['hour' => '6AM', 'sales' => 15000],
                    ['hour' => '9AM', 'sales' => 25000],
                    ['hour' => '12PM', 'sales' => 45000],
                    ['hour' => '3PM', 'sales' => 20000],
                    ['hour' => '6PM', 'sales' => 35000],
                    ['hour' => '9PM', 'sales' => 15000]
                ]
            ];
        }
    }

    private function getStaffData()
    {
        // Mock staff data
        return [
            'total_staff' => 24,
            'active_staff' => 18,
            'on_break' => 3,
            'off_duty' => 3,
            'performance_rating' => 4.2,
            'top_performers' => [
                ['name' => 'Maria Santos', 'role' => 'Cashier', 'rating' => 4.8, 'sales' => 45000],
                ['name' => 'Juan Dela Cruz', 'role' => 'Kitchen Staff', 'rating' => 4.6, 'sales' => 42000],
                ['name' => 'Ana Reyes', 'role' => 'Server', 'rating' => 4.5, 'sales' => 41000],
                ['name' => 'Pedro Martinez', 'role' => 'Cashier', 'rating' => 4.4, 'sales' => 39500],
                ['name' => 'Luz Garcia', 'role' => 'Kitchen Staff', 'rating' => 4.3, 'sales' => 38000]
            ]
        ];
    }

    private function getOperationalData(int $branchId)
    {
        try {
            $alerts = [];
            
            // Check for items expiring within 7 days (using 'expiry' column) - for current branch only
            try {
                $expiringItems = $this->db->table('products')
                    ->where('branch_id', $branchId)
                    ->where('expiry IS NOT NULL')
                    ->where('expiry <=', date('Y-m-d', strtotime('+7 days')))
                    ->where('expiry >', date('Y-m-d'))
                    ->countAllResults();
                
                if ($expiringItems > 0) {
                    $alerts[] = ['type' => 'warning', 'message' => $expiringItems . ' items expiring within 7 days'];
                }
            } catch (Exception $e) {
                log_message('debug', 'Expiring items check failed: ' . $e->getMessage());
            }
            
            // Check for low stock items - using proper CodeIgniter 4 syntax - for current branch only
            try {
                // Get products from current branch and check in PHP instead of SQL
                $lowStockProducts = $this->db->table('products')
                    ->select('id, stock_qty, min_stock')
                    ->where('branch_id', $branchId)
                    ->get()
                    ->getResultArray();
                
                $lowStockCount = 0;
                foreach ($lowStockProducts as $product) {
                    $minStock = $product['min_stock'] ?? 10;
                    if ($product['stock_qty'] <= $minStock && $product['stock_qty'] > 0) {
                        $lowStockCount++;
                    }
                }
                
                if ($lowStockCount > 0) {
                    $alerts[] = ['type' => 'warning', 'message' => $lowStockCount . ' items in low stock'];
                }
            } catch (Exception $e) {
                log_message('debug', 'Low stock check failed: ' . $e->getMessage());
            }
            
            // Check for pending purchase orders (if table exists)
            try {
                $tables = $this->db->listTables();
                if (in_array('purchase_orders', $tables)) {
                    // Still filter purchase orders by branch as they are branch-specific
                    $pendingOrders = $this->db->table('purchase_orders')
                        ->where('branch_id', $branchId)
                        ->where('status', 'pending')
                        ->countAllResults();
                    
                    if ($pendingOrders > 0) {
                        $alerts[] = ['type' => 'info', 'message' => $pendingOrders . ' pending purchase orders'];
                    }
                }
            } catch (Exception $e) {
                log_message('debug', 'Pending orders check failed: ' . $e->getMessage());
            }
            
            // Check for deliveries completed today (if table exists)
            try {
                if (in_array('deliveries', $tables)) {
                    $deliveredToday = $this->db->table('deliveries')
                        ->where('branch_id', $branchId)
                        ->where('DATE(actual_delivery_date)', date('Y-m-d'))
                        ->where('status', 'delivered')
                        ->countAllResults();
                    
                    if ($deliveredToday > 0) {
                        $alerts[] = ['type' => 'success', 'message' => $deliveredToday . ' deliveries completed today'];
                    }
                }
            } catch (Exception $e) {
                log_message('debug', 'Deliveries check failed: ' . $e->getMessage());
            }
            
            if (empty($alerts)) {
                $alerts[] = ['type' => 'success', 'message' => 'All systems operating normally'];
            }
            
            return [
                'customer_satisfaction' => 4.6,
                'average_order_time' => '8.5 minutes',
                'peak_hours' => ['11AM-2PM', '6PM-8PM'],
                'busiest_day' => 'Saturday',
                'slowest_day' => 'Tuesday',
                'alerts' => array_slice($alerts, 0, 3)
            ];
        } catch (Exception $e) {
            log_message('error', 'Error in getOperationalData: ' . $e->getMessage());
            return [
                'customer_satisfaction' => 4.6,
                'average_order_time' => '8.5 minutes',
                'peak_hours' => ['11AM-2PM', '6PM-8PM'],
                'busiest_day' => 'Saturday',
                'slowest_day' => 'Tuesday',
                'alerts' => [['type' => 'success', 'message' => 'Dashboard loaded successfully']]
            ];
        }
    }

    private function getPurchaseRequests(int $branchId)
    {
        try {
            $purchaseRequestModel = model('PurchaseRequestModel');
            $requests = $purchaseRequestModel->where('branch_id', $branchId)->findAll();
            
            $pending = 0;
            $approved = 0;
            $rejected = 0;
            $converted = 0;
            $totalValue = 0;
            
            foreach ($requests as $req) {
                $status = trim($req['status'] ?? '');
                if ($status === 'pending') {
                    $pending++;
                    $totalValue += (float)($req['total_amount'] ?? 0);
                } elseif ($status === 'approved') {
                    $approved++;
                } elseif ($status === 'rejected') {
                    $rejected++;
                } elseif ($status === 'converted_to_po') {
                    $converted++;
                }
            }
            
            return [
                'pending_approvals' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'converted_to_po' => $converted,
                'total_pending_value' => $totalValue,
                'requests' => array_slice($requests, 0, 5)
            ];
        } catch (Exception $e) {
            return [
                'pending_approvals' => 0,
                'approved' => 0,
                'rejected' => 0,
                'converted_to_po' => 0,
                'total_pending_value' => 0,
                'requests' => []
            ];
        }
    }

    private function getSupplierReports(int $branchId): array
    {
        try {
            // Get pending and in-transit orders
            $pendingOrders = 0;
            $inTransitOrders = 0;
            
            $tables = $this->db->listTables();
            if (in_array('purchase_orders', $tables)) {
                $pendingOrders = $this->db->table('purchase_orders')
                    ->where('branch_id', $branchId)
                    ->where('status', 'pending')
                    ->countAllResults();
                
                $inTransitOrders = $this->db->table('purchase_orders')
                    ->where('branch_id', $branchId)
                    ->where('status', 'in_transit')
                    ->countAllResults();
            }
            
            // Get delivery performance
            $onTimeDeliveries = 0;
            $delayedDeliveries = 0;
            $totalDeliveries = 0;
            
            if (in_array('deliveries', $tables)) {
                $deliveries = $this->db->table('deliveries')
                    ->where('branch_id', $branchId)
                    ->where('status', 'delivered')
                    ->get()
                    ->getResultArray();
                
                $totalDeliveries = count($deliveries);
                
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
            }
            
            $onTimeRate = $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100, 2) : 0;
            
            // Get actual supplier counts from database
            $activeSuppliers = [];
            $allSuppliers = [];
            try {
                $activeSuppliers = $this->supplierModel->getActiveSuppliers();
                $allSuppliers = $this->supplierModel->findAll();
            } catch (Exception $e) {
                log_message('error', 'Error getting suppliers: ' . $e->getMessage());
            }
            
            return [
                'active_suppliers' => count($activeSuppliers),
                'total_suppliers' => count($allSuppliers),
                'pending_orders' => $pendingOrders,
                'in_transit_orders' => $inTransitOrders,
                'on_time_delivery_rate' => $onTimeRate,
                'total_deliveries' => $totalDeliveries,
                'on_time_deliveries' => $onTimeDeliveries,
                'delayed_deliveries' => $delayedDeliveries,
            ];
        } catch (Exception $e) {
            log_message('error', 'Error in getSupplierReports: ' . $e->getMessage());
            return [
                'active_suppliers' => 0,
                'total_suppliers' => 0,
                'pending_orders' => 0,
                'in_transit_orders' => 0,
                'on_time_delivery_rate' => 0,
                'total_deliveries' => 0,
                'on_time_deliveries' => 0,
                'delayed_deliveries' => 0,
            ];
        }
    }

    private function getDeliveryTracking(int $branchId): array
    {
        try {
            $tables = $this->db->listTables();
            
            if (!in_array('deliveries', $tables)) {
                return [
                    'pending_deliveries' => 0,
                    'in_transit_deliveries' => 0,
                    'delivered_today' => 0,
                    'delayed_deliveries' => 0,
                    'total_deliveries' => 0,
                ];
            }
            
            $today = date('Y-m-d');
            
            $pending = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->where('status', 'pending')
                ->countAllResults();
            
            $inTransit = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->where('status', 'in_transit')
                ->countAllResults();
            
            $deliveredToday = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->where('DATE(actual_delivery_date)', $today)
                ->where('status', 'delivered')
                ->countAllResults();
            
            // Calculate delayed deliveries: scheduled date passed but not delivered
            $today = date('Y-m-d');
            $delayed = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->where('scheduled_date <', $today)
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->countAllResults();
            
            // Also count explicitly marked as delayed
            $explicitDelayed = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->where('status', 'delayed')
                ->countAllResults();
            
            // Use the higher count (either calculated or explicit)
            $delayedCount = max($delayed, $explicitDelayed);
            
            $total = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->countAllResults();
            
            return [
                'pending_deliveries' => $pending,
                'in_transit_deliveries' => $inTransit,
                'delivered_today' => $deliveredToday,
                'delayed_deliveries' => $delayedCount,
                'total_deliveries' => $total,
            ];
        } catch (Exception $e) {
            log_message('error', 'Error in getDeliveryTracking: ' . $e->getMessage());
            return [
                'pending_deliveries' => 0,
                'in_transit_deliveries' => 0,
                'delivered_today' => 0,
                'delayed_deliveries' => 0,
                'total_deliveries' => 0,
            ];
        }
    }

    /**
     * Deliveries page for manager - to confirm deliveries
     */
    public function deliveries()
    {
        $session = session();

        // Auth check
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return redirect()->to('/auth/login');
        }

        $branchId = $session->get('branch_id');
        if (empty($branchId)) {
            $session->setFlashdata('error', 'Branch assignment missing. Please contact the central admin.');
            return redirect()->back();
        }

        // Get pending and in-transit deliveries for this branch
        try {
            $deliveries = $this->db->table('deliveries d')
                ->select('d.*, po.order_number, s.name as supplier_name')
                ->join('purchase_orders po', 'po.id = d.purchase_order_id', 'left')
                ->join('suppliers s', 's.id = d.supplier_id', 'left')
                ->where('d.branch_id', $branchId)
                ->whereIn('d.status', ['scheduled', 'in_transit', 'pending'])
                ->orderBy('d.scheduled_date', 'ASC')
                ->get()
                ->getResultArray();

            // Format deliveries using trackDelivery to get payment status
            $deliveryModel = new \App\Models\DeliveryModel();
            $formattedDeliveries = [];
            foreach ($deliveries as $delivery) {
                // Use trackDelivery to get full details including payment status
                $deliveryDetails = $deliveryModel->trackDelivery($delivery['id']);
                if ($deliveryDetails) {
                    $formattedDeliveries[] = $deliveryDetails;
                } else {
                    // Fallback to basic data if trackDelivery fails
                    $formattedDeliveries[] = [
                        'id' => $delivery['id'],
                        'delivery_number' => $delivery['delivery_number'] ?? ('DLV-' . str_pad($delivery['id'], 5, '0', STR_PAD_LEFT)),
                        'purchase_order' => ['id' => $delivery['purchase_order_id'], 'order_number' => $delivery['order_number'] ?? 'N/A'],
                        'supplier' => ['id' => $delivery['supplier_id'], 'name' => $delivery['supplier_name'] ?? 'N/A'],
                        'status' => $delivery['status'] ?? 'scheduled',
                        'scheduled_date' => $delivery['scheduled_date'],
                        'actual_delivery_date' => $delivery['actual_delivery_date'],
                        'driver_name' => $delivery['driver_name'],
                        'vehicle_info' => $delivery['vehicle_info'],
                        'received_by' => $delivery['received_by'],
                        'received_at' => $delivery['received_at'],
                        'notes' => $delivery['notes'],
                        'created_at' => $delivery['created_at']
                    ];
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Error fetching deliveries: ' . $e->getMessage());
            $formattedDeliveries = [];
        }

        // Get dashboard data for sidebar
        $dashboardData = $this->getDashboardData((int)$branchId);

        return view('dashboards/manager_deliveries', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'branch_id' => $branchId,
                'user_id' => $session->get('user_id') ?? $session->get('id'),
            ],
            'deliveries' => $formattedDeliveries,
            'data' => $dashboardData
        ]);
    }

    /**
     * Stock Out page for manager
     */
    public function stockOut()
    {
        $session = session();

        // Auth check
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return redirect()->to('/auth/login');
        }

        $branchId = $session->get('branch_id');
        if (empty($branchId)) {
            $session->setFlashdata('error', 'Branch assignment missing. Please contact the central admin.');
            return redirect()->back();
        }

        // Get dashboard data for sidebar
        $dashboardData = $this->getDashboardData((int)$branchId);

        return view('dashboards/manager_stock_out', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'branch_id' => $branchId,
                'user_id' => $session->get('user_id') ?? $session->get('id'),
            ],
            'data' => $dashboardData
        ]);
    }

    /**
     * Settings page for manager
     */
    public function settings()
    {
        $session = session();

        // Auth check
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return redirect()->to('/auth/login');
        }

        $branchId = $session->get('branch_id');
        if (empty($branchId)) {
            $session->setFlashdata('error', 'Branch assignment missing. Please contact the central admin.');
            return redirect()->back();
        }

        // Get branch information
        $branchModel = model('BranchModel');
        $branch = $branchModel->find($branchId);

        return view('dashboards/manager_settings', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'branch_id' => $branchId,
            ],
            'branch' => $branch
        ]);
    }

    /**
     * Search products for stock out
     */
    public function searchProducts()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $query = $this->request->getGet('q');

        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON(['status' => 'success', 'products' => []]);
        }

        try {
            // Search only products from the user's assigned branch
            $products = $this->db->table('products')
                ->where('branch_id', $branchId)
                ->where('stock_qty >', 0)
                ->groupStart()
                    ->like('name', $query)
                    ->orLike('code', $query)
                ->groupEnd()
                ->limit(10)
                ->get()
                ->getResultArray();

            $formattedProducts = [];
            foreach ($products as $product) {
                $formattedProducts[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'code' => $product['code'] ?? '',
                    'stock_qty' => (int)($product['stock_qty'] ?? 0),
                    'unit' => $product['unit'] ?? ''
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'products' => $formattedProducts
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error searching products: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error searching products']);
        }
    }

    /**
     * Record stock out
     */
    public function recordStockOut()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $items = $this->request->getPost('items');

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Items are required']);
        }

        $stockTransactionModel = new \App\Models\StockTransactionModel();
        $userId = $session->get('user_id') ?? $session->get('id');
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $recordedCount = 0;
            
            foreach ($items as $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $reason = $item['reason'] ?? 'other';
                $notes = $item['notes'] ?? '';

                if ($productId > 0 && $quantity > 0) {
                    // Verify product belongs to the user's branch
                    $product = $this->db->table('products')
                        ->where('id', $productId)
                        ->where('branch_id', $branchId)
                        ->get()
                        ->getRowArray();

                    if ($product && $product['stock_qty'] >= $quantity) {
                        // Record STOCK-OUT transaction
                        $stockOutRecorded = $stockTransactionModel->recordStockOut(
                            $productId,
                            $quantity,
                            null, // reference_id
                            'stock_out', // reference_type
                            $userId, // created_by
                            $reason . ($notes ? ': ' . $notes : '') // notes
                        );

                        if ($stockOutRecorded) {
                            $recordedCount++;
                        }
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Stock out recorded successfully for ' . $recordedCount . ' item(s)'
            ]);
        } catch (Exception $e) {
            $db->transRollback();
            log_message('error', 'Error recording stock out: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ==================== USER MANAGEMENT ====================

    /**
     * Get users data for management (including deleted users) - filtered by branch
     */
    private function getUsersData(int $branchId): array
    {
        try {
            // Get users for this branch - ONLY Inventory Staff and Staff roles
            // Branch manager can only manage inventory staff and staff
            $users = $this->db->table('users u')
                ->select('u.*, b.name as branch_name, u.deleted_at')
                ->join('branches b', 'b.id = u.branch_id', 'left')
                ->where('u.branch_id', $branchId)
                ->whereIn('u.role', ['inventory_staff', 'inventorystaff', 'staff'])
                ->orderBy('u.deleted_at', 'ASC') // Deleted users at the bottom
                ->orderBy('u.created_at', 'DESC')
                ->get()
                ->getResultArray();
            
            // Sort users: active first (deleted_at is NULL), then deleted
            usort($users, function($a, $b) {
                $aDeleted = !empty($a['deleted_at']) ? 1 : 0;
                $bDeleted = !empty($b['deleted_at']) ? 1 : 0;
                if ($aDeleted !== $bDeleted) {
                    return $aDeleted - $bDeleted; // Active (0) comes before deleted (1)
                }
                // If both have same deletion status, sort by created_at
                $aTime = strtotime($a['created_at'] ?? '1970-01-01');
                $bTime = strtotime($b['created_at'] ?? '1970-01-01');
                return $bTime - $aTime; // Newer first
            });
            
        } catch (Exception $e) {
            log_message('error', 'Error fetching users in Manager: ' . $e->getMessage());
            $users = [];
        }

        // Separate active and deleted users
        $activeUsers = [];
        $deletedUsers = [];
        
        foreach ($users as $user) {
            if (!empty($user['deleted_at'])) {
                $deletedUsers[] = $user;
            } else {
                $activeUsers[] = $user;
            }
        }

        // Count users by role (only active users)
        $usersByRole = [];
        foreach ($activeUsers as $user) {
            $role = $user['role'] ?? 'unknown';
            if (!isset($usersByRole[$role])) {
                $usersByRole[$role] = 0;
            }
            $usersByRole[$role]++;
        }

        return [
            'all_users' => $users,
            'active_users' => $activeUsers,
            'deleted_users' => $deletedUsers,
            'users_by_role' => $usersByRole,
            'total_count' => count($activeUsers),
            'deleted_count' => count($deletedUsers),
        ];
    }

    /**
     * Create a new user (only Inventory Staff or Staff)
     */
    public function createUser()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $data = $this->request->getJSON(true);

        // Validate required fields
        if (empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email, password, and role are required']);
        }

        // RESTRICTION: Only allow Inventory Staff and Staff roles
        $allowedRoles = ['inventory_staff', 'inventorystaff', 'staff'];
        if (!in_array($data['role'], $allowedRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Only Inventory Staff and Staff roles are allowed']);
        }

        // Check if email already exists
        $existingUser = $this->userModel->where('email', $data['email'])->first();
        if ($existingUser) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email already exists']);
        }

        try {
            // Create user
            $userData = [
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $data['role'],
                'branch_id' => $branchId, // Always assign to manager's branch
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $userId = $this->userModel->insert($userData);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'CREATE',
                null,
                $userData,
                $session->get('user_id'),
                'User created by branch manager'
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User created successfully',
                'user_id' => $userId
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error creating user in Manager: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error creating user: ' . $e->getMessage()]);
        }
    }

    /**
     * Update a user
     */
    public function updateUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $data = $this->request->getJSON(true);

        // Get user (including deleted)
        $user = $this->userModel->withDeleted()->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Verify user belongs to manager's branch
        if ($user['branch_id'] != $branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to update this user']);
        }

        // RESTRICTION: Only allow Inventory Staff and Staff roles
        if (isset($data['role'])) {
            $allowedRoles = ['inventory_staff', 'inventorystaff', 'staff'];
            if (!in_array($data['role'], $allowedRoles)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Only Inventory Staff and Staff roles are allowed']);
            }
        }

        // Check email uniqueness if changed
        if (isset($data['email']) && $data['email'] !== $user['email']) {
            $existingUser = $this->userModel->where('email', $data['email'])->first();
            if ($existingUser) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Email already exists']);
            }
        }

        try {
            $oldData = $user;
            $updateData = [];

            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }
            if (isset($data['password']) && !empty($data['password'])) {
                $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            if (isset($data['role'])) {
                $updateData['role'] = $data['role'];
            }
            if (isset($data['branch_id'])) {
                $updateData['branch_id'] = $data['branch_id'];
            }

            $updateData['updated_at'] = date('Y-m-d H:i:s');

            $this->userModel->update($userId, $updateData);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'UPDATE',
                $oldData,
                array_merge($user, $updateData),
                $session->get('user_id'),
                'User updated by branch manager'
            );

            return $this->response->setJSON(['status' => 'success', 'message' => 'User updated successfully']);
        } catch (Exception $e) {
            log_message('error', 'Error updating user in Manager: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error updating user: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a user (soft delete)
     */
    public function deleteUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $currentUserId = $session->get('user_id');

        // Prevent deleting yourself
        if ($userId == $currentUserId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'You cannot delete your own account']);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Verify user belongs to manager's branch
        if ($user['branch_id'] != $branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to delete this user']);
        }

        // RESTRICTION: Only allow deleting Inventory Staff and Staff
        $allowedRoles = ['inventory_staff', 'inventorystaff', 'staff'];
        if (!in_array($user['role'], $allowedRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'You can only delete Inventory Staff and Staff accounts']);
        }

        try {
            // Soft delete
            $this->userModel->delete($userId);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'DELETE',
                $user,
                array_merge($user, ['deleted_at' => date('Y-m-d H:i:s')]),
                $session->get('user_id'),
                'User deleted by branch manager'
            );

            return $this->response->setJSON(['status' => 'success', 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            log_message('error', 'Error deleting user in Manager: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error deleting user: ' . $e->getMessage()]);
        }
    }

    /**
     * Get a single user
     */
    public function getUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $user = $this->userModel->withDeleted()->find($userId);

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Verify user belongs to manager's branch
        if ($user['branch_id'] != $branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        // Remove password from response
        unset($user['password']);

        return $this->response->setJSON(['status' => 'success', 'user' => $user]);
    }

    /**
     * Restore a deleted user
     */
    public function restoreUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['manager', 'branch_manager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $branchId = $session->get('branch_id');
        $user = $this->userModel->withDeleted()->find($userId);
        
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Verify user belongs to manager's branch
        if ($user['branch_id'] != $branchId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to restore this user']);
        }

        // Check if user is actually deleted
        if (empty($user['deleted_at'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User is not deleted']);
        }

        try {
            // Restore user by clearing deleted_at
            $this->userModel->restoreUser($userId);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'RESTORE',
                $user,
                array_merge($user, ['deleted_at' => null]),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User restored successfully',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
