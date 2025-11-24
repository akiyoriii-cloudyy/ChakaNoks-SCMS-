<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeProductsTable extends Migration
{
    public function up()
    {
        // Step 1: Ensure category_id is populated from category field before removing it
        // This will map existing category names to category_id
        $this->db->query("
            UPDATE products p
            INNER JOIN categories c ON c.name = p.category
            SET p.category_id = c.id
            WHERE p.category_id IS NULL AND p.category IS NOT NULL
        ");

        // Step 2: Create categories for any products that have category names but no matching category_id
        // First, get unique category names that don't have a category_id
        $categories = $this->db->query("
            SELECT DISTINCT p.category, p.created_by
            FROM products p
            WHERE p.category IS NOT NULL 
            AND p.category_id IS NULL
            AND p.category != ''
        ")->getResultArray();

        foreach ($categories as $cat) {
            // Check if category already exists
            $existing = $this->db->table('categories')
                ->where('name', $cat['category'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                // Create new category
                $this->db->table('categories')->insert([
                    'name' => $cat['category'],
                    'description' => 'Auto-created during normalization',
                    'created_by' => $cat['created_by'],
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $categoryId = $this->db->insertID();

                // Update products with this category
                $this->db->table('products')
                    ->where('category', $cat['category'])
                    ->where('category_id IS NULL', null, false)
                    ->update(['category_id' => $categoryId]);
            } else {
                // Update products with existing category
                $this->db->table('products')
                    ->where('category', $cat['category'])
                    ->where('category_id IS NULL', null, false)
                    ->update(['category_id' => $existing['id']]);
            }
        }

        // Step 3: Drop redundant branch_address column
        // First check if the column exists
        $fields = $this->db->getFieldData('products');
        $hasBranchAddress = false;
        foreach ($fields as $field) {
            if ($field->name === 'branch_address') {
                $hasBranchAddress = true;
                break;
            }
        }

        if ($hasBranchAddress) {
            $this->forge->dropColumn('products', 'branch_address');
        }

        // Step 4: Drop redundant category column
        $hasCategory = false;
        foreach ($fields as $field) {
            if ($field->name === 'category') {
                $hasCategory = true;
                break;
            }
        }

        if ($hasCategory) {
            $this->forge->dropColumn('products', 'category');
        }

        // Step 5: Ensure foreign key for category_id exists
        // Check if foreign key already exists
        $fkExists = false;
        try {
            $result = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'products' 
                AND COLUMN_NAME = 'category_id'
                AND CONSTRAINT_NAME != 'PRIMARY'
            ")->getResultArray();
            
            if (!empty($result)) {
                $fkExists = true;
            }
        } catch (\Exception $e) {
            // If query fails, assume FK doesn't exist
        }

        if (!$fkExists) {
            $this->forge->addForeignKey(
                'category_id',
                'categories',
                'id',
                'SET NULL',
                'CASCADE',
                'fk_products_category'
            );
        }
    }

    public function down()
    {
        // Re-add branch_address column
        $fields = [
            'branch_address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'branch_id',
            ],
        ];
        $this->forge->addColumn('products', $fields);

        // Populate branch_address from branches table
        $this->db->query("
            UPDATE products p
            INNER JOIN branches b ON b.id = p.branch_id
            SET p.branch_address = CONCAT(b.name, ' — ', b.address)
            WHERE p.branch_id IS NOT NULL
        ");

        // Re-add category column
        $fields = [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'category_id',
            ],
        ];
        $this->forge->addColumn('products', $fields);

        // Populate category from categories table
        $this->db->query("
            UPDATE products p
            INNER JOIN categories c ON c.id = p.category_id
            SET p.category = c.name
            WHERE p.category_id IS NOT NULL
        ");
    }
}

