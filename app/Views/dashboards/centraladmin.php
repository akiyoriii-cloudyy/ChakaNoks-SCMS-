<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Admin Dashboard — CHAKANOKS SCMS</title>
    
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
    
    <!-- CRITICAL: Force users section visibility immediately -->
    <style>
        /* Ensure navigation is ALWAYS visible */
        .dashboard-sidebar,
        .sidebar-nav,
        .nav-item {
            visibility: visible !important;
            display: block !important;
        }
        
        /* Content sections */
        .content-section {
            animation: fadeIn 0.3s ease-in;
            display: none;
        }
        
        .content-section.active {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Ensure dashboard-content container doesn't hide children */
        .dashboard-content {
            position: relative;
            min-height: 400px;
            overflow: visible !important;
        }
        
        .dashboard-content .content-section {
            position: relative;
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
            min-height: 200px;
            height: auto;
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
        
        .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px 12px;
        }
        
        /* Priority Badge Styles */
        .priority-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .priority-urgent { 
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #fff;
        }
        .priority-high { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #fff;
        }
        .priority-normal { 
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: #fff;
        }
        .priority-low { 
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: #fff;
        }
        
        /* Status Badge Styles */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        display: inline-block;
            text-transform: capitalize;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .status-pending { 
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #000;
        }
        .status-approved { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
        }
        .status-rejected { 
            background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
            color: #fff;
        }
        .status-converted { 
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            color: #fff;
        }
        
        /* Table row hover effect */
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Form select styling for filters */
        .form-select {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        .form-select option {
            background-color: #2d5016;
            color: white;
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
        
        .btn-view { 
            background: #10b981; 
            color: white; 
        }
        
        .btn-view:hover {
            background: #059669;
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
        
        .badge-info {
            background: #3b82f6;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Supplier selection styling */
        .supplier-option {
        transition: all 0.3s ease;
        }
        .supplier-option:hover {
            transform: translateX(5px);
        }
        .supplier-option input[type="radio"]:checked + div {
            font-weight: 600;
        }
        .supplier-list {
            scrollbar-width: thin;
            scrollbar-color: #2d5016 #f8f9fa;
        }
        .supplier-list::-webkit-scrollbar {
            width: 6px;
        }
        .supplier-list::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }
        .supplier-list::-webkit-scrollbar-thumb {
            background: #2d5016;
            border-radius: 10px;
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
                <?php 
                $currentUrl = current_url();
                $activeTab = $activeTab ?? 'dashboard';
                $isDashboard = ($activeTab === 'dashboard');
                ?>
                <a href="<?= base_url('centraladmin/dashboard') ?>" class="nav-item <?= $isDashboard ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('purchase/request/list') ?>" class="nav-item <?= strpos($currentUrl, 'purchase/request') !== false ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                    <?php if (($data['purchaseRequests']['pending_approvals'] ?? 0) > 0): ?>
                        <span class="badge badge-danger" style="margin-left: auto; background: rgba(239, 68, 68, 0.2); color: #ef4444;"><?= $data['purchaseRequests']['pending_approvals'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= base_url('purchase/order/list') ?>" class="nav-item <?= strpos($currentUrl, 'purchase/order') !== false ? 'active' : '' ?>">
                    <i class="fas fa-file-invoice"></i>
                    <span>Purchase Orders</span>
                </a>
                <a href="<?= base_url('delivery/branch/list') ?>" class="nav-item <?= strpos($currentUrl, 'delivery') !== false ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="<?= base_url('supplier/list') ?>" class="nav-item <?= strpos($currentUrl, 'supplier') !== false ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Suppliers</span>
                </a>
                <a href="<?= base_url('accounts-payable/list') ?>" class="nav-item <?= strpos($currentUrl, 'accounts-payable') !== false ? 'active' : '' ?>">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Accounts Payable</span>
                </a>
                <a href="<?= base_url('centraladmin/reports') ?>" class="nav-item <?= strpos($currentUrl, 'centraladmin/reports') !== false ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
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
                        <h2 class="page-title">Central Office Dashboard</h2>
                        <p class="page-subtitle">Real-time monitoring of all branches and operations</p>
    </div>
</div>
                <div class="header-right" style="display: flex; align-items: center; gap: 12px;">
                    <button class="btn btn-secondary" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                    <span class="refresh-indicator" id="lastRefresh" style="font-size: 0.8rem; color: #6b7280;">Last updated: Just now</span>
                    <?= view('components/notifications') ?>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Dashboard Content -->
                <?php 
                $activeTab = $activeTab ?? 'dashboard';
                ?>
                <!-- Dashboard Section - Hidden when users tab is active -->
                <div class="content-section <?= $activeTab === 'dashboard' ? 'active' : '' ?>" id="dashboardSection" style="<?= $activeTab === 'dashboard' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <!-- Welcome Banner -->
                    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15); display: inline-flex; align-items: center; gap: 12px; max-width: fit-content;">
                        <div style="width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-check" style="font-size: 18px; color: #28a745;"></i>
                        </div>
                        <div>
                            <div style="color: white; font-size: 1rem; font-weight: 700; line-height: 1.2;">Welcome to Central Office</div>
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
                                Stock Value: ₱<?= number_format($data['inventory']['total_stock_value'] ?? 0, 2) ?>
            </div>
        </div>

                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                            <h3 class="stat-value"><?= number_format($data['inventory']['low_stock_count'] ?? 0) ?></h3>
                            <p class="stat-label">Low Stock Items</p>
                            <div style="margin-top: 8px;">
                                <span class="badge badge-danger">Critical: <?= $data['inventory']['critical_items_count'] ?? 0 ?></span>
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

                        <!-- Branch Overview -->
                        <div class="content-card" style="grid-column: 1 / -1;">
                            <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                                <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-sitemap" style="font-size: 1.5rem;"></i>
                                    Branch Overview
                                    <span style="margin-left: auto; font-size: 0.875rem; font-weight: 500; opacity: 0.9;">5 Active Branches</span>
                                </h3>
                    </div>
                            <div id="branchOverviewContent" style="padding: 20px;">
                                <?php if (!empty($data['branches'])): ?>
                                    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                                        <?php foreach ($data['branches'] as $index => $branch): ?>
                                            <div class="branch-card" style="background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%); border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); border: 1px solid rgba(45, 80, 22, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(45, 80, 22, 0.15)'; this.style.borderColor='rgba(45, 80, 22, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.08)'; this.style.borderColor='rgba(45, 80, 22, 0.1)';">
                                                <div style="position: absolute; top: 0; right: 0; width: 60px; height: 60px; background: linear-gradient(135deg, rgba(45, 80, 22, 0.1) 0%, rgba(74, 124, 42, 0.1) 100%); border-radius: 0 0 0 50px;"></div>
                                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                                    <div style="width: 48px; height: 48px; border-radius: 10px; background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(45, 80, 22, 0.3);">
                                                        <i class="fas fa-store" style="color: white; font-size: 1.25rem;"></i>
                    </div>
                                                    <div style="flex: 1; min-width: 0;">
                                                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($branch['branch_name']) ?></h4>
                                                        <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b; font-weight: 500;"><?= esc($branch['branch_code']) ?></p>
                            </div>
                        </div>
                                                <?php if (!empty($branch['branch_address'])): ?>
                                                    <p style="margin: 0 0 12px 0; font-size: 0.8rem; color: #64748b; line-height: 1.4;">
                                                        <i class="fas fa-map-marker-alt" style="color: #2d5016; margin-right: 6px;"></i>
                                                        <?= esc($branch['branch_address']) ?>
                                                    </p>
                                                <?php endif; ?>
                                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; padding-top: 12px; border-top: 1px solid rgba(45, 80, 22, 0.1);">
                                                    <div>
                                                        <p style="margin: 0; font-size: 0.7rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Products</p>
                                                        <h3 style="margin: 4px 0 0 0; font-size: 1.25rem; font-weight: 700; color: #2d5016;"><?= number_format($branch['total_products']) ?></h3>
                    </div>
                                                    <div>
                                                        <p style="margin: 0; font-size: 0.7rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Low Stock</p>
                                                        <h3 style="margin: 4px 0 0 0; font-size: 1.25rem; font-weight: 700; color: #f59e0b;"><?= number_format($branch['low_stock'] ?? $branch['low_stock_items'] ?? 0) ?></h3>
                </div>
                                                    <div>
                                                        <p style="margin: 0; font-size: 0.7rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Critical</p>
                                                        <h3 style="margin: 4px 0 0 0; font-size: 1.25rem; font-weight: 700; color: #dc2626;"><?= number_format($branch['critical_alerts'] ?? 0) ?></h3>
            </div>
        </div>
    </div>
                                        <?php endforeach; ?>
                    </div>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                        <i class="fas fa-store" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                        <p style="margin: 0; font-size: 1rem; font-weight: 500;">No branch data available</p>
                </div>
                                <?php endif; ?>
            </div>
        </div>
    </div>

                    <!-- User Management Section -->
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
                            <?php 
                            // Get users data
                            $usersData = $data['users'] ?? null;
                            $activeUsers = $data['users']['active_users'] ?? [];
                            $deletedUsers = $data['users']['deleted_users'] ?? [];
                            ?>
                            <!-- Active Users Section -->
                            <h4 style="margin-bottom: 16px; color: #1e293b; font-weight: 600;">
                                <i class="fas fa-users" style="color: #10b981; margin-right: 8px;"></i>
                                Active Users (<?= count($activeUsers) ?>)
                            </h4>
                            <div style="overflow-x: auto;">
                                <table class="table table-hover" style="margin-bottom: 2rem; min-width: 100%;">
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
                            </div>
                            <div id="activeUsersPagination" style="margin-top: 16px;"></div>

                            <!-- Deleted Users Section -->
                            <?php if (!empty($deletedUsers)): ?>
                                <h4 style="margin-bottom: 16px; color: #1e293b; font-weight: 600; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e2e8f0;">
                                    <i class="fas fa-trash-restore" style="color: #ef4444; margin-right: 8px;"></i>
                                    Deleted Users (<?= count($deletedUsers) ?>)
                                </h4>
                                <div style="overflow-x: auto;">
                                    <table class="table table-hover" style="opacity: 0.7; min-width: 100%;">
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
                                </div>
                                <div id="deletedUsersPagination" style="margin-top: 16px;"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Purchase Requests Tab -->
                <div class="content-section <?= $activeTab === 'purchaseRequests' ? 'active' : '' ?>" id="purchaseRequestsSection" style="<?= $activeTab === 'purchaseRequests' ? 'display: block;' : 'display: none;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Purchase Requests</h3>
                            <div style="display: flex; gap: 10px;">
                                <select id="requestStatusFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd;">
                                    <option value="all">All Requests</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="converted_to_po">Converted to PO</option>
                                </select>
                                <select id="requestPriorityFilter" class="form-select" style="width: auto; min-width: 120px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd;">
                                    <option value="all">All Priorities</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="high">High</option>
                                    <option value="normal">Normal</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                        </div>
                        <div id="pendingRequestsContent" style="padding: 20px; min-height: 200px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading purchase requests...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Orders Tab -->
                <div class="content-section <?= $activeTab === 'purchaseOrders' ? 'active' : '' ?>" id="purchaseOrdersSection" style="<?= $activeTab === 'purchaseOrders' ? 'display: block;' : 'display: none;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Purchase Orders</h3>
                        </div>
                        <div id="purchaseOrdersContent" style="padding: 20px; min-height: 200px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading purchase orders...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deliveries Tab -->
                <div class="content-section <?= $activeTab === 'deliveries' ? 'active' : '' ?>" id="deliveriesSection" style="<?= $activeTab === 'deliveries' ? 'display: block;' : 'display: none;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Deliveries</h3>
                        </div>
                        <div id="deliveriesContent" style="padding: 20px; min-height: 200px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading deliveries...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suppliers Tab -->
                <div class="content-section <?= $activeTab === 'suppliers' ? 'active' : '' ?>" id="suppliersSection" style="<?= $activeTab === 'suppliers' ? 'display: block;' : 'display: none;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Suppliers</h3>
                        </div>
                        <div id="suppliersContent" style="padding: 20px; min-height: 200px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading suppliers...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accounts Payable Tab -->
                <div class="content-section <?= $activeTab === 'accountsPayable' ? 'active' : '' ?>" id="accountsPayableSection" style="<?= $activeTab === 'accountsPayable' ? 'display: block;' : 'display: none;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Accounts Payable</h3>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                                <select id="apStatusFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd;">
                                    <option value="all">All Status</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="partial">Partial</option>
                                    <option value="paid">Paid</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                                <select id="apInvoiceFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd;">
                                    <option value="all">All Invoices</option>
                                    <option value="with_invoice">With Invoice #</option>
                                    <option value="without_invoice">Without Invoice #</option>
                                </select>
                                <div style="display: flex; gap: 5px; align-items: center; padding: 0 5px;">
                                    <label style="font-size: 0.9rem; color: #2d5016; font-weight: 600; white-space: nowrap;">Month:</label>
                                    <input type="month" id="receiptMonthFilter" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.875rem; width: 140px;">
                                </div>
                                <button class="btn btn-sm btn-info" onclick="backfillAccountsPayable()" style="background: #17a2b8; border: none; padding: 8px 16px; border-radius: 6px; color: white;">
                                    <i class="fas fa-sync"></i> Backfill
                                </button>
                            </div>
                        </div>
                        <div id="accountsPayableContent" style="padding: 20px; min-height: 200px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading accounts payable...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Tab -->
                <div class="content-section <?= $activeTab === 'reports' ? 'active' : '' ?>" id="reportsSection" style="<?= $activeTab === 'reports' ? 'display: block;' : 'display: none;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white; padding: 20px 24px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 class="card-title" style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 12px;">
                                        <i class="fas fa-chart-line" style="font-size: 1.75rem;"></i>
                                        Comprehensive Monthly Reports
                                    </h3>
                                    <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 0.95rem;">General reports from all departments</p>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="month" id="reportsMonthFilter" class="form-control" 
                                           style="width: 200px; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.1); color: white;"
                                           value="<?= date('Y-m') ?>">
                                    <button class="btn btn-light" onclick="loadReports()" style="padding: 8px 20px; border-radius: 6px; font-weight: 500;">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                    <button class="btn btn-light" onclick="printAllReports()" style="padding: 8px 20px; border-radius: 6px; font-weight: 500;">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="reportsContent" style="padding: 20px; min-height: 200px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading reports...</p>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        </main>
    </div>

    <!-- Scripts -->
    <!-- Load jQuery FIRST before Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
        function refreshDashboard() {
            location.reload();
        }

        // Load data when page loads based on active tab
        function loadTabData() {
            const activeTab = '<?= $activeTab ?? 'dashboard' ?>';
            
            if (activeTab === 'purchaseRequests') {
                if (typeof loadPendingRequests === 'function') {
                    loadPendingRequests();
                }
            } else if (activeTab === 'purchaseOrders') {
                if (typeof loadPurchaseOrders === 'function') {
                    loadPurchaseOrders();
                }
            } else if (activeTab === 'deliveries') {
                if (typeof loadDeliveries === 'function') {
                    loadDeliveries();
                }
            } else if (activeTab === 'suppliers') {
                if (typeof loadSuppliers === 'function') {
                    loadSuppliers();
                }
            } else if (activeTab === 'accountsPayable') {
                if (typeof loadAccountsPayable === 'function') {
                    loadAccountsPayable();
                }
            } else if (activeTab === 'reports') {
                if (typeof loadReports === 'function') {
                    loadReports();
                }
            }
        }

        function loadPendingRequests() {
            console.log('loadPendingRequests called');
            const statusFilter = $('#requestStatusFilter').val() || 'all';
            const priorityFilter = $('#requestPriorityFilter').val() || 'all';
            
            const contentDiv = $('#pendingRequestsContent');
            if (!contentDiv.length) {
                console.error('pendingRequestsContent div not found!');
        return;
    }

            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading purchase requests...</p></div>');
            
            let url = '<?= base_url('purchase/request/api/list') ?>';
            const params = [];
            if (statusFilter !== 'all') params.push('status=' + encodeURIComponent(statusFilter));
            if (priorityFilter !== 'all') params.push('priority=' + encodeURIComponent(priorityFilter));
            if (params.length > 0) url += '?' + params.join('&');
            
            console.log('Loading from URL:', url);
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Purchase requests response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response status:', response.status);
                    console.log('Response requests:', response.requests);
                    console.log('Response requests type:', typeof response.requests);
                    console.log('Response requests length:', response.requests ? response.requests.length : 'N/A');
                    
                    if (response && response.status === 'success' && Array.isArray(response.requests)) {
                        let html = '<div class="table-responsive"><table class="table" style="margin-bottom: 0;"><thead><tr style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;"><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Request #</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Branch</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Priority</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Total Amount</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Date</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Actions</th></tr></thead><tbody>';
                        
                        if (response.requests.length === 0) {
                            html += '<tr><td colspan="7" style="text-align: center; padding: 60px 20px; color: #999;"><i class="fas fa-inbox fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i><h5 style="margin-top: 20px; color: #666;">No purchase requests found</h5><p style="color: #999;">No requests match the selected filters</p></td></tr>';
        } else {
            response.requests.forEach(function(req) {
                                const priority = (req.priority || 'normal').toLowerCase();
                                const status = (req.status || 'pending').toLowerCase();
                                
                                // Priority badge classes matching purchase_requests_list.php
                                let priorityClass = 'priority-normal';
                                if (priority === 'urgent') priorityClass = 'priority-urgent';
                                else if (priority === 'high') priorityClass = 'priority-high';
                                else if (priority === 'low') priorityClass = 'priority-low';
                                
                                // Status badge classes
                                let statusClass = 'status-pending';
                                if (status === 'approved') statusClass = 'status-approved';
                                else if (status === 'rejected') statusClass = 'status-rejected';
                                else if (status === 'converted_to_po') statusClass = 'status-converted';
                                
                                const requestNumber = req.request_number || ('PR-' + String(req.id).padStart(5, '0'));
                                const branchName = req.branch ? req.branch.name : 'N/A';
                                const totalAmount = parseFloat(req.total_amount || 0);
                                const createdDate = req.created_at ? new Date(req.created_at) : new Date();
                                
                                html += '<tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;">';
                                html += '<td style="padding: 15px; vertical-align: middle;"><strong style="color: #2d5016;">' + requestNumber + '</strong></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + branchName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="priority-badge ' + priorityClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: uppercase; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + priority.toUpperCase() + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + statusClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: capitalize; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + status.replace('_', ' ') + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333; font-weight: 600;">₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + createdDate.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><div style="display: flex; gap: 5px;">';
                                html += '<button class="btn btn-sm btn-info viewRequestBtn" data-id="' + req.id + '" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-eye"></i> View</button>';
                                if (status === 'pending') {
                                    html += '<button class="btn btn-sm btn-success approveRequestBtn" data-id="' + req.id + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-check"></i> Approve</button>';
                                    html += '<button class="btn btn-sm btn-danger rejectRequestBtn" data-id="' + req.id + '" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-times"></i> Reject</button>';
                                }
                                html += '</div></td>';
                                html += '</tr>';
                            });
                        }
                        
                        html += '</tbody></table></div>';
                        
                        const contentDiv = $('#pendingRequestsContent');
                        if (contentDiv.length) {
                            contentDiv.html(html);
                            console.log('Purchase requests table rendered successfully');
                            
                            // Attach event handlers
                            attachRequestHandlers();
                        } else {
                            console.error('pendingRequestsContent div not found after data load!');
                        }
                    } else {
                        console.warn('Response status is not success or requests array missing:', response);
                        const contentDiv = $('#pendingRequestsContent');
                        if (contentDiv.length) {
                            contentDiv.html('<div class="alert alert-warning" style="padding: 20px; border-radius: 8px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">No purchase requests found. Response: ' + JSON.stringify(response) + '</div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading requests:', error);
                    console.error('XHR Status:', xhr.status);
                    console.error('XHR Response:', xhr.responseText);
                    console.error('XHR ReadyState:', xhr.readyState);
                    const contentDiv = $('#pendingRequestsContent');
                    if (contentDiv.length) {
                        let errorMsg = 'Error loading purchase requests: ' + error + ' (Status: ' + xhr.status + ')';
                        if (xhr.responseText) {
                            try {
                                const errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse.message) {
                                    errorMsg += '<br>Message: ' + errorResponse.message;
                                }
                            } catch(e) {
                                errorMsg += '<br>Response: ' + xhr.responseText.substring(0, 200);
                            }
                        }
                        contentDiv.html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">' + errorMsg + '<br><small>Please check browser console (F12) for more details.</small></div>');
                    } else {
                        console.error('Cannot display error - pendingRequestsContent div not found!');
                        alert('Error: Cannot find content area. Please refresh the page.');
                    }
        }
    });
}

        function attachRequestHandlers() {
            // View request handler
            $('.viewRequestBtn').off('click').on('click', function() {
                const requestId = $(this).data('id');
                viewRequestDetails(requestId);
            });
            
            // Approve request handler
            $('.approveRequestBtn').off('click').on('click', function() {
                const requestId = $(this).data('id');
                if (confirm('Are you sure you want to approve this purchase request?')) {
                    approveRequest(requestId);
                }
            });
            
            // Accept supplier handler (from modal)
            $(document).on('click', '.acceptSupplierBtn', function() {
                const requestId = $(this).data('id');
                const selectedSupplier = $('input[name="supplier_' + requestId + '"]:checked').val();
                
                if (!selectedSupplier) {
                    alert('Please select a supplier before accepting.');
        return;
    }

                acceptSupplier(requestId, selectedSupplier);
            });
            
            // Reject request handler
            $('.rejectRequestBtn').off('click').on('click', function() {
                const requestId = $(this).data('id');
                const reason = prompt('Please provide a reason for rejection:');
                if (reason !== null && reason.trim() !== '') {
                    rejectRequest(requestId, reason);
                }
            });
        }
        
        function viewRequestDetails(requestId) {
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.request) {
            const req = response.request;
                        let html = '<div style="padding: 20px;">';
                        html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Purchase Request Details</h5>';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        html += '<div><strong>Request Number:</strong><br>' + (req.request_number || 'N/A') + '</div>';
                        html += '<div><strong>Branch:</strong><br>' + (req.branch ? req.branch.name : 'N/A') + '</div>';
                        html += '<div><strong>Status:</strong><br><span class="status-badge status-' + req.status + '">' + req.status.replace('_', ' ') + '</span></div>';
                        html += '<div><strong>Priority:</strong><br><span class="priority-badge priority-' + req.priority + '">' + req.priority.toUpperCase() + '</span></div>';
                        html += '<div><strong>Total Amount:</strong><br>₱' + parseFloat(req.total_amount || 0).toFixed(2) + '</div>';
                        html += '<div><strong>Date:</strong><br>' + new Date(req.created_at).toLocaleDateString() + '</div>';
                        html += '</div>';
                        
                        if (req.items && req.items.length > 0) {
                            html += '<h6 style="margin-top: 20px; margin-bottom: 10px; color: #2d5016; font-weight: 600;">Request Items:</h6>';
                            html += '<table class="table" style="margin-top: 10px;"><thead><tr style="background: #f8f9fa;"><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr></thead><tbody>';
                            req.items.forEach(function(item) {
                                const productName = item.product ? item.product.name : 'Product ID: ' + item.product_id;
                                html += '<tr>';
                                html += '<td>' + productName + '</td>';
                                html += '<td>' + item.quantity + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                                html += '<td>₱' + parseFloat(item.unit_price || 0).toFixed(2) + '</td>';
                                html += '<td>₱' + parseFloat(item.subtotal || 0).toFixed(2) + '</td>';
                                html += '</tr>';
                            });
                            html += '</tbody></table>';
                        }
                        
                        // Add supplier selection section (only for pending requests)
                        if (req.status === 'pending') {
                            // Check if supplier is already selected
                            const hasSelectedSupplier = req.selected_supplier_id;
                            
                            html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                            html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-building" style="margin-right: 8px;"></i>Select Supplier for Delivery:</h6>';
                            
                            if (hasSelectedSupplier) {
                                html += '<div class="alert alert-success" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px; padding: 12px;">';
                                html += '<i class="fas fa-check-circle"></i> Supplier already selected. You can approve this request from the list.';
                                html += '</div>';
        } else {
                                html += '<div id="supplierSelection_' + requestId + '" style="margin-bottom: 20px;">';
                                html += '<div class="text-center p-3" style="background: #f8f9fa; border-radius: 8px;">';
                                html += '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';
                                html += '<p class="mt-2 mb-0" style="color: #6c757d; font-size: 0.9rem;">Loading suppliers...</p>';
                                html += '</div>';
                                html += '</div>';
                                html += '<div id="acceptButtonContainer_' + requestId + '" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">';
                                html += '<button class="btn btn-primary btn-lg w-100 acceptSupplierBtn" data-id="' + requestId + '" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none; padding: 12px 30px; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 15px rgba(45, 80, 22, 0.3); color: white;">';
                                html += '<i class="fas fa-check"></i> Accept Supplier';
                                html += '</button>';
                                html += '</div>';
                            }
                        }
                        
                        html += '</div>';
                        
                        // Show in modal or alert
                        $('#requestModalBody').html(html);
                        $('#requestModal').modal('show');
                        
                        // Load suppliers if pending request and no supplier selected yet
                        if (req.status === 'pending' && !req.selected_supplier_id) {
                            loadSuppliersForRequest(requestId);
                        }
        } else {
                        alert('Failed to load request details');
                    }
                },
                error: function() {
                    alert('Error loading request details');
        }
    });
}

        function loadSuppliersForRequest(requestId) {
            $.ajax({
                url: '<?= base_url('supplier/') ?>?status=active',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.suppliers) {
                        let html = '<div style="max-height: 300px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; background: #f8f9fa;">';
                        
                        if (response.suppliers.length === 0) {
                            html += '<p class="text-center text-muted mb-0">No active suppliers available</p>';
                        } else {
                            html += '<div class="supplier-list" style="display: grid; gap: 10px;">';
                            response.suppliers.forEach(function(supplier) {
                                const supplierId = supplier.id;
                                const supplierName = supplier.name || 'Unnamed Supplier';
                                const contactInfo = supplier.phone || supplier.email || 'No contact info';
                                
                                html += '<label class="supplier-option" style="display: flex; align-items: center; padding: 12px; background: white; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; margin: 0;">';
                                html += '<input type="radio" name="supplier_' + requestId + '" value="' + supplierId + '" style="margin-right: 12px; width: 20px; height: 20px; cursor: pointer;" onchange="handleSupplierSelection(' + requestId + ', ' + supplierId + ')">';
                                html += '<div style="flex: 1;">';
                                html += '<div style="font-weight: 600; color: #2d5016; margin-bottom: 4px;">' + supplierName + '</div>';
                                html += '<div style="font-size: 0.85rem; color: #6c757d;">' + contactInfo + '</div>';
                                html += '</div>';
                                html += '<i class="fas fa-check-circle" style="color: #28a745; font-size: 1.2rem; opacity: 0; transition: opacity 0.3s ease;"></i>';
                                html += '</label>';
                            });
                            html += '</div>';
                        }
                        
                        html += '</div>';
                        $('#supplierSelection_' + requestId).html(html);
                        
                        // Add hover effects
                        $('.supplier-option').hover(
                            function() {
                                $(this).css({
                                    'border-color': '#2d5016',
                                    'background': '#f0f7f0',
                                    'transform': 'translateX(5px)'
                                });
                            },
                            function() {
                                if (!$(this).find('input[type="radio"]').is(':checked')) {
                                    $(this).css({
                                        'border-color': '#e5e7eb',
                                        'background': 'white',
                                        'transform': 'translateX(0)'
                                    });
                                }
                            }
                        );
                        
                        // Add checked state styling
                        $('.supplier-option input[type="radio"]').on('change', function() {
                            $('.supplier-option').css({
                                'border-color': '#e5e7eb',
                                'background': 'white'
                            });
                            $(this).closest('.supplier-option').css({
                                'border-color': '#28a745',
                                'background': '#f0f7f0',
                                'box-shadow': '0 2px 8px rgba(40, 167, 69, 0.2)'
                            });
                            $(this).closest('.supplier-option').find('i.fa-check-circle').css('opacity', '1');
                        });
        } else {
                        $('#supplierSelection_' + requestId).html('<div class="alert alert-warning">No suppliers available</div>');
                    }
                },
                error: function() {
                    $('#supplierSelection_' + requestId).html('<div class="alert alert-danger">Error loading suppliers</div>');
                }
            });
        }
        
        function handleSupplierSelection(requestId, supplierId) {
            // Show accept button when supplier is selected
            $('#acceptButtonContainer_' + requestId).fadeIn(300);
        }
        
        function acceptSupplier(requestId, supplierId) {
            // Show loading state
            const acceptBtn = $('.acceptSupplierBtn[data-id="' + requestId + '"]');
            const originalText = acceptBtn.html();
            acceptBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Accepting...');
            
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId + '/accept-supplier',
                method: 'POST',
                data: { supplier_id: supplierId },
                dataType: 'json',
                success: function(response) {
            if (response.status === 'success') {
                        $('#requestModal').modal('hide');
                        // Redirect to purchase request list
                        window.location.href = '<?= base_url('centraladmin/dashboard?tab=purchaseRequests') ?>';
            } else {
                        acceptBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Failed to accept supplier'));
                    }
                },
                error: function(xhr) {
                    acceptBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error accepting supplier';
                    alert('Error: ' + errorMsg);
        }
    });
}

        function approveRequest(requestId) {
            // Show delivery details modal first
            $('#approveRequestId').val(requestId);
            $('#deliveryDetailsModal').modal('show');
        }
        
        function submitApprovalWithDeliveryDetails() {
            const requestId = $('#approveRequestId').val();
            const driverName = $('#deliveryDriverName').val();
            const vehicleInfo = $('#deliveryVehicleInfo').val();
            const scheduledDate = $('#deliveryScheduledDate').val();
            
            if (!scheduledDate) {
                alert('Please enter a scheduled delivery date.');
                return;
            }
            
            // Show loading state
            const submitBtn = $('#submitApprovalBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving...');
            
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId + '/approve',
                method: 'POST',
                data: {
                    driver_name: driverName,
                    vehicle_info: vehicleInfo,
                    scheduled_delivery_date: scheduledDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deliveryDetailsModal').modal('hide');
                        alert('Purchase request approved successfully! Purchase Order and Delivery created.');
                        loadPendingRequests(); // Reload the list
                    } else {
                        submitBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Failed to approve request'));
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error approving request';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function rejectRequest(requestId, reason) {
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId + '/reject',
                method: 'POST',
                data: { reason: reason },
                dataType: 'json',
                success: function(response) {
            if (response.status === 'success') {
                        alert('Purchase request rejected successfully!');
                        loadPendingRequests(); // Reload the list
            } else {
                        alert('Error: ' + (response.message || 'Failed to reject request'));
                    }
                },
                error: function() {
                    alert('Error rejecting request');
                }
            });
        }

        function loadPurchaseOrders() {
            console.log('loadPurchaseOrders called');
            const contentDiv = $('#purchaseOrdersContent');
            if (!contentDiv.length) {
                console.error('purchaseOrdersContent div not found!');
                return;
            }
            
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading purchase orders...</p></div>');
            
            const url = '<?= base_url('purchase/order/list') ?>';
            console.log('Loading purchase orders from URL:', url);
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Purchase orders response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response status:', response.status);
                    console.log('Response orders:', response.orders);
                    console.log('Response orders type:', typeof response.orders);
                    console.log('Response orders length:', response.orders ? response.orders.length : 'N/A');
                    
                    if (response && response.status === 'success' && Array.isArray(response.orders)) {
                        let html = '<div class="table-responsive"><table class="table" style="margin-bottom: 0;"><thead><tr style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;"><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Order Number</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Supplier</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Branch</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Total Amount</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Expected Delivery</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Actions</th></tr></thead><tbody>';
                        
                        if (response.orders.length === 0) {
                            html += '<tr><td colspan="7" style="text-align: center; padding: 60px 20px; color: #999;"><i class="fas fa-inbox fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i><h5 style="margin-top: 20px; color: #666;">No purchase orders found</h5><p style="color: #999;">No purchase orders have been created yet</p></td></tr>';
                        } else {
                            response.orders.forEach(function(order) {
                                const status = (order.status || 'pending').toLowerCase();
                                
                                // Status badge classes
                                let statusClass = 'status-pending';
                                let statusText = 'Pending';
                                if (status === 'approved') {
                                    statusClass = 'status-approved';
                                    statusText = 'Approved';
                                } else if (status === 'sent_to_supplier') {
                                    statusClass = 'status-converted';
                                    statusText = 'Sent to Supplier';
                                } else if (status === 'in_transit') {
                                    statusClass = 'status-converted';
                                    statusText = 'In Transit';
                                } else if (status === 'delivered') {
                                    statusClass = 'status-approved';
                                    statusText = 'Delivered';
                                } else if (status === 'cancelled') {
                                    statusClass = 'status-rejected';
                                    statusText = 'Cancelled';
                                } else if (status === 'partial') {
                                    statusClass = 'status-pending';
                                    statusText = 'Partial';
                                }
                                
                                const orderNumber = order.order_number || ('PO-' + String(order.id).padStart(5, '0'));
                                const supplierName = order.supplier ? order.supplier.name : 'N/A';
                                const branchName = order.branch ? order.branch.name : 'N/A';
                                const totalAmount = parseFloat(order.total_amount || 0);
                                const expectedDate = order.expected_delivery_date ? new Date(order.expected_delivery_date) : null;
                                
                                html += '<tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;">';
                                html += '<td style="padding: 15px; vertical-align: middle;"><strong style="color: #2d5016;">' + orderNumber + '</strong></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + supplierName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + branchName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + statusClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: capitalize; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + statusText + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333; font-weight: 600;">₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (expectedDate ? expectedDate.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'N/A') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><button class="btn btn-sm btn-info viewOrderBtn" data-id="' + order.id + '" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-eye"></i> View</button>';
                                
                                // Add approve button for pending orders
                                if (status === 'pending') {
                                    html += ' <button class="btn btn-sm btn-success approveOrderBtn" data-id="' + order.id + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px;"><i class="fas fa-check"></i> Approve</button>';
                                }
                                
                                html += '</td>';
                                html += '</tr>';
                            });
                        }
                        
                        html += '</tbody></table></div>';
                        
                        const contentDiv = $('#purchaseOrdersContent');
                        if (contentDiv.length) {
                            contentDiv.html(html);
                            console.log('Purchase orders table rendered successfully');
                            
                            // Attach event handlers
                            attachOrderHandlers();
                        } else {
                            console.error('purchaseOrdersContent div not found after data load!');
                        }
                    } else {
                        console.warn('Response status is not success or orders array missing:', response);
                        const contentDiv = $('#purchaseOrdersContent');
                        if (contentDiv.length) {
                            contentDiv.html('<div class="alert alert-warning" style="padding: 20px; border-radius: 8px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">No purchase orders found. Response: ' + JSON.stringify(response) + '</div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading orders:', error);
                    console.error('XHR Status:', xhr.status);
                    console.error('XHR Response:', xhr.responseText);
                    const contentDiv = $('#purchaseOrdersContent');
                    if (contentDiv.length) {
                        contentDiv.html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">Error loading purchase orders: ' + error + ' (Status: ' + xhr.status + '). Please check console for details.</div>');
                    }
                }
            });
        }
        
        function attachOrderHandlers() {
            // View order handler
            $('.viewOrderBtn').off('click').on('click', function() {
                const orderId = $(this).data('id');
                viewOrderDetails(orderId);
            });
            
            // Approve order handler
            $('.approveOrderBtn').off('click').on('click', function() {
                const orderId = $(this).data('id');
                if (confirm('Are you sure you want to approve this purchase order?')) {
                    approvePurchaseOrder(orderId);
        }
    });
}

        function viewOrderDetails(orderId) {
            $.ajax({
                url: '<?= base_url('purchase/order/') ?>' + orderId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.order) {
                        const order = response.order;
                        let html = '<div style="padding: 20px;">';
                        html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Purchase Order Details</h5>';
                        
                        // Branch and Supplier Section
                        html += '<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #2d5016; padding: 15px; margin-bottom: 20px; border-radius: 8px;">';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
                        html += '<div><strong style="color: #2d5016;"><i class="fas fa-building"></i> Branch:</strong><br><span style="font-size: 1.05rem; color: #333;">' + (order.branch ? order.branch.name : 'N/A') + '</span></div>';
                        html += '<div><strong style="color: #2d5016;"><i class="fas fa-truck"></i> Supplier:</strong><br><span style="font-size: 1.05rem; color: #333;">' + (order.supplier ? order.supplier.name : 'N/A') + '</span></div>';
                        html += '</div></div>';
                        
                        // Approved By Section (if approved)
                        if (order.approved_by && order.approved_by_user) {
                            html += '<div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; border-radius: 8px;">';
                            html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
                            html += '<div><strong style="color: #155724;"><i class="fas fa-check-circle"></i> Approved By (Central Admin):</strong><br><span style="font-size: 1.05rem; color: #155724;">' + (order.approved_by_user.email || order.approved_by_user.username || 'User ID: ' + order.approved_by) + '</span></div>';
                            html += '<div><strong style="color: #155724;"><i class="fas fa-clock"></i> Approved Date:</strong><br><span style="font-size: 1.05rem; color: #155724;">' + (order.approved_at ? new Date(order.approved_at).toLocaleString() : 'N/A') + '</span></div>';
                            html += '</div></div>';
                        }
                        
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        html += '<div><strong>Order Number:</strong><br>' + (order.order_number || 'N/A') + '</div>';
                        html += '<div><strong>Status:</strong><br><span class="status-badge status-' + order.status + '">' + order.status.replace('_', ' ') + '</span></div>';
                        html += '<div><strong>Total Amount:</strong><br>₱' + parseFloat(order.total_amount || 0).toFixed(2) + '</div>';
                        html += '<div><strong>Order Date:</strong><br>' + new Date(order.order_date).toLocaleDateString() + '</div>';
                        html += '<div><strong>Expected Delivery:</strong><br>' + (order.expected_delivery_date ? new Date(order.expected_delivery_date).toLocaleDateString() : 'N/A') + '</div>';
                        html += '<div><strong>Actual Delivery:</strong><br>' + (order.actual_delivery_date ? new Date(order.actual_delivery_date).toLocaleDateString() : 'N/A') + '</div>';
                        html += '</div>';
                        
                        if (order.items && order.items.length > 0) {
                            html += '<h6 style="margin-top: 20px; margin-bottom: 10px; color: #2d5016; font-weight: 600;">Order Items:</h6>';
                            html += '<table class="table" style="margin-top: 10px;"><thead><tr style="background: #f8f9fa;"><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th><th>Received</th></tr></thead><tbody>';
                            order.items.forEach(function(item) {
                                const productName = item.product ? item.product.name : 'Product ID: ' + item.product_id;
                                const unit = item.unit || (item.product ? item.product.unit : 'pcs') || 'pcs';
                                html += '<tr>';
                                html += '<td>' + productName + '</td>';
                                html += '<td>' + item.quantity + ' <span style="font-weight: 600; color: #2d5016;">' + unit + '</span></td>';
                                html += '<td>₱' + parseFloat(item.unit_price || 0).toFixed(2) + '</td>';
                                html += '<td>₱' + parseFloat(item.subtotal || 0).toFixed(2) + '</td>';
                                html += '<td>' + (item.received_quantity || 0) + ' <span style="font-weight: 600; color: #2d5016;">' + unit + '</span></td>';
                                html += '</tr>';
                            });
                            html += '</tbody></table>';
                        }
                        
                        html += '</div>';
                        
                        $('#orderModalBody').html(html);
                        $('#orderModal').modal('show');
        } else {
                        alert('Failed to load order details');
                    }
                },
                error: function() {
                    alert('Error loading order details');
                }
            });
        }
        
        function approvePurchaseOrder(orderId) {
            $.ajax({
                url: '<?= base_url('purchase/order/') ?>' + orderId + '/approve',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
        if (response.status === 'success') {
                        alert('Purchase order approved successfully!');
                        loadPurchaseOrders(); // Reload the list
        } else {
                        alert('Error: ' + (response.message || 'Failed to approve order'));
                    }
                },
                error: function() {
                    alert('Error approving order');
                }
            });
        }

        function loadDeliveries() {
            console.log('loadDeliveries called');
            const contentDiv = $('#deliveriesContent');
            if (!contentDiv.length) {
                console.error('deliveriesContent div not found!');
                return;
            }
            
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading deliveries...</p></div>');
            
            const url = '<?= base_url('centraladmin/deliveries/list') ?>';
            console.log('Loading deliveries from URL:', url);
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                timeout: 10000, // 10 second timeout
                success: function(response) {
                    console.log('Deliveries response:', response);
                    console.log('Response status:', response.status);
                    console.log('Deliveries count:', response.deliveries ? response.deliveries.length : 0);
                    
                    if (response && response.status === 'success' && Array.isArray(response.deliveries)) {
                        let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Delivery #</th><th>Purchase Order</th><th>Supplier</th><th>Branch</th><th>Status</th><th>Scheduled Date</th><th>Actual Date</th><th>Actions</th></tr></thead><tbody>';
                        
                        if (response.deliveries.length === 0) {
                            html += '<tr><td colspan="8" style="text-align: center; padding: 60px 20px; color: #999;"><i class="fas fa-truck fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i><h5 style="margin-top: 20px; color: #666;">No deliveries found</h5><p style="color: #999;">No deliveries are available</p></td></tr>';
            } else {
                            response.deliveries.forEach(function(delivery) {
                                const status = (delivery.status || 'pending').toLowerCase();
                                let statusClass = 'status-pending';
                                let statusText = 'Pending';
                                if (status === 'delivered') {
                                    statusClass = 'status-approved';
                                    statusText = 'Delivered';
                                } else if (status === 'in_transit') {
                                    statusClass = 'status-converted';
                                    statusText = 'In Transit';
                                } else if (status === 'scheduled') {
                                    statusClass = 'status-pending';
                                    statusText = 'Scheduled';
                                } else if (status === 'cancelled') {
                                    statusClass = 'status-rejected';
                                    statusText = 'Cancelled';
                                }
                                
                                html += '<tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;">';
                                html += '<td style="padding: 15px; vertical-align: middle;"><strong style="color: #2d5016;">' + (delivery.delivery_number || 'DLV-' + String(delivery.id).padStart(5, '0')) + '</strong></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + (delivery.order_number || 'N/A') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + (delivery.supplier_name || 'N/A') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + (delivery.branch_name || 'N/A') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + statusClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: capitalize; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + statusText + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (delivery.scheduled_date ? new Date(delivery.scheduled_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'N/A') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (delivery.actual_delivery_date ? new Date(delivery.actual_delivery_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : '<span style="color: #999; font-style: italic;">-</span>') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><button class="btn btn-sm btn-info viewDeliveryBtn" data-id="' + delivery.id + '" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-eye"></i> View</button></td>';
                                html += '</tr>';
                            });
            }
            
            html += '</tbody></table></div>';
                        const contentDiv = $('#deliveriesContent');
                        if (contentDiv.length) {
                            contentDiv.html(html);
                            console.log('Deliveries table rendered successfully');
                            
                            // Attach event handlers for view buttons
                            attachDeliveryHandlers();
        } else {
                            console.error('deliveriesContent div not found after data load!');
                        }
                    } else {
                        console.warn('Response status is not success or deliveries array missing:', response);
                        const contentDiv = $('#deliveriesContent');
                        if (contentDiv.length) {
                            let message = 'No deliveries found';
                            if (response && response.message) {
                                message = response.message;
                            }
                            contentDiv.html('<div class="alert alert-warning" style="padding: 20px; border-radius: 8px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">' + message + '</div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== AJAX ERROR ===');
                    console.error('Error loading deliveries:', error);
                    console.error('XHR Status:', xhr.status);
                    console.error('XHR Status Text:', xhr.statusText);
                    console.error('XHR Response Text:', xhr.responseText);
                    console.error('XHR Response JSON:', xhr.responseJSON);
                    
                    const contentDiv = $('#deliveriesContent');
                    if (contentDiv.length) {
                        let errorMsg = 'Error loading deliveries: ' + error;
                        if (xhr.status) {
                            errorMsg += ' (HTTP ' + xhr.status + ')';
                        }
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg += '<br>Message: ' + xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMsg += '<br>Response: ' + xhr.responseText.substring(0, 200);
                        }
                        contentDiv.html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">' + errorMsg + '<br><small>Please check browser console (F12) for more details.</small></div>');
                    } else {
                        console.error('Cannot display error - deliveriesContent div not found!');
                        alert('Error: Cannot find content area. Error: ' + error);
                    }
        }
    });
}

        function loadSuppliers() {
            console.log('loadSuppliers called');
            const contentDiv = $('#suppliersContent');
            if (!contentDiv.length) {
                console.error('suppliersContent div not found!');
                return;
            }
            
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading suppliers...</p></div>');
            
            const url = '<?= base_url('supplier/performance') ?>';
            console.log('Loading suppliers from URL:', url);
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Suppliers response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response status:', response.status);
                    console.log('Response suppliers:', response.suppliers);
                    console.log('Response suppliers type:', typeof response.suppliers);
                    console.log('Response suppliers length:', response.suppliers ? response.suppliers.length : 'N/A');
                    
                    if (response && response.status === 'success' && Array.isArray(response.suppliers)) {
                        let html = '<div class="table-responsive"><table class="table" style="margin-bottom: 0;"><thead><tr style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;"><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Supplier Name</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Contact</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Total Deliveries</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">On-Time Rate</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Delayed</th></tr></thead><tbody>';
                        
                        if (response.suppliers.length === 0) {
                            html += '<tr><td colspan="6" style="text-align: center; padding: 60px 20px; color: #999;"><i class="fas fa-building fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i><h5 style="margin-top: 20px; color: #666;">No suppliers found</h5><p style="color: #999;">No suppliers are available</p></td></tr>';
                        } else {
            response.suppliers.forEach(function(supplier) {
                                const statusClass = supplier.status === 'active' ? 'status-approved' : 'status-rejected';
                                const statusText = (supplier.status || 'inactive').charAt(0).toUpperCase() + (supplier.status || 'inactive').slice(1);
                                html += '<tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;">';
                                html += '<td style="padding: 15px; vertical-align: middle;"><strong style="color: #2d5016;">' + (supplier.name || 'N/A') + '</strong></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + (supplier.phone || supplier.email || 'N/A') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + statusClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: capitalize; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + statusText + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333; font-weight: 600;">' + (supplier.total_deliveries || 0) + '</td>';
                                const rateColor = supplier.on_time_rate >= 80 ? 'status-approved' : supplier.on_time_rate >= 60 ? 'status-pending' : 'status-rejected';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + rateColor + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + (supplier.on_time_rate || 0) + '%</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + (supplier.delayed_deliveries || 0) + '</td>';
                                html += '</tr>';
                            });
                        }
                        
                        html += '</tbody></table></div>';
                        const contentDiv = $('#suppliersContent');
                        if (contentDiv.length) {
                            contentDiv.html(html);
                            console.log('Suppliers table rendered successfully');
                        } else {
                            console.error('suppliersContent div not found after data load!');
                        }
            } else {
                        $('#suppliersContent').html('<div class="alert alert-info">No suppliers found</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading suppliers:', error);
                    console.error('XHR Status:', xhr.status);
                    console.error('XHR Response:', xhr.responseText);
                    const contentDiv = $('#suppliersContent');
                    if (contentDiv.length) {
                        contentDiv.html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">Error loading suppliers: ' + error + ' (Status: ' + xhr.status + '). Please check console for details.</div>');
                    }
        }
    });
}

        function loadAccountsPayable() {
            console.log('loadAccountsPayable called');
            const contentDiv = $('#accountsPayableContent');
            if (!contentDiv.length) {
                console.error('accountsPayableContent div not found!');
                return;
            }
            
            const statusFilter = $('#apStatusFilter').val() || 'all';
            const invoiceFilter = $('#apInvoiceFilter').val() || 'all';
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading accounts payable...</p></div>');
            
            let url = '<?= base_url('accounts-payable/api/list') ?>';
            const params = [];
            if (statusFilter !== 'all') {
                params.push('payment_status=' + encodeURIComponent(statusFilter));
            }
            if (invoiceFilter !== 'all') {
                params.push('invoice_filter=' + encodeURIComponent(invoiceFilter));
            }
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            
            console.log('Loading accounts payable from URL:', url);
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Accounts Payable response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response status:', response.status);
                    console.log('Response accounts_payable:', response.accounts_payable);
                    console.log('Response accounts_payable type:', typeof response.accounts_payable);
                    console.log('Response accounts_payable length:', response.accounts_payable ? response.accounts_payable.length : 'N/A');
                    
                    if (response && response.status === 'success' && Array.isArray(response.accounts_payable)) {
                        let html = '<div class="table-responsive" style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; width: 100%;"><thead><tr style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;"><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">PO Number</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Supplier</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Branch</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Invoice #</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Amount</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Paid</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Balance</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Payment Date</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Payment Method</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Payment Reference</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Due Date</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Actions</th></tr></thead><tbody>';
                        
                        if (response.accounts_payable.length === 0) {
                            html += '<tr><td colspan="13" style="text-align: center; padding: 60px 20px; color: #999;"><i class="fas fa-inbox fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i><h5 style="margin-top: 20px; color: #666;">No accounts payable found</h5><p style="color: #999;">No accounts payable records available</p></td></tr>';
        } else {
                            response.accounts_payable.forEach(function(ap) {
                                const status = (ap.payment_status || 'unpaid').toLowerCase();
                                
                                // Status badge classes
                                let statusClass = 'status-pending';
                                let statusText = 'Unpaid';
                                if (status === 'paid') {
                                    statusClass = 'status-approved';
                                    statusText = 'Paid';
                                } else if (status === 'partial') {
                                    statusClass = 'status-converted';
                                    statusText = 'Partial';
                                } else if (status === 'overdue') {
                                    statusClass = 'status-rejected';
                                    statusText = 'Overdue';
                                }
                                
                                const orderNumber = ap.order_number || 'N/A';
                                const supplierName = ap.supplier ? ap.supplier.name : 'N/A';
                                const branchName = ap.branch ? ap.branch.name : 'N/A';
                                const invoiceNumber = ap.invoice_number || '';
                                const amount = parseFloat(ap.amount || ap.total_amount || 0);
                                // Check both paid_amount and amount_paid (in case of field name differences)
                                const paidAmount = parseFloat(ap.paid_amount || ap.amount_paid || 0);
                                const balance = parseFloat(ap.balance || (amount - paidAmount) || 0);
                                const dueDate = ap.due_date ? new Date(ap.due_date) : null;
                                const daysInfo = ap.days_info || '';
                                const paymentDate = ap.payment_date ? new Date(ap.payment_date) : null;
                                // Log raw payment method before formatting
                                console.log('Raw payment_method for AP ' + ap.id + ':', ap.payment_method, 'Type:', typeof ap.payment_method);
                                const paymentMethod = formatPaymentMethod(ap.payment_method || '');
                                const paymentReference = ap.payment_reference || '';
                                
                                // Determine if payment has been made (for showing receipt/print buttons)
                                // Show buttons if: status is paid/partial OR there's any payment amount OR payment date exists
                                const hasPayment = paidAmount > 0 || paymentDate !== null;
                                const isPaid = status === 'paid';
                                const isPartial = status === 'partial';
                                // ALWAYS show receipt buttons if there's any payment made, regardless of status
                                const showReceiptButtons = isPaid || isPartial || hasPayment;
                                
                                // Force show if paidAmount > 0 (most reliable check)
                                const forceShowReceipt = paidAmount > 0;
                                
                                // Debug: Log ALL accounts payable entries to see what's happening
                                console.log('=== AP Entry Debug ===');
                                console.log('AP ID:', ap.id);
                                console.log('Status:', status);
                                console.log('Paid Amount:', paidAmount);
                                console.log('Amount:', amount);
                                console.log('Balance:', balance);
                                console.log('Payment Date:', paymentDate);
                                console.log('Raw Payment Method:', ap.payment_method);
                                console.log('Formatted Payment Method:', paymentMethod);
                                console.log('isPaid:', isPaid);
                                console.log('isPartial:', isPartial);
                                console.log('hasPayment:', hasPayment);
                                console.log('showReceiptButtons:', showReceiptButtons);
                                console.log('forceShowReceipt:', forceShowReceipt);
                                console.log('Full AP Object:', ap);
                                console.log('========================');
                                
                                html += '<tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;">';
                                html += '<td style="padding: 15px; vertical-align: middle;"><strong style="color: #2d5016;">' + orderNumber + '</strong></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + supplierName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + branchName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">';
                                if (invoiceNumber) {
                                    html += '<span class="editable-invoice" data-id="' + ap.id + '" data-invoice="' + invoiceNumber + '" style="cursor: pointer; padding: 4px 8px; border-radius: 4px; transition: all 0.2s;" onmouseover="this.style.background=\'#f0f0f0\';" onmouseout="this.style.background=\'transparent\';">' + invoiceNumber + ' <i class="fas fa-edit" style="font-size: 0.7rem; color: #6c757d;"></i></span>';
                                } else {
                                    html += '<span class="editable-invoice" data-id="' + ap.id + '" data-invoice="" style="cursor: pointer; padding: 4px 8px; border-radius: 4px; color: #999; font-style: italic; transition: all 0.2s;" onmouseover="this.style.background=\'#f0f0f0\';" onmouseout="this.style.background=\'transparent\';">Click to add <i class="fas fa-plus" style="font-size: 0.7rem; color: #6c757d;"></i></span>';
                                }
                                html += '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333; font-weight: 600;">₱' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #28a745; font-weight: 600;">₱' + paidAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: ' + (balance > 0 ? '#dc3545' : '#28a745') + '; font-weight: 600;">₱' + balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + statusClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: capitalize; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + statusText + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (paymentDate ? paymentDate.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : '<span style="color: #999; font-style: italic;">-</span>') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (paymentMethod !== 'Not specified' ? paymentMethod : '<span style="color: #999; font-style: italic;">-</span>') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (paymentReference || '<span style="color: #999; font-style: italic;">-</span>') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (dueDate ? dueDate.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'N/A') + '<br><small style="color: ' + (status === 'overdue' ? '#dc3545' : '#6c757d') + ';">' + daysInfo + '</small></td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><div style="display: flex; gap: 5px; flex-wrap: wrap;">';
                                html += '<button class="btn btn-sm btn-info viewAPBtn" data-id="' + ap.id + '" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-eye"></i> View</button>';
                                
                                // Add payment button for unpaid/partial/overdue (only if not fully paid)
                                if (status !== 'paid' && balance > 0) {
                                    html += ' <button class="btn btn-sm btn-success recordPaymentBtn" data-id="' + ap.id + '" data-balance="' + balance + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px;"><i class="fas fa-money-bill-wave"></i> Pay</button>';
                                }
                                
                                // Add receipt and print buttons - ALWAYS show if paidAmount > 0
                                if (paidAmount > 0) {
                                    console.log('✓ RENDERING RECEIPT BUTTONS FOR AP ID:', ap.id, 'Paid Amount:', paidAmount);
                                    html += ' <button class="btn btn-sm btn-primary viewReceiptBtn" data-id="' + ap.id + '" style="background: #2d5016; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;"><i class="fas fa-receipt"></i> Receipt</button>';
                                    html += ' <button class="btn btn-sm btn-success printReceiptBtn" data-id="' + ap.id + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;"><i class="fas fa-print"></i> Print Receipt</button>';
                                } else if (isPaid || isPartial) {
                                    console.log('✓ RENDERING RECEIPT BUTTONS FOR AP ID:', ap.id, 'Status:', status);
                                    html += ' <button class="btn btn-sm btn-primary viewReceiptBtn" data-id="' + ap.id + '" style="background: #2d5016; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;"><i class="fas fa-receipt"></i> Receipt</button>';
                                    html += ' <button class="btn btn-sm btn-success printReceiptBtn" data-id="' + ap.id + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;"><i class="fas fa-print"></i> Print Receipt</button>';
                                } else {
                                    console.log('✗ NOT RENDERING RECEIPT BUTTONS FOR AP ID:', ap.id, '- Paid Amount:', paidAmount, 'Status:', status);
                                }
                                
                                // Add invoice update button (always show for easy access)
                                html += ' <button class="btn btn-sm btn-warning updateInvoiceBtn" data-id="' + ap.id + '" data-invoice="' + invoiceNumber + '" style="background: #ffc107; color: #000; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px;"><i class="fas fa-file-invoice"></i> Invoice</button>';
                                
                                html += '</div></td>';
                                html += '</tr>';
                            });
                        }
                        
                        html += '</tbody></table></div>';
                        
                        const contentDiv = $('#accountsPayableContent');
                        if (contentDiv.length) {
                            contentDiv.html(html);
                            console.log('Accounts Payable table rendered successfully');
                            
                            // Attach event handlers
                            attachAPHandlers();
                        } else {
                            console.error('accountsPayableContent div not found after data load!');
                        }
                    } else {
                        console.warn('Response status is not success or accounts_payable array missing:', response);
                        const contentDiv = $('#accountsPayableContent');
                        if (contentDiv.length) {
                            contentDiv.html('<div class="alert alert-warning" style="padding: 20px; border-radius: 8px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">No accounts payable found. Response: ' + JSON.stringify(response) + '</div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading accounts payable:', error);
                    console.error('XHR Response:', xhr.responseText);
                    console.error('Status:', status);
                    
                    let errorMsg = 'Error loading accounts payable. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch(e) {
                            errorMsg = 'Server error: ' + xhr.status + ' ' + xhr.statusText;
                        }
                    }
                    
                    $('#accountsPayableContent').html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;"><strong>Error:</strong> ' + errorMsg + '<br><small>Check browser console for details.</small></div>');
                }
            });
        }
        
        function backfillAccountsPayable() {
            if (!confirm('This will create accounts payable entries for all approved purchase orders that don\'t have AP records yet. Continue?')) {
        return;
    }

            $.ajax({
                url: '<?= base_url('accounts-payable/backfill') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
        if (response.status === 'success') {
                        alert('Success! Created ' + response.created + ' accounts payable entries.');
                        loadAccountsPayable(); // Reload the list
                    } else {
                        alert('Error: ' + (response.message || 'Failed to backfill'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error backfilling accounts payable';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function loadAccountsPayableSummary() {
            $.ajax({
                url: '<?= base_url('accounts-payable/summary') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.summary) {
                        const s = response.summary;
                        let html = '<div style="padding: 20px; background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%); border-radius: 12px; margin-bottom: 20px;">';
                        html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Accounts Payable Summary</h5>';
                        html += '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Total Amount</div><div style="font-size: 1.5rem; font-weight: 700; color: #2d5016;">₱' + parseFloat(s.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</div></div>';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Total Paid</div><div style="font-size: 1.5rem; font-weight: 700; color: #28a745;">₱' + parseFloat(s.total_paid || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</div></div>';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Total Balance</div><div style="font-size: 1.5rem; font-weight: 700; color: #dc3545;">₱' + parseFloat(s.total_balance || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</div></div>';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Unpaid</div><div style="font-size: 1.5rem; font-weight: 700; color: #ffc107;">' + (s.unpaid_count || 0) + '</div></div>';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Partial</div><div style="font-size: 1.5rem; font-weight: 700; color: #17a2b8;">' + (s.partial_count || 0) + '</div></div>';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Paid</div><div style="font-size: 1.5rem; font-weight: 700; color: #28a745;">' + (s.paid_count || 0) + '</div></div>';
                        html += '<div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 5px;">Overdue</div><div style="font-size: 1.5rem; font-weight: 700; color: #dc3545;">' + (s.overdue_count || 0) + '</div><div style="font-size: 0.85rem; color: #dc3545; margin-top: 5px;">₱' + parseFloat(s.overdue_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</div></div>';
                        html += '</div></div>';
                        
                        // Show in modal
                        $('#apSummaryModalBody').html(html);
                        $('#apSummaryModal').modal('show');
        } else {
                        alert('Failed to load summary');
                    }
                },
                error: function() {
                    alert('Error loading summary');
                }
            });
        }
        
        function attachDeliveryHandlers() {
            // View delivery handler
            $('.viewDeliveryBtn').off('click').on('click', function() {
                const deliveryId = $(this).data('id');
                viewDeliveryDetails(deliveryId);
            });
        }
        
        function viewDeliveryDetails(deliveryId) {
            $.get('<?= base_url('delivery/') ?>' + deliveryId + '/track', function(response) {
                if (response.status === 'success' && response.delivery) {
                    const delivery = response.delivery;
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Delivery Information</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Delivery Number:</strong><br>' + (delivery.delivery_number || 'N/A') + '</div>';
                    html += '<div><strong>Status:</strong><br><span class="badge ' + (delivery.status === 'delivered' ? 'badge-success' : delivery.status === 'scheduled' ? 'badge-warning' : 'badge-info') + '">' + (delivery.status || 'scheduled') + '</span></div>';
                    html += '<div><strong>Purchase Order:</strong><br>' + (delivery.purchase_order ? (delivery.purchase_order.order_number || 'N/A') : 'N/A') + '</div>';
                    html += '<div><strong>Supplier:</strong><br>' + (delivery.supplier ? delivery.supplier.name : 'N/A') + '</div>';
                    html += '<div><strong>Branch:</strong><br>' + (delivery.branch ? delivery.branch.name : 'N/A') + '</div>';
                    html += '<div><strong>Scheduled Date:</strong><br>' + (delivery.scheduled_date || 'N/A') + '</div>';
                    html += '<div><strong>Actual Delivery Date:</strong><br>' + (delivery.actual_delivery_date || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Driver Name:</strong><br>' + (delivery.driver_name || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Vehicle Info:</strong><br>' + (delivery.vehicle_info || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Scheduled By:</strong><br>' + (delivery.scheduled_by_user ? (delivery.scheduled_by_user.email || delivery.scheduled_by_user.username || 'User ID: ' + delivery.scheduled_by_user.id) : '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Received By:</strong><br>' + (delivery.received_by_user ? (delivery.received_by_user.email || delivery.received_by_user.username || 'User ID: ' + delivery.received_by_user.id) : '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Received At:</strong><br>' + (delivery.received_at || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '</div>';
                    
                    if (delivery.items && delivery.items.length > 0) {
                        html += '<h6 style="margin-top: 20px; margin-bottom: 10px; color: #2d5016; font-weight: 600;">Delivery Items:</h6>';
                        html += '<table class="table" style="margin-top: 10px;"><thead><tr style="background: #f8f9fa;"><th>Product</th><th>Quantity</th><th>Received Quantity</th></tr></thead><tbody>';
                        delivery.items.forEach(function(item) {
                            const productName = item.product ? item.product.name : 'Product ID: ' + item.product_id;
                            html += '<tr>';
                            html += '<td>' + productName + '</td>';
                            html += '<td>' + item.quantity + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                            html += '<td>' + (item.received_quantity || 0) + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                    }
                    
                    if (delivery.notes) {
                        html += '<div class="mt-3"><strong>Notes:</strong><br>' + delivery.notes + '</div>';
                    }
                    
                    if (delivery.tracking) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Tracking Information:</h6>';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
                        html += '<div><strong>Current Status:</strong><br>' + delivery.tracking.current_status + '</div>';
                        if (delivery.tracking.days_until_delivery !== null) {
                            html += '<div><strong>Days Until Delivery:</strong><br>' + Math.round(delivery.tracking.days_until_delivery) + ' days</div>';
                        }
                        if (delivery.tracking.is_overdue) {
                            html += '<div><strong>Status:</strong><br><span class="badge badge-danger">Overdue</span></div>';
                        }
                        if (delivery.tracking.is_delayed) {
                            html += '<div><strong>Status:</strong><br><span class="badge badge-warning">Delayed</span></div>';
                        }
                        html += '</div>';
                    }
                    
                    html += '</div>';
                    
                    $('#deliveryModalBody').html(html);
                    $('#deliveryModal').modal('show');
                } else {
                    alert('Failed to load delivery details: ' + (response.message || 'Unknown error'));
                }
            }).fail(function() {
                alert('Error loading delivery details');
            });
        }
        
        function attachAPHandlers() {
            // View AP handler
            $('.viewAPBtn').off('click').on('click', function() {
                const apId = $(this).data('id');
                viewAPDetails(apId);
            });
            
            // Record payment handler
            $('.recordPaymentBtn').off('click').on('click', function() {
                const apId = $(this).data('id');
                const balance = $(this).data('balance');
                recordPayment(apId, balance);
            });
            
            // View receipt handler
            $('.viewReceiptBtn').off('click').on('click', function() {
                const apId = $(this).data('id');
                showReceipt(apId);
            });
            
            // Print receipt handler (direct print)
            $('.printReceiptBtn').off('click').on('click', function() {
                const apId = $(this).data('id');
                printReceiptDirect(apId);
            });
            
            // Update invoice handler
            $('.updateInvoiceBtn').off('click').on('click', function() {
                const apId = $(this).data('id');
                const invoiceNumber = $(this).data('invoice') || '';
                updateInvoice(apId, invoiceNumber);
            });
            
            // Editable invoice number handler
            $('.editable-invoice').off('click').on('click', function() {
                const apId = $(this).data('id');
                const currentInvoice = $(this).data('invoice') || '';
                const $this = $(this);
                
                // Create input field
                const input = $('<input>', {
                    type: 'text',
                    class: 'form-control',
                    value: currentInvoice,
                    style: 'width: 150px; display: inline-block; padding: 4px 8px; border: 2px solid #2d5016; border-radius: 4px;',
                    onblur: function() {
                        const newInvoice = $(this).val().trim();
                        saveInvoiceNumber(apId, newInvoice, $this);
                    },
                    onkeypress: function(e) {
                        if (e.which === 13) { // Enter key
                            $(this).blur();
                        }
                    }
                });
                
                $this.html(input);
                input.focus();
                input.select();
            });
        }
        
        function saveInvoiceNumber(apId, invoiceNumber, $element) {
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/update-invoice',
                method: 'POST',
                data: { invoice_number: invoiceNumber },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (invoiceNumber) {
                            $element.html(invoiceNumber + ' <i class="fas fa-edit" style="font-size: 0.7rem; color: #6c757d;"></i>');
                            $element.data('invoice', invoiceNumber);
                        } else {
                            $element.html('Click to add <i class="fas fa-plus" style="font-size: 0.7rem; color: #6c757d;"></i>');
                            $element.data('invoice', '');
                        }
                        // Update the button data as well
                        $('.updateInvoiceBtn[data-id="' + apId + '"]').data('invoice', invoiceNumber);
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update invoice number'));
                        loadAccountsPayable(); // Reload to reset
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating invoice number';
                    alert('Error: ' + errorMsg);
                    loadAccountsPayable(); // Reload to reset
                }
            });
        }
        
        function viewAPDetails(apId) {
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.accounts_payable) {
                        const ap = response.accounts_payable;
                        let html = '<div style="padding: 20px;">';
                        html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Accounts Payable Details</h5>';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        html += '<div><strong>Purchase Order:</strong><br>' + (ap.order_number || 'N/A') + '</div>';
                        html += '<div><strong>Supplier:</strong><br>' + (ap.supplier_name || 'N/A') + '</div>';
                        html += '<div><strong>Branch:</strong><br>' + (ap.branch_name || 'N/A') + '</div>';
                        html += '<div><strong>Invoice Number:</strong><br>' + (ap.invoice_number || 'Not set') + '</div>';
                        html += '<div><strong>Invoice Date:</strong><br>' + (ap.invoice_date ? new Date(ap.invoice_date).toLocaleDateString() : 'N/A') + '</div>';
                        html += '<div><strong>Due Date:</strong><br>' + (ap.due_date ? new Date(ap.due_date).toLocaleDateString() : 'N/A') + '</div>';
                        html += '<div><strong>Total Amount:</strong><br>₱' + parseFloat(ap.amount || 0).toFixed(2) + '</div>';
                        html += '<div><strong>Paid Amount:</strong><br>₱' + parseFloat(ap.paid_amount || 0).toFixed(2) + '</div>';
                        html += '<div><strong>Balance:</strong><br><span style="color: ' + (parseFloat(ap.balance || 0) > 0 ? '#dc3545' : '#28a745') + '; font-weight: 600;">₱' + parseFloat(ap.balance || 0).toFixed(2) + '</span></div>';
                        html += '<div><strong>Payment Status:</strong><br><span class="status-badge status-' + ap.payment_status + '">' + ap.payment_status + '</span></div>';
                        html += '<div><strong>Payment Date:</strong><br>' + (ap.payment_date ? new Date(ap.payment_date).toLocaleDateString() : 'N/A') + '</div>';
                        html += '<div><strong>Payment Method:</strong><br>' + formatPaymentMethod(ap.payment_method || '') + '</div>';
                        html += '<div><strong>Payment Reference:</strong><br>' + (ap.payment_reference || 'N/A') + '</div>';
                        html += '</div>';
                        
                        if (ap.notes) {
                            html += '<div style="margin-top: 20px;"><strong>Notes:</strong><br><p style="color: #666;">' + ap.notes + '</p></div>';
                        }
                        
                        html += '</div>';
                        
                        $('#apModalBody').html(html);
                        $('#apModal').modal('show');
                    } else {
                        alert('Failed to load accounts payable details');
                    }
                },
                error: function() {
                    alert('Error loading accounts payable details');
                }
            });
        }
        
        function recordPayment(apId, balance) {
            const paymentAmount = prompt('Enter payment amount (Balance: ₱' + parseFloat(balance).toFixed(2) + '):');
            
            if (!paymentAmount || parseFloat(paymentAmount) <= 0) {
                return;
            }

            const paymentMethod = prompt('Enter payment method (e.g., Bank Transfer, Cash, Check):') || 'Bank Transfer';
            const paymentReference = prompt('Enter payment reference (e.g., Check #, Transaction ID):') || '';
            
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/record-payment',
                method: 'POST',
                data: {
                    payment_amount: paymentAmount,
                    payment_method: paymentMethod,
                    payment_reference: paymentReference
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Small delay to ensure database is updated before reloading
                        setTimeout(function() {
                            loadAccountsPayable(); // Reload the list
                        }, 300);
                        // Show receipt after successful payment
                        if (response.payment_transaction_id) {
                            showReceipt(apId, response.payment_transaction_id);
                        } else {
                            showReceipt(apId);
                        }
                    } else {
                        alert('Error: ' + (response.message || 'Failed to record payment'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error recording payment';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function showReceipt(apId, paymentTransactionId = null) {
            let url = '<?= base_url('accounts-payable/') ?>' + apId + '/receipt';
            if (paymentTransactionId) {
                url += '/' + paymentTransactionId;
            }
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.receipt) {
                        renderReceipt(response.receipt);
                        $('#receiptModal').modal('show');
                    } else {
                        alert('Error loading receipt: ' + (response.message || 'Failed to load receipt'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error loading receipt';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function printReceiptDirect(apId, paymentTransactionId = null) {
            let url = '<?= base_url('accounts-payable/') ?>' + apId + '/receipt';
            if (paymentTransactionId) {
                url += '/' + paymentTransactionId;
            }
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.receipt) {
                        // Render receipt in a hidden container
                        renderReceipt(response.receipt);
                        
                        // Wait a bit for rendering, then print
                        setTimeout(function() {
                            const printContent = document.getElementById('receiptContent');
                            if (printContent) {
                                // Create a new window for printing
                                const printWindow = window.open('', '_blank', 'width=800,height=600');
                                
                                if (!printWindow) {
                                    alert('Please allow popups to print the receipt');
                                    return;
                                }
                                
                                printWindow.document.write('<!DOCTYPE html><html><head><title>CHAKANOKS - Payment Receipt</title>');
                                printWindow.document.write('<meta name="viewport" content="width=80mm">');
                                printWindow.document.write('<style>');
                                printWindow.document.write('* { margin: 0; padding: 0; box-sizing: border-box; }');
                                printWindow.document.write('html, body { width: 80mm; margin: 0; padding: 0; background: white; overflow: hidden; }');
                                printWindow.document.write('body { font-family: "Courier New", monospace; margin: 0; padding: 0; }');
                                printWindow.document.write('.receipt-container { max-width: 80mm; width: 80mm; min-width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-size: 10pt; line-height: 1.3; color: #000; }');
                                printWindow.document.write('img { max-height: 30px; max-width: 30px; object-fit: contain; }');
                                printWindow.document.write('@page { size: 80mm auto; margin: 0; width: 80mm; }');
                                printWindow.document.write('@media print { html, body { width: 80mm !important; margin: 0 !important; padding: 0 !important; } .receipt-container { max-width: 80mm !important; width: 80mm !important; min-width: 80mm !important; padding: 10mm 5mm !important; } @page { size: 80mm auto !important; margin: 0 !important; width: 80mm !important; } }');
                                printWindow.document.write('</style></head><body>');
                                printWindow.document.write(printContent.innerHTML);
                                printWindow.document.write('</body></html>');
                                printWindow.document.close();
                                
                                // Trigger print after content is loaded
                                printWindow.onload = function() {
                                    setTimeout(function() {
                                        printWindow.focus();
                                        printWindow.print();
                                        // Don't close immediately - let user interact with print dialog
                                    }, 250);
                                };
                                
                                // Fallback if onload doesn't fire
                                setTimeout(function() {
                                    if (printWindow.document.readyState === 'complete') {
                                        printWindow.focus();
                                        printWindow.print();
                                    }
                                }, 500);
                            } else {
                                alert('Receipt content not found');
                            }
                        }, 100);
                    } else {
                        alert('Error loading receipt: ' + (response.message || 'Failed to load receipt'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error loading receipt';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function renderReceipt(receipt) {
            const statusClass = receipt.payment_status === 'paid' ? 'badge-success' : 
                               receipt.payment_status === 'partial' ? 'badge-warning' : 
                               receipt.payment_status === 'overdue' ? 'badge-danger' : 'badge-secondary';
            const statusText = receipt.payment_status === 'paid' ? 'PAID' : 
                              receipt.payment_status === 'partial' ? 'PARTIAL' : 
                              receipt.payment_status === 'overdue' ? 'OVERDUE' : 'UNPAID';
            
            // Grocery store receipt style - narrow width (80mm/3 inches)
            let html = '<div class="receipt-container" id="receiptContent" style="max-width: 80mm; width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-family: "Courier New", monospace; font-size: 10pt; line-height: 1.3; color: #000;">';
            
            // Company Header - Compact
            html += '<div style="text-align: center; margin-bottom: 8mm; padding-bottom: 5mm; border-bottom: 1px dashed #000;">';
            html += '<img src="<?= base_url('assets/images/529947519_1269388418065636_7025202690109522655_n.png') ?>" alt="CHAKANOKS Logo" style="max-height: 30px; max-width: 30px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto 3mm;">';
            html += '<div style="font-weight: bold; font-size: 14pt; letter-spacing: 1px; margin-bottom: 2mm;">CHAKANOKS</div>';
            html += '<div style="font-size: 8pt; color: #666; margin-bottom: 3mm;">Supply Chain Management System</div>';
            html += '<div style="font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-top: 3mm;">PAYMENT RECEIPT</div>';
            html += '</div>';
            
            // Invoice Information - Compact single column
            html += '<div style="text-align: center; margin-bottom: 5mm; padding-bottom: 3mm; border-bottom: 1px dashed #000;">';
            html += '<div style="font-weight: bold; font-size: 9pt; margin-bottom: 2mm;">INVOICE #: ' + (receipt.invoice_number || 'N/A') + '</div>';
            html += '<div style="font-size: 8pt; margin-bottom: 1mm;">Status: <strong>' + statusText + '</strong></div>';
            html += '</div>';
            
            // Supplier and Order Info
            html += '<div style="margin-bottom: 4mm; font-size: 9pt;">';
            html += '<div style="margin-bottom: 2mm;"><strong>Supplier:</strong> ' + (receipt.supplier ? receipt.supplier.name : 'N/A') + '</div>';
            html += '<div style="margin-bottom: 2mm;"><strong>PO #:</strong> ' + (receipt.purchase_order ? receipt.purchase_order.order_number : 'N/A') + '</div>';
            if (receipt.invoice_date) {
                html += '<div style="margin-bottom: 2mm;"><strong>Invoice Date:</strong> ' + formatDate(receipt.invoice_date) + '</div>';
            }
            if (receipt.due_date) {
                html += '<div style="margin-bottom: 2mm;"><strong>Due Date:</strong> ' + formatDate(receipt.due_date) + '</div>';
            }
            html += '</div>';
            
            // Payment Amounts - Compact
            html += '<div style="text-align: center; margin-bottom: 4mm; padding: 3mm 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">';
            html += '<div style="margin-bottom: 2mm;"><span style="font-size: 8pt;">Total Amount:</span><br><span style="font-size: 12pt; font-weight: bold;">₱' + parseFloat(receipt.amounts.total_amount || 0).toFixed(2) + '</span></div>';
            html += '<div style="margin-bottom: 2mm;"><span style="font-size: 8pt;">Paid Amount:</span><br><span style="font-size: 12pt; font-weight: bold; color: #28a745;">₱' + parseFloat(receipt.amounts.total_paid || 0).toFixed(2) + '</span></div>';
            const balance = parseFloat(receipt.amounts.balance || 0);
            html += '<div><span style="font-size: 8pt;">Balance:</span><br><span style="font-size: 12pt; font-weight: bold; color: ' + (balance > 0 ? '#dc3545' : '#28a745') + ';">₱' + balance.toFixed(2) + '</span></div>';
            html += '</div>';
            
            // Payment Information - Compact
            html += '<div style="margin-bottom: 4mm; font-size: 9pt;">';
            html += '<div style="text-align: center; font-weight: bold; margin-bottom: 2mm; padding-bottom: 2mm; border-bottom: 1px dashed #000;">PAYMENT DETAILS</div>';
            
            if (receipt.all_payments && receipt.all_payments.length > 0) {
                const latestPayment = receipt.all_payments[0];
                html += '<div style="margin-bottom: 2mm;"><strong>Method:</strong> ' + formatPaymentMethod(latestPayment.payment_method || '') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Reference:</strong> ' + (latestPayment.payment_reference || 'N/A') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Date:</strong> ' + formatDate(latestPayment.payment_date) + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Amount:</strong> ₱' + parseFloat(latestPayment.payment_amount || 0).toFixed(2) + '</div>';
                if (receipt.all_payments.length > 1) {
                    html += '<div style="margin-top: 2mm; font-size: 8pt; color: #666;">Total Payments: ' + receipt.all_payments.length + ' transaction(s)</div>';
                }
            } else if (receipt.payment_details) {
                html += '<div style="margin-bottom: 2mm;"><strong>Method:</strong> ' + formatPaymentMethod(receipt.payment_details.payment_method || '') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Reference:</strong> ' + (receipt.payment_details.payment_reference || 'N/A') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Date:</strong> ' + (receipt.payment_details.payment_date ? formatDate(receipt.payment_details.payment_date) : 'N/A') + '</div>';
            }
            html += '</div>';
            
            // Receipt Footer - Compact
            html += '<div style="text-align: center; margin-top: 5mm; padding-top: 3mm; border-top: 1px dashed #000; font-size: 8pt; color: #666;">';
            html += '<div style="margin-bottom: 1mm;">Receipt #: ' + (receipt.receipt_number || 'N/A') + '</div>';
            html += '<div style="margin-bottom: 1mm;">Date: ' + formatDate(receipt.receipt_date) + '</div>';
            if (receipt.recorded_by) {
                html += '<div style="margin-bottom: 1mm;">Recorded by: ' + (receipt.recorded_by.name || 'N/A') + '</div>';
            }
            // Get month from filter
            const receiptMonth = $('#receiptMonthFilter').val(); // Format: YYYY-MM
            
            // Get month name for display
            let monthName = '';
            if (receiptMonth) {
                const [year, month] = receiptMonth.split('-');
                monthName = new Date(parseInt(year), parseInt(month) - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            } else {
                const now = new Date();
                monthName = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            }
            
            const generatedDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            html += '<div style="margin-top: 3mm; font-size: 7pt;">Report Period: ' + monthName + '</div>';
            html += '<div style="margin-top: 1mm; font-size: 7pt;">Generated: ' + generatedDate + '</div>';
            html += '</div>';
            
            // Thank you message
            html += '<div style="text-align: center; margin-top: 5mm; padding-top: 3mm; border-top: 1px dashed #000; font-size: 9pt; font-weight: bold;">';
            html += 'Thank you for your payment!';
            html += '</div>';
            
            html += '</div>';
            
            $('#receiptModalBody').html(html);
        }
        
        function printReceipt() {
            const printContent = document.getElementById('receiptContent');
            if (!printContent) {
                alert('Receipt content not found');
                return;
            }
            
            // Create a new window for printing (better approach - doesn't affect current page)
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            
            if (!printWindow) {
                alert('Please allow popups to print the receipt');
                return;
            }
            
            // Write the print content with narrow receipt styling (80mm thermal receipt)
            printWindow.document.write('<!DOCTYPE html><html><head><title>CHAKANOKS - Payment Receipt</title>');
            printWindow.document.write('<meta name="viewport" content="width=80mm">');
            printWindow.document.write('<style>');
            printWindow.document.write('* { margin: 0; padding: 0; box-sizing: border-box; }');
            printWindow.document.write('html, body { width: 80mm; margin: 0; padding: 0; background: white; overflow: hidden; }');
            printWindow.document.write('body { font-family: "Courier New", monospace; margin: 0; padding: 0; }');
            printWindow.document.write('.receipt-container { max-width: 80mm; width: 80mm; min-width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-size: 10pt; line-height: 1.3; color: #000; }');
            printWindow.document.write('img { max-height: 30px; max-width: 30px; object-fit: contain; }');
            printWindow.document.write('@page { size: 80mm auto; margin: 0; width: 80mm; }');
            printWindow.document.write('@media print { html, body { width: 80mm !important; margin: 0 !important; padding: 0 !important; } .receipt-container { max-width: 80mm !important; width: 80mm !important; min-width: 80mm !important; padding: 10mm 5mm !important; } @page { size: 80mm auto !important; margin: 0 !important; width: 80mm !important; } }');
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(printContent.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            // Wait for content to load, then trigger print
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                    printWindow.print();
                    // Don't close immediately - let user see the print dialog
                    // printWindow.close();
                }, 250);
            };
            
            // Fallback if onload doesn't fire
            setTimeout(function() {
                if (printWindow.document.readyState === 'complete') {
                    printWindow.focus();
                    printWindow.print();
                }
            }, 500);
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }
        
        function formatDateTime(date) {
            return date.toLocaleString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        function formatPaymentMethod(method) {
            // Handle null, undefined, or empty values
            if (!method || (typeof method === 'string' && method.trim() === '')) {
                return 'Not specified';
            }
            
            // Convert to string and normalize
            const methodStr = String(method).trim();
            if (methodStr === '') {
                return 'Not specified';
            }
            
            // Normalize to lowercase for comparison
            const normalizedMethod = methodStr.toLowerCase();
            
            // Map database ENUM values to user-friendly display format
            const methodMap = {
                // Database ENUM values (exact matches)
                'cash': 'Cash',
                'check': 'Check',
                'bank_transfer': 'Bank Transfer',
                'credit_card': 'Credit Card',
                'online': 'Online Payment',
                'other': 'Other',
                
                // Common variations (for backward compatibility)
                'cheque': 'Check',
                'bank transfer': 'Bank Transfer',
                'banktransfer': 'Bank Transfer',
                'bank-transfer': 'Bank Transfer',
                'transfer': 'Bank Transfer',
                'bank': 'Bank Transfer',
                'credit card': 'Credit Card',
                'creditcard': 'Credit Card',
                'credit-card': 'Credit Card',
                'card': 'Credit Card',
                'online payment': 'Online Payment',
                'onlinepayment': 'Online Payment',
                'online-payment': 'Online Payment',
                'paypal': 'PayPal',
                'gcash': 'GCash',
                'maya': 'Maya',
                'paymaya': 'PayMaya'
            };
            
            // First, check exact match
            if (methodMap[normalizedMethod]) {
                return methodMap[normalizedMethod];
            }
            
            // If not found in map, try to match partial strings (contains)
            for (const key in methodMap) {
                if (normalizedMethod.includes(key) || key.includes(normalizedMethod)) {
                    return methodMap[key];
                }
            }
            
            // If not found, capitalize first letter of each word (fallback)
            return methodStr.split(/[\s_-]+/)
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' ');
        }
        
        function updateInvoice(apId, currentInvoice) {
            const invoiceNumber = prompt('Enter invoice number:', currentInvoice || '');
            if (invoiceNumber === null) return; // User cancelled
            
            const invoiceDate = prompt('Enter invoice date (YYYY-MM-DD):', '');
            if (invoiceDate === null) return; // User cancelled
            
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/update-invoice',
                method: 'POST',
                data: {
                    invoice_number: invoiceNumber || '',
                    invoice_date: invoiceDate || null
                },
                dataType: 'json',
                success: function(response) {
            if (response.status === 'success') {
                        alert('Invoice information updated successfully!');
                        loadAccountsPayable(); // Reload the list
            } else {
                        alert('Error: ' + (response.message || 'Failed to update invoice'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating invoice';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function loadReports() {
            console.log('loadReports called');
            const contentDiv = $('#reportsContent');
            if (!contentDiv.length) {
                console.error('reportsContent div not found!');
                return;
            }
            
            const month = $('#reportsMonthFilter').val() || new Date().toISOString().slice(0, 7);
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading reports...</p></div>');
            
            $.ajax({
                url: '<?= base_url('centraladmin/api/monthly-reports') ?>',
                method: 'GET',
                data: { month: month },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        renderReports(response.reports, month);
                    } else {
                        contentDiv.html('<div class="alert alert-danger">Error: ' + (response.message || 'Failed to load reports') + '</div>');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading reports:', xhr);
                    contentDiv.html('<div class="alert alert-danger">Error loading reports. Please try again.</div>');
                }
            });
        }
        
        function renderReports(reports, month) {
            const contentDiv = $('#reportsContent');
            const monthName = new Date(month + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            
            let html = '<div style="display: flex; flex-direction: column; gap: 24px;">';
            
            // Branch Manager Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #17a2b8;">';
            html += '<h4 style="color: #17a2b8; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-building"></i> Branch Manager Reports</h4>';
            
            // Sales Report
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-chart-line" style="color: #10b981; margin-right: 8px;"></i>Sales Report - ' + monthName + '</h5>';
            if (reports.branch_manager.sales && reports.branch_manager.sales.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Sales</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Transactions</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Avg Transaction</th></tr></thead><tbody>';
                let totalSales = 0;
                reports.branch_manager.sales.forEach(function(sale) {
                    totalSales += parseFloat(sale.total_sales || 0);
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (sale.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #10b981;">₱' + parseFloat(sale.total_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (sale.transaction_count || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">₱' + parseFloat(sale.avg_transaction || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>';
                });
                html += '<tr style="background: #f8fafc; font-weight: 700;"><td style="padding: 12px; border-top: 2px solid #e2e8f0;">Total</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0; color: #2d5016;">₱' + totalSales.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td><td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td></tr>';
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No sales data available for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Inventory Summary
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-warehouse" style="color: #3b82f6; margin-right: 8px;"></i>Inventory Summary - ' + monthName + '</h5>';
            if (reports.branch_manager.inventory && reports.branch_manager.inventory.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Items</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Value</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Low Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Expired</th></tr></thead><tbody>';
                reports.branch_manager.inventory.forEach(function(inv) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_stock || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #10b981;">₱' + parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.low_stock_items > 0 ? '#f59e0b' : '#64748b') + ';">' + (inv.low_stock_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.expired_items > 0 ? '#ef4444' : '#64748b') + ';">' + (inv.expired_items || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No inventory data available for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Product Details (Branch → Category → Product)
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-list" style="color: #6366f1; margin-right: 8px;"></i>Product Details - ' + monthName + '</h5>';
            if (reports.branch_manager.inventory_details && Object.keys(reports.branch_manager.inventory_details).length > 0) {
                // Flatten the nested structure for display
                let detailsHtml = '';
                Object.keys(reports.branch_manager.inventory_details).sort().forEach(function(branchName) {
                    detailsHtml += '<div style="margin-bottom: 20px;"><h6 style="color: #17a2b8; margin-bottom: 10px; font-weight: 600;">Branch: ' + branchName + '</h6>';
                    Object.keys(reports.branch_manager.inventory_details[branchName]).sort().forEach(function(categoryName) {
                        detailsHtml += '<div style="margin-bottom: 15px; margin-left: 20px;"><strong style="color: #64748b;">Category: ' + categoryName + '</strong>';
                        detailsHtml += '<div style="overflow-x: auto; margin-top: 8px;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0; font-size: 0.9em;">';
                        detailsHtml += '<thead style="background: #f8fafc;"><tr><th style="padding: 8px; border-bottom: 1px solid #e2e8f0;">Product</th><th style="padding: 8px; border-bottom: 1px solid #e2e8f0;">Total Stock</th><th style="padding: 8px; border-bottom: 1px solid #e2e8f0;">Total Value</th><th style="padding: 8px; border-bottom: 1px solid #e2e8f0;">Low Stock</th><th style="padding: 8px; border-bottom: 1px solid #e2e8f0;">Expired</th></tr></thead><tbody>';
                        reports.branch_manager.inventory_details[branchName][categoryName].forEach(function(product) {
                            detailsHtml += '<tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">' + (product.product_name || 'N/A') + '</td>';
                            detailsHtml += '<td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">' + (product.total_stock || 0) + '</td>';
                            detailsHtml += '<td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #10b981;">₱' + parseFloat(product.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                            detailsHtml += '<td style="padding: 8px; border-bottom: 1px solid #e2e8f0; color: ' + (product.is_low_stock ? '#f59e0b' : '#64748b') + ';">' + (product.is_low_stock ? 'Yes' : 'No') + '</td>';
                            detailsHtml += '<td style="padding: 8px; border-bottom: 1px solid #e2e8f0; color: ' + (product.is_expired ? '#ef4444' : '#64748b') + ';">' + (product.is_expired ? 'Yes' : 'No') + '</td></tr>';
                        });
                        detailsHtml += '</tbody></table></div></div>';
                    });
                    detailsHtml += '</div>';
                });
                html += detailsHtml;
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No product details available for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Damage Products Report
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 8px;"></i>Damage Products Report - ' + monthName + '</h5>';
            if (reports.branch_manager.damage_products && reports.branch_manager.damage_products.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #fef2f2;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Product</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Category</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Damaged</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Incidents</th></tr></thead><tbody>';
                reports.branch_manager.damage_products.forEach(function(damage) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.product_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.category || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #ef4444; font-weight: 600;">' + (damage.total_damaged || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.damage_count || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No damaged products reported for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            // Inventory Staff Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">';
            html += '<h4 style="color: #10b981; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-boxes"></i> Inventory Staff Reports</h4>';
            
            // Inventory Summary
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-warehouse" style="color: #3b82f6; margin-right: 8px;"></i>Inventory Summary - ' + monthName + '</h5>';
            if (reports.inventory_staff.inventory && reports.inventory_staff.inventory.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Items</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Value</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Low Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Expired</th></tr></thead><tbody>';
                reports.inventory_staff.inventory.forEach(function(inv) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_stock || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #2d5016;">₱' + parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.low_stock_items > 0 ? '#ef4444' : '#10b981') + ';">' + (inv.low_stock_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.expired_items > 0 ? '#ef4444' : '#10b981') + ';">' + (inv.expired_items || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No inventory data available</p>';
            }
            html += '</div>';
            
            // Damage Products Report (Inventory Staff)
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 8px;"></i>Damage Products Report - ' + monthName + '</h5>';
            if (reports.inventory_staff.damage_products && reports.inventory_staff.damage_products.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #fef2f2;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Product</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Category</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Damaged</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Incidents</th></tr></thead><tbody>';
                reports.inventory_staff.damage_products.forEach(function(damage) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.product_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.category || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #ef4444; font-weight: 600;">' + (damage.total_damaged || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.damage_count || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No damaged products reported for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            // Franchise Manager Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #8b5cf6;">';
            html += '<h4 style="color: #8b5cf6; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-handshake"></i> Franchise Manager Reports</h4>';
            
            // Applications
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-file-alt" style="color: #6366f1; margin-right: 8px;"></i>Franchise Applications - ' + monthName + '</h5>';
            if (reports.franchise_manager.applications && reports.franchise_manager.applications.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Status</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Date</th></tr></thead><tbody>';
                reports.franchise_manager.applications.forEach(function(app) {
                    const statusColor = app.status === 'approved' ? '#10b981' : app.status === 'rejected' ? '#ef4444' : '#f59e0b';
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (app.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;"><span style="color: ' + statusColor + '; font-weight: 600;">' + (app.status || 'N/A').toUpperCase() + '</span></td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (app.created_at ? new Date(app.created_at).toLocaleDateString() : 'N/A') + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No franchise applications for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Royalties
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-money-bill-wave" style="color: #f59e0b; margin-right: 8px;"></i>Royalty Payments - ' + monthName + '</h5>';
            if (reports.franchise_manager.royalties && reports.franchise_manager.royalties.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Gross Sales</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Royalty Amount</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Due</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Status</th></tr></thead><tbody>';
                let totalRoyalties = 0;
                reports.franchise_manager.royalties.forEach(function(royalty) {
                    totalRoyalties += parseFloat(royalty.total_due || 0);
                    const statusColor = royalty.status === 'paid' ? '#10b981' : royalty.status === 'overdue' ? '#ef4444' : '#f59e0b';
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (royalty.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">₱' + parseFloat(royalty.gross_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">₱' + parseFloat(royalty.royalty_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">₱' + parseFloat(royalty.total_due || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;"><span style="color: ' + statusColor + '; font-weight: 600;">' + (royalty.status || 'N/A').toUpperCase() + '</span></td></tr>';
                });
                html += '<tr style="background: #f8fafc; font-weight: 700;"><td style="padding: 12px; border-top: 2px solid #e2e8f0;">Total</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td><td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0; color: #2d5016;">₱' + totalRoyalties.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td></tr>';
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No royalty payments for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            // Logistics Coordinator Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">';
            html += '<h4 style="color: #f59e0b; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-truck"></i> Logistics Coordinator Reports</h4>';
            
            // Delivery Summary
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-shipping-fast" style="color: #3b82f6; margin-right: 8px;"></i>Delivery Summary - ' + monthName + '</h5>';
            if (reports.logistics_coordinator.delivery_summary && reports.logistics_coordinator.delivery_summary.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Deliveries</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Completed</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Pending</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">In Transit</th></tr></thead><tbody>';
                reports.logistics_coordinator.delivery_summary.forEach(function(summary) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (summary.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">' + (summary.total_deliveries || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #10b981;">' + (summary.completed_deliveries || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #f59e0b;">' + (summary.pending_deliveries || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #3b82f6;">' + (summary.in_transit_deliveries || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No delivery data for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            html += '</div>';
            
            contentDiv.html(html);
        }
        
        function printAllReports() {
            const month = $('#reportsMonthFilter').val() || new Date().toISOString().slice(0, 7);
            const monthName = new Date(month + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            
            // Fetch reports data for printing
            $.ajax({
                url: '<?= base_url('centraladmin/api/monthly-reports') ?>',
                method: 'GET',
                data: { month: month },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        generatePrintReport(response.reports, monthName);
                    } else {
                        alert('Error: ' + (response.message || 'Failed to load reports'));
                    }
                },
                error: function() {
                    alert('Error loading reports for printing');
                }
            });
        }
        
        function generatePrintReport(reports, monthName) {
            const printWindow = window.open('', '_blank');
            const reportHTML = generateReportHTML(reports, monthName);
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            printWindow.print();
        }
        
        function generateReportHTML(reports, monthName) {
            return `<!DOCTYPE html>
<html>
<head>
    <title>CHAKANOKS - Comprehensive Monthly Report - ${monthName}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #2d5016; padding-bottom: 20px; }
        .header h1 { color: #2d5016; margin: 0; }
        .section { margin-bottom: 40px; page-break-inside: avoid; }
        .section-title { background: #2d5016; color: white; padding: 10px; font-weight: bold; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; font-weight: bold; }
        .total-row { background: #f8f9fa; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHAKANOKS SUPPLY CHAIN MANAGEMENT SYSTEM</h1>
        <h2>Comprehensive Monthly Report</h2>
        <p><strong>Report Period: ${monthName}</strong></p>
        <p>Generated: ${new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
    </div>
    
    ${generateSectionHTML('Branch Manager Reports', reports.branch_manager, monthName)}
    ${generateSectionHTML('Inventory Staff Reports', reports.inventory_staff, monthName)}
    ${generateSectionHTML('Franchise Manager Reports', reports.franchise_manager, monthName)}
    ${generateSectionHTML('Logistics Coordinator Reports', reports.logistics_coordinator, monthName)}
</body>
</html>`;
        }
        
        function generateSectionHTML(title, data, monthName) {
            let html = `<div class="section"><div class="section-title">${title} - ${monthName}</div>`;
            
            if (title.includes('Branch Manager')) {
                // Sales Report
                if (data.sales && data.sales.length > 0) {
                    html += '<h3>Sales Report</h3><table><tr><th>Branch</th><th>Total Sales</th><th>Transactions</th><th>Avg Transaction</th></tr>';
                    data.sales.forEach(sale => {
                        html += `<tr><td>${sale.branch_name || 'N/A'}</td><td>₱${parseFloat(sale.total_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${sale.transaction_count || 0}</td><td>₱${parseFloat(sale.avg_transaction || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Damage Products
                if (data.damage_products && data.damage_products.length > 0) {
                    html += '<h3>Damage Products Report</h3><table><tr><th>Branch</th><th>Product</th><th>Category</th><th>Total Damaged</th><th>Incidents</th></tr>';
                    data.damage_products.forEach(damage => {
                        html += `<tr><td>${damage.branch_name || 'N/A'}</td><td>${damage.product_name || 'N/A'}</td><td>${damage.category || 'N/A'}</td><td>${damage.total_damaged || 0}</td><td>${damage.damage_count || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
            } else if (title.includes('Inventory Staff')) {
                // Inventory Summary
                if (data.inventory && data.inventory.length > 0) {
                    html += '<h3>Inventory Summary</h3><table><tr><th>Branch</th><th>Total Items</th><th>Total Stock</th><th>Total Value</th><th>Low Stock</th><th>Expired</th></tr>';
                    data.inventory.forEach(inv => {
                        html += `<tr><td>${inv.branch_name || 'N/A'}</td><td>${inv.total_items || 0}</td><td>${inv.total_stock || 0}</td><td>₱${parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${inv.low_stock_items || 0}</td><td>${inv.expired_items || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Damage Products
                if (data.damage_products && data.damage_products.length > 0) {
                    html += '<h3>Damage Products Report</h3><table><tr><th>Branch</th><th>Product</th><th>Category</th><th>Total Damaged</th><th>Incidents</th></tr>';
                    data.damage_products.forEach(damage => {
                        html += `<tr><td>${damage.branch_name || 'N/A'}</td><td>${damage.product_name || 'N/A'}</td><td>${damage.category || 'N/A'}</td><td>${damage.total_damaged || 0}</td><td>${damage.damage_count || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
            } else if (title.includes('Franchise Manager')) {
                // Applications
                if (data.applications && data.applications.length > 0) {
                    html += '<h3>Franchise Applications</h3><table><tr><th>Branch</th><th>Status</th><th>Date</th></tr>';
                    data.applications.forEach(app => {
                        html += `<tr><td>${app.branch_name || 'N/A'}</td><td>${(app.status || 'N/A').toUpperCase()}</td><td>${app.created_at ? new Date(app.created_at).toLocaleDateString() : 'N/A'}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Royalties
                if (data.royalties && data.royalties.length > 0) {
                    html += '<h3>Royalty Payments</h3><table><tr><th>Branch</th><th>Gross Sales</th><th>Royalty Amount</th><th>Total Due</th><th>Status</th></tr>';
                    data.royalties.forEach(royalty => {
                        html += `<tr><td>${royalty.branch_name || 'N/A'}</td><td>₱${parseFloat(royalty.gross_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>₱${parseFloat(royalty.royalty_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>₱${parseFloat(royalty.total_due || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${(royalty.status || 'N/A').toUpperCase()}</td></tr>`;
                    });
                    html += '</table>';
                }
            } else if (title.includes('Logistics Coordinator')) {
                // Delivery Summary
                if (data.delivery_summary && data.delivery_summary.length > 0) {
                    html += '<h3>Delivery Summary</h3><table><tr><th>Branch</th><th>Total Deliveries</th><th>Completed</th><th>Pending</th><th>In Transit</th></tr>';
                    data.delivery_summary.forEach(summary => {
                        html += `<tr><td>${summary.branch_name || 'N/A'}</td><td>${summary.total_deliveries || 0}</td><td>${summary.completed_deliveries || 0}</td><td>${summary.pending_deliveries || 0}</td><td>${summary.in_transit_deliveries || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
            }
            
            html += '</div>';
            return html;
        }
        
        // Load reports on page load if reports tab is active
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab === 'reports') {
                loadReports();
            }
            
            // Auto-load when month filter changes
            $('#reportsMonthFilter').on('change', function() {
                loadReports();
            });
        });

        // Filter change handlers
        $(document).on('change', '#apStatusFilter', function() {
            loadAccountsPayable();
        });
        
        $(document).on('change', '#apInvoiceFilter', function() {
            loadAccountsPayable();
        });
        
        // Initialize dashboard
        $(document).ready(function() {
            // Initialize month filter with current month
            const today = new Date();
            const monthStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            
            $('#receiptMonthFilter').val(monthStr);
            
            // Show/hide sections based on active tab (Like System Administrator)
            const activeTab = '<?= $activeTab ?? 'dashboard' ?>';
            
            // Hide all sections first
            $('.content-section').removeClass('active').hide();
            
            // Show the appropriate section based on active tab
            if (activeTab === 'dashboard') {
                $('#dashboardSection').addClass('active').show();
            } else if (activeTab === 'purchaseRequests') {
                $('#purchaseRequestsSection').addClass('active').show();
            } else if (activeTab === 'purchaseOrders') {
                $('#purchaseOrdersSection').addClass('active').show();
            } else if (activeTab === 'deliveries') {
                $('#deliveriesSection').addClass('active').show();
            } else if (activeTab === 'suppliers') {
                $('#suppliersSection').addClass('active').show();
            } else if (activeTab === 'accountsPayable') {
                $('#accountsPayableSection').addClass('active').show();
            } else if (activeTab === 'reports') {
                $('#reportsSection').addClass('active').show();
            }
            
            // Load tab data for sections
            loadTabData();
            
            // Filter change handlers
            $(document).on('change', '#requestStatusFilter, #requestPriorityFilter', function() {
                loadPendingRequests();
            });
        });
</script>
    
    <!-- Request Details Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="requestModalLabel">Purchase Request Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="requestModalBody">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="orderModalLabel">Purchase Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderModalBody">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Accounts Payable Details Modal -->
    <div class="modal fade" id="apModal" tabindex="-1" aria-labelledby="apModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="apModalLabel">Accounts Payable Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="apModalBody">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Accounts Payable Summary Modal -->
    <div class="modal fade" id="apSummaryModal" tabindex="-1" aria-labelledby="apSummaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="apSummaryModalLabel">Accounts Payable Summary</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="apSummaryModalBody">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delivery Details Modal -->
    <div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="deliveryModalLabel">
                        <i class="fas fa-truck"></i> Delivery Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="deliveryModalBody">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delivery Details Modal for Approval -->
    <div class="modal fade" id="deliveryDetailsModal" tabindex="-1" aria-labelledby="deliveryDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="deliveryDetailsModalLabel">
                        <i class="fas fa-truck"></i> Set Delivery Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3" style="color: #666;">Please provide delivery details before approving this purchase request. These details will be used when creating the delivery record.</p>
                    <form id="deliveryDetailsForm">
                        <input type="hidden" id="approveRequestId" name="request_id">
                        <div class="mb-3">
                            <label for="deliveryScheduledDate" class="form-label">Scheduled Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="deliveryScheduledDate" name="scheduled_delivery_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="deliveryDriverName" class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="deliveryDriverName" name="driver_name" placeholder="Enter driver name (optional)">
                        </div>
                        <div class="mb-3">
                            <label for="deliveryVehicleInfo" class="form-label">Vehicle Information</label>
                            <input type="text" class="form-control" id="deliveryVehicleInfo" name="vehicle_info" placeholder="Enter vehicle info (e.g., Plate #, Model) (optional)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitApprovalBtn" onclick="submitApprovalWithDeliveryDetails()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                        <i class="fas fa-check"></i> Approve & Create PO
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="fas fa-receipt"></i> Payment Receipt
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiptModalBody" style="padding: 0;">
                    <!-- Receipt content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #receiptContent, #receiptContent * {
                visibility: visible;
            }
            #receiptContent {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        
        .receipt-container {
            padding: 30px;
            background: white;
            font-family: 'Arial', sans-serif;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 3px solid #2d5016;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .receipt-header h2 {
            color: #2d5016;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .receipt-header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        
        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .receipt-info-section h4 {
            color: #2d5016;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .receipt-info-section p {
            margin: 5px 0;
            color: #333;
            font-size: 14px;
        }
        
        .receipt-details {
            margin: 30px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .receipt-details th {
            background: #2d5016;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .receipt-details td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .receipt-details tr:last-child td {
            border-bottom: none;
        }
        
        .receipt-totals {
            margin-top: 20px;
            text-align: right;
        }
        
        .receipt-totals table {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
        }
        
        .receipt-totals td {
            padding: 8px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .receipt-totals .total-row {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 16px;
        }
        
        .receipt-totals .total-label {
            text-align: right;
            color: #2d5016;
        }
        
        .receipt-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .payment-status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-partial {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>

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
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white; padding: 20px 24px; border-bottom: none;">
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
                            style="border-radius: 8px; padding: 10px 24px; font-weight: 600; background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none; box-shadow: 0 4px 12px rgba(45, 80, 22, 0.3); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(45, 80, 22, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(45, 80, 22, 0.3)'">
                        <i class="fas fa-save" style="margin-right: 6px;"></i>Update User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // User Management Pagination
        const userPaginationState = {
            active: { data: <?= json_encode($activeUsers ?? []) ?>, currentPage: 1 },
            deleted: { data: <?= json_encode($deletedUsers ?? []) ?>, currentPage: 1 }
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
                            const isProtected = user.role === 'system_admin' || (user.role === 'central_admin' && (user.branch_name || '') === 'Central Office');
                            if (isProtected) {
                                html += '<span style="color: #64748b; font-size: 0.875rem; font-style: italic;">Protected</span>';
                            } else {
                                html += '<button class="btn-action btn-delete" onclick="deleteUser(' + user.id + ')" style="background: #dc3545; color: white;"><i class="fas fa-trash"></i> Delete</button>';
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
            <?php if (!empty($deletedUsers)): ?>
            renderUsers('deleted');
            <?php endif; ?>
        });
        
        // User Management Functions
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
            
            fetch('<?= base_url('centraladmin/create-user') ?>', {
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
            fetch('<?= base_url('centraladmin/get-user/') ?>' + userId)
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
            
            fetch('<?= base_url('centraladmin/update-user/') ?>' + userId, {
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
            
            fetch('<?= base_url('centraladmin/delete-user/') ?>' + userId, {
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
            
            fetch('<?= base_url('centraladmin/restore-user/') ?>' + userId, {
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
