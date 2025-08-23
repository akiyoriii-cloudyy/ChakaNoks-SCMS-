<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','unsigned' => true,'auto_increment' => true],
            'branch_id'   => ['type' => 'INT','unsigned' => true],
            'code'        => ['type' => 'VARCHAR','constraint' => 50,'unique' => true],
            'name'        => ['type' => 'VARCHAR','constraint' => 150],
            'price'       => ['type' => 'DECIMAL','constraint' => '10,2'],
            'cost'        => ['type' => 'DECIMAL','constraint' => '10,2','null' => true],
            'created_at'  => ['type' => 'DATETIME','null' => true],
            'updated_at'  => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
