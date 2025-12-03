<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentTransactionsTable extends Migration
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
            'payment_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'accounts_payable_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'payment_date' => [
                'type' => 'DATE',
            ],
            'payment_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'check', 'bank_transfer', 'credit_card', 'online', 'other'],
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Check number, transaction ID, etc.',
            ],
            'bank_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'check_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'paid_by' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User who made the payment',
            ],
            'recorded_by' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User who recorded the payment',
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
        // Note: 'payment_number' already has UNIQUE constraint, no need for separate key
        $this->forge->addKey('accounts_payable_id');
        $this->forge->addKey('payment_date');

        $this->forge->addForeignKey('accounts_payable_id', 'accounts_payable', 'id', 'CASCADE', 'CASCADE', 'fk_payment_trans_ap');
        $this->forge->addForeignKey('paid_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_payment_trans_paid_by');
        $this->forge->addForeignKey('recorded_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_payment_trans_recorded_by');

        $this->forge->createTable('payment_transactions', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Individual payment transactions for accounts payable',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('payment_transactions', true);
    }
}

