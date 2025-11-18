<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'delivery_number'       => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'purchase_order_id'     => ['type' => 'INT', 'unsigned' => true],
            'supplier_id'           => ['type' => 'INT', 'unsigned' => true],
            'branch_id'             => ['type' => 'INT', 'unsigned' => true],
            'status'                => ['type' => 'ENUM', 'constraint' => ['scheduled', 'in_transit', 'delivered', 'partial_delivery', 'cancelled', 'delayed'], 'default' => 'scheduled'],
            'scheduled_date'        => ['type' => 'DATE'],
            'actual_delivery_date'  => ['type' => 'DATE', 'null' => true],
            'driver_name'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'vehicle_info'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'received_by'           => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'received_at'           => ['type' => 'DATETIME', 'null' => true],
            'notes'                 => ['type' => 'TEXT', 'null' => true],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        // delivery_number already has UNIQUE constraint which creates an index
        $this->forge->addKey('purchase_order_id');
        $this->forge->addKey('supplier_id');
        $this->forge->addKey('branch_id');
        $this->forge->addKey('status');
        
        $this->forge->addForeignKey(
            'purchase_order_id',
            'purchase_orders',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_deliveries_purchase_order'
        );
        
        $this->forge->addForeignKey(
            'supplier_id',
            'suppliers',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_deliveries_supplier'
        );
        
        $this->forge->addForeignKey(
            'branch_id',
            'branches',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_deliveries_branch'
        );
        
        $this->forge->addForeignKey(
            'received_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_deliveries_received_by'
        );

        $this->forge->createTable('deliveries', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Delivery records and tracking',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('deliveries', true);
    }
}

