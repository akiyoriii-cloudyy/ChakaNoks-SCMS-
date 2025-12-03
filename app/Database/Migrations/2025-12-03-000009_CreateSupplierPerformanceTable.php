<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierPerformanceTable extends Migration
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
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'period_year' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'period_month' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            // Delivery metrics
            'total_deliveries' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'on_time_deliveries' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'late_deliveries' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'avg_delay_days' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0,
            ],
            // Quality metrics
            'total_items_delivered' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'rejected_items' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'quality_issues_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            // Financial metrics
            'total_purchase_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'avg_order_value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            // Scores (calculated)
            'delivery_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => '0-100 score based on on-time delivery',
            ],
            'quality_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => '0-100 score based on quality',
            ],
            'overall_score' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Weighted average of all scores',
            ],
            'calculated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['supplier_id', 'period_year', 'period_month'], false, true, 'unique_supplier_period');
        $this->forge->addKey(['period_year', 'period_month']);
        $this->forge->addKey('overall_score');

        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE', 'fk_supplier_performance_supplier');

        $this->forge->createTable('supplier_performance_metrics', false, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Supplier performance tracking and scoring',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('supplier_performance_metrics', true);
    }
}

