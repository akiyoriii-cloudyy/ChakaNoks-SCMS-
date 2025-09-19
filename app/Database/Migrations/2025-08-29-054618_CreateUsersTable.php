<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'branch_id'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'       => [
                'type'    => 'ENUM("superadmin","central_admin","branch_manager","staff","franchise_manager","logistics_coordinator","inventory_staff")',
                'default' => 'branch_manager',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        // Add an index for the future FK
        $this->forge->addKey('branch_id');
        $this->forge->createTable('users', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Application users',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
