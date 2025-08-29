<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersAddMoreRoles extends Migration
{
    public function up()
    {
        // ✅ Update ENUM field for role (no recreate table)
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
            ) NOT NULL DEFAULT 'branch_manager'
        ");
    }

    public function down()
    {
        // ✅ Revert to original ENUM (before roles were added)
        $this->db->query("
            ALTER TABLE `users` 
            MODIFY `role` ENUM(
                'superadmin',
                'branch_manager',
                'staff'
            ) NOT NULL DEFAULT 'branch_manager'
        ");
    }
}
