<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'report_number' => ['type' => 'VARCHAR', 'constraint' => 50],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('reports', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Inventory Reports for products',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('reports', true);
    }
}
