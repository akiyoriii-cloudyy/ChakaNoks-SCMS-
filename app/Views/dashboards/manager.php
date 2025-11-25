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
                <div class="header-right">
                    <a href="<?= base_url('staff/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-boxes"></i>
                        <span>View Inventory</span>
                    </a>
                </div>
            </header>

            <div class="dashboard-content">
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
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
