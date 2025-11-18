<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'product_id'    => ['type' => 'INT', 'unsigned' => true],
            'transaction_type' => ['type' => 'ENUM', 'constraint' => ['stock_in', 'stock_out'], 'comment' => 'STOCK-IN or STOCK-OUT'],
            'quantity'      => ['type' => 'INT', 'unsigned' => true],
            'reference_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'comment' => 'e.g., delivery, sale, adjustment'],
            'reference_id'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'stock_before'  => ['type' => 'INT', 'unsigned' => true, 'comment' => 'Stock quantity before transaction'],
            'stock_after'   => ['type' => 'INT', 'unsigned' => true, 'comment' => 'Stock quantity after transaction'],
            'is_new_stock'  => ['type' => 'BOOLEAN', 'default' => false, 'comment' => 'NEW STOCK flag'],
            'is_expired'    => ['type' => 'BOOLEAN', 'default' => false, 'comment' => 'EXPIRE? flag'],
            'is_old_stock'  => ['type' => 'BOOLEAN', 'default' => false, 'comment' => 'OLDS flag'],
            'expiry_date'   => ['type' => 'DATE', 'null' => true],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'created_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true); // Primary key
        $this->forge->addKey('product_id');
        $this->forge->addKey('transaction_type');
        $this->forge->addKey(['reference_type', 'reference_id']); // Composite key
        $this->forge->addKey('created_by');
        
        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_stock_transactions_product'
        );
        
        $this->forge->addForeignKey(
            'created_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_stock_transactions_created_by'
        );

        $this->forge->createTable('stock_transactions', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Stock transactions: STOCK-IN → NEW STOCK, STOCK-OUT → EXPIRE? → OLDS',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('stock_transactions', true);
    }
}

