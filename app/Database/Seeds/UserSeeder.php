<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'branch_id' => null,
                'email'     => 'superadmin@chakanoks.test',
                'password'  => password_hash('SuperPass123!', PASSWORD_DEFAULT),
                'role'      => 'superadmin',
            ],
            [
                'branch_id' => 1,
                'email'     => 'central@chakanoks.test',
                'password'  => password_hash('Central123!', PASSWORD_DEFAULT),
                'role'      => 'central_admin',
            ],
            [
                'branch_id' => 2,
                'email'     => 'matina.manager@chakanoks.test',
                'password'  => password_hash('Branch123!', PASSWORD_DEFAULT),
                'role'      => 'branch_manager',
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
