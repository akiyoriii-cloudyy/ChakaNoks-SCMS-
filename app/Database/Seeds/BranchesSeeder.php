<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class BranchesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now();

        // ✅ Define branches seed data with real addresses
        $branches = [
            [
                'code'    => 'CENTRAL',
                'name'    => 'Central Office',
                'address' => 'Quimpo Boulevard, Ecoland, Davao City, Philippines',
            ],
            [
                'code'    => 'MATINA',
                'name'    => 'Matina Branch',
                'address' => 'MacArthur Highway, Matina Crossing, Davao City, Philippines',
            ],
            [
                'code'    => 'TORIL',
                'name'    => 'Toril Branch',
                'address' => 'Agton Street, Toril, Davao City, Philippines',
            ],
            [
                'code'    => 'BUHANGIN',
                'name'    => 'Buhangin Branch',
                'address' => 'Buhangin Road, Buhangin Proper, Davao City, Philippines',
            ],
            [
                'code'    => 'AGDAO',
                'name'    => 'Agdao Branch',
                'address' => 'Lapu-Lapu Street, Agdao, Davao City, Philippines',
            ],
            [
                'code'    => 'LANANG',
                'name'    => 'Lanang Branch',
                'address' => 'JP Laurel Avenue, Lanang, Davao City, Philippines',
            ],
        ];

        $tbl = $this->db->table('branches');

        foreach ($branches as $b) {
            $existing = $tbl->where('code', $b['code'])->get()->getRowArray();

            if ($existing) {
                // 🔄 Update existing branch
                $tbl->where('id', $existing['id'])->update([
                    'name'       => $b['name'],
                    'address'    => $b['address'],
                    'updated_at' => $now,
                ]);
                echo "🔄 Updated branch: {$b['code']} - {$b['name']}\n";
            } else {
                // ✅ Insert new branch
                $tbl->insert([
                    'code'       => $b['code'],
                    'name'       => $b['name'],
                    'address'    => $b['address'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                echo "✅ Inserted branch: {$b['code']} - {$b['name']}\n";
            }
        }
    }
}
