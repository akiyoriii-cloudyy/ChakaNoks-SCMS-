<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Administrator Dashboard — CHAKANOKS SCMS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Professional Dashboard CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-pro.css') ?>">
    
    <style>
        .dashboard-sidebar,
        .sidebar-nav,
        .nav-item {
            visibility: visible !important;
            display: block !important;
        }
        
        .content-section {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d5016;
            margin: 0;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }
        
        .stat-icon.primary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
        .stat-icon.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .stat-icon.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .stat-icon.info { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 8px 0;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-success { background: #10b981; color: white; }
        .badge-warning { background: #f59e0b; color: white; }
        .badge-danger { background: #ef4444; color: white; }
        .badge-info { background: #3b82f6; color: white; }
        
        .table {
            font-size: 0.9rem;
        }
        
        .table th {
            font-weight: 600;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-edit { 
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
            font-weight: 500;
        }
        .btn-edit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }
        .btn-edit:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }
        .btn-delete { background: #ef4444; color: white; }
        .btn-view { background: #10b981; color: white; }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">CHAKANOKS</h1>
                        <p class="logo-subtitle">System Administration</p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <?php 
                $currentUrl = current_url();
                $activeTab = $activeTab ?? 'overview';
                ?>
                <a href="<?= base_url('systemadministrator/dashboard?tab=overview') ?>" class="nav-item <?= $activeTab === 'overview' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Overview</span>
                </a>
                <a href="<?= base_url('systemadministrator/dashboard?tab=users') ?>" class="nav-item <?= $activeTab === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                <a href="<?= base_url('systemadministrator/dashboard?tab=security') ?>" class="nav-item <?= $activeTab === 'security' ? 'active' : '' ?>">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security</span>
                </a>
                <a href="<?= base_url('systemadministrator/dashboard?tab=backups') ?>" class="nav-item <?= $activeTab === 'backups' ? 'active' : '' ?>">
                    <i class="fas fa-database"></i>
                    <span>Backups</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile-card" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.95) 0%, rgba(74, 124, 42, 0.95) 100%); border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255, 255, 255, 0.3);">
                            <i class="fas fa-user-shield" style="font-size: 32px; color: white;"></i>
                        </div>
                        <div class="user-info" style="flex: 1; min-width: 0;">
                            <div class="user-name" style="font-size: 0.9rem; font-weight: 600; color: white; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($me['email'] ?? 'User') ?></div>
                            <div class="user-role" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">System Administrator</div>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('/auth/logout') ?>" class="logout-btn" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.9) 0%, rgba(74, 124, 42, 0.9) 100%); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; color: white; text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <header class="dashboard-header">
                <div class="header-left">
                    <div class="page-title-section">
                        <h2 class="page-title">System Administrator Dashboard</h2>
                        <p class="page-subtitle">Maintain SCMS, manage users, ensure data security, and perform backups</p>
                    </div>
                </div>
                <div class="header-right" style="display: flex; align-items: center; gap: 12px;">
                    <button class="btn btn-secondary" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                    <?= view('components/notifications') ?>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Overview Tab -->
                <div class="tab-content <?= $activeTab === 'overview' ? 'active' : '' ?>" id="overviewSection" style="<?= $activeTab === 'overview' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="stat-card">
                            <div class="stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="stat-value"><?= number_format($data['overview']['total_users'] ?? 0) ?></h3>
                            <p class="stat-label">Total Users</p>
                            <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                                Active (30 days): <?= number_format($data['overview']['active_users'] ?? 0) ?>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon success">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <h3 class="stat-value"><?= number_format($data['overview']['total_branches'] ?? 0) ?></h3>
                            <p class="stat-label">Total Branches</p>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon info">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h3 class="stat-value"><?= number_format($data['overview']['total_products'] ?? 0) ?></h3>
                            <p class="stat-label">Total Products</p>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon warning">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3 class="stat-value"><?= number_format($data['overview']['recent_audit_logs'] ?? 0) ?></h3>
                            <p class="stat-label">Audit Logs (7 days)</p>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon danger">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="stat-value"><?= number_format($data['overview']['security_events'] ?? 0) ?></h3>
                            <p class="stat-label">Security Events (7 days)</p>
                        </div>
                    </div>

                    <!-- Users by Role -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie" style="color: var(--info); margin-right: 8px;"></i>
                                Users by Role
                            </h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <?php foreach ($data['users']['users_by_role'] ?? [] as $role => $count): ?>
                                <div style="padding: 16px; background: #f8f9fa; border-radius: 8px;">
                                    <p style="margin: 0; font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;"><?= esc(ucwords(str_replace('_', ' ', $role))) ?></p>
                                    <h4 style="margin: 8px 0 0 0; font-size: 1.5rem; font-weight: 700; color: #2d5016;"><?= number_format($count) ?></h4>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- User Management Tab -->
                <div class="tab-content <?= $activeTab === 'users' ? 'active' : '' ?>" id="usersSection" style="<?= $activeTab === 'users' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title" style="margin: 0;">
                                <i class="fas fa-users" style="color: var(--info); margin-right: 8px;"></i>
                                User Management
                            </h3>
                            <button class="btn btn-primary" onclick="showCreateUserModal()" style="background: #2d5016; border: none; padding: 10px 20px; border-radius: 6px; color: white; font-weight: 500;">
                                <i class="fas fa-plus"></i> Create User
                            </button>
                        </div>
                        <div id="usersContent" style="padding: 20px 0;">
                            <!-- Active Users Section -->
                            <h4 style="margin-bottom: 16px; color: #1e293b; font-weight: 600;">
                                <i class="fas fa-users" style="color: #10b981; margin-right: 8px;"></i>
                                Active Users (<?= count($data['users']['active_users'] ?? []) ?>)
                            </h4>
                            <table class="table table-hover" style="margin-bottom: 2rem;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Branch</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Users will be loaded via JavaScript pagination -->
                                </tbody>
                            </table>
                            <div id="activeUsersPagination" style="margin-top: 16px;"></div>

                            <!-- Deleted Users Section -->
                            <?php if (!empty($data['users']['deleted_users'])): ?>
                                <h4 style="margin-bottom: 16px; color: #1e293b; font-weight: 600; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e2e8f0;">
                                    <i class="fas fa-trash-restore" style="color: #ef4444; margin-right: 8px;"></i>
                                    Deleted Users (<?= count($data['users']['deleted_users'] ?? []) ?>)
                                </h4>
                                <table class="table table-hover" style="opacity: 0.7;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Branch</th>
                                            <th>Deleted At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="deletedUsersTableBody">
                                        <!-- Deleted users will be loaded via JavaScript pagination -->
                                    </tbody>
                                </table>
                                <div id="deletedUsersPagination" style="margin-top: 16px;"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-content <?= $activeTab === 'security' ? 'active' : '' ?>" id="securitySection" style="<?= $activeTab === 'security' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt" style="color: var(--danger); margin-right: 8px;"></i>
                                Security Events
                            </h3>
                        </div>
                        <div id="securityContent" style="padding: 20px 0;">
                            <h4 style="margin-bottom: 16px; color: #1e293b;">Recent Failed Login Attempts</h4>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Event Type</th>
                                        <th>IP Address</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data['security']['failed_logins'])): ?>
                                        <tr>
                                            <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                                                <i class="fas fa-shield-alt" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No failed login attempts in the last 7 days</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach (array_slice($data['security']['failed_logins'] ?? [], 0, 10) as $event): ?>
                                            <tr>
                                                <td><?= esc($event['email'] ?? $event['user_id'] ?? 'N/A') ?></td>
                                                <td><span class="badge badge-danger"><?= esc($event['event_type'] ?? 'N/A') ?></span></td>
                                                <td><?= esc($event['ip_address'] ?? 'N/A') ?></td>
                                                <td><?= $event['created_at'] ? date('Y-m-d H:i:s', strtotime($event['created_at'])) : 'N/A' ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history" style="color: var(--info); margin-right: 8px;"></i>
                                Recent Audit Trail
                            </h3>
                        </div>
                        <div style="padding: 20px 0;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Table</th>
                                        <th>Record ID</th>
                                        <th>Action</th>
                                        <th>Changed By</th>
                                        <th>Timestamp</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data['security']['recent_audits'])): ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                                                <i class="fas fa-history" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No audit trail entries found</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach (array_slice($data['security']['recent_audits'] ?? [], 0, 20) as $audit): ?>
                                            <tr>
                                                <td><?= esc($audit['table_name'] ?? 'N/A') ?></td>
                                                <td><?= esc($audit['record_id'] ?? 'N/A') ?></td>
                                                <td><span class="badge badge-info"><?= esc($audit['action'] ?? 'N/A') ?></span></td>
                                                <td><?= esc($audit['changed_by_email'] ?? 'System') ?></td>
                                                <td><?= $audit['created_at'] ? date('Y-m-d H:i:s', strtotime($audit['created_at'])) : 'N/A' ?></td>
                                                <td>
                                                    <button class="btn-action btn-view" onclick="viewAuditDetails(<?= $audit['id'] ?>)" style="margin-right: 5px;">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Backups Tab -->
                <div class="tab-content <?= $activeTab === 'backups' ? 'active' : '' ?>" id="backupsSection" style="<?= $activeTab === 'backups' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title" style="margin: 0;">
                                <i class="fas fa-database" style="color: var(--success); margin-right: 8px;"></i>
                                Database Backups
                            </h3>
                            <button class="btn btn-success" onclick="createBackup()" style="background: #10b981; border: none; padding: 10px 20px; border-radius: 6px; color: white; font-weight: 500;">
                                <i class="fas fa-plus"></i> Create Backup
                            </button>
                        </div>
                        <div style="padding: 20px 0;">
                            <?php if (empty($data['backups']['backups'])): ?>
                                <div style="text-align: center; padding: 40px; color: #64748b;">
                                    <i class="fas fa-database" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500;">No backups found</p>
                                    <p style="margin: 8px 0 0 0; font-size: 0.875rem;">Create your first backup to get started</p>
                                </div>
                            <?php else: ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Size</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['backups']['backups'] as $backup): ?>
                                            <tr>
                                                <td><?= esc($backup['filename']) ?></td>
                                                <td><?= $backup['size'] ? number_format($backup['size'] / 1024, 2) . ' KB' : 'N/A' ?></td>
                                                <td><?= esc($backup['created_at']) ?></td>
                                                <td>
                                                    <a href="<?= base_url('systemadministrator/download-backup/' . urlencode($backup['filename'])) ?>" class="btn-action btn-view" style="margin-right: 5px; text-decoration: none;">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                    <button class="btn-action btn-delete" onclick="deleteBackup('<?= esc($backup['filename'], 'js') ?>')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="central_admin">Central Admin</option>
                                <option value="branch_manager">Branch Manager</option>
                                <option value="staff">Staff</option>
                                <option value="franchise_manager">Franchise Manager</option>
                                <option value="logistics_coordinator">Logistics Coordinator</option>
                                <option value="inventory_staff">Inventory Staff</option>
                                <option value="system_admin">System Administrator</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch (Optional)</label>
                            <select class="form-select" name="branch_id">
                                <option value="">None</option>
                                <?php
                                $branchModel = new \App\Models\BranchModel();
                                foreach ($branchModel->findAll() as $branch):
                                ?>
                                    <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitCreateUser()">Create User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: none; border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15); overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 20px 24px; border-bottom: none;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-edit" style="font-size: 1.2rem;"></i>
                        </div>
                        <h5 class="modal-title" style="margin: 0; font-weight: 600; font-size: 1.25rem;">Edit User</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity: 0.8;"></button>
                </div>
                <div class="modal-body" style="padding: 28px 24px; background: #f8fafc;">
                    <form id="editUserForm">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-envelope" style="color: #3b82f6; font-size: 0.9rem;"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control" name="email" id="edit_email" required 
                                   style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 10px 14px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-lock" style="color: #10b981; font-size: 0.9rem;"></i>
                                Password
                            </label>
                            <input type="password" class="form-control" name="password" id="edit_password"
                                   style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 10px 14px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                                   placeholder="Leave blank to keep current password">
                            <small class="text-muted" style="display: block; margin-top: 6px; font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-info-circle" style="margin-right: 4px;"></i>Leave blank to keep current password
                            </small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user-tag" style="color: #8b5cf6; font-size: 0.9rem;"></i>
                                Role
                            </label>
                            <select class="form-select" name="role" id="edit_role" required
                                    style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 10px 14px; transition: all 0.3s ease; background-color: white;"
                                    onfocus="this.style.borderColor='#8b5cf6'; this.style.boxShadow='0 0 0 3px rgba(139, 92, 246, 0.1)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">Select Role</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="central_admin">Central Admin</option>
                                <option value="branch_manager">Branch Manager</option>
                                <option value="staff">Staff</option>
                                <option value="franchise_manager">Franchise Manager</option>
                                <option value="logistics_coordinator">Logistics Coordinator</option>
                                <option value="inventory_staff">Inventory Staff</option>
                                <option value="system_admin">System Administrator</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-building" style="color: #f59e0b; font-size: 0.9rem;"></i>
                                Branch <span style="font-weight: 400; color: #64748b; font-size: 0.9rem;">(Optional)</span>
                            </label>
                            <select class="form-select" name="branch_id" id="edit_branch_id"
                                    style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 10px 14px; transition: all 0.3s ease; background-color: white;"
                                    onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">None</option>
                                <?php
                                $branchModel = new \App\Models\BranchModel();
                                foreach ($branchModel->findAll() as $branch):
                                ?>
                                    <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="padding: 20px 24px; background: white; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                            style="border-radius: 8px; padding: 10px 20px; font-weight: 500; border: 1.5px solid #e2e8f0; background: white; color: #64748b; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1'"
                            onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="fas fa-times" style="margin-right: 6px;"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitEditUser()"
                            style="border-radius: 8px; padding: 10px 24px; font-weight: 600; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(99, 102, 241, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(99, 102, 241, 0.3)'">
                        <i class="fas fa-save" style="margin-right: 6px;"></i>Update User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Details Modal -->
    <div class="modal fade" id="auditDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Audit Trail Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="auditDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status"></div>
                        <p class="mt-2">Loading audit details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // User Management Pagination
        const userPaginationState = {
            active: { data: <?= json_encode($data['users']['active_users'] ?? []) ?>, currentPage: 1 },
            deleted: { data: <?= json_encode($data['users']['deleted_users'] ?? []) ?>, currentPage: 1 }
        };
        
        function paginateUsers(array, page, perPage) {
            const totalItems = array.length;
            const totalPages = Math.ceil(totalItems / perPage);
            const startIndex = (page - 1) * perPage;
            const endIndex = startIndex + perPage;
            return {
                data: array.slice(startIndex, endIndex),
                currentPage: page,
                totalPages: totalPages,
                totalItems: totalItems,
                perPage: perPage
            };
        }
        
        function generateUserPaginationControls(type, currentPage, totalPages, totalItems) {
            if (totalPages <= 1) return '';
            let html = '<div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8fafc; border-radius: 8px;">';
            html += '<div style="color: #64748b; font-size: 0.875rem;">Showing ' + ((currentPage - 1) * 10 + 1) + ' to ' + Math.min(currentPage * 10, totalItems) + ' of ' + totalItems + ' entries</div>';
            html += '<div style="display: flex; gap: 8px;">';
            if (currentPage > 1) {
                html += '<button onclick="changeUserPage(\'' + type + '\', ' + (currentPage - 1) + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer;">Previous</button>';
            }
            for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
                html += '<button onclick="changeUserPage(\'' + type + '\', ' + i + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: ' + (i === currentPage ? '#2d5016' : 'white') + '; color: ' + (i === currentPage ? 'white' : '#1e293b') + '; border-radius: 4px; cursor: pointer; font-weight: ' + (i === currentPage ? '600' : '400') + ';">' + i + '</button>';
            }
            if (currentPage < totalPages) {
                html += '<button onclick="changeUserPage(\'' + type + '\', ' + (currentPage + 1) + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer;">Next</button>';
            }
            html += '</div></div>';
            return html;
        }
        
        function renderUsers(type) {
            const state = userPaginationState[type];
            const paginated = paginateUsers(state.data, state.currentPage, 10);
            const tbodyId = type === 'active' ? 'usersTableBody' : 'deletedUsersTableBody';
            const paginationId = type === 'active' ? 'activeUsersPagination' : 'deletedUsersPagination';
            const tbody = document.getElementById(tbodyId);
            const paginationDiv = document.getElementById(paginationId);
            
            if (!tbody) return;
            
            let html = '';
            if (paginated.data.length === 0) {
                html = '<tr><td colspan="' + (type === 'active' ? '5' : '6') + '" style="text-align: center; padding: 40px; color: #64748b;"><i class="fas fa-users" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i><p style="margin: 0; font-size: 1rem; font-weight: 500;">No ' + type + ' users found</p></td></tr>';
            } else {
                paginated.data.forEach(function(user) {
                    html += '<tr' + (type === 'deleted' ? ' style="background-color: #fef2f2;"' : '') + '>';
                    html += '<td>' + user.id + '</td>';
                    html += '<td>' + (type === 'deleted' ? '<del style="color: #64748b;">' + user.email + '</del>' : user.email) + '</td>';
                    html += '<td><span class="badge badge-info"' + (type === 'deleted' ? ' style="opacity: 0.7;"' : '') + '>' + user.role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) + '</span></td>';
                    html += '<td>' + (user.branch_name || 'N/A') + '</td>';
                    if (type === 'deleted') {
                        html += '<td>' + (user.deleted_at ? new Date(user.deleted_at).toLocaleString() : 'N/A') + '</td>';
                    }
                    html += '<td>';
                    if (type === 'active') {
                        html += '<button class="btn-action btn-edit" onclick="editUser(' + user.id + ')" style="margin-right: 5px;"><i class="fas fa-edit"></i> Edit</button>';
                        <?php if (isset($me['user_id'])): ?>
                        if (user.id != <?= $me['user_id'] ?>) {
                            const isProtected = user.role === 'central_admin' && (user.branch_name || '') === 'Central Office';
                            if (isProtected) {
                                html += '<span style="color: #64748b; font-size: 0.875rem; font-style: italic;">Protected</span>';
                            } else {
                                html += '<button class="btn-action btn-delete" onclick="deleteUser(' + user.id + ')"><i class="fas fa-trash"></i> Delete</button>';
                            }
                        } else {
                            html += '<span style="color: #64748b; font-size: 0.875rem;">Current User</span>';
                        }
                        <?php endif; ?>
                    } else {
                        html += '<button class="btn-action btn-view" onclick="restoreUser(' + user.id + ')" style="background: #10b981; color: white;"><i class="fas fa-undo"></i> Restore</button>';
                    }
                    html += '</td></tr>';
                });
            }
            tbody.innerHTML = html;
            if (paginationDiv) {
                paginationDiv.innerHTML = generateUserPaginationControls(type, paginated.currentPage, paginated.totalPages, paginated.totalItems);
            }
        }
        
        function changeUserPage(type, page) {
            userPaginationState[type].currentPage = page;
            renderUsers(type);
        }
        
        // Initialize pagination on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderUsers('active');
            <?php if (!empty($data['users']['deleted_users'])): ?>
            renderUsers('deleted');
            <?php endif; ?>
        });
        
        function refreshDashboard() {
            location.reload();
        }

        function showCreateUserModal() {
            const modal = new bootstrap.Modal(document.getElementById('createUserModal'));
            modal.show();
        }

        function submitCreateUser() {
            const form = document.getElementById('createUserForm');
            const formData = new FormData(form);
            
            // Show loading state
            const submitBtn = form.querySelector('button[onclick="submitCreateUser()"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Creating...';
            
            fetch('<?= base_url('systemadministrator/create-user') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (data.status === 'success') {
                    alert('User created successfully');
                    bootstrap.Modal.getInstance(document.getElementById('createUserModal')).hide();
                    form.reset();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                alert('Error creating user: ' + error);
            });
        }

        function editUser(userId) {
            // Fetch user data
            fetch('<?= base_url('systemadministrator/get-user/') ?>' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const user = data.user;
                        document.getElementById('edit_user_id').value = user.id;
                        document.getElementById('edit_email').value = user.email || '';
                        document.getElementById('edit_role').value = user.role || '';
                        document.getElementById('edit_branch_id').value = user.branch_id || '';
                        document.getElementById('edit_password').value = '';
                        
                        // Check if user is protected (system_admin or central_admin in Central Office)
                        const roleSelect = document.getElementById('edit_role');
                        const roleContainer = roleSelect.closest('.mb-4');
                        const isProtected = user.role === 'system_admin' || 
                                          (user.role === 'central_admin' && user.branch_name === 'Central Office');
                        
                        if (isProtected) {
                            roleSelect.disabled = true;
                            roleSelect.style.backgroundColor = '#f1f5f9';
                            roleSelect.style.borderColor = '#cbd5e1';
                            roleSelect.style.cursor = 'not-allowed';
                            roleSelect.style.color = '#64748b';
                            roleSelect.style.opacity = '0.8';
                            
                            // Remove existing protection note if any
                            const existingNote = roleContainer.querySelector('.protection-note');
                            if (existingNote) {
                                existingNote.remove();
                            }
                            
                            // Add enhanced protection note
                            const note = document.createElement('div');
                            note.className = 'protection-note';
                            note.style.cssText = 'margin-top: 8px; padding: 10px 12px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 3px solid #f59e0b; border-radius: 6px; display: flex; align-items: center; gap: 8px;';
                            note.innerHTML = '<i class="fas fa-shield-alt" style="color: #f59e0b; font-size: 0.9rem;"></i><span style="color: #92400e; font-size: 0.875rem; font-weight: 500;">Role cannot be changed for protected accounts.</span>';
                            roleContainer.appendChild(note);
                        } else {
                            roleSelect.disabled = false;
                            roleSelect.style.backgroundColor = 'white';
                            roleSelect.style.borderColor = '#e2e8f0';
                            roleSelect.style.cursor = 'pointer';
                            roleSelect.style.color = '#1e293b';
                            roleSelect.style.opacity = '1';
                            
                            // Remove protection note if exists
                            const existingNote = roleContainer.querySelector('.protection-note');
                            if (existingNote) {
                                existingNote.remove();
                            }
                        }
                        
                        // Handle branch protection for protected users
                        const branchSelect = document.getElementById('edit_branch_id');
                        const branchContainer = branchSelect.closest('.mb-4');
                        
                        if (isProtected) {
                            branchSelect.disabled = true;
                            branchSelect.style.backgroundColor = '#f1f5f9';
                            branchSelect.style.borderColor = '#cbd5e1';
                            branchSelect.style.cursor = 'not-allowed';
                            branchSelect.style.color = '#64748b';
                            branchSelect.style.opacity = '0.8';
                            
                            // Remove existing protection note if any
                            const existingBranchNote = branchContainer.querySelector('.protection-note-branch');
                            if (existingBranchNote) {
                                existingBranchNote.remove();
                            }
                            
                            // Add enhanced protection note for branch
                            const branchNote = document.createElement('div');
                            branchNote.className = 'protection-note-branch';
                            branchNote.style.cssText = 'margin-top: 8px; padding: 10px 12px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 3px solid #f59e0b; border-radius: 6px; display: flex; align-items: center; gap: 8px;';
                            branchNote.innerHTML = '<i class="fas fa-shield-alt" style="color: #f59e0b; font-size: 0.9rem;"></i><span style="color: #92400e; font-size: 0.875rem; font-weight: 500;">Branch cannot be changed for protected accounts.</span>';
                            branchContainer.appendChild(branchNote);
                        } else {
                            branchSelect.disabled = false;
                            branchSelect.style.backgroundColor = 'white';
                            branchSelect.style.borderColor = '#e2e8f0';
                            branchSelect.style.cursor = 'pointer';
                            branchSelect.style.color = '#1e293b';
                            branchSelect.style.opacity = '1';
                            
                            // Remove protection note if exists
                            const existingBranchNote = branchContainer.querySelector('.protection-note-branch');
                            if (existingBranchNote) {
                                existingBranchNote.remove();
                            }
                        }
                        
                        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                        modal.show();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error loading user: ' + error);
                });
        }

        function submitEditUser() {
            const form = document.getElementById('editUserForm');
            const formData = new FormData(form);
            const userId = formData.get('user_id');
            
            // Remove password if empty
            if (!formData.get('password')) {
                formData.delete('password');
            }
            
            fetch('<?= base_url('systemadministrator/update-user/') ?>' + userId, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('User updated successfully');
                    bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error updating user: ' + error);
            });
        }

        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? You can restore it later.')) {
                return;
            }
            
            fetch('<?= base_url('systemadministrator/delete-user/') ?>' + userId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('User deleted successfully. You can restore it from the deleted users section.');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error deleting user: ' + error);
            });
        }

        function restoreUser(userId) {
            if (!confirm('Are you sure you want to restore this user?')) {
                return;
            }
            
            fetch('<?= base_url('systemadministrator/restore-user/') ?>' + userId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('User restored successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error restoring user: ' + error);
            });
        }

        function createBackup() {
            if (!confirm('Create a new database backup? This may take a few moments.')) {
                return;
            }
            
            // Show loading indicator
            const backupBtn = event.target;
            const originalText = backupBtn.innerHTML;
            backupBtn.disabled = true;
            backupBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Creating...';
            
            fetch('<?= base_url('systemadministrator/create-backup') ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                backupBtn.disabled = false;
                backupBtn.innerHTML = originalText;
                
                if (data.status === 'success') {
                    alert('Backup created successfully: ' + data.filename + '\nSize: ' + (data.size / 1024).toFixed(2) + ' KB');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                backupBtn.disabled = false;
                backupBtn.innerHTML = originalText;
                alert('Error creating backup: ' + error);
            });
        }

        function deleteBackup(filename) {
            if (!confirm('Are you sure you want to delete this backup?')) {
                return;
            }
            
            fetch('<?= base_url('systemadministrator/delete-backup/') ?>' + encodeURIComponent(filename), {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Backup deleted successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error deleting backup: ' + error);
            });
        }

        function viewAuditDetails(auditId) {
            const modal = new bootstrap.Modal(document.getElementById('auditDetailsModal'));
            const contentDiv = document.getElementById('auditDetailsContent');
            
            contentDiv.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p class="mt-2">Loading audit details...</p></div>';
            modal.show();
            
            fetch('<?= base_url('systemadministrator/get-audit-details/') ?>' + auditId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const audit = data.audit;
                        let html = '<div class="audit-details">';
                        html += '<table class="table table-bordered">';
                        html += '<tr><th style="width: 200px;">Table Name</th><td>' + (audit.table_name || 'N/A') + '</td></tr>';
                        html += '<tr><th>Record ID</th><td>' + (audit.record_id || 'N/A') + '</td></tr>';
                        html += '<tr><th>Action</th><td><span class="badge badge-info">' + (audit.action || 'N/A') + '</span></td></tr>';
                        html += '<tr><th>Changed By</th><td>' + (audit.changed_by_email || 'System') + '</td></tr>';
                        html += '<tr><th>IP Address</th><td>' + (audit.ip_address || 'N/A') + '</td></tr>';
                        html += '<tr><th>User Agent</th><td>' + (audit.user_agent || 'N/A') + '</td></tr>';
                        html += '<tr><th>Timestamp</th><td>' + (audit.created_at || 'N/A') + '</td></tr>';
                        html += '<tr><th>Changed Fields</th><td>' + (audit.changed_fields || 'N/A') + '</td></tr>';
                        
                        if (audit.old_values) {
                            html += '<tr><th>Old Values</th><td><pre style="max-height: 200px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px;">' + JSON.stringify(audit.old_values, null, 2) + '</pre></td></tr>';
                        }
                        if (audit.new_values) {
                            html += '<tr><th>New Values</th><td><pre style="max-height: 200px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px;">' + JSON.stringify(audit.new_values, null, 2) + '</pre></td></tr>';
                        }
                        
                        html += '</table></div>';
                        contentDiv.innerHTML = html;
                    } else {
                        contentDiv.innerHTML = '<div class="alert alert-danger">Error: ' + (data.message || 'Failed to load audit details') + '</div>';
                    }
                })
                .catch(error => {
                    contentDiv.innerHTML = '<div class="alert alert-danger">Error loading audit details: ' + error + '</div>';
                });
        }
    </script>
</body>
</html>

