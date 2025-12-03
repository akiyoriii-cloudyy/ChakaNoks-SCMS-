<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNormalizedStockTransactionsTable extends Migration
{
    public function up()
    {
        // Drop old stock_transactions table
        $this->forge->dropTable('stock_transactions', true);

        // Create new normalized version
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'transaction_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
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
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['stock_in', 'stock_out', 'transfer', 'adjustment', 'return', 'waste', 'expired'],
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Positive for stock_in, negative for stock_out',
            ],
            'unit_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            // Reference to source document
            'reference_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'delivery, sale, transfer, adjustment, waste, expired',
            ],
            'reference_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            // Stock levels at transaction time (for audit)
            'batch_qty_before' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'batch_qty_after' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'branch_total_before' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'branch_total_after' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            // Transaction metadata
            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'transaction_date' => [
                'type' => 'DATETIME',
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
        // Note: 'transaction_number' already has UNIQUE constraint, no need for separate key
        $this->forge->addKey('batch_id');
        $this->forge->addKey(['product_id', 'branch_id']);
        $this->forge->addKey('transaction_type');
        $this->forge->addKey('transaction_date');
        $this->forge->addKey(['reference_type', 'reference_id']);

        $this->forge->addForeignKey('batch_id', 'stock_batches', 'id', 'CASCADE', 'CASCADE', 'fk_stock_trans_batch');
        $this->forge->addForeignKey('product_id', 'product_catalog', 'id', 'CASCADE', 'CASCADE', 'fk_stock_trans_product');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE', 'fk_stock_trans_branch');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_stock_trans_created_by');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_stock_trans_approved_by');

        $this->forge->createTable('stock_transactions', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Normalized stock transactions with batch-level tracking',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('stock_transactions', true);

        // Recreate old structure if rolling back
        $this->db->query("
            CREATE TABLE `stock_transactions` (
                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `product_id` int(10) UNSIGNED NOT NULL,
                `transaction_type` enum('stock_in','stock_out') NOT NULL,
                `quantity` int(10) UNSIGNED NOT NULL,
                `reference_type` varchar(50) DEFAULT NULL,
                `reference_id` int(10) UNSIGNED DEFAULT NULL,
                `stock_before` int(10) UNSIGNED NOT NULL,
                `stock_after` int(10) UNSIGNED NOT NULL,
                `is_new_stock` tinyint(1) NOT NULL DEFAULT 0,
                `is_expired` tinyint(1) NOT NULL DEFAULT 0,
                `is_old_stock` tinyint(1) NOT NULL DEFAULT 0,
                `expiry_date` date DEFAULT NULL,
                `notes` text DEFAULT NULL,
                `created_by` int(10) UNSIGNED DEFAULT NULL,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `product_id` (`product_id`),
                KEY `transaction_type` (`transaction_type`),
                KEY `reference_type_reference_id` (`reference_type`,`reference_id`),
                KEY `created_by` (`created_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }
}

