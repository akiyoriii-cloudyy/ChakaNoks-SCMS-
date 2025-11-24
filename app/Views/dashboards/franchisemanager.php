<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Manager Dashboard — CHAKANOKS SCMS</title>
    
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
                <a href="#" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-store"></i>
                    <span>Store Overview</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-file-invoice"></i>
                    <span>Sales Reports</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-sitemap"></i>
                    <span>My Branches</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Revenue</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Staff</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile-card" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.95) 0%, rgba(74, 124, 42, 0.95) 100%); border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255, 255, 255, 0.3); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-user-circle" style="font-size: 32px; color: white;"></i>
                        </div>
                        <div class="user-info" style="flex: 1; min-width: 0;">
                            <div class="user-name" style="font-size: 0.9rem; font-weight: 600; color: white; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc(session()->get('email') ?? 'User') ?></div>
                            <div class="user-role" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Franchise Manager</div>
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
                        <h2 class="page-title">Franchise Manager Dashboard</h2>
                        <p class="page-subtitle">Manage franchise operations and local sales</p>
                    </div>
                </div>
                <div class="header-right">
                    <button class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
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
                            <div class="stat-icon info">
                                <i class="fas fa-sitemap"></i>
                            </div>
                        </div>
                        <h3 class="stat-value"><?= number_format($data['inventory']['branches_count'] ?? 0) ?></h3>
                        <p class="stat-label">Total Branches</p>
                        <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                            Active locations
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-header">
                            <div class="stat-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <h3 class="stat-value"><?= number_format($data['inventory']['total_stock_value'] ?? 0, 0) ?></h3>
                        <p class="stat-label">Total Stock Value</p>
                        <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                            Across all branches
                        </div>
                    </div>
                </div>

                <!-- Additional Content Cards -->
                <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
                    <!-- Branch Overview -->
                    <div class="content-card" style="grid-column: 1 / -1;">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-sitemap" style="font-size: 1.5rem;"></i>
                                Branch Overview
                                <span style="margin-left: auto; font-size: 0.875rem; font-weight: 500; opacity: 0.9;">5 Active Branches</span>
                            </h3>
                        </div>
                        <?php if (!empty($data['branches'])): ?>
                            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 10px;">
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
                                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid rgba(45, 80, 22, 0.1);">
                                            <div>
                                                <p style="margin: 0; font-size: 0.75rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Products</p>
                                                <h3 style="margin: 4px 0 0 0; font-size: 1.5rem; font-weight: 700; color: #2d5016;"><?= number_format($branch['total_products']) ?></h3>
                                            </div>
                                            <div style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, rgba(45, 80, 22, 0.1) 0%, rgba(74, 124, 42, 0.1) 100%); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-boxes" style="color: #2d5016; font-size: 1.1rem;"></i>
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

                    <!-- Inventory Summary -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar" style="color: var(--info); margin-right: 8px;"></i>
                                Inventory Summary
                            </h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                            <div>
                                <p class="stat-label">Total Items</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--primary-green); margin: 0.5rem 0;">
                                    <?= number_format($data['inventory']['total_items'] ?? 0) ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Low Stock</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--warning); margin: 0.5rem 0;">
                                    <?= number_format($data['inventory']['low_stock_count'] ?? 0) ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Critical Items</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--danger); margin: 0.5rem 0;">
                                    <?= number_format($data['inventory']['critical_items_count'] ?? 0) ?>
                                </h4>
                            </div>
                            <div>
                                <p class="stat-label">Total Value</p>
                                <h4 style="font-size: 1.75rem; font-weight: 700; color: var(--success); margin: 0.5rem 0;">
                                    ₱<?= number_format($data['inventory']['total_stock_value'] ?? 0, 2) ?>
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
