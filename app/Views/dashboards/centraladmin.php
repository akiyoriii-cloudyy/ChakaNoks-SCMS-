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
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .nav-tabs { border-bottom: none; background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%); border-radius: 12px; padding: 8px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12); margin-bottom: 24px; }
        .nav-tabs .nav-link { border: none; border-radius: 8px; color: #6b7280; font-weight: 600; padding: 12px 20px; transition: all 0.3s ease; }
        .nav-tabs .nav-link:hover { background: rgba(45, 80, 22, 0.08); color: #2d5016; transform: translateY(-2px); }
        .nav-tabs .nav-link.active { background: linear-gradient(135deg, #2d5016, #4a7c2a); color: #f9fafb; box-shadow: 0 10px 25px rgba(45, 80, 22, 0.3); }
        
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
                <?php $activeTab = $activeTab ?? 'dashboard'; ?>
                <a href="<?= base_url('centraladmin/dashboard?tab=dashboard') ?>" class="nav-item <?= $activeTab === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('centraladmin/dashboard?tab=purchaseRequests') ?>" class="nav-item <?= $activeTab === 'purchaseRequests' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                    <?php if (($data['purchaseRequests']['pending_approvals'] ?? 0) > 0): ?>
                        <span class="badge badge-danger" style="margin-left: auto; background: rgba(239, 68, 68, 0.2); color: #ef4444;"><?= $data['purchaseRequests']['pending_approvals'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= base_url('centraladmin/dashboard?tab=purchaseOrders') ?>" class="nav-item <?= $activeTab === 'purchaseOrders' ? 'active' : '' ?>">
                    <i class="fas fa-file-invoice"></i>
                    <span>Purchase Orders</span>
                </a>
                <a href="<?= base_url('centraladmin/dashboard?tab=deliveries') ?>" class="nav-item <?= $activeTab === 'deliveries' ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="<?= base_url('centraladmin/dashboard?tab=suppliers') ?>" class="nav-item <?= $activeTab === 'suppliers' ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Suppliers</span>
                </a>
                <a href="<?= base_url('centraladmin/dashboard?tab=accountsPayable') ?>" class="nav-item <?= $activeTab === 'accountsPayable' ? 'active' : '' ?>">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Accounts Payable</span>
                </a>
                <a href="<?= base_url('centraladmin/dashboard?tab=reports') ?>" class="nav-item <?= $activeTab === 'reports' ? 'active' : '' ?>">
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
                <!-- Tab Navigation -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="#" data-tab="dashboard" onclick="goToDashboard(); return false;">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'purchaseRequests' ? 'active' : '' ?>" href="#" data-tab="purchaseRequests" onclick="showPendingRequests(); return false;">
                <i class="fas fa-shopping-cart"></i> Purchase Requests
                            <?php if (($data['purchaseRequests']['pending_approvals'] ?? 0) > 0): ?>
                    <span class="badge bg-danger"><?= $data['purchaseRequests']['pending_approvals'] ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'purchaseOrders' ? 'active' : '' ?>" href="#" data-tab="purchaseOrders" onclick="showPurchaseOrders(); return false;">
                <i class="fas fa-file-invoice"></i> Purchase Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'deliveries' ? 'active' : '' ?>" href="#" data-tab="deliveries" onclick="showDeliveries(); return false;">
                <i class="fas fa-truck"></i> Deliveries
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'suppliers' ? 'active' : '' ?>" href="#" data-tab="suppliers" onclick="showSuppliers(); return false;">
                <i class="fas fa-building"></i> Suppliers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'accountsPayable' ? 'active' : '' ?>" href="#" data-tab="accountsPayable" onclick="showAccountsPayable(); return false;">
                <i class="fas fa-money-check-alt"></i> Accounts Payable
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'reports' ? 'active' : '' ?>" href="#" data-tab="reports" onclick="showReports(); return false;">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
    </ul>

                <!-- Dashboard Tab -->
                <div class="tab-content <?= $activeTab === 'dashboard' ? 'active' : '' ?>" id="dashboardSection">
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

                <!-- Other Tab Contents (Purchase Requests, Orders, etc.) -->
                <div class="tab-content <?= $activeTab === 'purchaseRequests' ? 'active' : '' ?>" id="purchaseRequestsSection" style="display: none;">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Purchase Requests</h3>
                            <div style="display: flex; gap: 10px;">
                                <select id="requestStatusFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.2); color: white;">
                                    <option value="all">All Requests</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="converted_to_po">Converted to PO</option>
                                </select>
                                <select id="requestPriorityFilter" class="form-select" style="width: auto; min-width: 120px; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.2); color: white;">
                                    <option value="all">All Priorities</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="high">High</option>
                                    <option value="normal">Normal</option>
                                    <option value="low">Low</option>
                        </select>
                </div>
                </div>
                        <div id="pendingRequestsContent" style="padding: 20px;">
                            <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>

                <div class="tab-content <?= $activeTab === 'purchaseOrders' ? 'active' : '' ?>" id="purchaseOrdersSection" style="display: none;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">Purchase Orders</h3>
                </div>
                        <div id="purchaseOrdersContent">
                            <!-- Content loaded via JavaScript -->
                    </div>
                    </div>
                    </div>

                <div class="tab-content <?= $activeTab === 'deliveries' ? 'active' : '' ?>" id="deliveriesSection" style="display: none;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">Deliveries</h3>
                    </div>
                        <div id="deliveriesContent">
                            <!-- Content loaded via JavaScript -->
                    </div>
                        </div>
                        </div>

                <div class="tab-content <?= $activeTab === 'suppliers' ? 'active' : '' ?>" id="suppliersSection" style="display: none;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">Suppliers</h3>
                    </div>
                        <div id="suppliersContent">
                            <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>

                <div class="tab-content <?= $activeTab === 'accountsPayable' ? 'active' : '' ?>" id="accountsPayableSection" style="display: <?= $activeTab === 'accountsPayable' ? 'block' : 'none' ?>;">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">Accounts Payable</h3>
                            <div style="display: flex; gap: 10px;">
                                <select id="apStatusFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.2); color: white;">
                                    <option value="all">All Status</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="partial">Partial</option>
                                    <option value="paid">Paid</option>
                                    <option value="overdue">Overdue</option>
                        </select>
                                <button class="btn btn-sm btn-info" onclick="backfillAccountsPayable()" style="background: #17a2b8; border: none; padding: 8px 16px; border-radius: 6px; color: white;">
                                    <i class="fas fa-sync"></i> Backfill
                                </button>
                                <button class="btn btn-sm btn-primary" onclick="loadAccountsPayableSummary()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none; padding: 8px 16px; border-radius: 6px; color: white;">
                                    <i class="fas fa-chart-line"></i> Summary
                                </button>
                    </div>
                            </div>
                        <div id="accountsPayableContent" style="padding: 20px;">
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                            </div>
                                <p class="mt-2">Loading accounts payable...</p>
                            </div>
                            </div>
                        </div>
                    </div>

                <div class="tab-content <?= $activeTab === 'reports' ? 'active' : '' ?>" id="reportsSection" style="display: none;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">Reports</h3>
                    </div>
                        <div id="reportsContent">
                            <!-- Content loaded via JavaScript -->
                        </div>
                    </div>
                </div>
                </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        // Tab switching
        function goToDashboard() {
            $('.tab-content').removeClass('active').hide();
            $('#dashboardSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="dashboard"]').addClass('active');
        }

        function showPendingRequests() {
            $('.tab-content').removeClass('active').hide();
            $('#purchaseRequestsSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="purchaseRequests"]').addClass('active');
            loadPendingRequests();
        }

        function showPurchaseOrders() {
            $('.tab-content').removeClass('active').hide();
            $('#purchaseOrdersSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="purchaseOrders"]').addClass('active');
            loadPurchaseOrders();
        }

        function showDeliveries() {
            $('.tab-content').removeClass('active').hide();
            $('#deliveriesSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="deliveries"]').addClass('active');
            loadDeliveries();
        }

        function showSuppliers() {
            $('.tab-content').removeClass('active').hide();
            $('#suppliersSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="suppliers"]').addClass('active');
            loadSuppliers();
        }

        function showAccountsPayable() {
            $('.tab-content').removeClass('active').hide();
            $('#accountsPayableSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="accountsPayable"]').addClass('active');
            loadAccountsPayable();
        }

        function showReports() {
            $('.tab-content').removeClass('active').hide();
            $('#reportsSection').addClass('active').show();
            $('.nav-link').removeClass('active');
            $('[data-tab="reports"]').addClass('active');
            loadReports();
        }

        function refreshDashboard() {
            location.reload();
        }

        function loadPendingRequests() {
            const statusFilter = $('#requestStatusFilter').val() || 'all';
            const priorityFilter = $('#requestPriorityFilter').val() || 'all';
            
            $('#pendingRequestsContent').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading purchase requests...</p></div>');
            
            let url = '<?= base_url('purchase/request/api/list') ?>';
            const params = [];
            if (statusFilter !== 'all') params.push('status=' + encodeURIComponent(statusFilter));
            if (priorityFilter !== 'all') params.push('priority=' + encodeURIComponent(priorityFilter));
            if (params.length > 0) url += '?' + params.join('&');
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.requests) {
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
            $('#pendingRequestsContent').html(html);
                        
                        // Attach event handlers
                        attachRequestHandlers();
                    } else {
                        $('#pendingRequestsContent').html('<div class="alert alert-info" style="padding: 20px; border-radius: 8px; background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;">No purchase requests found</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading requests:', error);
                    $('#pendingRequestsContent').html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">Error loading purchase requests. Please try again.</div>');
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
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId + '/approve',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
            if (response.status === 'success') {
                        alert('Purchase request approved successfully! Purchase Order created.');
                        loadPendingRequests(); // Reload the list
            } else {
                        alert('Error: ' + (response.message || 'Failed to approve request'));
                    }
                },
                error: function(xhr) {
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
            $('#purchaseOrdersContent').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading purchase orders...</p></div>');
            
            $.ajax({
                url: '<?= base_url('purchase/order/list') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.orders) {
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
            $('#purchaseOrdersContent').html(html);
                        
                        // Attach event handlers
                        attachOrderHandlers();
        } else {
                        $('#purchaseOrdersContent').html('<div class="alert alert-info" style="padding: 20px; border-radius: 8px; background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;">No purchase orders found</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading orders:', error);
                    $('#purchaseOrdersContent').html('<div class="alert alert-danger" style="padding: 20px; border-radius: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">Error loading purchase orders. Please try again.</div>');
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
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        html += '<div><strong>Order Number:</strong><br>' + (order.order_number || 'N/A') + '</div>';
                        html += '<div><strong>Supplier:</strong><br>' + (order.supplier ? order.supplier.name : 'N/A') + '</div>';
                        html += '<div><strong>Branch:</strong><br>' + (order.branch ? order.branch.name : 'N/A') + '</div>';
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
                                html += '<tr>';
                                html += '<td>' + productName + '</td>';
                                html += '<td>' + item.quantity + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                                html += '<td>₱' + parseFloat(item.unit_price || 0).toFixed(2) + '</td>';
                                html += '<td>₱' + parseFloat(item.subtotal || 0).toFixed(2) + '</td>';
                                html += '<td>' + (item.received_quantity || 0) + '</td>';
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
            $('#deliveriesContent').html('<div class="text-center p-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            $.ajax({
                url: '<?= base_url('centraladmin/deliveries/list') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.deliveries) {
                        let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Delivery ID</th><th>Supplier</th><th>Branch</th><th>Status</th><th>Scheduled Date</th><th>Actual Date</th></tr></thead><tbody>';
                        
                        if (response.deliveries.length === 0) {
                            html += '<tr><td colspan="6" class="text-center text-muted">No deliveries found</td></tr>';
            } else {
                            response.deliveries.forEach(function(delivery) {
                                const statusClass = delivery.status === 'delivered' ? 'success' : delivery.status === 'in_transit' ? 'info' : delivery.status === 'scheduled' ? 'warning' : 'secondary';
                                html += '<tr>';
                                html += '<td><strong>#' + delivery.id + '</strong></td>';
                                html += '<td>' + (delivery.supplier_name || 'N/A') + '</td>';
                                html += '<td>' + (delivery.branch_name || 'N/A') + '</td>';
                                html += '<td><span class="badge bg-' + statusClass + '">' + (delivery.status || 'pending') + '</span></td>';
                                html += '<td>' + (delivery.scheduled_date ? new Date(delivery.scheduled_date).toLocaleDateString() : 'N/A') + '</td>';
                                html += '<td>' + (delivery.actual_delivery_date ? new Date(delivery.actual_delivery_date).toLocaleDateString() : '-') + '</td>';
                                html += '</tr>';
                            });
            }
            
            html += '</tbody></table></div>';
                        $('#deliveriesContent').html(html);
        } else {
                        $('#deliveriesContent').html('<div class="alert alert-info">No deliveries found</div>');
                    }
                },
                error: function() {
                    $('#deliveriesContent').html('<div class="alert alert-danger">Error loading deliveries</div>');
                }
            });
        }

        function loadSuppliers() {
            $('#suppliersContent').html('<div class="text-center p-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            $.ajax({
                url: '<?= base_url('supplier/performance') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.suppliers) {
                        let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Supplier Name</th><th>Contact</th><th>Status</th><th>Total Deliveries</th><th>On-Time Rate</th><th>Delayed</th></tr></thead><tbody>';
                        
                        if (response.suppliers.length === 0) {
                            html += '<tr><td colspan="6" class="text-center text-muted">No suppliers found</td></tr>';
            } else {
                            response.suppliers.forEach(function(supplier) {
                                const statusClass = supplier.status === 'active' ? 'success' : 'secondary';
                                html += '<tr>';
                                html += '<td><strong>' + (supplier.name || 'N/A') + '</strong></td>';
                                html += '<td>' + (supplier.phone || supplier.email || 'N/A') + '</td>';
                                html += '<td><span class="badge bg-' + statusClass + '">' + (supplier.status || 'inactive') + '</span></td>';
                                html += '<td>' + (supplier.total_deliveries || 0) + '</td>';
                                html += '<td><span class="badge bg-' + (supplier.on_time_rate >= 80 ? 'success' : supplier.on_time_rate >= 60 ? 'warning' : 'danger') + '">' + (supplier.on_time_rate || 0) + '%</span></td>';
                                html += '<td>' + (supplier.delayed_deliveries || 0) + '</td>';
                                html += '</tr>';
                            });
                        }
                        
                        html += '</tbody></table></div>';
                        $('#suppliersContent').html(html);
            } else {
                        $('#suppliersContent').html('<div class="alert alert-info">No suppliers found</div>');
                    }
                },
                error: function() {
                    $('#suppliersContent').html('<div class="alert alert-danger">Error loading suppliers</div>');
        }
    });
}

        function loadAccountsPayable() {
            const statusFilter = $('#apStatusFilter').val() || 'all';
            
            $('#accountsPayableContent').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading accounts payable...</p></div>');
            
            let url = '<?= base_url('accounts-payable/list') ?>';
            if (statusFilter !== 'all') {
                url += '?payment_status=' + encodeURIComponent(statusFilter);
            }
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.accounts_payable) {
                        let html = '<div class="table-responsive"><table class="table" style="margin-bottom: 0;"><thead><tr style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;"><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">PO Number</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Supplier</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Branch</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Invoice #</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Amount</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Paid</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Balance</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Due Date</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status</th><th style="padding: 15px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Actions</th></tr></thead><tbody>';
                        
                        if (response.accounts_payable.length === 0) {
                            html += '<tr><td colspan="10" style="text-align: center; padding: 60px 20px; color: #999;"><i class="fas fa-inbox fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i><h5 style="margin-top: 20px; color: #666;">No accounts payable found</h5><p style="color: #999;">No accounts payable records available</p></td></tr>';
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
                                const invoiceNumber = ap.invoice_number || '-';
                                const amount = parseFloat(ap.amount || 0);
                                const paidAmount = parseFloat(ap.paid_amount || 0);
                                const balance = parseFloat(ap.balance || 0);
                                const dueDate = ap.due_date ? new Date(ap.due_date) : null;
                                const daysInfo = ap.days_info || '';
                                
                                html += '<tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;">';
                                html += '<td style="padding: 15px; vertical-align: middle;"><strong style="color: #2d5016;">' + orderNumber + '</strong></td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + supplierName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333;">' + branchName + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + invoiceNumber + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #333; font-weight: 600;">₱' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #28a745; font-weight: 600;">₱' + paidAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: ' + (balance > 0 ? '#dc3545' : '#28a745') + '; font-weight: 600;">₱' + balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                html += '<td style="padding: 15px; vertical-align: middle; color: #666;">' + (dueDate ? dueDate.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'N/A') + '<br><small style="color: ' + (status === 'overdue' ? '#dc3545' : '#6c757d') + ';">' + daysInfo + '</small></td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><span class="status-badge ' + statusClass + '" style="padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; text-transform: capitalize; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">' + statusText + '</span></td>';
                                html += '<td style="padding: 15px; vertical-align: middle;"><div style="display: flex; gap: 5px;">';
                                html += '<button class="btn btn-sm btn-info viewAPBtn" data-id="' + ap.id + '" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;"><i class="fas fa-eye"></i> View</button>';
                                
                                // Add payment button for unpaid/partial/overdue
                                if (status !== 'paid') {
                                    html += ' <button class="btn btn-sm btn-success recordPaymentBtn" data-id="' + ap.id + '" data-balance="' + balance + '" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px;"><i class="fas fa-money-bill-wave"></i> Pay</button>';
                                }
                                
                                // Add invoice update button
                                if (!ap.invoice_number) {
                                    html += ' <button class="btn btn-sm btn-warning updateInvoiceBtn" data-id="' + ap.id + '" style="background: #ffc107; color: #000; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem; margin-left: 5px;"><i class="fas fa-file-invoice"></i> Invoice</button>';
                                }
                                
                                html += '</div></td>';
                                html += '</tr>';
                            });
                        }
                        
                        html += '</tbody></table></div>';
                        $('#accountsPayableContent').html(html);
                        
                        // Attach event handlers
                        attachAPHandlers();
                    } else {
                        $('#accountsPayableContent').html('<div class="alert alert-info" style="padding: 20px; border-radius: 8px; background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;">No accounts payable found</div>');
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
                updateInvoice(apId);
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
        
        function updateInvoice(apId) {
            const invoiceNumber = prompt('Enter invoice number:');
            if (!invoiceNumber) return;
            
            const invoiceDate = prompt('Enter invoice date (YYYY-MM-DD):');
            if (!invoiceDate) return;
            
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/update-invoice',
                method: 'POST',
                data: {
                    invoice_number: invoiceNumber,
                    invoice_date: invoiceDate
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
            $('#reportsContent').html('<div class="alert alert-info">Reports feature coming soon</div>');
        }

        // Filter change handlers
        $(document).on('change', '#apStatusFilter', function() {
            loadAccountsPayable();
        });
        
        // Initialize
$(document).ready(function() {
            const activeTab = '<?= $activeTab ?? 'dashboard' ?>';
            if (activeTab === 'purchaseRequests') showPendingRequests();
            else if (activeTab === 'purchaseOrders') showPurchaseOrders();
            else if (activeTab === 'deliveries') showDeliveries();
            else if (activeTab === 'suppliers') showSuppliers();
            else if (activeTab === 'accountsPayable') showAccountsPayable();
            else if (activeTab === 'reports') showReports();
            else goToDashboard();
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
</body>
</html>
