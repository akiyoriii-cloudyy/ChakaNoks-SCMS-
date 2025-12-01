<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduledByToDeliveries extends Migration
{
    public function up()
    {
        // Check if column already exists
        if (!$this->db->fieldExists('scheduled_by', 'deliveries')) {
            // Add scheduled_by column
            $this->forge->addColumn('deliveries', [
                'scheduled_by' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'branch_id'
                ]
            ]);
        }
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('deliveries', 'fk_deliveries_scheduled_by');
        
        // Drop the column
        $this->forge->dropColumn('deliveries', 'scheduled_by');
    }
}

