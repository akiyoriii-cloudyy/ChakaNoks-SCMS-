<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'branch_id' => 1,
                'code'      => 'P001',
                'name'      => 'Chicken BBQ',
                'price'     => 120.00,
                'cost'      => 80.00,
            ],
            [
                'branch_id' => 2,
                'code'      => 'P002',
                'name'      => 'Pork BBQ',
                'price'     => 100.00,
                'cost'      => 65.00,
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
