<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        /** ✅ Build helper maps */
        $branchRows = $this->db->table('branches')->get()->getResultArray();
        $branchIdByCode = [];
        foreach ($branchRows as $b) {
            $branchIdByCode[$b['code']] = (int) $b['id'];
        }

        // ✅ Get all inventory staff users
        $invUsers = $this->db->table('users')
            ->where('role', 'inventory_staff')
            ->get()->getResultArray();

        // ✅ Map branch_id => inventory_staff user id (first found)
        $invByBranchId = [];
        foreach ($invUsers as $u) {
            if (!empty($u['branch_id']) && !isset($invByBranchId[$u['branch_id']])) {
                $invByBranchId[$u['branch_id']] = (int) $u['id'];
            }
        }

        /** ✅ Product seed data */
        $items = [
            ['name' => 'Whole Chicken',     'category' => 'Chicken Parts', 'branch' => 'MATINA',   'stock' => 120, 'unit' => 'kg',  'min_stock' => 50, 'max_stock' => 200, 'expiry' => date('Y-m-d', strtotime('+5 days'))],
            ['name' => 'Chicken Breast',    'category' => 'Chicken Parts', 'branch' => 'TORIL',    'stock' => 80,  'unit' => 'kg',  'min_stock' => 40, 'max_stock' => 150, 'expiry' => date('Y-m-d', strtotime('+4 days'))],
            ['name' => 'Chicken Wings',     'category' => 'Chicken Parts', 'branch' => 'BUHANGIN', 'stock' => 20,  'unit' => 'kg',  'min_stock' => 30, 'max_stock' => 120, 'expiry' => date('Y-m-d', strtotime('+2 days'))],
            ['name' => 'Chicken Drumstick', 'category' => 'Chicken Parts', 'branch' => 'AGDAO',    'stock' => 60,  'unit' => 'kg',  'min_stock' => 50, 'max_stock' => 200, 'expiry' => date('Y-m-d', strtotime('+6 days'))],
            ['name' => 'Chicken Liver',     'category' => 'Chicken Parts', 'branch' => 'LANANG',   'stock' => 15,  'unit' => 'kg',  'min_stock' => 25, 'max_stock' => 80,  'expiry' => date('Y-m-d', strtotime('+1 day'))],
            // Example fallback product
            ['name' => 'Other',             'category' => 'Chicken Parts', 'branch' => 'MATINA',   'stock' => 5,   'unit' => 'pcs', 'min_stock' => 1,  'max_stock' => 20,  'expiry' => null],
        ];

        $tbl = $this->db->table('products');

        foreach ($items as $p) {
            $branchCode   = $p['branch'];
            $branchId     = $branchIdByCode[$branchCode] ?? null;
            $createdBy    = $branchId ? ($invByBranchId[$branchId] ?? null) : null;

            if (!$branchId) {
                echo "⚠️ Skipped {$p['name']} → branch '{$branchCode}' not found\n";
                continue;
            }

            $row = [
                'name'       => $p['name'],
                'category'   => $p['category'],
                'branch_id'  => $branchId,
                'created_by' => $createdBy,
                'stock'      => $p['stock'],
                'unit'       => $p['unit'],
                'min_stock'  => $p['min_stock'],
                'max_stock'  => $p['max_stock'],
                'expiry'     => $p['expiry'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            /** ✅ Upsert logic (update if exists, else insert) */
            $existing = $tbl->where('name', $row['name'])
                            ->where('branch_id', $row['branch_id'])
                            ->get()->getRowArray();

            if ($existing) {
                $tbl->where('id', $existing['id'])->update($row);
                echo "🔄 Updated product: {$row['name']} ({$branchCode})\n";
            } else {
                $tbl->insert($row);
                echo "✅ Inserted product: {$row['name']} ({$branchCode})\n";
            }
        }
    }
}
