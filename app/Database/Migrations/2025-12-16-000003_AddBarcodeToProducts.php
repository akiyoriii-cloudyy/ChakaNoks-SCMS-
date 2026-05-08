<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBarcodeToProducts extends Migration
{
    public function up()
    {
        // Check if column already exists
        $fields = $this->db->getFieldData('products');
        $columnExists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'barcode') {
                $columnExists = true;
                break;
            }
        }
        
        // Only add column if it doesn't exist
        if (!$columnExists) {
            $this->forge->addColumn('products', [
                'barcode' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'unique' => true,
                    'after' => 'name',
                ],
            ]);
            
            // Add index for faster barcode lookups
            $this->forge->addKey('barcode');
        }
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'barcode');
    }
}

