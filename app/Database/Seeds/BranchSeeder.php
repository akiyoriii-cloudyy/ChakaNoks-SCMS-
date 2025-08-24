<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code'    => 'CENTRAL',
                'name'    => 'Central Office',
                'address' => 'Poblacion District, Davao City',
            ],
            [
                'code'    => 'MATINA',
                'name'    => 'Matina Branch',
                'address' => 'Matina, Davao City',
            ],
            [
                'code'    => 'TORIL',
                'name'    => 'Toril Branch',
                'address' => 'Toril, Davao City',
            ],
            [
                'code'    => 'BUHANGIN',
                'name'    => 'Buhangin Branch',
                'address' => 'Buhangin, Davao City',
            ],
            [
                'code'    => 'AGDAO',
                'name'    => 'Agdao Branch',
                'address' => 'Agdao, Davao City',
            ],
            [
                'code'    => 'LANANG',
                'name'    => 'Lanang Branch',
                'address' => 'Lanang, Davao City',
            ],
        ];

        $this->db->table('branches')->insertBatch($data);
    }
}
