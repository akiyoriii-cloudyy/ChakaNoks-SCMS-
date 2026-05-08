<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class AddDeletedAtToProducts extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'products:add-deleted-at';
    protected $description = 'Add deleted_at column to products table';

    public function run(array $params)
    {
        $db = Database::connect();
        
        CLI::write("Adding deleted_at column to products table...", 'yellow');
        
        try {
            // Check if column already exists
            $columns = $db->query("SHOW COLUMNS FROM `products` LIKE 'deleted_at'")->getResult();
            
            if (!empty($columns)) {
                CLI::write("  (Column already exists - skipping)", 'yellow');
                return;
            }
            
            // Add the column
            $sql = "ALTER TABLE `products` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`";
            $db->query($sql);
            
            CLI::write("✓ deleted_at column added successfully!", 'green');
            
            // Mark migration as run in migrations table
            $migrationData = [
                'version' => '2025-12-17-000001',
                'class' => 'App\\Database\\Migrations\\AddDeletedAtToProducts',
                'group' => 'default',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 13
            ];
            
            // Get current max batch
            $maxBatch = $db->query("SELECT MAX(batch) as max_batch FROM migrations")->getRow();
            $batch = ($maxBatch && $maxBatch->max_batch) ? (int)$maxBatch->max_batch + 1 : 13;
            $migrationData['batch'] = $batch;
            
            // Check if migration record exists
            $existing = $db->query("SELECT * FROM migrations WHERE version = '2025-12-17-000001'")->getRow();
            if (!$existing) {
                $db->table('migrations')->insert($migrationData);
                CLI::write("✓ Migration record added", 'green');
            }
            
        } catch (\Exception $e) {
            CLI::write("✗ Error: " . $e->getMessage(), 'red');
            return;
        }
        
        CLI::write("Migration completed successfully!", 'green');
    }
}

