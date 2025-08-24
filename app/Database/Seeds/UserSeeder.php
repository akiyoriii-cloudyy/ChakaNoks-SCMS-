<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get branch IDs dynamically
        $central = $this->db->table('branches')->where('code', 'CENTRAL')->get()->getRow();
        $matina  = $this->db->table('branches')->where('code', 'MATINA')->get()->getRow();
        $toril = $this->db->table('branches')->where('code', 'TORIL')->get()->getRow();
        $buhangin  = $this->db->table('branches')->where('code', 'BUHANGIN')->get()->getRow();
        $agdao = $this->db->table('branches')->where('code', 'AGDAO')->get()->getRow();
        $lanang  = $this->db->table('branches')->where('code', 'LANANG')->get()->getRow();

        $data = [
            [
                'branch_id' => null, // superadmin not tied to a branch
                'email'    => 'superadmin1@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'superadmin',
            ],
            [
                'branch_id' => $central ? $central->id : null,
                'email'    => 'central.admin@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'central_admin',
            ],
            [
                'branch_id' => $matina ? $matina->id : null,
                'email'    => 'matina.manager@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'branch_manager',
            ],
            [
                'branch_id' => $toril ? $toril->id : null,
                'email'    => 'abbywakwak.staff@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'staff',
            ],
            [
                'branch_id' => $buhangin ? $buhangin->id : null,
                'email'    => 'Franchise.Manager@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'franchise_manager',
            ],
            [
                'branch_id' => $agdao ? $agdao->id : null,
                'email'    => 'Inventory.Staff@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'inventory_staff',
            ],
            [
                'branch_id' => $lanang ? $lanang->id : null,
                'email'    => 'logisticsCoordinator.staff@chakanoks.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'logistics_coordinator',
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
