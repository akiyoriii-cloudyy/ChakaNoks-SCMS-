<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        // ✅ Get all inventory staff users
        $invUsers = $this->db->table('users')
            ->where('role', 'inventory_staff')
            ->get()->getResultArray();

        // ✅ Use first inventory staff as default created_by
        $defaultUser = !empty($invUsers) ? (int) $invUsers[0]['id'] : null;

        /** ✅ Product seed data */
        $items = [
            [
                'name'           => 'Whole Chicken',
                'category'       => 'Chicken Parts',
                'branch_address' => 'MATINA Branch, Davao City',
                'stock_qty'      => 1700,
                'unit'           => 'kg',
                'min_stock'      => 1700,
                'max_stock'      => 1700,
                'expiry'         => date('Y-m-d', strtotime('+105 days')),
            ],
            [
                'name'           => 'Chicken Breast',
                'category'       => 'Chicken Parts',
                'branch_address' => 'TORIL Branch, Davao City',
                'stock_qty'      => 1600,
                'unit'           => 'kg',
                'min_stock'      => 1600,
                'max_stock'      => 1680,
                'expiry'         => date('Y-m-d', strtotime('+93 days')),
            ],
            [
                'name'           => 'Chicken Wings',
                'category'       => 'Chicken Parts',
                'branch_address' => 'BUHANGIN Branch, Davao City',
                'stock_qty'      => 1300,
                'unit'           => 'kg',
                'min_stock'      => 1300,
                'max_stock'      => 1350,
                'expiry'         => date('Y-m-d', strtotime('+85 days')),
            ],
            [
                'name'           => 'Chicken Drumstick',
                'category'       => 'Chicken Parts',
                'branch_address' => 'AGDAO Branch, Davao City',
                'stock_qty'      => 1800,
                'unit'           => 'kg',
                'min_stock'      => 1800,
                'max_stock'      => 1950,
                'expiry'         => date('Y-m-d', strtotime('+90 days')),
            ],
            [
                'name'           => 'Chicken Liver',
                'category'       => 'Chicken Parts',
                'branch_address' => 'LANANG Branch, Davao City',
                'stock_qty'      => 1500,
                'unit'           => 'kg',
                'min_stock'      => 1500,
                'max_stock'      => 1500,
                'expiry'         => date('Y-m-d', strtotime('+87 day')),
            ],
            [
                'name'           => 'Beplop',
                'category'       => 'Chicken Parts',
                'branch_address' => 'TORIL Branch, Davao City',
                'stock_qty'      => 1000,
                'unit'           => 'pcs',
                'min_stock'      => 1000,
                'max_stock'      => 1150,
                'expiry'         => date('Y-m-d', strtotime('+84 day')),
            ],
        ];

        $tbl = $this->db->table('products');

        foreach ($items as $p) {
            $row = [
                'name'           => $p['name'],
                'category'       => $p['category'],
                'branch_address' => $p['branch_address'],
                'created_by'     => $defaultUser,
                'stock_qty'      => $p['stock_qty'],
                'unit'           => $p['unit'],
                'min_stock'      => $p['min_stock'],
                'max_stock'      => $p['max_stock'],
                'expiry'         => $p['expiry'],
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

            // ✅ Upsert (check by name + branch_address)
            $existing = $tbl->where('name', $row['name'])
                            ->where('branch_address', $row['branch_address'])
                            ->get()->getRowArray();

            if ($existing) {
                $tbl->where('id', $existing['id'])->update($row);
                echo "🔄 Updated product: {$row['name']} ({$p['branch_address']})\n";
            } else {
                $tbl->insert($row);
                echo "✅ Inserted product: {$row['name']} ({$p['branch_address']})\n";
            }
        }
    }
}
