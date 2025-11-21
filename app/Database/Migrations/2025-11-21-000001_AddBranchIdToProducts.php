<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBranchIdToProducts extends Migration
{
    public function up()
    {
        // Add branch_id column if it does not yet exist
        $fields = [
            'branch_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ];

        $this->forge->addColumn('products', $fields);

        // Backfill branch_id using existing branch_address data
        $branchCodes = ['CENTRAL', 'MATINA', 'TORIL', 'BUHANGIN', 'AGDAO', 'LANANG'];
        foreach ($branchCodes as $code) {
            $branch = $this->db->table('branches')
                ->select('id')
                ->where('code', $code)
                ->get()
                ->getRowArray();

            if (!$branch) {
                continue;
            }

            $this->db->table('products')
                ->set('branch_id', $branch['id'])
                ->where('branch_id IS NULL', null, false)
                ->like('branch_address', $code, 'both')
                ->update();
        }

        // Ensure branch managers that created products inherit their branch_id
        $this->db->query("
            UPDATE products p
            INNER JOIN users u ON u.id = p.created_by
            SET p.branch_id = u.branch_id
            WHERE p.branch_id IS NULL AND u.branch_id IS NOT NULL
        ");

        // Add foreign key constraint
        $this->db->query("
            ALTER TABLE `products`
            ADD CONSTRAINT `fk_products_branch_id`
            FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        // Drop FK first
        $this->db->query("
            ALTER TABLE `products`
            DROP FOREIGN KEY `fk_products_branch_id`
        ");

        $this->forge->dropColumn('products', 'branch_id');
    }
}

