<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseRequestItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'purchase_request_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id'        => ['type' => 'INT', 'unsigned' => true],
            'quantity'          => ['type' => 'INT', 'unsigned' => true],
            'unit_price'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'subtotal'          => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'notes'             => ['type' => 'TEXT', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('purchase_request_id');
        $this->forge->addKey('product_id');
        
        $this->forge->addForeignKey(
            'purchase_request_id',
            'purchase_requests',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_request_items_request'
        );
        
        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_request_items_product'
        );

        $this->forge->createTable('purchase_request_items', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Items in purchase requests',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_request_items', true);
    }
}

