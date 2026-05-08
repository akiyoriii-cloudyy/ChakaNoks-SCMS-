<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSystemAdminRole extends Migration
{
    public function up()
    {
        // Add system_admin role to the ENUM
        $this->db->query("
            ALTER TABLE `users` 
            MODIFY `role` ENUM(
                'superadmin',
                'central_admin',
                'branch_manager',
                'staff',
                'franchise_manager',
                'logistics_coordinator',
                'inventory_staff',
                'system_admin'
            ) NOT NULL DEFAULT 'branch_manager'
        ");
    }

    public function down()
    {
        // Remove system_admin role from ENUM
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
}

