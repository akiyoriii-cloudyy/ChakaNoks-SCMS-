<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordResetToUsers extends Migration
{
    public function up()
    {
        // Check if columns already exist
        $fieldsInfo = $this->db->getFieldData('users');
        $existing   = array_map(static function ($f) { return strtolower($f->name); }, $fieldsInfo ?: []);
        $fields   = [];

        if (! in_array('reset_otp', $existing, true)) {
            $fields['reset_otp'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ];
        }

        if (! in_array('reset_expires', $existing, true)) {
            $fields['reset_expires'] = [
                'type' => 'DATETIME',
                'null' => true,
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['reset_otp', 'reset_expires']);
    }
}
