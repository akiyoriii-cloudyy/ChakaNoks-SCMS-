<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        // Check if table already exists
        if ($this->db->tableExists('notifications')) {
            return;
        }
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'comment' => 'NULL for broadcast notifications',
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Target role for notification',
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['info', 'success', 'warning', 'danger', 'system'],
                'default' => 'info',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'related_table' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'related_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'is_read' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'email_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'sms_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'read_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('role');
        $this->forge->addKey('is_read');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['related_table', 'related_id']);

        $this->forge->createTable('notifications', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'System notifications for users and roles',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('notifications', true);
    }
}

