<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','unsigned' => true,'auto_increment' => true],
            'branch_id'   => ['type' => 'INT','unsigned' => true,'null' => true],
            'email'       => ['type' => 'VARCHAR','constraint' => 100,'unique' => true],
            'password'    => ['type' => 'VARCHAR','constraint' => 255],
            'role'        => ['type' => 'ENUM("superadmin","central_admin","branch_manager")','default' => 'branch_manager'],
            'created_at'  => ['type' => 'DATETIME','null' => true],
            'updated_at'  => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
