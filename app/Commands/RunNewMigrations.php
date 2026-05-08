<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\MigrationRunner;

class RunNewMigrations extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'migrate:new';
    protected $description = 'Run new migrations (notifications and barcode)';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Create notifications table
        CLI::write("Creating notifications table...", 'yellow');
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `notifications` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NULL COMMENT 'NULL for broadcast notifications',
                `role` VARCHAR(50) NULL COMMENT 'Target role for notification',
                `type` ENUM('info', 'success', 'warning', 'danger', 'system') DEFAULT 'info',
                `title` VARCHAR(255) NOT NULL,
                `message` TEXT NOT NULL,
                `link` VARCHAR(500) NULL,
                `related_table` VARCHAR(100) NULL,
                `related_id` INT UNSIGNED NULL,
                `is_read` TINYINT(1) DEFAULT 0,
                `email_sent` TINYINT(1) DEFAULT 0,
                `sms_sent` TINYINT(1) DEFAULT 0,
                `created_at` DATETIME NULL,
                `read_at` DATETIME NULL,
                KEY `user_id` (`user_id`),
                KEY `role` (`role`),
                KEY `is_read` (`is_read`),
                KEY `created_at` (`created_at`),
                KEY `related` (`related_table`, `related_id`)
            ) ENGINE=InnoDB COMMENT='System notifications for users and roles'";
            $db->query($sql);
            CLI::write("✓ Notifications table created", 'green');
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                CLI::write("  (Table already exists - skipping)", 'yellow');
            } else {
                CLI::write("✗ Error: " . $e->getMessage(), 'red');
            }
        }
        
        // Add barcode column to products
        CLI::write("Adding barcode column to products...", 'yellow');
        try {
            // Check if column exists
            $columns = $db->query("SHOW COLUMNS FROM `products` LIKE 'barcode'")->getResult();
            if (empty($columns)) {
                $sql = "ALTER TABLE `products` ADD COLUMN `barcode` VARCHAR(100) NULL UNIQUE AFTER `name`";
                $db->query($sql);
                CLI::write("✓ Barcode column added", 'green');
            } else {
                CLI::write("  (Column already exists)", 'yellow');
            }
            
            // Check if index exists
            $indexes = $db->query("SHOW INDEX FROM `products` WHERE Key_name = 'barcode'")->getResult();
            if (empty($indexes)) {
                $db->query("ALTER TABLE `products` ADD INDEX `barcode` (`barcode`)");
                CLI::write("✓ Barcode index added", 'green');
            } else {
                CLI::write("  (Index already exists)", 'yellow');
            }
        } catch (\Exception $e) {
            CLI::write("✗ Error: " . $e->getMessage(), 'red');
        }
        
        CLI::write("All new migrations completed!", 'green');
    }
}

