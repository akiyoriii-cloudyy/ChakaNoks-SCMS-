<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'created_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('created_by');
        
        $this->forge->addForeignKey(
            'created_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_categories_created_by'
        );

        $this->forge->createTable('categories', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Product categories',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('categories', true);
    }
}

