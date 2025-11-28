<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * This seeder fixes purchase requests that have no items
 * and ensures all products have branch_id assigned
 */
class FixPurchaseRequestsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();
        
        echo "=== Checking Purchase Requests ===\n";
        
        // Get all purchase requests
        $requests = $this->db->table('purchase_requests')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
        
        echo "Total purchase requests: " . count($requests) . "\n\n";
        
        $requestsWithoutItems = [];
        foreach ($requests as $request) {
            $itemCount = $this->db->table('purchase_request_items')
                ->where('purchase_request_id', $request['id'])
                ->countAllResults();
            
            echo "PR #{$request['id']} ({$request['request_number']}): {$itemCount} items, Status: {$request['status']}, Branch ID: " . ($request['branch_id'] ?? 'NULL') . "\n";
            
            if ($itemCount === 0) {
                $requestsWithoutItems[] = $request;
            }
        }
        
        echo "\n=== Purchase Requests WITHOUT Items: " . count($requestsWithoutItems) . " ===\n";
        foreach ($requestsWithoutItems as $request) {
            echo "  - {$request['request_number']} (ID: {$request['id']}, Status: {$request['status']})\n";
        }
        
        // Get branches
        $branches = $this->db->table('branches')->get()->getResultArray();
        $branchMap = [];
        foreach ($branches as $branch) {
            $branchMap[$branch['id']] = $branch;
        }
        
        echo "\n=== Branches ===\n";
        foreach ($branches as $branch) {
            echo "  ID: {$branch['id']} - {$branch['code']} ({$branch['name']})\n";
        }
        
        // Check products without branch_id
        $productsWithoutBranch = $this->db->table('products')
            ->where('branch_id IS NULL')
            ->get()
            ->getResultArray();
        
        echo "\n=== Products WITHOUT Branch ID: " . count($productsWithoutBranch) . " ===\n";
        
        if (count($productsWithoutBranch) > 0) {
            // Get default branch (first one)
            $defaultBranchId = !empty($branches) ? $branches[0]['id'] : null;
            
            if ($defaultBranchId) {
                echo "Assigning default branch ID {$defaultBranchId} to products without branch...\n";
                
                $this->db->table('products')
                    ->where('branch_id IS NULL')
                    ->update(['branch_id' => $defaultBranchId, 'updated_at' => $now]);
                
                echo "✅ Updated " . count($productsWithoutBranch) . " products with branch_id\n";
            }
        }
        
        // Check total products
        $totalProducts = $this->db->table('products')->countAllResults();
        echo "\n=== Total Products: {$totalProducts} ===\n";
        
        // Sample products
        $sampleProducts = $this->db->table('products')
            ->select('products.id, products.name, products.price, products.branch_id, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->limit(20)
            ->get()
            ->getResultArray();
        
        echo "\n=== Sample Products ===\n";
        foreach ($sampleProducts as $product) {
            echo sprintf("  ID: %d | %s | ₱%.2f | Branch: %s | Category: %s\n",
                $product['id'],
                $product['name'],
                $product['price'] ?? 0,
                $product['branch_id'] ?? 'NULL',
                $product['category_name'] ?? 'N/A'
            );
        }
        
        echo "\n✅ Database check complete!\n";
    }
}

