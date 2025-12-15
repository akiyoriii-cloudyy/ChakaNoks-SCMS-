<?php
// Ensure all variables are defined with defaults
$products = $products ?? [];
$branches = $branches ?? [];
$applications = $applications ?? [];
$allocations = $allocations ?? [];
$royalties = $royalties ?? [];
$applicationStats = $applicationStats ?? ['total' => 0, 'pending' => 0, 'under_review' => 0, 'approved' => 0, 'rejected' => 0];
$allocationStats = $allocationStats ?? ['total' => 0, 'pending' => 0, 'approved' => 0, 'shipped' => 0, 'delivered' => 0, 'total_value' => 0];
$royaltyStats = $royaltyStats ?? ['total_due' => 0, 'total_paid' => 0, 'total_balance' => 0, 'pending_count' => 0, 'overdue_count' => 0, 'paid_count' => 0];
$me = $me ?? ['email' => 'User', 'role' => 'franchise_manager'];
$currentSection = $currentSection ?? 'overview';
?>
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
    
    <style>
        /* Professional gradient background matching other dashboards */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .dashboard-main {
            background: transparent;
        }

        /* Enhanced page header matching other dashboards */
        .dashboard-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            color: white;
            padding: 30px 40px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(45, 80, 22, 0.2);
        }

        .dashboard-header .page-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .dashboard-header .page-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin: 0;
        }

        .dashboard-header .btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .dashboard-header .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Enhanced content card matching other dashboards */
        .content-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            background: white;
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .content-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .content-card .card-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            color: white;
            padding: 20px 30px;
            border: none;
        }

        .content-card .card-title {
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Enhanced table styling matching other dashboards */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f9faf8;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #333;
        }

        /* Enhanced summary cards */
        .summary-card {
            flex: 1;
            min-width: 150px;
            text-align: center;
            padding: 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .summary-card .summary-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .summary-card .summary-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Form controls matching other dashboards */
        .form-select, .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #4a7c2a;
            box-shadow: 0 0 0 3px rgba(74, 124, 42, 0.1);
            outline: none;
        }

        /* Badge Styles */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-under_review { background: #dbeafe; color: #1e40af; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-on_hold { background: #e5e7eb; color: #374151; }
        .badge-shipped { background: #e0e7ff; color: #3730a3; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-partial { background: #fef3c7; color: #92400e; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }

        /* Section Display */
        .dashboard-section {
            display: none;
        }

        .dashboard-section.active {
            display: block;
        }

        /* Action Buttons */
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 4px;
        }

        .action-btn.approve { background: #d1fae5; color: #065f46; }
        .action-btn.approve:hover { background: #a7f3d0; }
        .action-btn.reject { background: #fee2e2; color: #991b1b; }
        .action-btn.reject:hover { background: #fecaca; }
        .action-btn.view { background: #dbeafe; color: #1e40af; }
        .action-btn.view:hover { background: #bfdbfe; }
        .action-btn.payment { background: #d1fae5; color: #065f46; }
        .action-btn.payment:hover { background: #a7f3d0; }

        /* Primary Button */
        .btn-primary-custom {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 80, 22, 0.3);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 8px;
            color: #1e293b;
        }

        /* Amount Display */
        .amount {
            font-family: 'Poppins', monospace;
            font-weight: 600;
        }

        .amount.positive { color: #059669; }
        .amount.negative { color: #dc2626; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 0.875rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php 
    $baseUrl = base_url('franchisemanager/dashboard');
    ?>
    
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
                <a href="<?= $baseUrl ?>?section=overview" class="nav-item <?= $currentSection === 'overview' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Overview</span>
                </a>
                <a href="<?= $baseUrl ?>?section=applications" class="nav-item <?= $currentSection === 'applications' ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Franchise Applications</span>
                </a>
                <a href="<?= $baseUrl ?>?section=allocations" class="nav-item <?= $currentSection === 'allocations' ? 'active' : '' ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Supply Allocations</span>
                </a>
                <a href="<?= $baseUrl ?>?section=royalties" class="nav-item <?= $currentSection === 'royalties' ? 'active' : '' ?>">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Royalty & Payments</span>
                </a>
                <a href="<?= $baseUrl ?>?section=partners" class="nav-item <?= $currentSection === 'partners' ? 'active' : '' ?>">
                    <i class="fas fa-handshake"></i>
                    <span>Franchise Partners</span>
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
                            <div class="user-role" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">FRANCHISE MANAGER</div>
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
                        <p class="page-subtitle">Manage franchise applications, supply allocations, and royalty payments</p>
                    </div>
                </div>
                <div class="header-right">
                    <button class="btn" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Welcome Banner -->
                <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15); display: inline-flex; align-items: center; gap: 12px; max-width: fit-content;">
                    <div style="width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-check" style="font-size: 18px; color: #28a745;"></i>
                    </div>
                    <div>
                        <div style="color: white; font-size: 1rem; font-weight: 700; line-height: 1.2;">Welcome to Franchise Manager</div>
                        <div style="color: rgba(255, 255, 255, 0.95); font-size: 0.875rem; font-weight: 500; line-height: 1.2;">Dashboard</div>
                    </div>
                </div>

                <!-- Overview Section -->
                <div id="overview-section" class="dashboard-section <?= $currentSection === 'overview' ? 'active' : '' ?>">
                    <!-- Summary Cards -->
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                        <div class="summary-card" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                            <div class="summary-value" style="color: #1e40af;"><?= number_format($applicationStats['pending'] ?? 0) ?></div>
                            <div class="summary-label">Pending Applications</div>
                        </div>
                        <div class="summary-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                            <div class="summary-value" style="color: #92400e;"><?= number_format($allocationStats['pending'] ?? 0) ?></div>
                            <div class="summary-label">Pending Allocations</div>
                        </div>
                        <div class="summary-card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                            <div class="summary-value" style="color: #065f46;">₱<?= number_format($royaltyStats['total_paid'] ?? 0, 0) ?></div>
                            <div class="summary-label">Total Collected</div>
                        </div>
                        <div class="summary-card" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                            <div class="summary-value" style="color: #991b1b;"><?= number_format($royaltyStats['overdue_count'] ?? 0) ?></div>
                            <div class="summary-label">Overdue Payments</div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
                        </div>
                        <div style="padding: 20px; display: flex; gap: 16px; flex-wrap: wrap;">
                            <button class="btn-primary-custom" onclick="openModal('applicationModal')">
                                <i class="fas fa-plus"></i> New Application
                            </button>
                            <button class="btn-primary-custom" onclick="openModal('allocationModal')" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="fas fa-truck"></i> Create Allocation
                            </button>
                            <button class="btn-primary-custom" onclick="openModal('royaltyModal')" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                                <i class="fas fa-receipt"></i> Record Royalty
                            </button>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
                        <div class="content-card">
                            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title"><i class="fas fa-file-alt"></i> Recent Applications</h3>
                                <a href="<?= $baseUrl ?>?section=applications" style="color: rgba(255,255,255,0.9); font-weight: 500; text-decoration: none; font-size: 0.9rem;">View All →</a>
                            </div>
                            <div style="padding: 20px;">
                                <?php if (!empty($applications)): ?>
                                    <?php foreach (array_slice($applications, 0, 3) as $app): ?>
                                        <div style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <div style="font-weight: 600; color: #1e293b;"><?= esc($app['applicant_name']) ?></div>
                                                    <div style="font-size: 0.85rem; color: #64748b;"><?= esc($app['city']) ?></div>
                                                </div>
                                                <span class="badge badge-<?= $app['status'] ?>"><?= ucfirst(str_replace('_', ' ', $app['status'])) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="empty-state" style="padding: 30px;">
                                        <i class="fas fa-file-alt"></i>
                                        <p>No applications yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title"><i class="fas fa-money-bill-wave"></i> Recent Payments</h3>
                                <a href="<?= $baseUrl ?>?section=royalties" style="color: rgba(255,255,255,0.9); font-weight: 500; text-decoration: none; font-size: 0.9rem;">View All →</a>
                            </div>
                            <div style="padding: 20px;">
                                <?php if (!empty($royalties)): ?>
                                    <?php foreach (array_slice($royalties, 0, 3) as $payment): ?>
                                        <div style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <div style="font-weight: 600; color: #1e293b;"><?= esc($payment['branch_name'] ?? 'Unknown Branch') ?></div>
                                                    <div style="font-size: 0.85rem; color: #64748b;"><?= date('F Y', mktime(0, 0, 0, $payment['period_month'], 1, $payment['period_year'])) ?></div>
                                                </div>
                                                <div style="text-align: right;">
                                                    <div class="amount positive">₱<?= number_format($payment['amount_paid'], 2) ?></div>
                                                    <span class="badge badge-<?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="empty-state" style="padding: 30px;">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <p>No payment records yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Section -->
                <div id="applications-section" class="dashboard-section <?= $currentSection === 'applications' ? 'active' : '' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title"><i class="fas fa-file-alt"></i> Franchise Applications</h3>
                            <button class="btn-primary-custom" onclick="openModal('applicationModal')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-plus"></i> New Application
                            </button>
                        </div>
                        
                        <?php if (!empty($applications)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Applicant</th>
                                            <th>Contact</th>
                                            <th>Location</th>
                                            <th>Investment</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($applications as $app): ?>
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 600;"><?= esc($app['applicant_name']) ?></div>
                                                </td>
                                                <td>
                                                    <div><?= esc($app['email']) ?></div>
                                                    <div style="font-size: 0.85rem; color: #64748b;"><?= esc($app['phone']) ?></div>
                                                </td>
                                                <td>
                                                    <div><?= esc($app['proposed_location']) ?></div>
                                                    <div style="font-size: 0.85rem; color: #64748b;"><?= esc($app['city']) ?></div>
                                                </td>
                                                <td class="amount">₱<?= number_format($app['investment_capital'], 2) ?></td>
                                                <td><span class="badge badge-<?= $app['status'] ?>"><?= ucfirst(str_replace('_', ' ', $app['status'])) ?></span></td>
                                                <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                                <td>
                                                    <button class="action-btn view" onclick="viewApplication(<?= $app['id'] ?>)" title="View details and edit">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <?php if ($app['status'] !== 'approved'): ?>
                                                        <button class="action-btn approve" onclick="reviewApplication(<?= $app['id'] ?>, 'approved')" title="Approve application">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if ($app['status'] !== 'rejected'): ?>
                                                        <button class="action-btn reject" onclick="reviewApplication(<?= $app['id'] ?>, 'rejected')" title="Reject application">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (isset($applicationsPager)): ?>
                                <?= render_pagination($applicationsPager, 'applications_page') ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <h3>No Applications</h3>
                                <p>Click "New Application" to add a franchise application.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Allocations Section -->
                <div id="allocations-section" class="dashboard-section <?= $currentSection === 'allocations' ? 'active' : '' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title"><i class="fas fa-boxes"></i> Supply Allocations</h3>
                            <button class="btn-primary-custom" onclick="openModal('allocationModal')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-plus"></i> Create Allocation
                            </button>
                        </div>
                        
                        <?php if (!empty($allocations)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Delivery Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allocations as $alloc): ?>
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 600;"><?= esc($alloc['branch_name'] ?? 'N/A') ?></div>
                                                    <div style="font-size: 0.85rem; color: #64748b;"><?= esc($alloc['branch_code'] ?? '') ?></div>
                                                </td>
                                                <td><?= esc($alloc['product_name'] ?? 'N/A') ?></td>
                                                <td><?= number_format($alloc['allocated_qty']) ?></td>
                                                <td class="amount">₱<?= number_format($alloc['unit_price'], 2) ?></td>
                                                <td class="amount positive">₱<?= number_format($alloc['total_amount'], 2) ?></td>
                                                <td><span class="badge badge-<?= $alloc['status'] ?>"><?= ucfirst($alloc['status']) ?></span></td>
                                                <td><?= $alloc['delivery_date'] ? date('M d, Y', strtotime($alloc['delivery_date'])) : '-' ?></td>
                                                <td>
                                                    <?php if ($alloc['status'] === 'pending'): ?>
                                                        <button class="action-btn approve" onclick="updateAllocation(<?= $alloc['id'] ?>, 'approved')">Approve</button>
                                                    <?php elseif ($alloc['status'] === 'approved'): ?>
                                                        <button class="action-btn view" onclick="updateAllocation(<?= $alloc['id'] ?>, 'shipped')">Ship</button>
                                                    <?php elseif ($alloc['status'] === 'shipped'): ?>
                                                        <button class="action-btn approve" onclick="updateAllocation(<?= $alloc['id'] ?>, 'delivered')">Delivered</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (isset($allocationsPager)): ?>
                                <?= render_pagination($allocationsPager, 'allocations_page') ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-boxes"></i>
                                <h3>No Allocations</h3>
                                <p>Click "Create Allocation" to allocate supplies to franchise partners.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Royalties Section -->
                <div id="royalties-section" class="dashboard-section <?= $currentSection === 'royalties' ? 'active' : '' ?>">
                    <div class="content-card">
                        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title"><i class="fas fa-money-bill-wave"></i> Royalty & Payment Tracking</h3>
                            <button class="btn-primary-custom" onclick="openModal('royaltyModal')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-plus"></i> Record Royalty
                            </button>
                        </div>

                        <!-- Summary Cards -->
                        <div style="padding: 20px; display: flex; gap: 16px; flex-wrap: wrap; border-bottom: 1px solid #e2e8f0;">
                            <div class="summary-card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); min-width: 180px;">
                                <div class="summary-value" style="color: #065f46;">₱<?= number_format($royaltyStats['total_paid'] ?? 0, 2) ?></div>
                                <div class="summary-label">Total Collected</div>
                            </div>
                            <div class="summary-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); min-width: 180px;">
                                <div class="summary-value" style="color: #92400e;">₱<?= number_format($royaltyStats['total_balance'] ?? 0, 2) ?></div>
                                <div class="summary-label">Pending</div>
                            </div>
                            <div class="summary-card" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); min-width: 180px;">
                                <div class="summary-value" style="color: #991b1b;"><?= number_format($royaltyStats['overdue_count'] ?? 0) ?></div>
                                <div class="summary-label">Overdue Records</div>
                            </div>
                        </div>
                        
                        <?php if (!empty($royalties)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th>Period</th>
                                            <th>Gross Sales</th>
                                            <th>Royalty (5%)</th>
                                            <th>Total Due</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($royalties as $royalty): ?>
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 600;"><?= esc($royalty['branch_name'] ?? 'N/A') ?></div>
                                                </td>
                                                <td><?= date('F Y', mktime(0, 0, 0, $royalty['period_month'], 1, $royalty['period_year'])) ?></td>
                                                <td class="amount">₱<?= number_format($royalty['gross_sales'], 2) ?></td>
                                                <td class="amount">₱<?= number_format($royalty['royalty_amount'], 2) ?></td>
                                                <td class="amount">₱<?= number_format($royalty['total_due'], 2) ?></td>
                                                <td class="amount positive">₱<?= number_format($royalty['amount_paid'], 2) ?></td>
                                                <td class="amount <?= $royalty['balance'] > 0 ? 'negative' : '' ?>">₱<?= number_format($royalty['balance'], 2) ?></td>
                                                <td><span class="badge badge-<?= $royalty['status'] ?>"><?= ucfirst($royalty['status']) ?></span></td>
                                                <td>
                                                    <?php if ($royalty['status'] !== 'paid'): ?>
                                                        <button class="action-btn payment" onclick="openPaymentModal(<?= $royalty['id'] ?>, <?= $royalty['balance'] ?>)">
                                                            <i class="fas fa-money-bill"></i> Pay
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (isset($royaltiesPager)): ?>
                                <?= render_pagination($royaltiesPager, 'royalties_page') ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-money-bill-wave"></i>
                                <h3>No Royalty Records</h3>
                                <p>Click "Record Royalty" to create royalty payment records for franchise partners.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Partners Section -->
                <div id="partners-section" class="dashboard-section <?= $currentSection === 'partners' ? 'active' : '' ?>">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-handshake"></i> Franchise Partners</h3>
                        </div>
                        <div style="padding: 20px;">
                            <?php if (!empty($branches)): ?>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                                    <?php foreach ($branches as $branch): ?>
                                        <?php 
                                        $branchName = strtolower($branch['name'] ?? '');
                                        if (strpos($branchName, 'central') !== false) continue;
                                        ?>
                                        <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                                                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-store" style="color: white; font-size: 1.5rem;"></i>
                                                </div>
                                                <div>
                                                    <h4 style="margin: 0; font-weight: 700; color: #1e293b;"><?= esc($branch['name']) ?></h4>
                                                    <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #64748b;"><?= esc($branch['code'] ?? '') ?></p>
                                                </div>
                                            </div>
                                            <?php if (!empty($branch['address'])): ?>
                                                <p style="margin: 0 0 12px 0; font-size: 0.85rem; color: #64748b;">
                                                    <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #2d5016;"></i>
                                                    <?= esc($branch['address']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <div style="display: flex; gap: 8px; margin-top: 16px;">
                                                <button class="btn-primary-custom" style="flex: 1; justify-content: center; padding: 10px 12px; font-size: 0.85rem;" onclick="openAllocationForBranch(<?= $branch['id'] ?>)">
                                                    <i class="fas fa-truck"></i> Allocate Supply
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-handshake"></i>
                                    <h3>No Partners Yet</h3>
                                    <p>Approved franchise applications will appear here as partners.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Application Modal -->
    <div id="applicationModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-alt" style="color: #2d5016; margin-right: 10px;"></i> New Franchise Application</h3>
                <button class="modal-close" onclick="closeModal('applicationModal')">&times;</button>
            </div>
            <form id="applicationForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Applicant Name *</label>
                        <input type="text" class="form-control" name="applicant_name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone *</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Investment Capital (₱)</label>
                        <input type="number" class="form-control" name="investment_capital" step="0.01">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Proposed Location *</label>
                        <input type="text" class="form-control" name="proposed_location" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Business Experience</label>
                    <textarea class="form-control" name="business_experience" rows="3" placeholder="Previous business experience..."></textarea>
                </div>
                <button type="submit" class="btn-primary-custom" style="width: 100%; justify-content: center;">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </form>
        </div>
    </div>

    <!-- Allocation Modal -->
    <div id="allocationModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-boxes" style="color: #f59e0b; margin-right: 10px;"></i> Create Supply Allocation</h3>
                <button class="modal-close" onclick="closeModal('allocationModal')">&times;</button>
            </div>
            <form id="allocationForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Franchise Branch *</label>
                        <select class="form-control" name="branch_id" id="allocationBranch" required>
                            <option value="">Select branch...</option>
                            <?php foreach ($branches as $branch): ?>
                                <?php if (strpos(strtolower($branch['name'] ?? ''), 'central') === false): ?>
                                    <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Product *</label>
                        <select class="form-control" name="product_id" required>
                            <option value="">Select product...</option>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?? 0 ?>"><?= esc($product['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity *</label>
                        <input type="number" class="form-control" name="allocated_qty" id="allocQty" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit Price (₱) *</label>
                        <input type="number" class="form-control" name="unit_price" id="allocPrice" step="0.01" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Allocation Date</label>
                        <input type="date" class="form-control" name="allocation_date" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expected Delivery Date</label>
                        <input type="date" class="form-control" name="delivery_date">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Additional notes..."></textarea>
                </div>
                <button type="submit" class="btn-primary-custom" style="width: 100%; justify-content: center; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-truck"></i> Create Allocation
                </button>
            </form>
        </div>
    </div>

    <!-- Royalty Modal -->
    <div id="royaltyModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-receipt" style="color: #3b82f6; margin-right: 10px;"></i> Record Royalty</h3>
                <button class="modal-close" onclick="closeModal('royaltyModal')">&times;</button>
            </div>
            <form id="royaltyForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Franchise Branch *</label>
                        <select class="form-control" name="branch_id" required>
                            <option value="">Select branch...</option>
                            <?php foreach ($branches as $branch): ?>
                                <?php if (strpos(strtolower($branch['name'] ?? ''), 'central') === false): ?>
                                    <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Period Month *</label>
                        <select class="form-control" name="period_month" required>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Period Year *</label>
                        <input type="number" class="form-control" name="period_year" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gross Sales (₱) *</label>
                        <input type="number" class="form-control" name="gross_sales" step="0.01" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Royalty Rate (%)</label>
                        <input type="number" class="form-control" name="royalty_rate" value="5" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marketing Fee (₱)</label>
                        <input type="number" class="form-control" name="marketing_fee" value="0" step="0.01">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Due Date *</label>
                    <input type="date" class="form-control" name="due_date" required>
                </div>
                <button type="submit" class="btn-primary-custom" style="width: 100%; justify-content: center; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i class="fas fa-save"></i> Save Royalty Record
                </button>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-money-bill" style="color: #059669; margin-right: 10px;"></i> Record Payment</h3>
                <button class="modal-close" onclick="closeModal('paymentModal')">&times;</button>
            </div>
            <form id="paymentForm">
                <input type="hidden" name="royalty_id" id="paymentRoyaltyId">
                <div class="form-group">
                    <label class="form-label">Amount to Pay (₱) *</label>
                    <input type="number" class="form-control" name="amount_paid" id="paymentAmount" step="0.01" required>
                    <small style="color: #64748b;">Balance: ₱<span id="paymentBalance">0.00</span></small>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Reference</label>
                    <input type="text" class="form-control" name="payment_reference" placeholder="e.g., Check #, Transfer Ref...">
                </div>
                <button type="submit" class="btn-primary-custom" style="width: 100%; justify-content: center; background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                    <i class="fas fa-check"></i> Record Payment
                </button>
            </form>
        </div>
    </div>

    <!-- View/Edit Application Modal -->
    <div id="viewApplicationModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-alt" style="color: #2d5016; margin-right: 10px;"></i> Application Details</h3>
                <button class="modal-close" onclick="closeModal('viewApplicationModal')">&times;</button>
            </div>
            <div id="applicationDetailsContent">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #2d5016;"></i>
                    <p style="margin-top: 16px; color: #64748b;">Loading application details...</p>
                </div>
            </div>
            <form id="editApplicationForm" style="display: none;">
                <input type="hidden" name="application_id" id="editAppId">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Applicant Name *</label>
                        <input type="text" class="form-control" name="applicant_name" id="editAppName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" id="editAppEmail" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone *</label>
                        <input type="text" class="form-control" name="phone" id="editAppPhone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Investment Capital (₱)</label>
                        <input type="number" class="form-control" name="investment_capital" id="editAppInvestment" step="0.01" readonly style="background-color: #f1f5f9; cursor: not-allowed;">
                        <small style="color: #64748b; font-size: 0.75rem; margin-top: 4px; display: block;">This field cannot be edited (proposed by franchise owner)</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Proposed Location *</label>
                        <input type="text" class="form-control" name="proposed_location" id="editAppLocation" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" class="form-control" name="city" id="editAppCity" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Business Experience</label>
                    <textarea class="form-control" name="business_experience" id="editAppExperience" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn-primary-custom" style="flex: 1; justify-content: center;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button type="button" class="btn-primary-custom" onclick="toggleEditMode(false)" style="flex: 1; justify-content: center; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }
        
        // Close modal on overlay click
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
        
        // Application form submit
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: '<?= base_url('franchisemanager/application/create') ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Application submitted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to submit application'));
                    }
                },
                error: function() {
                    alert('Error submitting application');
                }
            });
        });
        
        // View application details
        function viewApplication(id) {
            openModal('viewApplicationModal');
            document.getElementById('applicationDetailsContent').style.display = 'block';
            document.getElementById('editApplicationForm').style.display = 'none';
            
            $.ajax({
                url: '<?= base_url('franchisemanager/application/') ?>' + id + '/view',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data) {
                        displayApplicationDetails(response.data);
                    } else {
                        document.getElementById('applicationDetailsContent').innerHTML = 
                            '<div style="text-align: center; padding: 40px; color: #dc2626;">' +
                            '<i class="fas fa-exclamation-circle" style="font-size: 2rem;"></i>' +
                            '<p style="margin-top: 16px;">Failed to load application details.</p></div>';
                    }
                },
                error: function() {
                    document.getElementById('applicationDetailsContent').innerHTML = 
                        '<div style="text-align: center; padding: 40px; color: #dc2626;">' +
                        '<i class="fas fa-exclamation-circle" style="font-size: 2rem;"></i>' +
                        '<p style="margin-top: 16px;">Error loading application details.</p></div>';
                }
            });
        }
        
        // Display application details
        function displayApplicationDetails(app) {
            const statusColors = {
                'pending': '#fef3c7',
                'under_review': '#dbeafe',
                'approved': '#d1fae5',
                'rejected': '#fee2e2',
                'on_hold': '#e5e7eb'
            };
            const statusTextColors = {
                'pending': '#92400e',
                'under_review': '#1e40af',
                'approved': '#065f46',
                'rejected': '#991b1b',
                'on_hold': '#374151'
            };
            
            const statusBg = statusColors[app.status] || '#e5e7eb';
            const statusColor = statusTextColors[app.status] || '#374151';
            const statusText = app.status.replace('_', ' ').toUpperCase();
            
            document.getElementById('applicationDetailsContent').innerHTML = `
                <div style="padding: 0 8px;">
                    <div style="background: ${statusBg}; color: ${statusColor}; padding: 12px 20px; border-radius: 8px; text-align: center; font-weight: 700; font-size: 0.9rem; margin-bottom: 24px; letter-spacing: 0.5px;">
                        STATUS: ${statusText}
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Applicant Name</label>
                            <div style="font-size: 1rem; color: #1e293b; font-weight: 500;">${app.applicant_name || 'N/A'}</div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Email</label>
                            <div style="font-size: 1rem; color: #1e293b; font-weight: 500;">${app.email || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Phone</label>
                            <div style="font-size: 1rem; color: #1e293b; font-weight: 500;">${app.phone || 'N/A'}</div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Investment Capital</label>
                            <div style="font-size: 1rem; color: #1e293b; font-weight: 600;">₱${parseFloat(app.investment_capital || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Proposed Location</label>
                            <div style="font-size: 1rem; color: #1e293b; font-weight: 500;">${app.proposed_location || 'N/A'}</div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">City</label>
                            <div style="font-size: 1rem; color: #1e293b; font-weight: 500;">${app.city || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Business Experience</label>
                        <div style="font-size: 0.95rem; color: #475569; line-height: 1.6; background: #f8fafc; padding: 12px; border-radius: 6px;">${app.business_experience || 'No experience provided'}</div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Application Date</label>
                            <div style="font-size: 0.95rem; color: #1e293b;">${app.created_at || 'N/A'}</div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block;">Last Updated</label>
                            <div style="font-size: 0.95rem; color: #1e293b;">${app.updated_at || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 10px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                        <button class="btn-primary-custom" onclick="toggleEditMode(true, ${app.id})" style="flex: 1; justify-content: center;">
                            <i class="fas fa-edit"></i> Edit Application
                        </button>
                        <button class="btn-primary-custom" onclick="closeModal('viewApplicationModal')" style="flex: 1; justify-content: center; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </div>
            `;
            
            // Store app data for editing
            window.currentAppData = app;
        }
        
        // Toggle edit mode
        function toggleEditMode(enable, appId = null) {
            if (enable && window.currentAppData) {
                document.getElementById('applicationDetailsContent').style.display = 'none';
                document.getElementById('editApplicationForm').style.display = 'block';
                
                // Populate form with current data
                const app = window.currentAppData;
                document.getElementById('editAppId').value = app.id;
                document.getElementById('editAppName').value = app.applicant_name || '';
                document.getElementById('editAppEmail').value = app.email || '';
                document.getElementById('editAppPhone').value = app.phone || '';
                document.getElementById('editAppInvestment').value = app.investment_capital || '';
                document.getElementById('editAppLocation').value = app.proposed_location || '';
                document.getElementById('editAppCity').value = app.city || '';
                document.getElementById('editAppExperience').value = app.business_experience || '';
            } else {
                document.getElementById('applicationDetailsContent').style.display = 'block';
                document.getElementById('editApplicationForm').style.display = 'none';
            }
        }
        
        // Edit application form submit
        document.getElementById('editApplicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const appId = document.getElementById('editAppId').value;
            
            $.ajax({
                url: '<?= base_url('franchisemanager/application/') ?>' + appId + '/update',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Application updated successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update application'));
                    }
                },
                error: function() {
                    alert('Error updating application');
                }
            });
        });
        
        // Review application
        function reviewApplication(id, status) {
            if (!confirm('Are you sure you want to ' + status.replace('_', ' ') + ' this application?')) return;
            
            $.ajax({
                url: '<?= base_url('franchisemanager/application/') ?>' + id + '/status',
                method: 'POST',
                data: { status: status },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Application ' + status.replace('_', ' ') + ' successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update'));
                    }
                }
            });
        }
        
        // Allocation form submit
        document.getElementById('allocationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: '<?= base_url('franchisemanager/allocation/create') ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Allocation created successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to create allocation'));
                    }
                },
                error: function() {
                    alert('Error creating allocation');
                }
            });
        });
        
        // Update allocation status
        function updateAllocation(id, status) {
            $.ajax({
                url: '<?= base_url('franchisemanager/allocation/') ?>' + id + '/status',
                method: 'POST',
                data: { status: status },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update'));
                    }
                }
            });
        }
        
        // Open allocation modal for specific branch
        function openAllocationForBranch(branchId) {
            document.getElementById('allocationBranch').value = branchId;
            openModal('allocationModal');
        }
        
        // Royalty form submit
        document.getElementById('royaltyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: '<?= base_url('franchisemanager/royalty/create') ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Royalty record created successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to create record'));
                    }
                },
                error: function() {
                    alert('Error creating royalty record');
                }
            });
        });
        
        // Open payment modal
        function openPaymentModal(royaltyId, balance) {
            document.getElementById('paymentRoyaltyId').value = royaltyId;
            document.getElementById('paymentBalance').textContent = parseFloat(balance).toFixed(2);
            document.getElementById('paymentAmount').value = balance;
            document.getElementById('paymentAmount').max = balance;
            openModal('paymentModal');
        }
        
        // Payment form submit
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const royaltyId = document.getElementById('paymentRoyaltyId').value;
            const formData = new FormData(this);
            
            $.ajax({
                url: '<?= base_url('franchisemanager/royalty/') ?>' + royaltyId + '/payment',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Payment recorded successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to record payment'));
                    }
                },
                error: function() {
                    alert('Error recording payment');
                }
            });
        });
        
        // Auto-fill price when product selected
        $('select[name="product_id"]').on('change', function() {
            const price = $(this).find(':selected').data('price') || 0;
            $('#allocPrice').val(price);
        });
    </script>
</body>
</html>
