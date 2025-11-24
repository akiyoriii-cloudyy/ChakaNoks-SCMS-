<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        $branchRows = $this->db->table('branches')->select('id, code')->get()->getResultArray();
        $branchMap = [];
        foreach ($branchRows as $branchRow) {
            $branchMap[strtoupper($branchRow['code'])] = $branchRow['id'];
        }

        // ✅ Get all inventory staff users
        $invUsers = $this->db->table('users')
            ->where('role', 'inventory_staff')
            ->get()->getResultArray();

        // ✅ Use first inventory staff as default created_by
        $defaultUser = !empty($invUsers) ? (int) $invUsers[0]['id'] : null;

        // ✅ Get or create "Chicken Parts" category
        $category = $this->db->table('categories')
            ->where('name', 'Chicken Parts')
            ->get()
            ->getRowArray();
        
        if (!$category) {
            $this->db->table('categories')->insert([
                'name' => 'Chicken Parts',
                'description' => 'Chicken parts and products',
                'status' => 'active',
                'created_by' => $defaultUser,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $categoryId = $this->db->insertID();
        } else {
            $categoryId = $category['id'];
        }

        /** ✅ Product seed data - All Chicken Parts (Normalized: using branch_id and category_id) */
        $items = [
            ['name' => 'Whole Chicken', 'branch_code' => 'MATINA', 'stock_qty' => 500, 'unit' => 'kg', 'price' => 250.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry' => date('Y-m-d', strtotime('+105 days'))],
            ['name' => 'Chicken Breast', 'branch_code' => 'TORIL', 'stock_qty' => 600, 'unit' => 'kg', 'price' => 280.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry' => date('Y-m-d', strtotime('+93 days'))],
            ['name' => 'Chicken Thigh', 'branch_code' => 'BUHANGIN', 'stock_qty' => 550, 'unit' => 'kg', 'price' => 220.00, 'min_stock' => 100, 'max_stock' => 1000, 'expiry' => date('Y-m-d', strtotime('+90 days'))],
            ['name' => 'Chicken Wings', 'branch_code' => 'AGDAO', 'stock_qty' => 450, 'unit' => 'kg', 'price' => 180.00, 'min_stock' => 100, 'max_stock' => 800, 'expiry' => date('Y-m-d', strtotime('+85 days'))],
            ['name' => 'Chicken Drumstick', 'branch_code' => 'LANANG', 'stock_qty' => 500, 'unit' => 'kg', 'price' => 200.00, 'min_stock' => 100, 'max_stock' => 900, 'expiry' => date('Y-m-d', strtotime('+90 days'))],
            ['name' => 'Chicken Liver', 'branch_code' => 'MATINA', 'stock_qty' => 300, 'unit' => 'kg', 'price' => 120.00, 'min_stock' => 50, 'max_stock' => 600, 'expiry' => date('Y-m-d', strtotime('+87 days'))],
            ['name' => 'Chicken Gizzard', 'branch_code' => 'TORIL', 'stock_qty' => 280, 'unit' => 'kg', 'price' => 130.00, 'min_stock' => 50, 'max_stock' => 600, 'expiry' => date('Y-m-d', strtotime('+88 days'))],
            ['name' => 'Chicken Feet', 'branch_code' => 'BUHANGIN', 'stock_qty' => 200, 'unit' => 'kg', 'price' => 90.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry' => date('Y-m-d', strtotime('+92 days'))],
            ['name' => 'Chicken Head', 'branch_code' => 'AGDAO', 'stock_qty' => 150, 'unit' => 'kg', 'price' => 80.00, 'min_stock' => 30, 'max_stock' => 400, 'expiry' => date('Y-m-d', strtotime('+89 days'))],
            ['name' => 'Chicken Neck', 'branch_code' => 'LANANG', 'stock_qty' => 180, 'unit' => 'kg', 'price' => 100.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry' => date('Y-m-d', strtotime('+86 days'))],
            ['name' => 'Chicken Back', 'branch_code' => 'MATINA', 'stock_qty' => 220, 'unit' => 'kg', 'price' => 110.00, 'min_stock' => 50, 'max_stock' => 550, 'expiry' => date('Y-m-d', strtotime('+91 days'))],
            ['name' => 'Chicken Heart', 'branch_code' => 'TORIL', 'stock_qty' => 120, 'unit' => 'kg', 'price' => 140.00, 'min_stock' => 30, 'max_stock' => 400, 'expiry' => date('Y-m-d', strtotime('+84 days'))],
            ['name' => 'Chicken Kidney', 'branch_code' => 'BUHANGIN', 'stock_qty' => 100, 'unit' => 'kg', 'price' => 135.00, 'min_stock' => 30, 'max_stock' => 350, 'expiry' => date('Y-m-d', strtotime('+83 days'))],
            ['name' => 'Chicken Intestine', 'branch_code' => 'AGDAO', 'stock_qty' => 80, 'unit' => 'kg', 'price' => 95.00, 'min_stock' => 20, 'max_stock' => 300, 'expiry' => date('Y-m-d', strtotime('+82 days'))],
            ['name' => 'Chicken Blood', 'branch_code' => 'LANANG', 'stock_qty' => 60, 'unit' => 'kg', 'price' => 85.00, 'min_stock' => 20, 'max_stock' => 250, 'expiry' => date('Y-m-d', strtotime('+80 days'))],
            ['name' => 'Chicken Skin', 'branch_code' => 'MATINA', 'stock_qty' => 150, 'unit' => 'kg', 'price' => 75.00, 'min_stock' => 30, 'max_stock' => 400, 'expiry' => date('Y-m-d', strtotime('+94 days'))],
            ['name' => 'Chicken Fat', 'branch_code' => 'TORIL', 'stock_qty' => 100, 'unit' => 'kg', 'price' => 70.00, 'min_stock' => 20, 'max_stock' => 300, 'expiry' => date('Y-m-d', strtotime('+96 days'))],
            ['name' => 'Chicken Bones', 'branch_code' => 'BUHANGIN', 'stock_qty' => 200, 'unit' => 'kg', 'price' => 60.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry' => date('Y-m-d', strtotime('+98 days'))],
            ['name' => 'Chicken Tail', 'branch_code' => 'AGDAO', 'stock_qty' => 120, 'unit' => 'kg', 'price' => 105.00, 'min_stock' => 30, 'max_stock' => 350, 'expiry' => date('Y-m-d', strtotime('+87 days'))],
            ['name' => 'Chicken Leg Quarter', 'branch_code' => 'LANANG', 'stock_qty' => 350, 'unit' => 'kg', 'price' => 210.00, 'min_stock' => 80, 'max_stock' => 700, 'expiry' => date('Y-m-d', strtotime('+89 days'))],
            ['name' => 'Chicken Breast Fillet', 'branch_code' => 'MATINA', 'stock_qty' => 400, 'unit' => 'kg', 'price' => 320.00, 'min_stock' => 100, 'max_stock' => 800, 'expiry' => date('Y-m-d', strtotime('+91 days'))],
            ['name' => 'Chicken Tenderloin', 'branch_code' => 'TORIL', 'stock_qty' => 250, 'unit' => 'kg', 'price' => 290.00, 'min_stock' => 50, 'max_stock' => 600, 'expiry' => date('Y-m-d', strtotime('+88 days'))],
            ['name' => 'Chicken Wing Tip', 'branch_code' => 'BUHANGIN', 'stock_qty' => 180, 'unit' => 'kg', 'price' => 160.00, 'min_stock' => 40, 'max_stock' => 400, 'expiry' => date('Y-m-d', strtotime('+86 days'))],
            ['name' => 'Chicken Wing Flat', 'branch_code' => 'AGDAO', 'stock_qty' => 200, 'unit' => 'kg', 'price' => 170.00, 'min_stock' => 40, 'max_stock' => 450, 'expiry' => date('Y-m-d', strtotime('+85 days'))],
            ['name' => 'Chicken Wing Drumlette', 'branch_code' => 'LANANG', 'stock_qty' => 220, 'unit' => 'kg', 'price' => 175.00, 'min_stock' => 50, 'max_stock' => 500, 'expiry' => date('Y-m-d', strtotime('+84 days'))],
        ];

        $tbl = $this->db->table('products');

        foreach ($items as $p) {
            $branchCode = strtoupper($p['branch_code'] ?? '');
            $branchId = isset($branchMap[$branchCode]) ? $branchMap[$branchCode] : null;

            $row = [
                'name'           => $p['name'],
                'category_id'    => $categoryId,
                'branch_id'      => $branchId,
                'created_by'     => $defaultUser,
                'stock_qty'      => $p['stock_qty'],
                'unit'           => $p['unit'],
                'price'          => $p['price'] ?? 0,
                'min_stock'      => $p['min_stock'],
                'max_stock'      => $p['max_stock'],
                'expiry'         => $p['expiry'],
                'status'         => 'active',
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

            // ✅ Upsert (check by name + branch_id - normalized)
            $existing = $tbl->where('name', $row['name'])
                            ->where('branch_id', $row['branch_id'])
                            ->get()->getRowArray();

            if ($existing) {
                $tbl->where('id', $existing['id'])->update($row);
                echo "🔄 Updated product: {$row['name']} - ₱{$row['price']} (Branch ID: {$branchId})\n";
            } else {
                $tbl->insert($row);
                echo "✅ Inserted product: {$row['name']} - ₱{$row['price']} (Branch ID: {$branchId})\n";
            }
        }
    }
}
