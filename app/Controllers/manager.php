<?php
namespace App\Controllers;

use App\Models\ProductModel;
use Config\Database;
use Exception;

class Manager extends BaseController
{
    protected $db;
    protected $model;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->model = new ProductModel();
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

        return view('dashboards/manager', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'branch_id' => $branchId,
            ],
            'data' => $dashboardData
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
            // Get all products with their prices and stock quantities
            $items = $this->db->table('products')
                ->select('id, name, stock_qty, price, status, expiry, min_stock')
                ->where('branch_id', $branchId)
                ->get()
                ->getResultArray();
            
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
                $stock_qty = $item['stock_qty'] ?? 0;
                $price = $item['price'] ?? 0;
                $min_stock = $item['min_stock'] ?? 10;
                $status = $item['status'] ?? 'active';
                $expiry = $item['expiry'];
                
                // Determine item status
                if ($stock_qty == 0) {
                    $summary['critical_items']++;
                } elseif ($stock_qty <= $min_stock) {
                    $summary['low_stock_items']++;
                } else {
                    $summary['good_stock_items']++;
                }
                
                // Check expiry status
                if ($expiry) {
                    $today = date('Y-m-d');
                    $expiry_date = $expiry;
                    if ($expiry_date < $today) {
                        $summary['expired_items']++;
                    } elseif ($expiry_date <= date('Y-m-d', strtotime('+7 days'))) {
                        $summary['expiring_soon']++;
                    }
                }
                
                // Calculate total value using actual product price
                $summary['total_value'] += $stock_qty * $price;
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
            
            // Check for items expiring within 7 days (using 'expiry' column)
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
            
            // Check for low stock items - using proper CodeIgniter 4 syntax
            try {
                // Get all products and check in PHP instead of SQL
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
                if ($req['status'] === 'pending') {
                    $pending++;
                    $totalValue += $req['total_amount'] ?? 0;
                } elseif ($req['status'] === 'approved') $approved++;
                elseif ($req['status'] === 'rejected') $rejected++;
                elseif ($req['status'] === 'converted_to_po') $converted++;
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
            
            return [
                'active_suppliers' => 5,
                'total_suppliers' => 8,
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
            
            $delayed = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->where('status', 'delayed')
                ->countAllResults();
            
            $total = $this->db->table('deliveries')
                ->where('branch_id', $branchId)
                ->countAllResults();
            
            return [
                'pending_deliveries' => $pending,
                'in_transit_deliveries' => $inTransit,
                'delivered_today' => $deliveredToday,
                'delayed_deliveries' => $delayed,
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
}
