<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePasswordResetTokensTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'otp' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'otp_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_used' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'used_at' => [
                'type' => 'DATETIME',
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
        // Note: 'token' already has UNIQUE constraint, no need for separate key
        $this->forge->addKey(['user_id', 'is_used', 'reset_expires']);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_password_reset_user');

        $this->forge->createTable('password_reset_tokens', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Password reset tokens (normalized from users table)',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('password_reset_tokens', true);
    }
}

