<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * This seeder ensures all products have proper branch_id assigned
 * Distributes products across branches evenly
 */
class EnsureProductBranchesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();
        
        echo "=== Ensuring All Products Have Branches ===\n\n";
        
        // Get all branches
        $branches = $this->db->table('branches')->get()->getResultArray();
        
        if (empty($branches)) {
            echo "❌ No branches found! Cannot assign branches.\n";
            return;
        }
        
        $branchIds = array_column($branches, 'id');
        echo "Found " . count($branchIds) . " branches\n";
        
        // Get products without branch_id
        $productsWithoutBranch = $this->db->table('products')
            ->where('branch_id IS NULL OR branch_id = 0')
            ->get()
            ->getResultArray();
        
        echo "Products without branch: " . count($productsWithoutBranch) . "\n";
        
        // Distribute products across branches
        $updatedCount = 0;
        foreach ($productsWithoutBranch as $index => $product) {
            $branchId = $branchIds[$index % count($branchIds)];
            
            $this->db->table('products')
                ->where('id', $product['id'])
                ->update([
                    'branch_id' => $branchId,
                    'updated_at' => $now
                ]);
            
            $updatedCount++;
        }
        
        echo "✅ Updated {$updatedCount} products with branch assignments\n";
        
        // Also ensure all products have created_by
        $productsWithoutCreator = $this->db->table('products')
            ->where('created_by IS NULL')
            ->countAllResults();
        
        if ($productsWithoutCreator > 0) {
            // Get first inventory staff
            $invUser = $this->db->table('users')
                ->where('role', 'inventory_staff')
                ->limit(1)
                ->get()
                ->getRowArray();
            
            if ($invUser) {
                $this->db->table('products')
                    ->where('created_by IS NULL')
                    ->update([
                        'created_by' => $invUser['id'],
                        'updated_at' => $now
                    ]);
                
                echo "✅ Updated {$productsWithoutCreator} products with created_by\n";
            }
        }
        
        // Verify
        echo "\n=== Verification ===\n";
        
        $totalProducts = $this->db->table('products')->countAllResults();
        echo "Total products: {$totalProducts}\n";
        
        $productsWithBranch = $this->db->table('products')
            ->where('branch_id IS NOT NULL')
            ->where('branch_id >', 0)
            ->countAllResults();
        echo "Products with branch: {$productsWithBranch}\n";
        
        $productsWithCreator = $this->db->table('products')
            ->where('created_by IS NOT NULL')
            ->countAllResults();
        echo "Products with creator: {$productsWithCreator}\n";
        
        // Show distribution by branch
        echo "\n=== Products by Branch ===\n";
        foreach ($branches as $branch) {
            $count = $this->db->table('products')
                ->where('branch_id', $branch['id'])
                ->countAllResults();
            echo "  {$branch['code']} ({$branch['name']}): {$count} products\n";
        }
        
        echo "\n✅ All products now have proper branch assignments!\n";
    }
}

