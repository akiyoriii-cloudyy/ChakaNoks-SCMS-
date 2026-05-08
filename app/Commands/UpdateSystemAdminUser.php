<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class UpdateSystemAdminUser extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:update-system-admin-user';
    protected $description = 'Update system administrator user role';

    public function run(array $params)
    {
        $db = Database::connect();
        
        try {
            CLI::write('Updating system administrator user...', 'yellow');
            
            $result = $db->table('users')
                ->where('email', 'system.admin@chakanoks.test')
                ->update(['role' => 'system_admin']);
            
            if ($result) {
                CLI::write('✅ Successfully updated system administrator user!', 'green');
                
                $user = $db->table('users')
                    ->where('email', 'system.admin@chakanoks.test')
                    ->get()
                    ->getRowArray();
                
                if ($user) {
                    CLI::write('User details:', 'green');
                    CLI::write('   Email: ' . $user['email'], 'cyan');
                    CLI::write('   Role: ' . $user['role'], 'cyan');
                    CLI::write('   ID: ' . $user['id'], 'cyan');
                }
            } else {
                CLI::error('Failed to update user. User may not exist.');
                return EXIT_ERROR;
            }
            
        } catch (\Exception $e) {
            CLI::error('Error: ' . $e->getMessage());
            return EXIT_ERROR;
        }
        
        return EXIT_SUCCESS;
    }
}

