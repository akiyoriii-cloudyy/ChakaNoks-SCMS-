<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeysToFranchiseTables extends Migration
{
    public function up()
    {
        // Only add foreign keys for non-SQLite databases
        if ($this->db->DBDriver !== 'SQLite3') {
            // Check and add foreign key for franchise_applications.reviewed_by
            $result = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'franchise_applications'
                AND COLUMN_NAME = 'reviewed_by'
                AND REFERENCED_TABLE_NAME = 'users'
            ")->getResult();
            
            if (empty($result)) {
                $this->db->query('ALTER TABLE franchise_applications ADD CONSTRAINT fk_franchise_applications_reviewed_by FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
            }
            
            // Check and add foreign key for supply_allocations.branch_id
            $result = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'supply_allocations'
                AND COLUMN_NAME = 'branch_id'
                AND REFERENCED_TABLE_NAME = 'branches'
            ")->getResult();
            
            if (empty($result)) {
                $this->db->query('ALTER TABLE supply_allocations ADD CONSTRAINT fk_supply_allocations_branch FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }
            
            // Check and add foreign key for supply_allocations.product_id
            $result = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'supply_allocations'
                AND COLUMN_NAME = 'product_id'
                AND REFERENCED_TABLE_NAME = 'products'
            ")->getResult();
            
            if (empty($result)) {
                $this->db->query('ALTER TABLE supply_allocations ADD CONSTRAINT fk_supply_allocations_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }
            
            // Check and add foreign key for supply_allocations.created_by
            $result = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'supply_allocations'
                AND COLUMN_NAME = 'created_by'
                AND REFERENCED_TABLE_NAME = 'users'
            ")->getResult();
            
            if (empty($result)) {
                $this->db->query('ALTER TABLE supply_allocations ADD CONSTRAINT fk_supply_allocations_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
            }
            
            // Check and add foreign key for royalty_payments.branch_id
            $result = $this->db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'royalty_payments'
                AND COLUMN_NAME = 'branch_id'
                AND REFERENCED_TABLE_NAME = 'branches'
            ")->getResult();
            
            if (empty($result)) {
                $this->db->query('ALTER TABLE royalty_payments ADD CONSTRAINT fk_royalty_payments_branch FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE');
            }
        }
        
        // Add additional indexes for performance
        // Check and add index for franchise_applications.email
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'franchise_applications'
            AND INDEX_NAME = 'idx_franchise_applications_email'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_franchise_applications_email ON franchise_applications(email)');
        }
        
        // Check and add index for franchise_applications.reviewed_by
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'franchise_applications'
            AND INDEX_NAME = 'idx_franchise_applications_reviewed_by'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_franchise_applications_reviewed_by ON franchise_applications(reviewed_by)');
        }
        
        // Check and add index for supply_allocations.product_id
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'supply_allocations'
            AND INDEX_NAME = 'idx_supply_allocations_product'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_supply_allocations_product ON supply_allocations(product_id)');
        }
        
        // Check and add index for supply_allocations.allocation_date
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'supply_allocations'
            AND INDEX_NAME = 'idx_supply_allocations_date'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_supply_allocations_date ON supply_allocations(allocation_date)');
        }
        
        // Check and add index for supply_allocations.created_by
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'supply_allocations'
            AND INDEX_NAME = 'idx_supply_allocations_created_by'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_supply_allocations_created_by ON supply_allocations(created_by)');
        }
        
        // Check and add index for royalty_payments.due_date
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'royalty_payments'
            AND INDEX_NAME = 'idx_royalty_payments_due_date'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_royalty_payments_due_date ON royalty_payments(due_date)');
        }
        
        // Check and add index for royalty_payments.paid_date
        $result = $this->db->query("
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'royalty_payments'
            AND INDEX_NAME = 'idx_royalty_payments_paid_date'
        ")->getResult();
        
        if (empty($result)) {
            $this->db->query('CREATE INDEX idx_royalty_payments_paid_date ON royalty_payments(paid_date)');
        }
    }

    public function down()
    {
        // Drop foreign keys
        if (!$this->db->DBDriver === 'SQLite3') {
            $this->db->query('ALTER TABLE franchise_applications DROP FOREIGN KEY IF EXISTS fk_franchise_applications_reviewed_by');
            $this->db->query('ALTER TABLE supply_allocations DROP FOREIGN KEY IF EXISTS fk_supply_allocations_branch');
            $this->db->query('ALTER TABLE supply_allocations DROP FOREIGN KEY IF EXISTS fk_supply_allocations_product');
            $this->db->query('ALTER TABLE supply_allocations DROP FOREIGN KEY IF EXISTS fk_supply_allocations_created_by');
            $this->db->query('ALTER TABLE royalty_payments DROP FOREIGN KEY IF EXISTS fk_royalty_payments_branch');
        }
        
        // Drop indexes
        $this->db->query('DROP INDEX IF EXISTS idx_franchise_applications_email ON franchise_applications');
        $this->db->query('DROP INDEX IF EXISTS idx_franchise_applications_reviewed_by ON franchise_applications');
        $this->db->query('DROP INDEX IF EXISTS idx_supply_allocations_product ON supply_allocations');
        $this->db->query('DROP INDEX IF EXISTS idx_supply_allocations_date ON supply_allocations');
        $this->db->query('DROP INDEX IF EXISTS idx_supply_allocations_created_by ON supply_allocations');
        $this->db->query('DROP INDEX IF EXISTS idx_royalty_payments_due_date ON royalty_payments');
        $this->db->query('DROP INDEX IF EXISTS idx_royalty_payments_paid_date ON royalty_payments');
    }
}

