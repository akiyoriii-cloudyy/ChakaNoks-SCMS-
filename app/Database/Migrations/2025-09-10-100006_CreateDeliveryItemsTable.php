<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveryItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'delivery_id'      => ['type' => 'INT', 'unsigned' => true],
            'product_id'        => ['type' => 'INT', 'unsigned' => true],
            'expected_quantity' => ['type' => 'INT', 'unsigned' => true],
            'received_quantity' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'condition_status'  => ['type' => 'ENUM', 'constraint' => ['good', 'damaged', 'expired', 'partial'], 'default' => 'good'],
            'notes'             => ['type' => 'TEXT', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('delivery_id');
        $this->forge->addKey('product_id');
        
        $this->forge->addForeignKey(
            'delivery_id',
            'deliveries',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_delivery_items_delivery'
        );
        
        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_delivery_items_product'
        );

        $this->forge->createTable('delivery_items', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Items in deliveries',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('delivery_items', true);
    }
}

