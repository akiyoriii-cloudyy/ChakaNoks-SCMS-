<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

/**
 * This seeder updates all categories with NULL created_by to have a valid user ID
 * It ensures all categories have a proper created_by value
 */
class UpdateCategoriesCreatedBySeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();
        
        // Get inventory staff users (prefer inventory_staff, fallback to admin, then any user)
        $invUsers = $this->db->table('users')
            ->where('role', 'inventory_staff')
            ->get()->getResultArray();
        
        if (empty($invUsers)) {
            // Try admin role
            $invUsers = $this->db->table('users')
                ->where('role', 'admin')
                ->get()->getResultArray();
        }
        
        if (empty($invUsers)) {
            // Fallback to any user
            $invUsers = $this->db->table('users')
                ->limit(1)
                ->get()->getResultArray();
        }
        
        $defaultUserId = !empty($invUsers) ? (int) $invUsers[0]['id'] : null;
        
        if (!$defaultUserId) {
            echo "❌ Error: No users found in database. Cannot update created_by.\n";
            return;
        }
        
        echo "✅ Using user ID {$defaultUserId} as default created_by\n";
        
        // Update all categories with NULL created_by
        $categoriesTable = $this->db->table('categories');
        
        $categoriesWithNull = $categoriesTable
            ->where('created_by IS NULL')
            ->orWhere('created_by', 0)
            ->get()
            ->getResultArray();
        
        $updateCount = 0;
        
        foreach ($categoriesWithNull as $category) {
            $categoriesTable->where('id', $category['id'])
                ->update([
                    'created_by' => $defaultUserId,
                    'updated_at' => $now
                ]);
            $updateCount++;
            echo "✅ Updated category '{$category['name']}' (ID: {$category['id']}) with created_by = {$defaultUserId}\n";
        }
        
        if ($updateCount == 0) {
            echo "✅ All categories already have created_by values set.\n";
        } else {
            echo "\n📊 Summary: Updated {$updateCount} categories with created_by values\n";
        }
    }
}

