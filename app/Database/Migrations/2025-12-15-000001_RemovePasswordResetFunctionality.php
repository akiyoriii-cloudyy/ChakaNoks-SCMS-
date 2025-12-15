<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemovePasswordResetFunctionality extends Migration
{
    public function up()
    {
        // Drop password_reset_tokens table if it exists
        if ($this->db->tableExists('password_reset_tokens')) {
            $this->forge->dropTable('password_reset_tokens', true);
            log_message('info', 'password_reset_tokens table dropped');
        }

        // Remove password reset columns from users table if they exist
        $fields = [
            'reset_otp',
            'otp_expires',
            'reset_expires',
            'reset_token',
            'token_expires',
        ];

        // Check if users table exists and drop columns
        if ($this->db->tableExists('users')) {
            $fieldsToDrop = [];
            foreach ($fields as $field) {
                if ($this->db->fieldExists($field, 'users')) {
                    $fieldsToDrop[] = $field;
                }
            }
            
            if (!empty($fieldsToDrop)) {
                $this->forge->dropColumn('users', $fieldsToDrop);
                log_message('info', 'Password reset columns removed from users table: ' . implode(', ', $fieldsToDrop));
            }
        }

        log_message('info', 'Password reset functionality completely removed from database');
    }

    public function down()
    {
        // Recreate password_reset_tokens table
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
                'null' => true,
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
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('token');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_password_reset_user');
        $this->forge->createTable('password_reset_tokens', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Password reset tokens (normalized from users table)',
        ]);

        // Re-add password reset columns to users table
        $fields = [
            'reset_otp' => [
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
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'token_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        // Re-add password reset columns to users table
        foreach ($fields as $field => $attributes) {
            try {
                $this->forge->addColumn('users', [$field => $attributes]);
            } catch (\Exception $e) {
                // Column may already exist, continue
                log_message('debug', "Column {$field} may already exist in users table: " . $e->getMessage());
            }
        }
    }
}
