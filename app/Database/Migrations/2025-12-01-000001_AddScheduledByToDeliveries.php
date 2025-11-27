<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduledByToDeliveries extends Migration
{
    public function up()
    {
        // Add scheduled_by column
        $this->forge->addColumn('deliveries', [
            'scheduled_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'branch_id'
            ]
        ]);

        // Add index for scheduled_by
        $this->forge->addKey('scheduled_by');

        // Add foreign key constraint
        $this->forge->addForeignKey(
            'scheduled_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_deliveries_scheduled_by'
        );
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('deliveries', 'fk_deliveries_scheduled_by');
        
        // Drop the column
        $this->forge->dropColumn('deliveries', 'scheduled_by');
    }
}

