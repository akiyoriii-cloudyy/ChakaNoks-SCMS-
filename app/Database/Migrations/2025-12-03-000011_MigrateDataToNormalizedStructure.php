<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateDataToNormalizedStructure extends Migration
{
    public function up()
    {
        log_message('info', 'Starting data migration to normalized structure...');

        // STEP 1: Migrate products to product_catalog
        $this->migrateProductsToProductCatalog();

        // STEP 2: Create stock batches from existing products
        $this->createInitialStockBatches();

        // STEP 3: Migrate old stock transactions to new structure
        // (Note: This will be approximate since we don't have batch_id in old data)
        $this->migrateStockTransactions();

        // STEP 4: Migrate password reset data to separate table
        $this->migratePasswordResetData();

        // STEP 5: Link branches to franchise structure
        $this->setupFranchiseStructure();

        log_message('info', 'Data migration to normalized structure completed!');
    }

    private function migrateProductsToProductCatalog()
    {
        log_message('info', 'Migrating products to product_catalog...');

        // Get unique products (by name and category) from products table
        $products = $this->db->query("
            SELECT 
                MIN(id) as id,
                name,
                category_id,
                unit,
                AVG(price) as avg_price,
                MIN(created_by) as created_by,
                MIN(created_at) as created_at,
                MAX(updated_at) as updated_at,
                status
            FROM products
            GROUP BY name, category_id, unit
        ")->getResultArray();

        foreach ($products as $product) {
            // Generate SKU
            $sku = $this->generateSKU($product['name'], $product['category_id']);

            // Insert into product_catalog
            $this->db->table('product_catalog')->insert([
                'sku' => $sku,
                'name' => $product['name'],
                'category_id' => $product['category_id'],
                'unit' => $product['unit'],
                'selling_price' => $product['avg_price'],
                'standard_cost' => $product['avg_price'] * 0.7, // Estimate 70% cost
                'status' => $product['status'] === 'active' ? 'active' : 'discontinued',
                'created_by' => $product['created_by'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ]);
        }

        log_message('info', 'Migrated ' . count($products) . ' products to product_catalog');
    }

    private function createInitialStockBatches()
    {
        log_message('info', 'Creating initial stock batches...');

        // Get all products with stock
        $products = $this->db->query("
            SELECT p.*, pc.id as catalog_id
            FROM products p
            LEFT JOIN product_catalog pc ON (
                p.name = pc.name AND 
                COALESCE(p.category_id, 0) = COALESCE(pc.category_id, 0)
            )
            WHERE p.stock_qty > 0
        ")->getResultArray();

        $batchCount = 0;
        foreach ($products as $product) {
            if (!$product['catalog_id']) {
                continue; // Skip if catalog entry not found
            }

            // Generate batch number
            $batchNumber = 'BATCH-INIT-' . $product['id'] . '-' . date('Ymd');

            // Create stock batch
            $this->db->table('stock_batches')->insert([
                'batch_number' => $batchNumber,
                'product_id' => $product['catalog_id'],
                'branch_id' => $product['branch_id'],
                'supplier_id' => null, // Unknown for existing stock
                'quantity_initial' => $product['stock_qty'],
                'quantity_current' => $product['stock_qty'],
                'unit_cost' => $product['price'] * 0.7, // Estimate
                'expiry_date' => $product['expiry'],
                'received_date' => $product['created_at'],
                'status' => $this->determineBatchStatus($product['expiry']),
                'notes' => 'Migrated from old products table (ID: ' . $product['id'] . ')',
                'created_by' => $product['created_by'],
                'created_at' => $product['created_at'],
                'updated_at' => $product['updated_at'],
            ]);

            $batchCount++;
        }

        log_message('info', 'Created ' . $batchCount . ' initial stock batches');
    }

    private function migrateStockTransactions()
    {
        log_message('info', 'Migrating stock transactions...');

        // This is complex because old transactions don't have batch_id
        // We'll create approximate mappings
        
        $oldTransactions = $this->db->query("
            SELECT st.*, p.name, p.category_id, p.branch_id
            FROM stock_transactions st
            LEFT JOIN products p ON st.product_id = p.id
            ORDER BY st.created_at
        ")->getResultArray();

        log_message('info', 'Found ' . count($oldTransactions) . ' old transactions to migrate');

        // Note: Full migration would be complex
        // For now, just log that this needs manual review
        log_message('warning', 'Stock transaction migration requires manual review and batch assignment');
    }

    private function migratePasswordResetData()
    {
        log_message('info', 'Migrating password reset data...');

        // Get users with active reset tokens
        $users = $this->db->query("
            SELECT id, reset_otp, otp_expires, reset_expires
            FROM users
            WHERE reset_otp IS NOT NULL OR reset_expires IS NOT NULL
        ")->getResultArray();

        foreach ($users as $user) {
            if ($user['reset_otp']) {
                // Create token entry
                $token = bin2hex(random_bytes(32));
                
                $this->db->table('password_reset_tokens')->insert([
                    'user_id' => $user['id'],
                    'token' => $token,
                    'otp' => $user['reset_otp'],
                    'otp_expires' => $user['otp_expires'],
                    'reset_expires' => $user['reset_expires'],
                    'is_used' => false,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        log_message('info', 'Migrated password reset data for ' . count($users) . ' users');
    }

    private function setupFranchiseStructure()
    {
        log_message('info', 'Setting up franchise structure...');

        // Add franchise fields to branches if not exist
        $fields = $this->db->getFieldNames('branches');
        
        if (!in_array('franchise_owner_id', $fields)) {
            // Add columns using raw SQL for better compatibility
            $this->db->query("
                ALTER TABLE branches
                ADD COLUMN franchise_owner_id INT(10) UNSIGNED NULL AFTER address,
                ADD COLUMN franchise_type ENUM('company_owned', 'franchised') DEFAULT 'company_owned' AFTER franchise_owner_id
            ");

            // Add foreign key using raw SQL
            $this->db->query("
                ALTER TABLE branches
                ADD CONSTRAINT fk_branches_franchise_owner 
                FOREIGN KEY (franchise_owner_id) REFERENCES franchise_owners(id) 
                ON DELETE SET NULL ON UPDATE CASCADE
            ");
        }

        // All existing branches are company-owned by default
        $this->db->query("UPDATE branches SET franchise_type = 'company_owned' WHERE franchise_type IS NULL");

        log_message('info', 'Franchise structure setup completed');
    }

    private function generateSKU($name, $categoryId)
    {
        // Simple SKU generation: CATEGORY-HASH
        $categoryCode = $categoryId ? sprintf('C%02d', $categoryId) : 'C00';
        $nameHash = strtoupper(substr(md5($name), 0, 6));
        
        return $categoryCode . '-' . $nameHash;
    }

    private function determineBatchStatus($expiryDate)
    {
        if (!$expiryDate) {
            return 'available';
        }

        $expiry = new \DateTime($expiryDate);
        $now = new \DateTime();

        if ($expiry < $now) {
            return 'expired';
        }

        return 'available';
    }

    public function down()
    {
        log_message('warning', 'Rolling back data migration - this may result in data loss!');

        // Truncate new tables (preserve old data in products table)
        $this->db->table('stock_batches')->truncate();
        $this->db->table('product_catalog')->truncate();
        $this->db->table('password_reset_tokens')->truncate();

        // Remove added columns from branches
        $fields = $this->db->getFieldNames('branches');
        if (in_array('franchise_owner_id', $fields)) {
            // Drop foreign key first
            $this->db->query("
                ALTER TABLE branches
                DROP FOREIGN KEY IF EXISTS fk_branches_franchise_owner
            ");
            
            // Then drop columns
            $this->db->query("
                ALTER TABLE branches
                DROP COLUMN IF EXISTS franchise_owner_id,
                DROP COLUMN IF EXISTS franchise_type
            ");
        }

        log_message('info', 'Data migration rollback completed');
    }
}

