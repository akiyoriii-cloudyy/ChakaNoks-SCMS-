<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get some branches dynamically (they must exist in your branches table!)
        $central   = $this->db->table('branches')->where('code', 'CENTRAL')->get()->getRow();
        $matina    = $this->db->table('branches')->where('code', 'MATINA')->get()->getRow();
        $toril     = $this->db->table('branches')->where('code', 'TORIL')->get()->getRow();
        $buhangin  = $this->db->table('branches')->where('code', 'BUHANGIN')->get()->getRow();
        $agdao     = $this->db->table('branches')->where('code', 'AGDAO')->get()->getRow();
        $lanang    = $this->db->table('branches')->where('code', 'LANANG')->get()->getRow();

        $data = [
            [
                'branch_id' => $central ? $central->id : null,
                'name'      => 'Chicken Inasal',
                'price'     => 120.00,
                'stock'     => 50,
            ],
            [
                'branch_id' => $matina ? $matina->id : null,
                'name'      => 'Pork BBQ',
                'price'     => 90.00,
                'stock'     => 100,
            ],
            [
                'branch_id' => $toril ? $toril->id : null,
                'name'      => 'Halo-Halo',
                'price'     => 70.00,
                'stock'     => 30,
            ],
            [
                'branch_id' => $buhangin ? $buhangin->id : null,
                'name'      => 'Chicken Adobo',
                'price'     => 150.00,
                'stock'     => 40,
            ],
            [
                'branch_id' => $agdao ? $agdao->id : null,
                'name'      => 'Lechon Kawali',
                'price'     => 180.00,
                'stock'     => 25,
            ],
            [
                'branch_id' => $lanang ? $lanang->id : null,
                'name'      => 'Lechon Baka',
                'price'     => 195.00,
                'stock'     => 35,
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
