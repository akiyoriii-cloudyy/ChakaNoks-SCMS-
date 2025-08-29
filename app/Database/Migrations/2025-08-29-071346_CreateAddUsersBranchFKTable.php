<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsersBranchFK extends Migration
{
    public function up()
    {
        // Ensure both tables exist before adding the FK
        $this->db->query("
            ALTER TABLE `users`
            ADD CONSTRAINT `fk_users_branch_id`
            FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE `users`
            DROP FOREIGN KEY `fk_users_branch_id`
        ");
    }
}
