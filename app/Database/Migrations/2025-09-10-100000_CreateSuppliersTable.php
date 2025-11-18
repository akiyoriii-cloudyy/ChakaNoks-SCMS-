<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'contact_person'=> ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'address'       => ['type' => 'TEXT', 'null' => true],
            'payment_terms' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'comment' => 'e.g., Net 30, COD, etc.'],
            'status'        => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'suspended'], 'default' => 'active'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('suppliers', false, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Supplier records with contact details and terms',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('suppliers', true);
    }
}

