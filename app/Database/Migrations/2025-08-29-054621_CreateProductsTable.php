<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],

            // Replace branch_id with branch_address
            'branch_address'=> ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            // Linked to users (inventory_staff, etc.)
            'created_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],

            // Product details
            'name'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'category'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'price'         => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'stock_qty'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'unit'          => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'min_stock'     => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'max_stock'     => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'expiry'        => ['type' => 'DATE', 'null' => true],

            // ✅ New field: product status
            'status'        => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'active'],

            // Timestamps
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'created_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_products_created_by'
        );

        $this->forge->createTable('products', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Products managed by inventory staff',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}
