<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOtpColumnsToUsers extends Migration
{
    public function up()
    {
        // Add columns if they don't exist
        $fieldsInfo = $this->db->getFieldData('users');
        $existing   = array_map(static function ($f) { return strtolower($f->name); }, $fieldsInfo ?: []);
        $fields   = [];

        if (! in_array('reset_otp', $existing, true)) {
            $fields['reset_otp'] = [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'reset_token',
            ];
        }

        if (! in_array('otp_expires', $existing, true)) {
            $fields['otp_expires'] = [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'reset_otp',
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        $fieldsInfo = $this->db->getFieldData('users');
        $existing   = array_map(static function ($f) { return strtolower($f->name); }, $fieldsInfo ?: []);

        if (in_array('otp_expires', $existing, true)) {
            $this->forge->dropColumn('users', 'otp_expires');
        }

        if (in_array('reset_otp', $existing, true)) {
            $this->forge->dropColumn('users', 'reset_otp');
        }
    }
}


