<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToUsers extends Migration
{
    public function up()
    {
        // Check if column already exists
        $fields = $this->db->getFieldData('users');
        $columnExists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'deleted_at') {
                $columnExists = true;
                break;
            }
        }
        
        // Only add column if it doesn't exist
        if (!$columnExists) {
            $this->forge->addColumn('users', [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'updated_at',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'deleted_at');
    }
}





