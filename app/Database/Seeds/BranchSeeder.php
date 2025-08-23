<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code' => 'CENTRAL',
                'name' => 'Central Office',
                'address' => 'Davao City',
            ],
            [
                'code' => 'MATINA',
                'name' => 'Matina Branch',
                'address' => 'Matina, Davao City',
            ],
        ];

        $this->db->table('branches')->insertBatch($data);
    }
}
