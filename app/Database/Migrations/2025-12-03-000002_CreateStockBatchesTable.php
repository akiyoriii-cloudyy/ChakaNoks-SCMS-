<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockBatchesTable extends Migration
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
            'batch_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'quantity_initial' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'comment' => 'Initial quantity received',
            ],
            'quantity_current' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'comment' => 'Current available quantity',
            ],
            'unit_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'manufacture_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'received_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['available', 'depleted', 'expired', 'damaged', 'recalled', 'quarantine'],
                'default' => 'available',
            ],
            'purchase_order_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'delivery_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        // Note: 'batch_number' already has UNIQUE constraint, no need for separate key
        $this->forge->addKey(['product_id', 'branch_id']);
        $this->forge->addKey(['expiry_date', 'status']);
        $this->forge->addKey('status');

        $this->forge->addForeignKey('product_id', 'product_catalog', 'id', 'CASCADE', 'CASCADE', 'fk_stock_batches_product');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE', 'fk_stock_batches_branch');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'SET NULL', 'CASCADE', 'fk_stock_batches_supplier');
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE', 'fk_stock_batches_po');
        $this->forge->addForeignKey('delivery_id', 'deliveries', 'id', 'SET NULL', 'CASCADE', 'fk_stock_batches_delivery');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_stock_batches_created_by');

        $this->forge->createTable('stock_batches', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Physical inventory batches with FIFO/FEFO tracking',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('stock_batches', true);
    }
}

