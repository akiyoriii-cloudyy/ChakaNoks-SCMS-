<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditTrailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'table_name' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
            ],
            'record_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['INSERT', 'UPDATE', 'DELETE'],
            ],
            'old_values' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'new_values' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'changed_fields' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Comma-separated list of changed fields',
            ],
            'changed_by' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['table_name', 'record_id']);
        $this->forge->addKey('changed_by');
        $this->forge->addKey('created_at');
        $this->forge->addKey('action');

        $this->forge->addForeignKey('changed_by', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_audit_trail_changed_by');

        $this->forge->createTable('audit_trail', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Comprehensive audit trail for all database changes',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('audit_trail', true);
    }
}

