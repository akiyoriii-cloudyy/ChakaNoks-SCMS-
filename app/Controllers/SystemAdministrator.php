<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuditTrailModel;
use App\Models\BranchModel;
use Config\Database;
use Exception;

class SystemAdministrator extends BaseController
{
    protected $db;
    protected $userModel;
    protected $auditTrailModel;
    protected $branchModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->userModel = new UserModel();
        $this->auditTrailModel = new AuditTrailModel();
        $this->branchModel = new BranchModel();
    }

    public function dashboard()
    {
        $session = session();
        
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return redirect()->to('/auth/login');
        }

        $activeTab = $this->request->getGet('tab') ?: 'overview';

        // Get dashboard data
        $dashboardData = $this->getDashboardData();

        return view('dashboards/systemadministrator', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'user_id' => $session->get('user_id'),
            ],
            'data' => $dashboardData,
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * Get all dashboard data
     */
    private function getDashboardData(): array
    {
        return [
            'overview' => $this->getSystemOverview(),
            'users' => $this->getUsersData(),
            'security' => $this->getSecurityData(),
            'backups' => $this->getBackupHistory(),
            'audit_trail' => $this->getRecentAuditTrail(),
        ];
    }

    /**
     * System overview statistics
     */
    private function getSystemOverview(): array
    {
        try {
            $totalUsers = $this->db->table('users')->countAllResults(false);
            $activeUsers = $this->db->table('users')
                ->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                ->countAllResults(false);
        } catch (Exception $e) {
            $totalUsers = 0;
            $activeUsers = 0;
        }
        
        try {
            $totalBranches = $this->db->table('branches')->countAllResults(false);
        } catch (Exception $e) {
            $totalBranches = 0;
        }
        
        try {
            $totalProducts = $this->db->table('products')->countAllResults(false);
        } catch (Exception $e) {
            $totalProducts = 0;
        }
        
        try {
            $recentAuditLogs = $this->db->table('audit_trail')
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->countAllResults(false);
        } catch (Exception $e) {
            $recentAuditLogs = 0;
        }
        
        try {
            $securityEvents = $this->db->table('auth_logs')
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->whereIn('event_type', ['login_failed', 'otp_failed', 'account_locked'])
                ->countAllResults(false);
        } catch (Exception $e) {
            $securityEvents = 0;
        }

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'total_branches' => $totalBranches,
            'total_products' => $totalProducts,
            'recent_audit_logs' => $recentAuditLogs,
            'security_events' => $securityEvents,
        ];
    }

    /**
     * Get users data for management (including deleted users)
     */
    private function getUsersData(): array
    {
        try {
            // Get all users including deleted ones
            $users = $this->db->table('users u')
                ->select('u.*, b.name as branch_name, u.deleted_at')
                ->join('branches b', 'b.id = u.branch_id', 'left')
                ->orderBy('u.deleted_at', 'ASC') // Deleted users at the bottom
                ->orderBy('u.created_at', 'DESC')
                ->get()
                ->getResultArray();
        } catch (Exception $e) {
            $users = [];
        }

        // Separate active and deleted users
        $activeUsers = [];
        $deletedUsers = [];
        
        foreach ($users as $user) {
            if (!empty($user['deleted_at'])) {
                $deletedUsers[] = $user;
            } else {
                $activeUsers[] = $user;
            }
        }

        // Count users by role (only active users)
        $usersByRole = [];
        foreach ($activeUsers as $user) {
            $role = $user['role'] ?? 'unknown';
            if (!isset($usersByRole[$role])) {
                $usersByRole[$role] = 0;
            }
            $usersByRole[$role]++;
        }

        return [
            'all_users' => $users,
            'active_users' => $activeUsers,
            'deleted_users' => $deletedUsers,
            'users_by_role' => $usersByRole,
            'total_count' => count($activeUsers),
            'deleted_count' => count($deletedUsers),
        ];
    }

    /**
     * Get security-related data
     */
    private function getSecurityData(): array
    {
        // Recent failed login attempts
        try {
            $failedLogins = $this->db->table('auth_logs al')
                ->select('al.*, u.email')
                ->join('users u', 'u.id = al.user_id', 'left')
                ->where('al.event_type', 'login_failed')
                ->where('al.created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->orderBy('al.created_at', 'DESC')
                ->limit(20)
                ->get()
                ->getResultArray();
        } catch (Exception $e) {
            $failedLogins = [];
        }

        // Recent OTP failures
        try {
            $passwordResets = $this->db->table('auth_logs al')
                ->select('al.*, u.email')
                ->join('users u', 'u.id = al.user_id', 'left')
                ->where('al.event_type', 'otp_failed')
                ->where('al.created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->orderBy('al.created_at', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();
        } catch (Exception $e) {
            $passwordResets = [];
        }

        // Recent audit trail entries with user info
        try {
            $recentAudits = $this->db->table('audit_trail at')
                ->select('at.*, u.email as changed_by_email')
                ->join('users u', 'u.id = at.changed_by', 'left')
                ->orderBy('at.created_at', 'DESC')
                ->limit(50)
                ->get()
                ->getResultArray();
        } catch (Exception $e) {
            $recentAudits = [];
        }

        return [
            'failed_logins' => $failedLogins,
            'password_resets' => $passwordResets,
            'recent_audits' => $recentAudits,
        ];
    }

    /**
     * Get backup history (from backups directory if exists)
     */
    private function getBackupHistory(): array
    {
        $backupDir = WRITEPATH . 'backups/';
        $backups = [];

        if (is_dir($backupDir)) {
            $files = glob($backupDir . '*.sql');
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'created_at' => date('Y-m-d H:i:s', filemtime($file)),
                ];
            }
            // Sort by creation time, newest first
            usort($backups, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return [
            'backups' => $backups,
            'total_count' => count($backups),
        ];
    }

    /**
     * Get recent audit trail entries
     */
    private function getRecentAuditTrail(): array
    {
        return $this->auditTrailModel->getRecentChanges(100);
    }

    // ==================== USER MANAGEMENT ====================

    /**
     * Get users list (API)
     */
    public function getUsers()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $users = $this->getUsersData()['all_users'];
        
        return $this->response->setJSON([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
        ];

        // Validate
        if (empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email, password, and role are required']);
        }

        // Check if email already exists
        if ($this->userModel->getUserByEmail($data['email'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email already exists']);
        }

        try {
            $userId = $this->userModel->createUser($data);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'INSERT',
                null,
                $data,
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User created successfully',
                'user_id' => $userId,
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update user
     */
    public function updateUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        $oldValues = $user;
        $updateData = [];

        // Get user's branch information
        $branchName = null;
        if (!empty($user['branch_id'])) {
            $branch = $this->db->table('branches')->where('id', $user['branch_id'])->get()->getRowArray();
            $branchName = $branch['name'] ?? null;
        }

        // Check if user is protected (system_admin or central_admin in Central Office)
        $isProtected = false;
        if ($user['role'] === 'system_admin') {
            $isProtected = true;
        } elseif ($user['role'] === 'central_admin' && $branchName === 'Central Office') {
            $isProtected = true;
        }

        if ($this->request->getPost('email')) {
            $updateData['email'] = $this->request->getPost('email');
        }
        if ($this->request->getPost('role')) {
            $newRole = $this->request->getPost('role');
            // Prevent role changes for protected users
            if ($isProtected && $newRole !== $user['role']) {
                if ($user['role'] === 'system_admin') {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the role of System Administrator IT users. This account is protected.']);
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the role of Central Admin in Central Office. This account is protected.']);
                }
            }
            $updateData['role'] = $newRole;
        }
        if ($this->request->getPost('branch_id') !== null) {
            $newBranchId = $this->request->getPost('branch_id') ?: null;
            // Prevent branch changes for protected users
            if ($isProtected) {
                // Get current branch ID (handle null case)
                $currentBranchId = $user['branch_id'] ?? null;
                if ($newBranchId != $currentBranchId) {
                    if ($user['role'] === 'system_admin') {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the branch of System Administrator IT users. This account is protected.']);
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the branch of Central Admin in Central Office. This account is protected.']);
                    }
                }
            }
            $updateData['branch_id'] = $newBranchId;
        }
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        if (empty($updateData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No data to update']);
        }

        try {
            // Hash password if provided
            if (isset($updateData['password'])) {
                $updateData['password'] = password_hash($updateData['password'], PASSWORD_DEFAULT);
            }

            $this->userModel->update($userId, $updateData);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'UPDATE',
                $oldValues,
                array_merge($user, $updateData),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User updated successfully',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function deleteUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->userModel->withDeleted()->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Check if already deleted
        if (!empty($user['deleted_at'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User is already deleted']);
        }

        // Prevent deleting yourself
        if ($userId == $session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot delete your own account']);
        }

        // Get user's branch information
        $branchName = null;
        if (!empty($user['branch_id'])) {
            $branch = $this->db->table('branches')->where('id', $user['branch_id'])->get()->getRowArray();
            $branchName = $branch['name'] ?? null;
        }

        // Protect Central Admin in Central Office
        if ($user['role'] === 'central_admin' && $branchName === 'Central Office') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot delete Central Admin in Central Office. This account is protected.']);
        }

        try {
            // Soft delete by setting deleted_at
            $this->userModel->update($userId, ['deleted_at' => date('Y-m-d H:i:s')]);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'SOFT_DELETE',
                $user,
                array_merge($user, ['deleted_at' => date('Y-m-d H:i:s')]),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User deleted successfully. You can restore it later.',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Restore a deleted user
     */
    public function restoreUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->userModel->withDeleted()->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Check if user is actually deleted
        if (empty($user['deleted_at'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User is not deleted']);
        }

        try {
            // Restore user by clearing deleted_at
            $this->userModel->restoreUser($userId);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'RESTORE',
                $user,
                array_merge($user, ['deleted_at' => null]),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User restored successfully',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ==================== BACKUP MANAGEMENT ====================

    /**
     * Create database backup
     */
    public function createBackup()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $backupDir = WRITEPATH . 'backups/';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $backupDir . $filename;

            // Get database config
            $dbConfig = config('Database');
            $defaultGroup = $dbConfig->defaultGroup;
            $dbSettings = $dbConfig->{$defaultGroup};

            // Build mysqldump command
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                escapeshellarg($dbSettings['username']),
                escapeshellarg($dbSettings['password']),
                escapeshellarg($dbSettings['database']),
                escapeshellarg($filepath)
            );

            // Execute backup
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                // Fallback: Use PHP to export
                $this->exportDatabaseToFile($filepath);
            }

            // Log to audit trail
            $this->auditTrailModel->logChange(
                'system',
                0,
                'BACKUP',
                null,
                ['filename' => $filename, 'filepath' => $filepath],
                $session->get('user_id')
            );

            $fileSize = file_exists($filepath) ? filesize($filepath) : 0;

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Backup created successfully',
                'filename' => $filename,
                'size' => $fileSize,
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Export database to SQL file (PHP fallback)
     */
    private function exportDatabaseToFile($filepath)
    {
        $tables = $this->db->listTables();
        $output = "-- Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
        $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $output .= "SET AUTOCOMMIT = 0;\n";
        $output .= "START TRANSACTION;\n";
        $output .= "SET time_zone = \"+00:00\";\n\n";

        foreach ($tables as $table) {
            // Get table structure
            $query = $this->db->query("SHOW CREATE TABLE `{$table}`");
            $row = $query->getRowArray();
            if ($row) {
                $output .= "\n\n-- Table structure for table `{$table}`\n\n";
                $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $output .= $row['Create Table'] . ";\n\n";

                // Get table data
                $query = $this->db->query("SELECT * FROM `{$table}`");
                $rows = $query->getResultArray();

                if (!empty($rows)) {
                    $output .= "-- Dumping data for table `{$table}`\n\n";
                    $columns = array_keys($rows[0]);
                    $columnList = '`' . implode('`, `', $columns) . '`';
                    
                    foreach ($rows as $rowData) {
                        $values = [];
                        foreach ($columns as $col) {
                            $value = $rowData[$col];
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $this->db->escape($value);
                            }
                        }
                        $output .= "INSERT INTO `{$table}` ({$columnList}) VALUES (" . implode(',', $values) . ");\n";
                    }
                    $output .= "\n";
                }
            }
        }

        $output .= "\nCOMMIT;\n";
        file_put_contents($filepath, $output);
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return redirect()->to('/auth/login');
        }

        $filepath = WRITEPATH . 'backups/' . basename($filename);
        
        if (!file_exists($filepath)) {
            return $this->response->setStatusCode(404, 'Backup file not found');
        }

        return $this->response->download($filepath, null);
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $filepath = WRITEPATH . 'backups/' . basename($filename);
        
        if (!file_exists($filepath)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Backup file not found']);
        }

        try {
            unlink($filepath);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'system',
                0,
                'BACKUP_DELETE',
                ['filename' => $filename],
                null,
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Backup deleted successfully',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ==================== SECURITY MONITORING ====================

    /**
     * Get security events (API)
     */
    public function getSecurityEvents()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $events = $this->db->table('auth_logs')
            ->orderBy('created_at', 'DESC')
            ->limit(100)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'events' => $events,
        ]);
    }

    /**
     * Get audit trail (API)
     */
    public function getAuditTrail()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $table = $this->request->getGet('table');
        $recordId = $this->request->getGet('record_id');
        $limit = (int)($this->request->getGet('limit') ?: 100);

        $builder = $this->db->table('audit_trail at')
            ->select('at.*, u.email as changed_by_email')
            ->join('users u', 'u.id = at.changed_by', 'left');

        if ($table) {
            $builder->where('at.table_name', $table);
        }
        if ($recordId) {
            $builder->where('at.record_id', $recordId);
        }

        $trail = $builder->orderBy('at.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'trail' => $trail,
        ]);
    }

    /**
     * Get user by ID (API) - includes deleted users
     */
    public function getUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->db->table('users u')
            ->select('u.*, b.name as branch_name, b.id as branch_id, u.deleted_at')
            ->join('branches b', 'b.id = u.branch_id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRowArray();

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Remove password from response
        unset($user['password']);

        return $this->response->setJSON([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * Get audit details by ID (API)
     */
    public function getAuditDetails($auditId)
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $audit = $this->db->table('audit_trail at')
            ->select('at.*, u.email as changed_by_email')
            ->join('users u', 'u.id = at.changed_by', 'left')
            ->where('at.id', $auditId)
            ->get()
            ->getRowArray();

        if (!$audit) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Audit record not found']);
        }

        // Decode JSON fields
        if ($audit['old_values']) {
            $audit['old_values'] = json_decode($audit['old_values'], true);
        }
        if ($audit['new_values']) {
            $audit['new_values'] = json_decode($audit['new_values'], true);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'audit' => $audit,
        ]);
    }
}

