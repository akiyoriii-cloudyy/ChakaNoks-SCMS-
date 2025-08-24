<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersAddMoreRoles extends Migration
{
    public function up()
    {
        // Modify the ENUM column for role
        $this->db->query("
            ALTER TABLE `users` 
            MODIFY `role` ENUM(
                'superadmin',
                'central_admin',
                'branch_manager',
                'staff',
                'franchise_manager',
                'logistics_coordinator',
                'inventory_staff'
            ) DEFAULT 'branch_manager'
        ");
    }

    public function down()
    {
        // Rollback to the original ENUM
        $this->db->query("
            ALTER TABLE `users` 
            MODIFY `role` ENUM(
                'superadmin',
                'central_admin',
                'branch_manager'
            ) DEFAULT 'branch_manager'
        ");
    }
}
