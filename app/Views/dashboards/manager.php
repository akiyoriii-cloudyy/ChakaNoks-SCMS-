<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Manager Dashboard — CHAKANOKS SCMS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Professional Dashboard CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-pro.css') ?>">
    <style>
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
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            font-weight: 500;
        }
        
        .btn-edit:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-edit:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
        }
        
        .btn-view { 
            background: #10b981; 
            color: white; 
        }
        
        .btn-view:hover {
            background: #059669;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-info { 
            background: #3b82f6; 
            color: white; 
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            font-weight: 600;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">CHAKANOKS</h1>
                        <p class="logo-subtitle">Supply Chain Management</p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?= base_url('manager/dashboard') ?>" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('staff/dashboard') ?>" class="nav-item">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory Dashboard</span>
                </a>
                <a href="<?= base_url('purchase/request/new') ?>" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                </a>
                <a href="<?= base_url('purchase/request/list') ?>" class="nav-item">
                    <i class="fas fa-list"></i>
                    <span>My Requests</span>
                </a>
                <a href="<?= base_url('manager/deliveries') ?>" class="nav-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                    <?php if (isset($data['deliveries']['pending_deliveries']) && $data['deliveries']['pending_deliveries'] > 0): ?>
                        <span class="badge" style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; margin-left: auto;">
                            <?= $data['deliveries']['pending_deliveries'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="<?= base_url('manager/settings') ?>" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="<?= base_url('manager/dashboard?tab=users') ?>" class="nav-item <?= ($activeTab ?? 'dashboard') === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile-card" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.95) 0%, rgba(74, 124, 42, 0.95) 100%); border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255, 255, 255, 0.3); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-user-circle" style="font-size: 32px; color: white;"></i>
                        </div>
                        <div class="user-info" style="flex: 1; min-width: 0;">
                            <div class="user-name" style="font-size: 0.9rem; font-weight: 600; color: white; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($me['email'] ?? 'User') ?></div>
                            <div class="user-role" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;"><?= esc(ucwords(str_replace('_', ' ', $me['role'] ?? 'User'))) ?></div>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('/auth/logout') ?>" class="logout-btn" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.9) 0%, rgba(74, 124, 42, 0.9) 100%); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; color: white; text-decoration: none; font-weight: 500; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" onmouseover="this.style.background='linear-gradient(135deg, rgba(45, 80, 22, 1) 0%, rgba(74, 124, 42, 1) 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.2)';" onmouseout="this.style.background='linear-gradient(135deg, rgba(45, 80, 22, 0.9) 0%, rgba(74, 124, 42, 0.9) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
                    <i class="fas fa-sign-out-alt" style="font-size: 1rem;"></i>
                    <span style="font-size: 0.9rem;">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <header class="dashboard-header">
                <div class="header-left">
                    <div class="page-title-section">
                        <h2 class="page-title">Branch Manager Dashboard</h2>
                        <p class="page-subtitle">Monitor performance, track inventory, and manage operations</p>
                    </div>
                </div>
                <div class="header-right" style="display: flex; align-items: center; gap: 12px;">
                    <a href="<?= base_url('staff/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-boxes"></i>
                        <span>View Inventory</span>
                    </a>
                    <?= view('components/notifications') ?>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Dashboard Section -->
                <div id="dashboardSection" style="<?= ($activeTab ?? 'dashboard') === 'dashboard' ? 'display: block;' : 'display: none;' ?>">
                <!-- Welcome Banner -->
                <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15); display: inline-flex; align-items: center; gap: 12px; max-width: fit-content;">
                    <div style="width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-check" style="font-size: 18px; color: #28a745;"></i>
                    </div>
                    <div>
                        <div style="color: white; font-size: 1rem; font-weight: 700; line-height: 1.2;">Welcome to Branch Manager</div>
                        <div style="color: rgba(255, 255, 255, 0.95); font-size: 0.875rem; font-weight: 500; line-height: 1.2;">Dashboard</div>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                        <h3 class="stat-value"><?= number_format($data['inventory']['total_items'] ?? 0) ?></h3>
                        <p class="stat-label">Total Items</p>
                        <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                            Stock Value: ₱<?= number_format($data['inventory']['total_value'] ?? 0, 2) ?>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <h3 class="stat-value"><?= number_format($data['inventory']['low_stock_items'] ?? 0) ?></h3>
                        <p class="stat-label">Low Stock Items</p>
                        <div style="margin-top: 8px;">
                            <span class="badge badge-danger">Critical: <?= $data['inventory']['critical_items'] ?? 0 ?></span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-header">
                            <div class="stat-icon danger">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                        <h3 class="stat-value"><?= number_format($data['purchaseRequests']['pending_approvals'] ?? 0) ?></h3>
                        <p class="stat-label">Pending Approvals</p>
                        <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                            Value: ₱<?= number_format($data['purchaseRequests']['total_pending_value'] ?? 0, 2) ?>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-header">
                            <div class="stat-icon info">
                                <i class="fas fa-truck"></i>
                            </div>
                        </div>
                        <h3 class="stat-value"><?= number_format($data['deliveries']['in_transit_deliveries'] ?? 0) ?></h3>
                        <p class="stat-label">In Transit</p>
                        <div style="margin-top: 8px;">
                            <span class="badge badge-warning">Delayed: <?= $data['deliveries']['delayed_deliveries'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>

                <!-- Additional Content Cards -->
                <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- Supplier Reports -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-building" style="color: var(--info); margin-right: 8px;"></i>
                                Supplier Reports
                            </h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                            <div>
                                <p class="stat-label">Active Suppliers</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--info); margin: 0.5rem 0;">
                                    <?= $data['suppliers']['active_suppliers'] ?? 0 ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Total Suppliers</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin: 0.5rem 0;">
                                    <?= $data['suppliers']['total_suppliers'] ?? 0 ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Pending Orders</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--warning); margin: 0.5rem 0;">
                                    <?= $data['suppliers']['pending_orders'] ?? 0 ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">On-Time Rate</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--success); margin: 0.5rem 0;">
                                    <?= number_format($data['suppliers']['on_time_delivery_rate'] ?? 0, 1) ?>%
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Tracking -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-truck" style="color: var(--info); margin-right: 8px;"></i>
                                Delivery Tracking
                            </h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                            <div>
                                <p class="stat-label">Pending</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: #8b5cf6; margin: 0.5rem 0;">
                                    <?= $data['deliveries']['pending_deliveries'] ?? 0 ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Delivered Today</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--success); margin: 0.5rem 0;">
                                    <?= $data['deliveries']['delivered_today'] ?? 0 ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">In Transit</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--info); margin: 0.5rem 0;">
                                    <?= $data['deliveries']['in_transit_deliveries'] ?? 0 ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Delayed</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--danger); margin: 0.5rem 0;">
                                    <?= $data['deliveries']['delayed_deliveries'] ?? 0 ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- User Management Section -->
                <div id="usersSection" style="<?= ($activeTab ?? 'dashboard') === 'users' ? 'display: block;' : 'display: none;' ?>">
                <div class="content-card" style="margin-top: 2rem;">
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
                                    <th>Created</th>
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
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="inventory_staff">Inventory Staff</option>
                                <option value="staff">Staff</option>
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
                <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; padding: 20px 24px; border-bottom: none;">
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
                                Email Address <span style="color: #ef4444;">*</span>
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
                                Role <span style="color: #ef4444;">*</span>
                            </label>
                            <select class="form-select" name="role" id="edit_role" required
                                    style="border-radius: 8px; border: 1.5px solid #e2e8f0; padding: 10px 14px; transition: all 0.3s ease; background-color: white;"
                                    onfocus="this.style.borderColor='#8b5cf6'; this.style.boxShadow='0 0 0 3px rgba(139, 92, 246, 0.1)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">Select Role</option>
                                <option value="inventory_staff">Inventory Staff</option>
                                <option value="staff">Staff</option>
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
                            style="border-radius: 8px; padding: 10px 24px; font-weight: 600; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(23, 162, 184, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(23, 162, 184, 0.3)'">
                        <i class="fas fa-save" style="margin-right: 6px;"></i>Update User
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                html = '<tr><td colspan="' + (type === 'active' ? '5' : '5') + '" style="text-align: center; padding: 40px; color: #64748b;"><i class="fas fa-users" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i><p style="margin: 0; font-size: 1rem; font-weight: 500;">No ' + type + ' users found</p></td></tr>';
            } else {
                paginated.data.forEach(function(user) {
                    html += '<tr' + (type === 'deleted' ? ' style="background-color: #fef2f2;"' : '') + '>';
                    html += '<td>' + user.id + '</td>';
                    html += '<td>' + (type === 'deleted' ? '<del style="color: #64748b;">' + user.email + '</del>' : user.email) + '</td>';
                    html += '<td><span class="badge badge-info"' + (type === 'deleted' ? ' style="opacity: 0.7;"' : '') + '>' + user.role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) + '</span></td>';
                    if (type === 'active') {
                        html += '<td>' + (user.created_at ? new Date(user.created_at).toLocaleString() : 'N/A') + '</td>';
                    } else {
                        html += '<td>' + (user.deleted_at ? new Date(user.deleted_at).toLocaleString() : 'N/A') + '</td>';
                    }
                    html += '<td>';
                    if (type === 'active') {
                        html += '<button class="btn-action btn-edit" onclick="editUser(' + user.id + ')" style="margin-right: 5px;"><i class="fas fa-edit"></i> Edit</button>';
                        <?php if (isset($me['user_id'])): ?>
                        if (user.id != <?= $me['user_id'] ?>) {
                            html += '<button class="btn-action btn-delete" onclick="deleteUser(' + user.id + ')" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;"><i class="fas fa-trash"></i> Delete</button>';
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
        
        function showCreateUserModal() {
            const modal = new bootstrap.Modal(document.getElementById('createUserModal'));
            modal.show();
        }

        function submitCreateUser() {
            const form = document.getElementById('createUserForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            fetch('<?= base_url('manager/create-user') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('User created successfully');
                    bootstrap.Modal.getInstance(document.getElementById('createUserModal')).hide();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error creating user: ' + error);
            });
        }

        function editUser(userId) {
            fetch('<?= base_url('manager/get-user/') ?>' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const user = data.user;
                        document.getElementById('edit_user_id').value = user.id;
                        document.getElementById('edit_email').value = user.email || '';
                        document.getElementById('edit_role').value = user.role || '';
                        document.getElementById('edit_password').value = '';
                        
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
            const data = Object.fromEntries(formData);
            
            // Remove password if empty
            if (!data.password) {
                delete data.password;
            }
            
            fetch('<?= base_url('manager/update-user/') ?>' + userId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
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
            
            fetch('<?= base_url('manager/delete-user/') ?>' + userId, {
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
            
            fetch('<?= base_url('manager/restore-user/') ?>' + userId, {
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
    </script>
</body>
</html>
