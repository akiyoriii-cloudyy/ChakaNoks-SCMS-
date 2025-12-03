<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductCatalogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'standard_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Average cost from suppliers',
            ],
            'selling_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Standard selling price',
            ],
            'min_order_qty' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'reorder_point' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Minimum stock before reorder',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'discontinued', 'seasonal'],
                'default' => 'active',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // Note: 'sku' already has UNIQUE constraint, no need for separate key
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE', 'fk_product_catalog_category');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_product_catalog_created_by');

        $this->forge->createTable('product_catalog', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Master product catalog (normalized from products table)',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('product_catalog', true);
    }
}

