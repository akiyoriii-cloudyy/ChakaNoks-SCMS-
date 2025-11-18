<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryIdToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'category_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'created_by'
            ],
        ];

        $this->forge->addColumn('products', $fields);

        // Add foreign key
        $this->forge->addForeignKey(
            'category_id',
            'categories',
            'id',
            'SET NULL',
            'CASCADE',
            'fk_products_category'
        );
    }

    public function down()
    {
        $this->forge->dropForeignKey('products', 'fk_products_category');
        $this->forge->dropColumn('products', 'category_id');
    }
}

