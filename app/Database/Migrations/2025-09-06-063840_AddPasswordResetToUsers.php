<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordResetToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'reset_otp' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['reset_otp', 'reset_expires']);
    }
}
