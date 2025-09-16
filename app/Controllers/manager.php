<?php
namespace App\Controllers;

use App\Models\ProductModel;
use Config\Database;

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

        // Get dashboard data
        $dashboardData = $this->getDashboardData();

        return view('dashboards/manager', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'data' => $dashboardData
        ]);
    }

    private function getDashboardData()
    {
        // Get inventory summary
        $inventorySummary = $this->getInventorySummary();
        
        // Get sales data (mock data for now)
        $salesData = $this->getSalesData();
        
        // Get staff data (mock data for now)
        $staffData = $this->getStaffData();
        
        // Get operational metrics
        $operationalData = $this->getOperationalData();

        return [
            'inventory' => $inventorySummary,
            'sales' => $salesData,
            'staff' => $staffData,
            'operations' => $operationalData
        ];
    }

    private function getInventorySummary()
    {
        $items = $this->model->getInventory();
        
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
            $status = $item['status'] ?? 'Good';
            switch ($status) {
                case 'Critical':
                case 'Out of Stock':
                case 'Expired':
                    $summary['critical_items']++;
                    break;
                case 'Low Stock':
                case 'Expiring Soon':
                    $summary['low_stock_items']++;
                    break;
                case 'Good':
                    $summary['good_stock_items']++;
                    break;
            }

            // Calculate total value (mock calculation)
            $summary['total_value'] += ($item['stock_qty'] ?? 0) * 150; // Average price per unit
        }

        return $summary;
    }

    private function getSalesData()
    {
        // Mock sales data - in real app, this would come from sales table
        return [
            'today_sales' => 125000,
            'week_sales' => 875000,
            'month_sales' => 3200000,
            'growth_rate' => 12.5,
            'top_selling_items' => [
                ['name' => 'Chicken Breast', 'quantity' => 450, 'revenue' => 67500],
                ['name' => 'Chicken Wings', 'quantity' => 380, 'revenue' => 57000],
                ['name' => 'Chicken Drumstick', 'quantity' => 320, 'revenue' => 48000],
                ['name' => 'Whole Chicken', 'quantity' => 280, 'revenue' => 42000],
                ['name' => 'Chicken Liver', 'quantity' => 250, 'revenue' => 37500]
            ],
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

    private function getOperationalData()
    {
        return [
            'customer_satisfaction' => 4.6,
            'average_order_time' => '8.5 minutes',
            'peak_hours' => ['11AM-2PM', '6PM-8PM'],
            'busiest_day' => 'Saturday',
            'slowest_day' => 'Tuesday',
            'alerts' => [
                ['type' => 'warning', 'message' => '3 items expiring within 7 days'],
                ['type' => 'info', 'message' => 'Staff meeting scheduled for 3PM'],
                ['type' => 'success', 'message' => 'Monthly target achieved 2 days early']
            ]
        ];
    }
}
