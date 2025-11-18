<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'purchase_order_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id'        => ['type' => 'INT', 'unsigned' => true],
            'quantity'          => ['type' => 'INT', 'unsigned' => true],
            'unit_price'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'subtotal'          => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'received_quantity' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('purchase_order_id');
        $this->forge->addKey('product_id');
        
        $this->forge->addForeignKey(
            'purchase_order_id',
            'purchase_orders',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_order_items_order'
        );
        
        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_order_items_product'
        );

        $this->forge->createTable('purchase_order_items', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Items in purchase orders',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_order_items', false);
    }
}

