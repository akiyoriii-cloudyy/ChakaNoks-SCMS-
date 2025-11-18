<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_number'          => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'purchase_request_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'supplier_id'           => ['type' => 'INT', 'unsigned' => true],
            'branch_id'             => ['type' => 'INT', 'unsigned' => true],
            'status'                => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'sent_to_supplier', 'in_transit', 'delivered', 'cancelled', 'partial'], 'default' => 'pending'],
            'order_date'            => ['type' => 'DATE'],
            'expected_delivery_date' => ['type' => 'DATE', 'null' => true],
            'actual_delivery_date'  => ['type' => 'DATE', 'null' => true],
            'total_amount'          => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'approved_by'           => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'approved_at'           => ['type' => 'DATETIME', 'null' => true],
            'notes'                 => ['type' => 'TEXT', 'null' => true],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        // order_number already has UNIQUE constraint which creates an index
        $this->forge->addKey('supplier_id');
        $this->forge->addKey('branch_id');
        $this->forge->addKey('status');
        $this->forge->addKey('purchase_request_id');
        
        $this->forge->addForeignKey(
            'purchase_request_id',
            'purchase_requests',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_purchase_orders_request'
        );
        
        $this->forge->addForeignKey(
            'supplier_id',
            'suppliers',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_orders_supplier'
        );
        
        $this->forge->addForeignKey(
            'branch_id',
            'branches',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_orders_branch'
        );
        
        $this->forge->addForeignKey(
            'approved_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_purchase_orders_approved_by'
        );

        $this->forge->createTable('purchase_orders', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Purchase orders sent to suppliers',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_orders', true);
    }
}

