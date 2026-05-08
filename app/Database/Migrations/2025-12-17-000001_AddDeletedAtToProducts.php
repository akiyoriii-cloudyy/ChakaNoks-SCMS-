<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'updated_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'deleted_at');
    }
}

