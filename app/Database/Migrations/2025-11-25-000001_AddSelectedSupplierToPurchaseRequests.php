<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSelectedSupplierToPurchaseRequests extends Migration
{
    public function up()
    {
        $this->forge->addColumn('purchase_requests', [
            'selected_supplier_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'approved_at',
            ],
        ]);
        
        // Add foreign key constraint
        $this->forge->addForeignKey(
            'selected_supplier_id',
            'suppliers',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_purchase_requests_selected_supplier'
        );
    }

    public function down()
    {
        $this->forge->dropForeignKey('purchase_requests', 'fk_purchase_requests_selected_supplier');
        $this->forge->dropColumn('purchase_requests', 'selected_supplier_id');
    }
}

