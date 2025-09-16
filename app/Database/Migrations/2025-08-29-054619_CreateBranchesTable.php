<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'address'    => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('branches', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Company branches',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('branches', true);
    }
}
