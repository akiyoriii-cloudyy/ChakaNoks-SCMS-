<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\BranchModel;
use Config\Database;
use Exception;

class FranchiseManager extends BaseController
{
    protected $db;
    protected $productModel;
    protected $branchModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
    }

    public function dashboard()
    {
        $session = session();

        // Auth check
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return redirect()->to('/auth/login');
        }

        // Get all branches for franchise manager
        $branches = $this->branchModel->getAllBranches();
        
        // Get dashboard data
        $dashboardData = $this->getDashboardData();

        return view('dashboards/franchisemanager', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'data' => $dashboardData,
            'branches' => $branches
        ]);
    }

    private function getDashboardData(): array
    {
        // Get all products across all branches (normalized - using JOINs)
        $allProducts = $this->productModel->getInventory();
        
        $summary = [
            'total_items' => count($allProducts),
            'total_stock_value' => 0,
            'low_stock_count' => 0,
            'critical_items_count' => 0,
            'branches_count' => count($this->branchModel->getAllBranches()),
        ];

        foreach ($allProducts as $product) {
            $status = $product['status'] ?? 'Good';
            $stockQty = (int)($product['stock_qty'] ?? 0);
            $unitPrice = (float)($product['price'] ?? 0);
            
            $summary['total_stock_value'] += $stockQty * ($unitPrice > 0 ? $unitPrice : 150);

            if (in_array($status, ['Low Stock', 'Expiring Soon'])) {
                $summary['low_stock_count']++;
            }
            
            if (in_array($status, ['Critical', 'Out of Stock'])) {
                $summary['critical_items_count']++;
            }
        }

        return [
            'inventory' => $summary,
            'branches' => $this->getBranchSummary(),
        ];
    }

    private function getBranchSummary(): array
    {
        $branches = $this->branchModel->getAllBranches();
        $summary = [];

        foreach ($branches as $branch) {
            // Exclude "central" branch (case-insensitive check)
            $branchName = strtolower($branch['name'] ?? '');
            $branchCode = strtolower($branch['code'] ?? '');
            
            if (strpos($branchName, 'central') !== false || strpos($branchCode, 'central') !== false) {
                continue; // Skip central branch
            }

            // Get products for this branch (normalized - using branch_id)
            $branchProducts = $this->db->table('products')
                ->where('branch_id', $branch['id'])
                ->get()
                ->getResultArray();

            $summary[] = [
                'branch_id' => $branch['id'],
                'branch_name' => $branch['name'],
                'branch_code' => $branch['code'],
                'branch_address' => $branch['address'] ?? '',
                'total_products' => count($branchProducts),
            ];
        }

        // Limit to 5 branches only
        return array_slice($summary, 0, 5);
    }
}
