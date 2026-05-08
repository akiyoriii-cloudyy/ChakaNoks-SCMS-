<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class AddSystemAdminRole extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:add-system-admin-role';
    protected $description = 'Add system_admin role to users table ENUM';

    public function run(array $params)
    {
        $db = Database::connect();
        
        try {
            CLI::write('Adding system_admin role to users table...', 'yellow');
            
            $db->query("
                ALTER TABLE `users` 
                MODIFY `role` ENUM(
                    'superadmin',
                    'central_admin',
                    'branch_manager',
                    'staff',
                    'franchise_manager',
                    'logistics_coordinator',
                    'inventory_staff',
                    'system_admin'
                ) NOT NULL DEFAULT 'branch_manager'
            ");
            
            CLI::write('✅ Successfully added system_admin role!', 'green');
            
            // Verify the user exists
            $user = $db->table('users')
                ->where('email', 'system.admin@chakanoks.test')
                ->get()
                ->getRowArray();
            
            if ($user) {
                CLI::write('✅ System administrator user found:', 'green');
                CLI::write('   Email: ' . $user['email'], 'cyan');
                CLI::write('   Role: ' . $user['role'], 'cyan');
            } else {
                CLI::write('⚠️  System administrator user not found. Run the seeder:', 'yellow');
                CLI::write('   php spark db:seed UsersSeeder', 'cyan');
            }
            
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            return EXIT_ERROR;
        }
        
        return EXIT_SUCCESS;
    }
}

