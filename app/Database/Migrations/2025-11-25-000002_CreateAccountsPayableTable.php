<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountsPayableTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'purchase_order_id'     => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'supplier_id'           => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'branch_id'             => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'invoice_number'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'invoice_date'          => ['type' => 'DATE', 'null' => true],
            'due_date'              => ['type' => 'DATE', 'null' => true],
            'amount'                => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'paid_amount'           => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'balance'               => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'payment_status'        => ['type' => 'ENUM', 'constraint' => ['unpaid', 'partial', 'paid', 'overdue'], 'default' => 'unpaid'],
            'payment_date'          => ['type' => 'DATE', 'null' => true],
            'payment_method'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_reference'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'notes'                 => ['type' => 'TEXT', 'null' => true],
            'created_by'            => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('purchase_order_id');
        $this->forge->addKey('supplier_id');
        $this->forge->addKey('branch_id');
        $this->forge->addKey('payment_status');
        $this->forge->addKey('due_date');
        
        $this->forge->addForeignKey(
            'purchase_order_id',
            'purchase_orders',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_accounts_payable_purchase_order'
        );
        
        $this->forge->addForeignKey(
            'supplier_id',
            'suppliers',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_accounts_payable_supplier'
        );
        
        $this->forge->addForeignKey(
            'branch_id',
            'branches',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_accounts_payable_branch'
        );
        
        $this->forge->addForeignKey(
            'created_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_accounts_payable_created_by'
        );

        $this->forge->createTable('accounts_payable', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Accounts payable records for approved purchase orders',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('accounts_payable', true);
    }
}

