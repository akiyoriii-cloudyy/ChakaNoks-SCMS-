<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthLogsTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'event_type' => [
                'type' => 'ENUM',
                'constraint' => [
                    'login_success',
                    'login_failed',
                    'logout',
                    'otp_sent',
                    'otp_verified',
                    'otp_failed',
                    'account_locked',
                    'account_unlocked',
                ],
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
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'details' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Additional event details',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('event_type');
        $this->forge->addKey('created_at');
        $this->forge->addKey('email');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE', 'fk_auth_logs_user');

        $this->forge->createTable('auth_logs', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Authentication and security event audit log',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('auth_logs', true);
    }
}

