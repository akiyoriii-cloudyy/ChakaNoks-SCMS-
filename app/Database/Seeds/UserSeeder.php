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
                'email'    => 'markypadilla04@gmail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'central_admin',
            ],
            [
                'branch_id' => $matina ? $matina->id : null,
                'email'    => 'abbyyygarciaa@gmail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'branch_manager',
            ],          
            [
                'branch_id' => $buhangin ? $buhangin->id : null,
                'email'    => 'akiyorii03@gmail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'franchise_manager',
            ],
            [
                'branch_id' => $toril ? $toril->id : null,
                'email'    => 'wakwak321@gmail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'inventory_staff',    
            ],
            [
                'branch_id' => $lanang ? $lanang->id : null,
                'email'    => 'Gpalagpalag@gmail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'logistics_coordinator',
            ],                              
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
