<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFranchiseTables extends Migration
{
    public function up()
    {
        // Franchise Applications Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'applicant_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'proposed_location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'investment_capital' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'business_experience' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'under_review', 'approved', 'rejected', 'on_hold'],
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reviewed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('status');
        $this->forge->createTable('franchise_applications', true);

        // Supply Allocations Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'allocated_qty' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'shipped', 'delivered', 'cancelled'],
                'default' => 'pending',
            ],
            'allocation_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->addKey('branch_id');
        $this->forge->addKey('status');
        $this->forge->createTable('supply_allocations', true);

        // Royalty Payments Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'period_month' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'period_year' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'gross_sales' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'royalty_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 5.00,
            ],
            'royalty_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'marketing_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'total_due' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'amount_paid' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'partial', 'paid', 'overdue'],
                'default' => 'pending',
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'paid_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('branch_id');
        $this->forge->addKey('status');
        $this->forge->addKey(['period_year', 'period_month']);
        $this->forge->createTable('royalty_payments', true);
    }

    public function down()
    {
        $this->forge->dropTable('franchise_applications', true);
        $this->forge->dropTable('supply_allocations', true);
        $this->forge->dropTable('royalty_payments', true);
    }
}

