<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'request_number'=> ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'branch_id'     => ['type' => 'INT', 'unsigned' => true],
            'requested_by'  => ['type' => 'INT', 'unsigned' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected', 'converted_to_po', 'cancelled'], 'default' => 'pending'],
            'priority'      => ['type' => 'ENUM', 'constraint' => ['low', 'normal', 'high', 'urgent'], 'default' => 'normal'],
            'total_amount'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'approved_by'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'approved_at'   => ['type' => 'DATETIME', 'null' => true],
            'rejection_reason' => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        // request_number already has UNIQUE constraint which creates an index
        $this->forge->addKey('branch_id');
        $this->forge->addKey('status');
        
        $this->forge->addForeignKey(
            'branch_id',
            'branches',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_requests_branch'
        );
        
        $this->forge->addForeignKey(
            'requested_by',
            'users',
            'id',
            'CASCADE',
            'CASCADE',
            'fk_purchase_requests_requested_by'
        );
        
        $this->forge->addForeignKey(
            'approved_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_purchase_requests_approved_by'
        );

        $this->forge->createTable('purchase_requests', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Purchase requests from branches',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('purchase_requests', true);
    }
}

