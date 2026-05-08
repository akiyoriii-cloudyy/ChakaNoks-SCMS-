<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // ✅ Get branch IDs dynamically
        $central   = $this->db->table('branches')->where('code', 'CENTRAL')->get()->getRow();
        $matina    = $this->db->table('branches')->where('code', 'MATINA')->get()->getRow();
        $toril     = $this->db->table('branches')->where('code', 'TORIL')->get()->getRow();
        $buhangin  = $this->db->table('branches')->where('code', 'BUHANGIN')->get()->getRow();
        $agdao     = $this->db->table('branches')->where('code', 'AGDAO')->get()->getRow();
        $lanang    = $this->db->table('branches')->where('code', 'LANANG')->get()->getRow();

        // ✅ Define user seed data
        $users = [
            [
                'branch_id' => null,
                'email'     => 'hunzkie123@gmail.com',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'superadmin',
            ],
            [
                'branch_id' => null,
                'email'     => 'system.admin@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'system_admin',
            ],
            [
                'branch_id' => $central ? $central->id : null,
                'email'     => 'mansuetomarky@gmail.com',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'central_admin',
            ],
            [
                'branch_id' => $matina ? $matina->id : null,
                'email'     => 'rualesabigail09@gmail.com',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
            [
                'branch_id' => $matina ? $matina->id : null,
                'email'     => 'matina.manager@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
            [
                'branch_id' => $toril ? $toril->id : null,
                'email'     => 'toril.manager@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
            [
                'branch_id' => $buhangin ? $buhangin->id : null,
                'email'     => 'buhangin.manager@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
            [
                'branch_id' => $agdao ? $agdao->id : null,
                'email'     => 'agdao.manager@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
            [
                'branch_id' => $lanang ? $lanang->id : null,
                'email'     => 'lanang.manager@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
            [
                'branch_id' => $buhangin ? $buhangin->id : null,
                'email'     => 'imonakoplss@gmail.com',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'franchise_manager',
            ],
            [
                'branch_id' => $lanang ? $lanang->id : null,
                'email'     => 'gpalagpalag@gmail.com',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'logistics_coordinator',
            ],
            // ✅ Inventory Staff for each branch
            [
                'branch_id' => $matina ? $matina->id : null,
                'email'     => 'matina.inventory@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'inventory_staff',
            ],
            [
                'branch_id' => $toril ? $toril->id : null,
                'email'     => 'toril.inventory@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'inventory_staff',
            ],
            [
                'branch_id' => $buhangin ? $buhangin->id : null,
                'email'     => 'buhangin.inventory@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'inventory_staff',
            ],
            [
                'branch_id' => $agdao ? $agdao->id : null,
                'email'     => 'agdao.inventory@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'inventory_staff',
            ],
            [
                'branch_id' => $lanang ? $lanang->id : null,
                'email'     => 'lanang.inventory@chakanoks.test',
                'password'  => password_hash('password123', PASSWORD_DEFAULT),
                'role'      => 'inventory_staff',
            ],
        ];

        $tbl = $this->db->table('users');

        foreach ($users as $u) {
            $existing = $tbl->where('email', $u['email'])->get()->getRowArray();

            if ($existing) {
                // 🔄 Update existing
                $tbl->where('id', $existing['id'])->update($u);
                echo "🔄 Updated user: {$u['email']}\n";
            } else {
                // ✅ Insert new
                $tbl->insert($u);
                echo "✅ Inserted user: {$u['email']}\n";
            }
        }
    }
}
