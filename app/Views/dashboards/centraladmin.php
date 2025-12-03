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
                $isDashboard = strpos($currentUrl, 'centraladmin/dashboard') !== false && strpos($currentUrl, '?tab=') === false;
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
                <div class="header-right">
                    <button class="btn btn-secondary" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                    <span class="refresh-indicator" id="lastRefresh" style="font-size: 0.8rem; color: #6b7280;">Last updated: Just now</span>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Dashboard Content -->
                <div class="tab-content active" id="dashboardSection" style="display: block !important;">
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

                <!-- Purchase Requests Tab -->
                <div class="tab-content <?= $activeTab === 'purchaseRequests' ? 'active' : '' ?>" id="purchaseRequestsSection" style="<?= $activeTab === 'purchaseRequests' ? 'display: block !important;' : 'display: none !important;' ?>">
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
                <div class="tab-content <?= $activeTab === 'purchaseOrders' ? 'active' : '' ?>" id="purchaseOrdersSection" style="<?= $activeTab === 'purchaseOrders' ? 'display: block !important;' : 'display: none !important;' ?>">
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
                <div class="tab-content <?= $activeTab === 'deliveries' ? 'active' : '' ?>" id="deliveriesSection" style="<?= $activeTab === 'deliveries' ? 'display: block !important;' : 'display: none !important;' ?>">
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
                <div class="tab-content <?= $activeTab === 'suppliers' ? 'active' : '' ?>" id="suppliersSection" style="<?= $activeTab === 'suppliers' ? 'display: block !important;' : 'display: none !important;' ?>">
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
                <div class="tab-content <?= $activeTab === 'accountsPayable' ? 'active' : '' ?>" id="accountsPayableSection" style="<?= $activeTab === 'accountsPayable' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Accounts Payable</h3>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
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
                <div class="tab-content <?= $activeTab === 'reports' ? 'active' : '' ?>" id="reportsSection" style="<?= $activeTab === 'reports' ? 'display: block !important;' : 'display: none !important;' ?>">
                    <div class="content-card">
                        <div class="card-header" style="margin-bottom: 20px;">
                            <h3 class="card-title" style="margin: 0;">Reports</h3>
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
                                const amount = parseFloat(ap.amount || 0);
                                const paidAmount = parseFloat(ap.paid_amount || 0);
                                const balance = parseFloat(ap.balance || 0);
                                const dueDate = ap.due_date ? new Date(ap.due_date) : null;
                                const daysInfo = ap.days_info || '';
                                const paymentDate = ap.payment_date ? new Date(ap.payment_date) : null;
                                const paymentMethod = ap.payment_method || '';
                                const paymentReference = ap.payment_reference || '';
                                
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
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (paymentMethod || '<span style="color: #999; font-style: italic;">-</span>') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (paymentReference || '<span style="color: #999; font-style: italic;">-</span>') + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (dueDate ? dueDate.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'N/A') + '<br><small style="color: ' + (status === 'overdue' ? '#dc3545' : '#6c757d') + ';">' + daysInfo + '</small></td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><div style="display: flex; gap: 5px; flex-wrap: wrap;">';
                                html += '<button class="btn btn-sm btn-info viewAPBtn" data-id="' + ap.id + '" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-eye"></i> View</button>';
                                
                                // Add payment button for unpaid/partial/overdue
                                if (status !== 'paid') {
                                    html += ' <button class="btn btn-sm btn-success recordPaymentBtn" data-id="' + ap.id + '" data-balance="' + balance + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px;"><i class="fas fa-money-bill-wave"></i> Pay</button>';
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
                        html += '<div><strong>Payment Method:</strong><br>' + (ap.payment_method || 'N/A') + '</div>';
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
                        alert('Payment recorded successfully!');
                        loadAccountsPayable(); // Reload the list
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
            
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading reports...</p></div>');
            
            // For now, show a placeholder with useful information
            setTimeout(function() {
                let html = '<div class="content-card" style="padding: 40px; text-align: center;">';
                html += '<i class="fas fa-chart-bar fa-4x" style="color: #2d5016; margin-bottom: 20px; opacity: 0.3;"></i>';
                html += '<h4 style="color: #2d5016; margin-bottom: 10px;">Reports Dashboard</h4>';
                html += '<p style="color: #666; margin-bottom: 30px;">Comprehensive reporting features coming soon</p>';
                html += '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">';
                html += '<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e5e7eb;"><h5 style="color: #2d5016;">Inventory Reports</h5><p style="color: #666; font-size: 0.9rem;">Stock levels, turnover, and trends</p></div>';
                html += '<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e5e7eb;"><h5 style="color: #2d5016;">Purchase Reports</h5><p style="color: #666; font-size: 0.9rem;">Purchase requests and orders analysis</p></div>';
                html += '<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e5e7eb;"><h5 style="color: #2d5016;">Supplier Reports</h5><p style="color: #666; font-size: 0.9rem;">Performance and delivery metrics</p></div>';
                html += '<div style="padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e5e7eb;"><h5 style="color: #2d5016;">Financial Reports</h5><p style="color: #666; font-size: 0.9rem;">Accounts payable and expenses</p></div>';
                html += '</div>';
                html += '</div>';
                
                if (contentDiv.length) {
                    contentDiv.html(html);
                    console.log('Reports content rendered successfully');
                }
            }, 500);
        }

        // Filter change handlers
        $(document).on('change', '#apStatusFilter', function() {
            loadAccountsPayable();
        });
        
        $(document).on('change', '#apInvoiceFilter', function() {
            loadAccountsPayable();
        });
        
        // Initialize
        $(document).ready(function() {
            console.log('=== DASHBOARD INITIALIZATION START ===');
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            
            // Load data for active tab
            loadTabData();
            
            // Filter change handlers
            $(document).on('change', '#requestStatusFilter, #requestPriorityFilter', function() {
                console.log('Filter changed, reloading requests');
                loadPendingRequests();
            });
            
            console.log('=== DASHBOARD INITIALIZATION COMPLETE ===');
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
</body>
</html>
