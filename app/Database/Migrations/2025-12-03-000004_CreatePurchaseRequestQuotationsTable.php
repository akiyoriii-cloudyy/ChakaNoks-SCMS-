<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseRequestQuotationsTable extends Migration
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
            'purchase_request_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'quoted_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'delivery_lead_time' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Delivery time in days',
            ],
            'payment_terms' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'quoted_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'valid_until' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'selected', 'rejected'],
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'quoted_by' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User who obtained the quote',
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
        $this->forge->addKey(['purchase_request_id', 'supplier_id'], false, true, 'unique_request_supplier');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('purchase_request_id', 'purchase_requests', 'id', 'CASCADE', 'CASCADE', 'fk_quotations_request');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE', 'fk_quotations_supplier');
        $this->forge->addForeignKey('quoted_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_quotations_quoted_by');

        $this->forge->createTable('purchase_request_quotations', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Supplier quotations for purchase requests',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_request_quotations', true);
    }
}

