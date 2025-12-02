<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics Coordinator Dashboard — CHAKANOKS SCMS</title>
    
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
        /* Status Badge Styles */
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-scheduled { 
            background: #0d6efd; 
            color: #fff; 
        }
        
        .status-in_transit { 
            background: #fd7e14; 
            color: #fff; 
        }
        
        .status-delivered { 
            background: #28a745; 
            color: #fff; 
        }
        
        .status-approved {
            background: #17a2b8;
            color: #fff;
        }
        
        .status-partial {
            background: #ffc107;
            color: #000;
        }
        
        .status-partial_delivery {
            background: #ffc107;
            color: #000;
        }
        
        .status-pending {
            background: #6c757d;
            color: #fff;
        }
        
        .status-sent_to_supplier {
            background: #6f42c1;
            color: #fff;
        }
        
        .status-cancelled {
            background: #dc3545;
            color: #fff;
        }
        
        /* Delivery Progress Bar */
        .progress-container {
            background: #f1f5f9;
            border-radius: 20px;
            height: 10px;
            overflow: hidden;
            margin: 8px 0;
        }
        
        .progress-bar {
            height: 100%;
            border-radius: 20px;
            transition: width 0.3s ease;
        }
        
        .progress-scheduled { background: #0d6efd; }
        .progress-in_transit { background: #fd7e14; }
        .progress-delivered { background: #28a745; }
        
        .delivery-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .delivery-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .table thead th {
            background-color: var(--primary-green);
            color: white;
            font-weight: 600;
            border: none;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(45, 80, 22, 0.05);
        }
        
        .badge-danger {
            background-color: var(--danger);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        
        .badge-warning {
            background-color: var(--warning);
            color: #000;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        
        /* Content Sections */
        .dashboard-content .content-section {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }
        
        .dashboard-content .content-section.active {
            display: block !important;
        }
        
        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 30px;
            margin: 20px 0;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-green);
            border: 2px solid white;
            box-shadow: 0 0 0 3px var(--primary-green);
        }
        
        .timeline-item.completed::before {
            background: #28a745;
            box-shadow: 0 0 0 3px #28a745;
        }
        
        .timeline-item.active::before {
            background: #fd7e14;
            box-shadow: 0 0 0 3px #fd7e14;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(253, 126, 20, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(253, 126, 20, 0); }
            100% { box-shadow: 0 0 0 0 rgba(253, 126, 20, 0); }
        }
        
        /* Info Detail Styles */
        .info-detail-box {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-green);
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        /* Vehicle Status Indicator */
        .vehicle-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .vehicle-available { background: #d1fae5; color: #065f46; }
        .vehicle-in_transit { background: #fef3c7; color: #92400e; }
        .vehicle-maintenance { background: #fee2e2; color: #991b1b; }
        
        /* Driver Status Indicator */
        .driver-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .driver-online { color: #10b981; }
        .driver-offline { color: #9ca3af; }
        .driver-busy { color: #f59e0b; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">CHAKANOKS</h1>
                        <p class="logo-subtitle">Supply Chain Management</p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="javascript:void(0);" class="nav-item active" onclick="showSection('dashboard', event);">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" onclick="showSection('schedule', event);">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Schedule Delivery</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" onclick="showSection('track', event);">
                    <i class="fas fa-search"></i>
                    <span>Track Orders</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" onclick="showSection('deliveries', event);">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" onclick="showSection('schedules', event);">
                    <i class="fas fa-file-contract"></i>
                    <span>Schedules</span>
                </a>
                <a href="javascript:void(0);" class="nav-item" onclick="showSection('fleet', event);">
                    <i class="fas fa-truck-loading"></i>
                    <span>Fleet Management</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile-card" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.95) 0%, rgba(74, 124, 42, 0.95) 100%); border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255, 255, 255, 0.3); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-user-circle" style="font-size: 32px; color: white;"></i>
                        </div>
                        <div class="user-info" style="flex: 1; min-width: 0;">
                            <div class="user-name" style="font-size: 0.9rem; font-weight: 600; color: white; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($me['email'] ?? session()->get('email') ?? 'User') ?></div>
                            <div class="user-role" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;"><?= esc(ucwords(str_replace('_', ' ', $me['role'] ?? session()->get('role') ?? 'User'))) ?></div>
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
                        <h2 class="page-title">Logistics Coordinator Dashboard</h2>
                        <p class="page-subtitle">Manage deliveries, schedule shipments, and track orders</p>
                    </div>
                </div>
                <div class="header-right">
                    <button class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                    <button class="btn btn-primary ms-2" onclick="generateDeliveryReport()">
                        <i class="fas fa-file-export"></i>
                        <span>Export Report</span>
                    </button>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Dashboard Section -->
                <div id="dashboard-section" class="content-section active">
                    <!-- Key Metrics -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div class="stat-icon warning">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <h3 class="stat-value"><?= number_format(count($pendingPOs ?? [])) ?></h3>
                            <p class="stat-label">Pending Orders</p>
                            <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                                <small>Need Scheduling</small>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div class="stat-icon info">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                            <h3 class="stat-value"><?= number_format(count($scheduledDeliveries ?? [])) ?></h3>
                            <p class="stat-label">Scheduled Deliveries</p>
                            <div style="margin-top: 8px;">
                                <span class="badge badge-warning">Ready to ship</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div class="stat-icon primary">
                                    <i class="fas fa-truck"></i>
                                </div>
                            </div>
                            <h3 class="stat-value"><?= number_format(count($inTransitDeliveries ?? [])) ?></h3>
                            <p class="stat-label">In Transit</p>
                            <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                                <small>Active Deliveries</small>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div class="stat-icon success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <h3 class="stat-value"><?= number_format((count($pendingPOs ?? []) + count($scheduledDeliveries ?? []) + count($inTransitDeliveries ?? []))) ?></h3>
                            <p class="stat-label">Total Active</p>
                            <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                                <small>All Orders</small>
                            </div>
                        </div>
                    </div>

                    <!-- Content Cards -->
                    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <!-- Pending Purchase Orders -->
                        <div class="content-card" style="grid-column: 1 / -1;">
                            <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                                <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
                                    Pending Purchase Orders (Need Scheduling)
                                </h3>
                            </div>
                            <?php if (empty($pendingPOs)): ?>
                                <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500;">No pending purchase orders</p>
                                </div>
                            <?php else: ?>
                                <!-- ===== PENDING PURCHASE ORDER DETAILS GUIDE ===== -->
                                <!-- 
                                    DATABASE STRUCTURE REQUIRED for Purchase Orders:
                                    
                                    1. purchase_orders table:
                                       - id (Primary Key)
                                       - order_number (VARCHAR, unique)
                                       - supplier_id (INT, foreign key)
                                       - branch_id (INT, foreign key)
                                       - total_amount (DECIMAL)
                                       - status (ENUM: 'pending', 'approved', 'sent_to_supplier', 'cancelled')
                                       - expected_delivery_date (DATE)
                                       - notes (TEXT)
                                       - created_at (TIMESTAMP)
                                       - updated_at (TIMESTAMP)
                                    
                                    2. purchase_order_items table:
                                       - id (Primary Key)
                                       - purchase_order_id (INT, foreign key)
                                       - product_id (INT, foreign key)
                                       - product_name (VARCHAR)
                                       - quantity (INT)
                                       - unit_price (DECIMAL)
                                       - total_price (DECIMAL)
                                    
                                    3. suppliers table:
                                       - id (Primary Key)
                                       - name (VARCHAR)
                                       - contact_person (VARCHAR)
                                       - phone (VARCHAR)
                                       - email (VARCHAR)
                                       - address (TEXT)
                                    
                                    4. branches table:
                                       - id (Primary Key)
                                       - name (VARCHAR)
                                       - address (TEXT)
                                       - phone (VARCHAR)
                                       - manager_name (VARCHAR)
                                -->
                                
                                <div class="mb-3" style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="info-label">Total Pending</div>
                                            <div class="info-value" style="font-size: 1.5rem;"><?= count($pendingPOs) ?></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-label">Total Amount</div>
                                            <div class="info-value" style="font-size: 1.5rem;">
                                                ₱<?= number_format(array_sum(array_column($pendingPOs, 'total_amount')), 2) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-label">Urgent Orders</div>
                                            <div class="info-value" style="font-size: 1.5rem; color: #dc3545;">
                                                <?php
                                                $urgentCount = 0;
                                                $today = new DateTime();
                                                foreach ($pendingPOs as $po) {
                                                    if (!empty($po['expected_delivery_date'])) {
                                                        $expectedDate = new DateTime($po['expected_delivery_date']);
                                                        $daysDiff = $today->diff($expectedDate)->days;
                                                        if ($daysDiff <= 3 && $expectedDate >= $today) {
                                                            $urgentCount++;
                                                        }
                                                    }
                                                }
                                                echo $urgentCount;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-label">Avg. Amount</div>
                                            <div class="info-value" style="font-size: 1.5rem;">
                                                ₱<?= count($pendingPOs) > 0 ? number_format(array_sum(array_column($pendingPOs, 'total_amount')) / count($pendingPOs), 2) : '0.00' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <?php foreach ($pendingPOs as $po): ?>
                                    <?php
                                    $orderDate = !empty($po['created_at']) ? new DateTime($po['created_at']) : (!empty($po['order_date']) ? new DateTime($po['order_date']) : null);
                                    $expectedDate = !empty($po['expected_delivery_date']) ? new DateTime($po['expected_delivery_date']) : null;
                                    $today = new DateTime();
                                    $daysLeft = null;
                                    $isUrgent = false;
                                    $isOverdue = false;
                                    
                                    if ($expectedDate) {
                                        $daysLeft = $today->diff($expectedDate)->days;
                                        if ($expectedDate < $today) {
                                            $isOverdue = true;
                                            $daysLeft = -$daysLeft;
                                        } elseif ($daysLeft <= 3) {
                                            $isUrgent = true;
                                        }
                                    }
                                    ?>
                                    <div class="col-12">
                                        <div class="card" style="border: 2px solid <?= $isOverdue ? '#dc3545' : ($isUrgent ? '#ffc107' : '#d4e8c9') ?>; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                                            <div class="card-body" style="padding: 20px;">
                                                <!-- Header Row -->
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-md-4">
                                                        <div style="display: flex; align-items: center; gap: 12px;">
                                                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 8px rgba(45, 80, 22, 0.2);">
                                                                <i class="fas fa-shopping-cart" style="color: white; font-size: 1.5rem;"></i>
                                                            </div>
                                                            <div>
                                                                <h5 style="margin: 0; color: var(--primary-green); font-weight: 700; font-size: 1.1rem;">
                                                                    <?= esc($po['order_number'] ?? 'N/A') ?>
                                                                </h5>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar-alt"></i> 
                                                                    <?= $orderDate ? $orderDate->format('M d, Y h:i A') : 'N/A' ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <div class="info-label">Total Amount</div>
                                                        <div class="info-value" style="font-size: 1.5rem;">
                                                            ₱<?= number_format($po['total_amount'] ?? 0, 2) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <div class="info-label">Status</div>
                                                        <?php if ($isOverdue): ?>
                                                            <span class="badge bg-danger" style="font-size: 0.9rem; padding: 6px 12px;">
                                                                <i class="fas fa-exclamation-triangle"></i> Overdue
                                                            </span>
                                                        <?php elseif ($isUrgent): ?>
                                                            <span class="badge bg-warning text-dark" style="font-size: 0.9rem; padding: 6px 12px;">
                                                                <i class="fas fa-clock"></i> Urgent
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge" style="background: #ffc107; color: #000; font-size: 0.9rem; padding: 6px 12px;">
                                                                <i class="fas fa-hourglass-half"></i> Pending
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-3 text-end">
                                                        <?php if ($expectedDate): ?>
                                                            <div class="info-label">Expected Delivery</div>
                                                            <div style="font-size: 1rem; font-weight: 600; color: <?= $isOverdue ? '#dc3545' : ($isUrgent ? '#ffc107' : 'var(--primary-green)') ?>;">
                                                                <i class="fas fa-clock"></i> <?= $expectedDate->format('M d, Y') ?>
                                                            </div>
                                                            <?php if ($daysLeft !== null): ?>
                                                                <small class="text-muted">
                                                                    <?= $isOverdue ? abs($daysLeft) . ' days overdue' : ($daysLeft . ' days left') ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="fas fa-exclamation-circle"></i> Date not set
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Details Row -->
                                                <div class="row mb-3" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 0;">
                                                    <div class="col-md-4">
                                                        <div class="info-detail-box">
                                                            <div class="info-label">
                                                                <i class="fas fa-truck"></i> Supplier Information
                                                            </div>
                                                            <div class="info-value">
                                                                <?= esc($po['supplier']['name'] ?? ($po['supplier_id'] ?? 'N/A')) ?>
                                                            </div>
                                                            <?php if (!empty($po['supplier']['contact_person'])): ?>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-user"></i> <?= esc($po['supplier']['contact_person']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (!empty($po['supplier']['phone'])): ?>
                                                                <br><small class="text-muted">
                                                                    <i class="fas fa-phone"></i> <?= esc($po['supplier']['phone']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (!empty($po['supplier']['email'])): ?>
                                                                <br><small class="text-muted">
                                                                    <i class="fas fa-envelope"></i> <?= esc($po['supplier']['email']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-detail-box">
                                                            <div class="info-label">
                                                                <i class="fas fa-building"></i> Delivery Branch
                                                            </div>
                                                            <div class="info-value">
                                                                <?= esc($po['branch']['name'] ?? ($po['branch_id'] ?? 'N/A')) ?>
                                                            </div>
                                                            <?php if (!empty($po['branch']['address'])): ?>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-map-marker-alt"></i> <?= esc($po['branch']['address']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (!empty($po['branch']['phone'])): ?>
                                                                <br><small class="text-muted">
                                                                    <i class="fas fa-phone"></i> <?= esc($po['branch']['phone']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (!empty($po['branch']['manager_name'])): ?>
                                                                <br><small class="text-muted">
                                                                    <i class="fas fa-user-tie"></i> <?= esc($po['branch']['manager_name']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-detail-box">
                                                            <div class="info-label">
                                                                <i class="fas fa-box"></i> Order Details
                                                            </div>
                                                            <div style="margin-top: 5px;">
                                                                <?php if (!empty($po['items_count'])): ?>
                                                                    <span class="badge bg-info" style="margin-right: 5px;">
                                                                        <i class="fas fa-boxes"></i> <?= $po['items_count'] ?> items
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if (!empty($po['items'])): ?>
                                                                    <div style="margin-top: 8px; font-size: 0.9rem;">
                                                                        <?php 
                                                                        $items = is_array($po['items']) ? $po['items'] : [];
                                                                        $displayItems = array_slice($items, 0, 3);
                                                                        foreach ($displayItems as $item): 
                                                                        ?>
                                                                            <div style="padding: 4px 0; border-bottom: 1px solid #e5e7eb;">
                                                                                <i class="fas fa-check-circle text-success me-1" style="font-size: 0.75rem;"></i>
                                                                                <span><?= esc($item['product_name'] ?? $item['name'] ?? 'Item') ?></span>
                                                                                <span class="text-muted">(Qty: <?= $item['quantity'] ?? 1 ?>)</span>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                        <?php if (count($items) > 3): ?>
                                                                            <small class="text-muted">
                                                                                <i class="fas fa-ellipsis-h"></i> +<?= count($items) - 3 ?> more items
                                                                            </small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Notes and Actions -->
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <?php if (!empty($po['notes']) || !empty($po['remarks'])): ?>
                                                            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px 15px; border-radius: 4px;">
                                                                <strong style="color: #856404; font-size: 0.85rem;">
                                                                    <i class="fas fa-sticky-note"></i> Order Notes:
                                                                </strong>
                                                                <p style="margin: 5px 0 0 0; color: #856404; font-size: 0.9rem;">
                                                                    <?= esc($po['notes'] ?? $po['remarks'] ?? '') ?>
                                                                </p>
                                                            </div>
                                                        <?php else: ?>
                                                            <small class="text-muted">
                                                                <i class="fas fa-info-circle"></i> No additional notes for this order
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <button class="btn btn-primary" onclick="scheduleDelivery(<?= $po['id'] ?>)" style="margin-right: 8px;">
                                                            <i class="fas fa-calendar"></i> Schedule Delivery
                                                        </button>
                                                        <button class="btn btn-info" onclick="viewPurchaseOrderDetails(<?= $po['id'] ?>)">
                                                            <i class="fas fa-eye"></i> View Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Delivery Status Cards -->
                    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
                        <!-- Scheduled Deliveries -->
                        <div class="content-card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-check" style="color: var(--info); margin-right: 8px;"></i>
                                    Scheduled Deliveries
                                </h3>
                            </div>
                            <?php if (empty($scheduledDeliveries)): ?>
                                <div style="text-align: center; padding: 20px; color: #64748b;">
                                    <i class="fas fa-calendar-times" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                    <p style="margin: 0; font-size: 0.9rem;">No scheduled deliveries</p>
                                </div>
                            <?php else: ?>
                                <!-- ===== SCHEDULED DELIVERIES DETAILS GUIDE ===== -->
                                <!-- 
                                    DATABASE STRUCTURE REQUIRED for Scheduled Deliveries:
                                    
                                    1. deliveries table:
                                       - id (Primary Key)
                                       - delivery_number (VARCHAR, unique)
                                       - purchase_order_id (INT, foreign key)
                                       - scheduled_date (DATE)
                                       - scheduled_time (TIME)
                                       - driver_id (INT, foreign key to drivers table)
                                       - vehicle_id (INT, foreign key to vehicles table)
                                       - status (ENUM: 'scheduled', 'in_transit', 'delivered', 'cancelled')
                                       - notes (TEXT)
                                       - scheduled_by (INT, foreign key to users table)
                                       - created_at (TIMESTAMP)
                                       - updated_at (TIMESTAMP)
                                    
                                    2. drivers table:
                                       - id (Primary Key)
                                       - name (VARCHAR)
                                       - license_number (VARCHAR)
                                       - phone (VARCHAR)
                                       - status (ENUM: 'available', 'busy', 'off_duty')
                                    
                                    3. vehicles table:
                                       - id (Primary Key)
                                       - vehicle_number (VARCHAR)
                                       - vehicle_type (ENUM: 'truck', 'van', 'car')
                                       - capacity (VARCHAR)
                                       - status (ENUM: 'available', 'in_transit', 'maintenance')
                                    
                                    4. delivery_items table (if tracking per item):
                                       - id (Primary Key)
                                       - delivery_id (INT, foreign key)
                                       - purchase_order_item_id (INT, foreign key)
                                       - quantity (INT)
                                       - status (ENUM: 'pending', 'loaded', 'delivered')
                                -->
                                
                                <?php foreach ($scheduledDeliveries as $delivery): ?>
                                <div class="delivery-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= esc($delivery['delivery_number']) ?></strong>
                                            <span class="status-badge status-scheduled ms-2">Scheduled</span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt"></i> 
                                                <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?>
                                                <?php if (!empty($delivery['scheduled_time'])): ?>
                                                    at <?= date('h:i A', strtotime($delivery['scheduled_time'])) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Delivery Progress -->
                                    <div class="progress-container">
                                        <div class="progress-bar progress-scheduled" style="width: 33%"></div>
                                    </div>
                                    
                                    <!-- Timeline -->
                                    <div class="timeline">
                                        <div class="timeline-item completed">
                                            <small class="text-muted">Order Approved</small>
                                        </div>
                                        <div class="timeline-item active">
                                            <small class="text-muted">Scheduled for Delivery</small>
                                        </div>
                                        <div class="timeline-item">
                                            <small class="text-muted">In Transit</small>
                                        </div>
                                        <div class="timeline-item">
                                            <small class="text-muted">Delivered</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-detail-box">
                                                <div class="info-label">Driver Information</div>
                                                <div class="info-value">
                                                    <?= esc($delivery['driver_name'] ?? 'Not assigned') ?>
                                                    <?php if (!empty($delivery['driver_phone'])): ?>
                                                        <br><small class="text-muted">
                                                            <i class="fas fa-phone"></i> <?= esc($delivery['driver_phone']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($delivery['driver_status'])): ?>
                                                    <div class="driver-status mt-1">
                                                        <i class="fas fa-circle <?= $delivery['driver_status'] === 'available' ? 'driver-online' : ($delivery['driver_status'] === 'busy' ? 'driver-busy' : 'driver-offline') ?>"></i>
                                                        <small class="text-muted"><?= ucfirst($delivery['driver_status']) ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-detail-box">
                                                <div class="info-label">Vehicle Information</div>
                                                <div class="info-value">
                                                    <?= esc($delivery['vehicle_info'] ?? 'Not assigned') ?>
                                                    <?php if (!empty($delivery['vehicle_type'])): ?>
                                                        <br><small class="text-muted">
                                                            <i class="fas fa-truck"></i> <?= ucfirst($delivery['vehicle_type']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($delivery['vehicle_capacity'])): ?>
                                                        <br><small class="text-muted">
                                                            <i class="fas fa-weight-hanging"></i> <?= $delivery['vehicle_capacity'] ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($delivery['vehicle_status'])): ?>
                                                    <span class="vehicle-status vehicle-<?= $delivery['vehicle_status'] ?>">
                                                        <?= ucfirst($delivery['vehicle_status']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-detail-box mt-3">
                                        <div class="info-label">Delivery Details</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">PO Number:</small><br>
                                                <strong><?= esc($delivery['purchase_order']['order_number'] ?? $delivery['purchase_order_id'] ?? 'N/A') ?></strong>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Branch:</small><br>
                                                <strong><?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></strong>
                                            </div>
                                        </div>
                                        <?php if (!empty($delivery['notes'])): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">Notes:</small><br>
                                                <small><?= esc($delivery['notes']) ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                            <i class="fas fa-truck"></i> Mark In Transit
                                        </button>
                                        <button class="btn btn-sm btn-info ms-1" onclick="viewDeliveryDetails(<?= $delivery['id'] ?>)">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                        <button class="btn btn-sm btn-secondary ms-1" onclick="rescheduleDelivery(<?= $delivery['id'] ?>)">
                                            <i class="fas fa-calendar-alt"></i> Reschedule
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- In Transit Deliveries -->
                        <div class="content-card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-truck" style="color: var(--warning); margin-right: 8px;"></i>
                                    In Transit Deliveries
                                </h3>
                            </div>
                            <?php if (empty($inTransitDeliveries)): ?>
                                <div style="text-align: center; padding: 20px; color: #64748b;">
                                    <i class="fas fa-truck-moving" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                    <p style="margin: 0; font-size: 0.9rem;">No deliveries in transit</p>
                                </div>
                            <?php else: ?>
                                <!-- ===== IN TRANSIT DELIVERIES DETAILS GUIDE ===== -->
                                <!-- 
                                    DATABASE STRUCTURE REQUIRED for In Transit Deliveries:
                                    
                                    Additional fields for tracking:
                                    
                                    1. deliveries table add:
                                       - actual_departure_time (DATETIME)
                                       - estimated_arrival_time (DATETIME)
                                       - current_location (VARCHAR)
                                       - tracking_number (VARCHAR)
                                       - distance_traveled (DECIMAL, in km)
                                       - estimated_time_remaining (INT, in minutes)
                                    
                                    2. delivery_tracking table (for location updates):
                                       - id (Primary Key)
                                       - delivery_id (INT, foreign key)
                                       - latitude (DECIMAL)
                                       - longitude (DECIMAL)
                                       - location_name (VARCHAR)
                                       - timestamp (DATETIME)
                                       - notes (TEXT)
                                    
                                    3. delivery_status_updates table:
                                       - id (Primary Key)
                                       - delivery_id (INT, foreign key)
                                       - status (ENUM: 'departed', 'in_transit', 'delayed', 'arrived')
                                       - notes (TEXT)
                                       - created_at (TIMESTAMP)
                                -->
                                
                                <?php foreach ($inTransitDeliveries as $delivery): ?>
                                <div class="delivery-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= esc($delivery['delivery_number']) ?></strong>
                                            <span class="status-badge status-in_transit ms-2">In Transit</span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 
                                                <?php 
                                                if (!empty($delivery['estimated_time_remaining'])) {
                                                    $hours = floor($delivery['estimated_time_remaining'] / 60);
                                                    $minutes = $delivery['estimated_time_remaining'] % 60;
                                                    echo "ETA: " . ($hours > 0 ? "{$hours}h " : "") . "{$minutes}m";
                                                }
                                                ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Delivery Progress -->
                                    <div class="progress-container">
                                        <div class="progress-bar progress-in_transit" style="width: 66%"></div>
                                    </div>
                                    
                                    <!-- Timeline -->
                                    <div class="timeline">
                                        <div class="timeline-item completed">
                                            <small class="text-muted">Order Approved</small>
                                        </div>
                                        <div class="timeline-item completed">
                                            <small class="text-muted">Scheduled</small>
                                        </div>
                                        <div class="timeline-item active">
                                            <small class="text-muted">In Transit</small>
                                            <?php if (!empty($delivery['current_location'])): ?>
                                                <br><small class="text-success">
                                                    <i class="fas fa-map-marker-alt"></i> <?= esc($delivery['current_location']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="timeline-item">
                                            <small class="text-muted">Delivered</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-detail-box">
                                                <div class="info-label">Driver & Vehicle</div>
                                                <div class="info-value">
                                                    <i class="fas fa-user"></i> <?= esc($delivery['driver_name'] ?? 'N/A') ?>
                                                    <br>
                                                    <i class="fas fa-truck"></i> <?= esc($delivery['vehicle_info'] ?? 'N/A') ?>
                                                </div>
                                                <?php if (!empty($delivery['driver_phone'])): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone"></i> <?= esc($delivery['driver_phone']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-detail-box">
                                                <div class="info-label">Tracking Information</div>
                                                <?php if (!empty($delivery['tracking_number'])): ?>
                                                    <div class="info-value">
                                                        <i class="fas fa-barcode"></i> <?= esc($delivery['tracking_number']) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($delivery['distance_traveled'])): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-road"></i> <?= number_format($delivery['distance_traveled'], 1) ?> km traveled
                                                    </small>
                                                <?php endif; ?>
                                                <?php if (!empty($delivery['departed_at'])): ?>
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-sign-out-alt"></i> Departed: <?= date('h:i A', strtotime($delivery['departed_at'])) ?>
                                                    </small>
                                                <?php endif; ?>
                                                <?php if (!empty($delivery['estimated_arrival_time'])): ?>
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-sign-in-alt"></i> Estimated Arrival: <?= date('h:i A', strtotime($delivery['estimated_arrival_time'])) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Live Tracking -->
                                    <div class="info-detail-box mt-3">
                                        <div class="info-label">Live Status</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Current Location:</small><br>
                                                <strong><?= esc($delivery['current_location'] ?? 'Updating...') ?></strong>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Last Update:</small><br>
                                                <strong><?= !empty($delivery['last_tracking_update']) ? date('h:i A', strtotime($delivery['last_tracking_update'])) : 'No updates' ?></strong>
                                            </div>
                                        </div>
                                        <?php if (!empty($delivery['next_checkpoint'])): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">Next Checkpoint:</small><br>
                                                <small><?= esc($delivery['next_checkpoint']) ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delivered')">
                                            <i class="fas fa-check"></i> Mark Delivered
                                        </button>
                                        <button class="btn btn-sm btn-warning ms-1" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delayed')">
                                            <i class="fas fa-clock"></i> Report Delay
                                        </button>
                                        <button class="btn btn-sm btn-info ms-1" onclick="viewLiveTracking(<?= $delivery['id'] ?>)">
                                            <i class="fas fa-map-marked-alt"></i> Live Tracking
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Schedule Delivery Section -->
                <div id="schedule-section" class="content-section">
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-calendar-plus" style="font-size: 1.5rem;"></i>
                                Schedule Delivery
                            </h3>
                        </div>
                        <?php if (empty($pendingPOs)): ?>
                            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No pending purchase orders to schedule</p>
                            </div>
                        <?php else: ?>
                            <div class="mb-3" style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="info-label">Total Pending</div>
                                        <div class="info-value" style="font-size: 1.5rem;"><?= count($pendingPOs) ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-label">Total Amount</div>
                                        <div class="info-value" style="font-size: 1.5rem;">
                                            ₱<?= number_format(array_sum(array_column($pendingPOs, 'total_amount')), 2) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-label">Urgent Orders</div>
                                        <div class="info-value" style="font-size: 1.5rem; color: #dc3545;">
                                            <?php
                                            $urgentCount = 0;
                                            $today = new DateTime();
                                            foreach ($pendingPOs as $po) {
                                                if (!empty($po['expected_delivery_date'])) {
                                                    $expectedDate = new DateTime($po['expected_delivery_date']);
                                                    $daysDiff = $today->diff($expectedDate)->days;
                                                    if ($daysDiff <= 3 && $expectedDate >= $today) {
                                                        $urgentCount++;
                                                    }
                                                }
                                            }
                                            echo $urgentCount;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-label">Avg. Amount</div>
                                        <div class="info-value" style="font-size: 1.5rem;">
                                            ₱<?= count($pendingPOs) > 0 ? number_format(array_sum(array_column($pendingPOs, 'total_amount')) / count($pendingPOs), 2) : '0.00' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fleet Availability -->
                            <div class="content-card mb-3">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <i class="fas fa-truck-loading"></i> Fleet Availability
                                    </h4>
                                </div>
                                <div class="row" id="fleetAvailability">
                                    <!-- Fleet data will be loaded here -->
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <?php foreach ($pendingPOs as $po): ?>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h5 class="mb-1"><?= esc($po['order_number'] ?? 'N/A') ?></h5>
                                                    <p class="text-muted mb-2">
                                                        Supplier: <?= esc($po['supplier']['name'] ?? 'N/A') ?> | 
                                                        Branch: <?= esc($po['branch']['name'] ?? 'N/A') ?>
                                                    </p>
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-boxes"></i> <?= $po['items_count'] ?? 0 ?> items
                                                        </span>
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-money-bill-wave"></i> ₱<?= number_format($po['total_amount'] ?? 0, 2) ?>
                                                        </span>
                                                        <?php if (!empty($po['expected_delivery_date'])): ?>
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-calendar"></i> Expected: <?= date('M d', strtotime($po['expected_delivery_date'])) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <button class="btn btn-primary" onclick="openScheduleModal(<?= $po['id'] ?>)">
                                                        <i class="fas fa-calendar"></i> Schedule Now
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Track Orders Section -->
                <div id="track-section" class="content-section">
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-search" style="font-size: 1.5rem;"></i>
                                Order Tracking
                            </h3>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Order Number, Supplier, or Branch..." id="searchOrders">
                                <button class="btn btn-primary" onclick="searchOrders()">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <button class="btn btn-secondary" onclick="clearSearch()">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                        
                        <div id="trackingResults">
                            <?php if (empty($allOrders ?? [])): ?>
                                <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                    <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500;">No purchase orders found</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>PO Number</th>
                                                <th>Supplier</th>
                                                <th>Branch</th>
                                                <th>Status</th>
                                                <th>Expected Date</th>
                                                <th class="text-end">Total Amount</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($allOrders as $order): ?>
                                            <tr>
                                                <td>
                                                    <span style="font-weight: 600; color: #1e293b;"><?= esc($order['order_number'] ?? 'N/A') ?></span>
                                                </td>
                                                <td><?= esc($order['supplier']['name'] ?? ($order['supplier_id'] ?? 'N/A')) ?></td>
                                                <td><?= esc($order['branch']['name'] ?? ($order['branch_id'] ?? 'N/A')) ?></td>
                                                <td>
                                                    <?php 
                                                    $status = strtolower($order['status'] ?? 'pending');
                                                    if ($status === 'partial_delivery') {
                                                        $status = 'partial';
                                                    }
                                                    $statusDisplay = ucwords(str_replace('_', ' ', $order['status'] ?? 'pending'));
                                                    ?>
                                                    <span class="status-badge status-<?= esc($status) ?>">
                                                        <?= esc($statusDisplay) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($order['expected_delivery_date']): ?>
                                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                                        <?= date('M d, Y', strtotime($order['expected_delivery_date'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not set</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end" style="font-weight: 600; color: var(--primary-green);">
                                                    ₱<?= number_format($order['total_amount'] ?? 0, 2) ?>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $order['id'] ?>)">
                                                        <i class="fas fa-eye me-1"></i> Track
                                                    </button>
                                                    <button class="btn btn-sm btn-secondary" onclick="viewOrderHistory(<?= $order['id'] ?>)">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Deliveries Section -->
                <div id="deliveries-section" class="content-section">
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-truck" style="font-size: 1.5rem;"></i>
                                All Deliveries
                            </h3>
                        </div>
                        
                        <!-- Filter Options -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-control" id="filterStatus" onchange="filterDeliveries()">
                                    <option value="">All Status</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="filterDate" onchange="filterDeliveries()">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="filterDriver" onchange="filterDeliveries()">
                                    <option value="">All Drivers</option>
                                    <!-- Drivers will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-secondary w-100" onclick="resetFilters()">
                                    <i class="fas fa-redo"></i> Reset Filters
                                </button>
                            </div>
                        </div>
                        
                        <?php if (empty($allDeliveries ?? [])): ?>
                            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <i class="fas fa-truck" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No deliveries found</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Delivery Number</th>
                                            <th>PO Number</th>
                                            <th>Supplier</th>
                                            <th>Branch</th>
                                            <th>Status</th>
                                            <th>Scheduled Date</th>
                                            <th>Driver</th>
                                            <th>Vehicle</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allDeliveries as $delivery): ?>
                                        <tr>
                                            <td><strong><?= esc($delivery['delivery_number'] ?? 'N/A') ?></strong></td>
                                            <td><?= esc($delivery['purchase_order']['order_number'] ?? ($delivery['purchase_order_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($delivery['supplier']['name'] ?? ($delivery['supplier_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= esc($delivery['status'] ?? 'pending') ?>">
                                                    <?= esc(ucwords(str_replace('_', ' ', $delivery['status'] ?? 'pending'))) ?>
                                                </span>
                                            </td>
                                            <td><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'Not set' ?></td>
                                            <td><?= esc($delivery['driver_name'] ?? 'N/A') ?></td>
                                            <td><?= esc($delivery['vehicle_info'] ?? 'N/A') ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <?php if ($delivery['status'] === 'scheduled'): ?>
                                                        <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')" title="Mark In Transit">
                                                            <i class="fas fa-truck"></i>
                                                        </button>
                                                    <?php elseif ($delivery['status'] === 'in_transit'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delivered')" title="Mark Delivered">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-info" onclick="viewDeliveryDetails(<?= $delivery['id'] ?>)" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-secondary" onclick="viewDeliveryHistory(<?= $delivery['id'] ?>)" title="History">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Schedules Section -->
                <div id="schedules-section" class="content-section">
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-file-contract" style="font-size: 1.5rem;"></i>
                                Scheduled Deliveries Calendar
                            </h3>
                        </div>
                        
                        <div class="mb-3">
                            <div id="calendarControls" class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-secondary" onclick="prevWeek()">
                                    <i class="fas fa-chevron-left"></i> Previous Week
                                </button>
                                <h4 id="calendarWeekTitle" style="margin: 0;"></h4>
                                <button class="btn btn-secondary" onclick="nextWeek()">
                                    Next Week <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            
                            <div id="calendarView" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                                <!-- Calendar will be populated dynamically -->
                            </div>
                        </div>
                        
                        <?php if (empty($scheduledDeliveries)): ?>
                            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <i class="fas fa-calendar-times" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No scheduled deliveries</p>
                            </div>
                        <?php else: ?>
                            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                <?php foreach ($scheduledDeliveries as $delivery): ?>
                                <div class="delivery-card">
                                    <!-- Delivery card content same as in dashboard -->
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Fleet Management Section -->
                <div id="fleet-section" class="content-section">
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-truck-loading" style="font-size: 1.5rem;"></i>
                                Fleet Management
                            </h3>
                        </div>
                        
                        <!-- ===== FLEET MANAGEMENT GUIDE ===== -->
                        <!-- 
                            DATABASE STRUCTURE REQUIRED for Fleet Management:
                            
                            1. vehicles table:
                               - id (Primary Key)
                               - vehicle_number (VARCHAR, unique)
                               - vehicle_type (ENUM: 'small_truck', 'medium_truck', 'large_truck', 'van', 'car')
                               - capacity (VARCHAR)
                               - make (VARCHAR)
                               - model (VARCHAR)
                               - year (INT)
                               - fuel_type (ENUM: 'diesel', 'petrol', 'electric')
                               - current_mileage (DECIMAL)
                               - status (ENUM: 'available', 'in_transit', 'maintenance', 'reserved')
                               - last_maintenance_date (DATE)
                               - next_maintenance_date (DATE)
                               - created_at (TIMESTAMP)
                            
                            2. drivers table:
                               - id (Primary Key)
                               - name (VARCHAR)
                               - license_number (VARCHAR)
                               - license_expiry (DATE)
                               - phone (VARCHAR)
                               - email (VARCHAR)
                               - status (ENUM: 'available', 'busy', 'off_duty', 'on_leave')
                               - current_location (VARCHAR)
                               - assigned_vehicle_id (INT, foreign key)
                               - created_at (TIMESTAMP)
                            
                            3. maintenance_records table:
                               - id (Primary Key)
                               - vehicle_id (INT, foreign key)
                               - maintenance_type (ENUM: 'routine', 'repair', 'inspection')
                               - description (TEXT)
                               - cost (DECIMAL)
                               - date_performed (DATE)
                               - next_due_date (DATE)
                               - technician (VARCHAR)
                               - notes (TEXT)
                        -->
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-card-header">
                                        <div class="stat-icon primary">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="totalVehicles">0</h3>
                                    <p class="stat-label">Total Vehicles</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-card-header">
                                        <div class="stat-icon success">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="availableVehicles">0</h3>
                                    <p class="stat-label">Available</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-card-header">
                                        <div class="stat-icon warning">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="maintenanceVehicles">0</h3>
                                    <p class="stat-label">In Maintenance</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-card-header">
                                        <div class="stat-icon info">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="totalDrivers">0</h3>
                                    <p class="stat-label">Active Drivers</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="content-card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <i class="fas fa-truck"></i> Vehicle Fleet
                                        </h4>
                                        <button class="btn btn-sm btn-primary" onclick="addVehicle()">
                                            <i class="fas fa-plus"></i> Add Vehicle
                                        </button>
                                    </div>
                                    <div id="vehicleList" class="table-responsive">
                                        <!-- Vehicles will be loaded here -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="content-card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            <i class="fas fa-user"></i> Drivers
                                        </h4>
                                        <button class="btn btn-sm btn-primary" onclick="addDriver()">
                                            <i class="fas fa-plus"></i> Add Driver
                                        </button>
                                    </div>
                                    <div id="driverList" class="table-responsive">
                                        <!-- Drivers will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="content-card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="fas fa-tools"></i> Upcoming Maintenance
                                </h4>
                            </div>
                            <div id="maintenanceList">
                                <!-- Maintenance records will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

<!-- Schedule Delivery Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Delivery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <input type="hidden" id="poId" name="purchase_order_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Scheduled Date *</label>
                                <input type="date" class="form-control" id="scheduledDate" name="scheduled_date" required min="<?= date('Y-m-d') ?>">
                                <small class="text-muted">Must be a future date</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Scheduled Time *</label>
                                <input type="time" class="form-control" id="scheduledTime" name="scheduled_time" required>
                                <small class="text-muted">Preferred delivery time</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Select Driver *</label>
                                <select class="form-control" id="driverSelect" name="driver_id" required>
                                    <option value="">Select a driver</option>
                                    <!-- Drivers will be populated dynamically -->
                                </select>
                                <div id="driverInfo" class="mt-2" style="display: none;">
                                    <small class="text-muted" id="driverLicense"></small><br>
                                    <small class="text-muted" id="driverPhone"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Select Vehicle *</label>
                                <select class="form-control" id="vehicleSelect" name="vehicle_id" required>
                                    <option value="">Select a vehicle</option>
                                    <!-- Vehicles will be populated dynamically -->
                                </select>
                                <div id="vehicleInfo" class="mt-2" style="display: none;">
                                    <small class="text-muted" id="vehicleType"></small><br>
                                    <small class="text-muted" id="vehicleCapacity"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tracking Number (Optional)</label>
                        <input type="text" class="form-control" id="trackingNumber" name="tracking_number" placeholder="e.g., TRK-123456">
                        <small class="text-muted">For external tracking systems</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Special Instructions / Notes</label>
                        <textarea class="form-control" id="deliveryNotes" name="notes" rows="3" placeholder="Any special instructions for the driver..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Important:</strong> Ensure the selected driver and vehicle are available on the scheduled date and time.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitSchedule()">
                    <i class="fas fa-calendar-check"></i> Schedule Delivery
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Order Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Tracking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="trackingContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Delivery Details Modal -->
<div class="modal fade" id="deliveryDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delivery Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="deliveryDetailsContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Live Tracking Modal -->
<div class="modal fade" id="liveTrackingModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Live Delivery Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="liveTrackingContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Management Modal -->
<div class="modal fade" id="vehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vehicleModalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Driver Management Modal -->
<div class="modal fade" id="driverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="driverModalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Global variables
let currentWeekStart = new Date();
let fleetData = {};
let driversData = {};

// Initialize when document is ready
$(document).ready(function() {
    // Hide all sections except dashboard
    $('.dashboard-content .content-section').not('#dashboard-section').removeClass('active').hide();
    
    // Show dashboard section
    $('#dashboard-section').addClass('active').show();
    
    // Set first nav item as active
    $('.nav-item').first().addClass('active');
    
    // Load fleet data
    loadFleetData();
    
    // Load drivers for filters
    loadDriversForFilter();
    
    // Initialize calendar
    initializeCalendar();
    
    // Set default date for filter
    $('#filterDate').val(new Date().toISOString().split('T')[0]);
});

// Section Navigation
function showSection(sectionId, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Use jQuery since it's loaded
    $('.dashboard-content .content-section').removeClass('active').hide();
    $('.nav-item').removeClass('active');
    
    const targetSection = $('#' + sectionId + '-section');
    if (targetSection.length) {
        targetSection.addClass('active').show();
    }
    
    // Set active nav item
    $(event ? event.target.closest('.nav-item') : $('.nav-item').eq(['dashboard', 'schedule', 'track', 'deliveries', 'schedules', 'fleet'].indexOf(sectionId))).addClass('active');
    
    $('html, body').animate({ scrollTop: 0 }, 300);
    
    // Load specific data if needed
    if (sectionId === 'fleet') {
        loadFleetManagementData();
    }
    
    return false;
}

// ===== PURCHASE ORDER FUNCTIONS =====
function viewPurchaseOrderDetails(poId) {
    $.get('<?= base_url('purchase/order/') ?>' + poId + '/details', function(response) {
        if (response.status === 'success') {
            const order = response.order;
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Order Information</div>
                            <div class="info-value">${order.order_number}</div>
                            <p class="mb-1"><strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status}</span></p>
                            <p class="mb-1"><strong>Created:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                            ${order.expected_delivery_date ? `<p class="mb-1"><strong>Expected Delivery:</strong> ${new Date(order.expected_delivery_date).toLocaleDateString()}</p>` : ''}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Financial Information</div>
                            <p class="mb-1"><strong>Total Amount:</strong> ₱${parseFloat(order.total_amount).toFixed(2)}</p>
                            <p class="mb-1"><strong>Tax:</strong> ₱${parseFloat(order.tax_amount || 0).toFixed(2)}</p>
                            <p class="mb-1"><strong>Discount:</strong> ₱${parseFloat(order.discount_amount || 0).toFixed(2)}</p>
                            <p class="mb-1"><strong>Final Amount:</strong> ₱${parseFloat(order.final_amount || order.total_amount).toFixed(2)}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Supplier Information</div>
                            <p class="mb-1"><strong>Name:</strong> ${order.supplier ? order.supplier.name : 'N/A'}</p>
                            ${order.supplier && order.supplier.contact_person ? `<p class="mb-1"><strong>Contact:</strong> ${order.supplier.contact_person}</p>` : ''}
                            ${order.supplier && order.supplier.phone ? `<p class="mb-1"><strong>Phone:</strong> ${order.supplier.phone}</p>` : ''}
                            ${order.supplier && order.supplier.email ? `<p class="mb-1"><strong>Email:</strong> ${order.supplier.email}</p>` : ''}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Delivery Branch</div>
                            <p class="mb-1"><strong>Branch:</strong> ${order.branch ? order.branch.name : 'N/A'}</p>
                            ${order.branch && order.branch.address ? `<p class="mb-1"><strong>Address:</strong> ${order.branch.address}</p>` : ''}
                            ${order.branch && order.branch.phone ? `<p class="mb-1"><strong>Phone:</strong> ${order.branch.phone}</p>` : ''}
                            ${order.branch && order.branch.manager_name ? `<p class="mb-1"><strong>Manager:</strong> ${order.branch.manager_name}</p>` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            if (order.items && order.items.length > 0) {
                order.items.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.product_name || 'Product'}</td>
                            <td>${item.quantity || 0}</td>
                            <td>₱${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                            <td>₱${parseFloat(item.total_price || 0).toFixed(2)}</td>
                            <td><span class="status-badge status-${item.status || 'pending'}">${item.status || 'Pending'}</span></td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="5" class="text-center">No items found</td></tr>`;
            }
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
                
                ${order.notes ? `
                <div class="alert alert-warning mt-3">
                    <strong><i class="fas fa-sticky-note"></i> Notes:</strong>
                    <p class="mb-0">${order.notes}</p>
                </div>
                ` : ''}
            `;
            
            $('#trackingContent').html(html);
            $('#trackingModal').modal('show');
        } else {
            alert('Error loading order details: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading order details');
    });
}

// ===== SCHEDULING FUNCTIONS =====
function openScheduleModal(poId) {
    $('#poId').val(poId);
    
    // Set default date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('#scheduledDate').val(tomorrow.toISOString().split('T')[0]);
    
    // Set default time to 9:00 AM
    $('#scheduledTime').val('09:00');
    
    // Load available drivers and vehicles
    loadAvailableDrivers();
    loadAvailableVehicles();
    
    $('#scheduleModal').modal('show');
}

function loadAvailableDrivers() {
    $.get('<?= base_url('delivery/available-drivers') ?>', function(response) {
        if (response.status === 'success') {
            const driverSelect = $('#driverSelect');
            driverSelect.empty();
            driverSelect.append('<option value="">Select a driver</option>');
            
            response.drivers.forEach(driver => {
                driverSelect.append(`<option value="${driver.id}" data-license="${driver.license_number || ''}" data-phone="${driver.phone || ''}">${driver.name} (${driver.status})</option>`);
            });
        }
    });
}

function loadAvailableVehicles() {
    $.get('<?= base_url('delivery/available-vehicles') ?>', function(response) {
        if (response.status === 'success') {
            const vehicleSelect = $('#vehicleSelect');
            vehicleSelect.empty();
            vehicleSelect.append('<option value="">Select a vehicle</option>');
            
            response.vehicles.forEach(vehicle => {
                vehicleSelect.append(`<option value="${vehicle.id}" data-type="${vehicle.vehicle_type || ''}" data-capacity="${vehicle.capacity || ''}">${vehicle.vehicle_number} (${vehicle.vehicle_type})</option>`);
            });
        }
    });
}

// Show driver info when selected
$('#driverSelect').change(function() {
    const selectedOption = $(this).find(':selected');
    if (selectedOption.val()) {
        $('#driverInfo').show();
        $('#driverLicense').html(`<i class="fas fa-id-card"></i> License: ${selectedOption.data('license') || 'N/A'}`);
        $('#driverPhone').html(`<i class="fas fa-phone"></i> Phone: ${selectedOption.data('phone') || 'N/A'}`);
    } else {
        $('#driverInfo').hide();
    }
});

// Show vehicle info when selected
$('#vehicleSelect').change(function() {
    const selectedOption = $(this).find(':selected');
    if (selectedOption.val()) {
        $('#vehicleInfo').show();
        $('#vehicleType').html(`<i class="fas fa-truck"></i> Type: ${selectedOption.data('type') || 'N/A'}`);
        $('#vehicleCapacity').html(`<i class="fas fa-weight-hanging"></i> Capacity: ${selectedOption.data('capacity') || 'N/A'}`);
    } else {
        $('#vehicleInfo').hide();
    }
});

function submitSchedule() {
    const formData = {
        purchase_order_id: $('#poId').val(),
        scheduled_date: $('#scheduledDate').val(),
        scheduled_time: $('#scheduledTime').val(),
        driver_id: $('#driverSelect').val(),
        vehicle_id: $('#vehicleSelect').val(),
        tracking_number: $('#trackingNumber').val(),
        notes: $('#deliveryNotes').val()
    };

    // Validate form
    if (!formData.scheduled_date || !formData.scheduled_time || !formData.driver_id || !formData.vehicle_id) {
        alert('Please fill all required fields');
        return;
    }

    $.ajax({
        url: '<?= base_url('delivery/schedule') ?>',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            if (response.status === 'success') {
                alert('Delivery scheduled successfully!');
                $('#scheduleModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            alert('Error scheduling delivery: ' + error);
        }
    });
}

function rescheduleDelivery(deliveryId) {
    // Similar to scheduleDelivery but for existing deliveries
    // You would load existing delivery data and allow rescheduling
    alert('Reschedule feature coming soon!');
}

// ===== DELIVERY TRACKING FUNCTIONS =====
function trackOrder(poId) {
    $.get('<?= base_url('purchase/order/') ?>' + poId + '/track', function(response) {
        if (response.status === 'success') {
            const order = response.order;
            let html = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-detail-box">
                            <div class="info-label">Order Information</div>
                            <div class="info-value">${order.order_number}</div>
                            <p><strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status}</span></p>
                            <p><strong>Supplier:</strong> ${order.supplier ? order.supplier.name : 'N/A'}</p>
                            <p><strong>Branch:</strong> ${order.branch ? order.branch.name : 'N/A'}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-detail-box">
                            <div class="info-label">Delivery Information</div>
            `;
            
            if (order.delivery) {
                html += `
                    <p><strong>Delivery #:</strong> ${order.delivery.delivery_number}</p>
                    <p><strong>Status:</strong> <span class="status-badge status-${order.delivery.status}">${order.delivery.status}</span></p>
                    <p><strong>Scheduled:</strong> ${new Date(order.delivery.scheduled_date).toLocaleDateString()}</p>
                    ${order.delivery.driver_name ? `<p><strong>Driver:</strong> ${order.delivery.driver_name}</p>` : ''}
                `;
            } else {
                html += `<p class="text-muted">No delivery scheduled yet</p>`;
            }
            
            html += `
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-detail-box">
                            <div class="info-label">Timeline</div>
                            <div class="timeline">
                                <div class="timeline-item ${order.status !== 'pending' ? 'completed' : 'active'}">
                                    <small class="text-muted">Order Created</small>
                                    <br><small>${new Date(order.created_at).toLocaleDateString()}</small>
                                </div>
            `;
            
            // Add timeline items based on order and delivery status
            if (order.delivery) {
                const delivery = order.delivery;
                html += `
                    <div class="timeline-item ${delivery.status !== 'scheduled' ? 'completed' : 'active'}">
                        <small class="text-muted">Scheduled for Delivery</small>
                        <br><small>${new Date(delivery.scheduled_date).toLocaleDateString()}</small>
                    </div>
                `;
                
                if (delivery.status === 'in_transit' || delivery.status === 'delivered') {
                    html += `
                        <div class="timeline-item ${delivery.status === 'delivered' ? 'completed' : 'active'}">
                            <small class="text-muted">In Transit</small>
                            ${delivery.actual_departure_time ? `<br><small>Departed: ${new Date(delivery.actual_departure_time).toLocaleTimeString()}</small>` : ''}
                        </div>
                    `;
                }
                
                if (delivery.status === 'delivered') {
                    html += `
                        <div class="timeline-item completed">
                            <small class="text-muted">Delivered</small>
                            ${delivery.actual_delivery_date ? `<br><small>${new Date(delivery.actual_delivery_date).toLocaleDateString()}</small>` : ''}
                        </div>
                    `;
                }
            }
            
            html += `
                            </div>
                        </div>
                    </div>
                </div>
                
                ${order.delivery && order.delivery.status === 'in_transit' ? `
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-satellite-dish"></i> Live Tracking</h5>
                            <p>Delivery is currently in transit</p>
                            ${order.delivery.current_location ? `<p><strong>Current Location:</strong> ${order.delivery.current_location}</p>` : ''}
                            ${order.delivery.estimated_arrival_time ? `<p><strong>Estimated Arrival:</strong> ${new Date(order.delivery.estimated_arrival_time).toLocaleString()}</p>` : ''}
                            <button class="btn btn-sm btn-primary" onclick="viewLiveTracking(${order.delivery.id})">
                                <i class="fas fa-map-marked-alt"></i> View Live Map
                            </button>
                        </div>
                    </div>
                </div>
                ` : ''}
            `;
            
            $('#trackingContent').html(html);
            $('#trackingModal').modal('show');
        } else {
            alert('Error: ' + (response.message || 'Unknown error'));
        }
    });
}

function viewDeliveryDetails(deliveryId) {
    $.get('<?= base_url('delivery/') ?>' + deliveryId + '/details', function(response) {
        if (response.status === 'success') {
            const delivery = response.delivery;
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Delivery Information</div>
                            <div class="info-value">${delivery.delivery_number}</div>
                            <p><strong>Status:</strong> <span class="status-badge status-${delivery.status}">${delivery.status}</span></p>
                            <p><strong>Scheduled Date:</strong> ${new Date(delivery.scheduled_date).toLocaleDateString()}</p>
                            ${delivery.scheduled_time ? `<p><strong>Scheduled Time:</strong> ${delivery.scheduled_time}</p>` : ''}
                            ${delivery.tracking_number ? `<p><strong>Tracking #:</strong> ${delivery.tracking_number}</p>` : ''}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Personnel & Vehicle</div>
                            <p><strong>Driver:</strong> ${delivery.driver_name || 'N/A'}</p>
                            ${delivery.driver_phone ? `<p><strong>Driver Phone:</strong> ${delivery.driver_phone}</p>` : ''}
                            <p><strong>Vehicle:</strong> ${delivery.vehicle_info || 'N/A'}</p>
                            ${delivery.vehicle_type ? `<p><strong>Type:</strong> ${delivery.vehicle_type}</p>` : ''}
                            ${delivery.vehicle_capacity ? `<p><strong>Capacity:</strong> ${delivery.vehicle_capacity}</p>` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Order Information</div>
                            <p><strong>PO Number:</strong> ${delivery.purchase_order ? delivery.purchase_order.order_number : 'N/A'}</p>
                            <p><strong>Supplier:</strong> ${delivery.supplier ? delivery.supplier.name : 'N/A'}</p>
                            <p><strong>Branch:</strong> ${delivery.branch ? delivery.branch.name : 'N/A'}</p>
                            ${delivery.branch && delivery.branch.address ? `<p><strong>Address:</strong> ${delivery.branch.address}</p>` : ''}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-detail-box">
                            <div class="info-label">Tracking Information</div>
            `;
            
            if (delivery.status === 'in_transit' || delivery.status === 'delivered') {
                if (delivery.actual_departure_time) {
                    html += `<p><strong>Departed:</strong> ${new Date(delivery.actual_departure_time).toLocaleString()}</p>`;
                }
                if (delivery.current_location) {
                    html += `<p><strong>Current Location:</strong> ${delivery.current_location}</p>`;
                }
                if (delivery.estimated_arrival_time) {
                    html += `<p><strong>Estimated Arrival:</strong> ${new Date(delivery.estimated_arrival_time).toLocaleString()}</p>`;
                }
                if (delivery.distance_traveled) {
                    html += `<p><strong>Distance Traveled:</strong> ${delivery.distance_traveled} km</p>`;
                }
            }
            
            if (delivery.status === 'delivered' && delivery.actual_delivery_date) {
                html += `<p><strong>Delivered:</strong> ${new Date(delivery.actual_delivery_date).toLocaleString()}</p>`;
            }
            
            html += `
                        </div>
                    </div>
                </div>
                
                ${delivery.notes ? `
                <div class="alert alert-warning mt-3">
                    <strong><i class="fas fa-sticky-note"></i> Delivery Notes:</strong>
                    <p class="mb-0">${delivery.notes}</p>
                </div>
                ` : ''}
                
                <div class="mt-3">
                    <h5>Delivery Items</h5>
            `;
            
            if (delivery.items && delivery.items.length > 0) {
                html += `
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                delivery.items.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.product_name || 'Product'}</td>
                            <td>${item.quantity || 0}</td>
                            <td><span class="status-badge status-${item.status || 'pending'}">${item.status || 'Pending'}</span></td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                html += `<p class="text-muted">No delivery items found</p>`;
            }
            
            html += `
                </div>
            `;
            
            $('#deliveryDetailsContent').html(html);
            $('#deliveryDetailsModal').modal('show');
        } else {
            alert('Error loading delivery details: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading delivery details');
    });
}

function viewLiveTracking(deliveryId) {
    $.get('<?= base_url('delivery/') ?>' + deliveryId + '/live-tracking', function(response) {
        if (response.status === 'success') {
            const tracking = response.tracking;
            let html = `
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-map-marked-alt"></i> Live Map</h6>
                            </div>
                            <div class="card-body" style="height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <!-- In a real application, you would integrate with Google Maps or similar -->
                                <div style="text-align: center;">
                                    <i class="fas fa-map fa-3x text-muted mb-3"></i>
                                    <p>Live map integration would go here</p>
                                    <p><strong>Current Location:</strong> ${tracking.current_location || 'Unknown'}</p>
                                    ${tracking.last_update ? `<p><small>Last update: ${new Date(tracking.last_update).toLocaleTimeString()}</small></p>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-detail-box">
                            <div class="info-label">Delivery Information</div>
                            <p><strong>Delivery #:</strong> ${tracking.delivery_number}</p>
                            <p><strong>Driver:</strong> ${tracking.driver_name || 'N/A'}</p>
                            <p><strong>Vehicle:</strong> ${tracking.vehicle_info || 'N/A'}</p>
                            ${tracking.estimated_arrival_time ? `<p><strong>ETA:</strong> ${new Date(tracking.estimated_arrival_time).toLocaleTimeString()}</p>` : ''}
                            ${tracking.distance_traveled ? `<p><strong>Distance:</strong> ${tracking.distance_traveled} km</p>` : ''}
                            ${tracking.estimated_time_remaining ? `
                                <p><strong>Time Remaining:</strong> 
                                    ${Math.floor(tracking.estimated_time_remaining / 60)}h ${tracking.estimated_time_remaining % 60}m
                                </p>
                            ` : ''}
                        </div>
                        
                        <div class="info-detail-box mt-3">
                            <div class="info-label">Location History</div>
            `;
            
            if (tracking.history && tracking.history.length > 0) {
                tracking.history.forEach(update => {
                    html += `
                        <div style="border-bottom: 1px solid #e5e7eb; padding: 8px 0;">
                            <small><strong>${update.location_name}</strong></small><br>
                            <small class="text-muted">${new Date(update.timestamp).toLocaleTimeString()}</small>
                        </div>
                    `;
                });
            } else {
                html += `<p class="text-muted">No location history available</p>`;
            }
            
            html += `
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Tracking Information</h6>
                            <p class="mb-2">This live tracking updates every 5 minutes. For real-time tracking, drivers can update their location through the mobile app.</p>
                            <button class="btn btn-sm btn-primary" onclick="refreshTracking(${deliveryId})">
                                <i class="fas fa-sync-alt"></i> Refresh Location
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="contactDriver(${deliveryId})">
                                <i class="fas fa-phone"></i> Contact Driver
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            $('#liveTrackingContent').html(html);
            $('#liveTrackingModal').modal('show');
        } else {
            alert('Error loading live tracking: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading live tracking');
    });
}

function refreshTracking(deliveryId) {
    // This would trigger a location update request to the driver
    alert('Location refresh request sent to driver');
}

function contactDriver(deliveryId) {
    // This would show driver contact information or initiate a call
    alert('Driver contact feature coming soon!');
}

// ===== DELIVERY STATUS UPDATES =====
function updateDeliveryStatus(deliveryId, status) {
    let message = `Update delivery status to ${status}?`;
    if (status === 'delivered') {
        message = "Mark this delivery as delivered? This will complete the delivery process.";
    } else if (status === 'in_transit') {
        message = "Mark this delivery as in transit? This indicates the driver has departed.";
    }
    
    if (confirm(message)) {
        const data = {
            status: status,
            actual_delivery_date: status === 'delivered' ? new Date().toISOString().split('T')[0] : null,
            actual_departure_time: status === 'in_transit' ? new Date().toISOString() : null
        };
        
        $.ajax({
            url: '<?= base_url('delivery/') ?>' + deliveryId + '/update-status',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                if (response.status === 'success') {
                    alert('Delivery status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                alert('Error updating delivery status');
            }
        });
    }
}

function updateDeliveryStatus(deliveryId, status) {
    if (status === 'delayed') {
        const reason = prompt('Please enter the reason for delay:');
        if (!reason) return;
        
        const data = {
            status: 'delayed',
            delay_reason: reason,
            estimated_arrival_time: prompt('Enter new estimated arrival time (YYYY-MM-DD HH:MM):')
        };
        
        $.ajax({
            url: '<?= base_url('delivery/') ?>' + deliveryId + '/update-status',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                if (response.status === 'success') {
                    alert('Delivery marked as delayed');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            }
        });
    } else {
        // Existing code for other status updates
        const data = {
            status: status,
            actual_delivery_date: status === 'delivered' ? new Date().toISOString().split('T')[0] : null,
            actual_departure_time: status === 'in_transit' ? new Date().toISOString() : null
        };
        
        // ... rest of existing code
    }
}

// ===== SEARCH AND FILTER FUNCTIONS =====
function searchOrders() {
    const searchTerm = $('#searchOrders').val().trim();
    if (!searchTerm) {
        alert('Please enter a search term');
        return;
    }
    
    $.get('<?= base_url('purchase/search') ?>', { q: searchTerm }, function(response) {
        if (response.status === 'success') {
            displaySearchResults(response.orders);
        } else {
            alert('Search error: ' + (response.message || 'Unknown error'));
        }
    });
}

function displaySearchResults(orders) {
    let html = '';
    
    if (orders.length === 0) {
        html = `
            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                <i class="fas fa-search" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No orders found matching your search</p>
            </div>
        `;
    } else {
        html = `
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Expected Date</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        orders.forEach(order => {
            const status = strtolower(order.status || 'pending');
            const statusDisplay = ucwords(str_replace('_', ' ', order.status || 'pending'));
            
            html += `
                <tr>
                    <td><span style="font-weight: 600;">${order.order_number || 'N/A'}</span></td>
                    <td>${order.supplier?.name || order.supplier_id || 'N/A'}</td>
                    <td>${order.branch?.name || order.branch_id || 'N/A'}</td>
                    <td><span class="status-badge status-${status}">${statusDisplay}</span></td>
                    <td>${order.expected_delivery_date ? new Date(order.expected_delivery_date).toLocaleDateString() : 'Not set'}</td>
                    <td class="text-end" style="font-weight: 600;">₱${parseFloat(order.total_amount || 0).toFixed(2)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" onclick="trackOrder(${order.id})">
                            <i class="fas fa-eye me-1"></i> Track
                        </button>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
    }
    
    $('#trackingResults').html(html);
}

function clearSearch() {
    $('#searchOrders').val('');
    location.reload();
}

function filterDeliveries() {
    const status = $('#filterStatus').val();
    const date = $('#filterDate').val();
    const driver = $('#filterDriver').val();
    
    // In a real application, this would make an AJAX call to filter deliveries
    // For now, we'll just show an alert
    alert(`Filtering deliveries by: Status=${status}, Date=${date}, Driver=${driver}`);
    
    // Example AJAX call:
    /*
    $.get('<?= base_url('delivery/filter') ?>', {
        status: status,
        date: date,
        driver: driver
    }, function(response) {
        // Update the deliveries table with filtered results
    });
    */
}

function resetFilters() {
    $('#filterStatus').val('');
    $('#filterDate').val(new Date().toISOString().split('T')[0]);
    $('#filterDriver').val('');
    filterDeliveries();
}

function loadDriversForFilter() {
    $.get('<?= base_url('delivery/drivers') ?>', function(response) {
        if (response.status === 'success') {
            const filterDriver = $('#filterDriver');
            response.drivers.forEach(driver => {
                filterDriver.append(`<option value="${driver.id}">${driver.name}</option>`);
            });
        }
    });
}

// ===== CALENDAR FUNCTIONS =====
function initializeCalendar() {
    updateCalendar();
}

function updateCalendar() {
    const start = new Date(currentWeekStart);
    start.setDate(start.getDate() - start.getDay()); // Start on Sunday
    
    const end = new Date(start);
    end.setDate(end.getDate() + 6); // End on Saturday
    
    $('#calendarWeekTitle').text(
        `Week of ${start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${end.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`
    );
    
    const calendarView = $('#calendarView');
    calendarView.empty();
    
    // Add day headers
    for (let i = 0; i < 7; i++) {
        const day = new Date(start);
        day.setDate(day.getDate() + i);
        
        const dayName = day.toLocaleDateString('en-US', { weekday: 'short' });
        const dayNumber = day.getDate();
        const isToday = isSameDay(day, new Date());
        
        calendarView.append(`
            <div class="text-center ${isToday ? 'bg-light' : ''}" style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div class="fw-bold ${isToday ? 'text-primary' : 'text-muted'}">${dayName}</div>
                <div class="h4 ${isToday ? 'text-primary' : ''}">${dayNumber}</div>
                <div id="deliveries-${day.toISOString().split('T')[0]}" class="mt-2">
                    <!-- Deliveries for this day will be added here -->
                </div>
            </div>
        `);
    }
    
    // Load deliveries for this week
    loadWeekDeliveries(start, end);
}

function loadWeekDeliveries(startDate, endDate) {
    $.get('<?= base_url('delivery/week-deliveries') ?>', {
        start_date: startDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0]
    }, function(response) {
        if (response.status === 'success') {
            response.deliveries.forEach(delivery => {
                const deliveryDate = new Date(delivery.scheduled_date).toISOString().split('T')[0];
                const dayElement = $(`#deliveries-${deliveryDate}`);
                
                if (dayElement.length) {
                    const statusColor = delivery.status === 'scheduled' ? 'primary' : 
                                      delivery.status === 'in_transit' ? 'warning' : 
                                      delivery.status === 'delivered' ? 'success' : 'secondary';
                    
                    dayElement.append(`
                        <div class="mb-1 p-1" style="background: var(--bs-${statusColor}-bg-subtle); border-left: 3px solid var(--bs-${statusColor}); border-radius: 4px;">
                            <small style="font-size: 0.7rem;">
                                <strong>${delivery.delivery_number}</strong><br>
                                ${delivery.driver_name || 'No driver'}<br>
                                <span class="badge bg-${statusColor}">${delivery.status}</span>
                            </small>
                        </div>
                    `);
                }
            });
        }
    });
}

function prevWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    updateCalendar();
}

function nextWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    updateCalendar();
}

function isSameDay(date1, date2) {
    return date1.getFullYear() === date2.getFullYear() &&
           date1.getMonth() === date2.getMonth() &&
           date1.getDate() === date2.getDate();
}

// ===== FLEET MANAGEMENT FUNCTIONS =====
function loadFleetData() {
    $.get('<?= base_url('fleet/data') ?>', function(response) {
        if (response.status === 'success') {
            fleetData = response;
            updateFleetAvailability();
        }
    });
}

function updateFleetAvailability() {
    if (!fleetData || !fleetData.vehicles) return;
    
    const fleetAvailability = $('#fleetAvailability');
    fleetAvailability.empty();
    
    // Count vehicles by status
    const statusCount = {
        available: 0,
        in_transit: 0,
        maintenance: 0,
        reserved: 0
    };
    
    fleetData.vehicles.forEach(vehicle => {
        statusCount[vehicle.status] = (statusCount[vehicle.status] || 0) + 1;
    });
    
    // Update statistics
    $('#totalVehicles').text(fleetData.vehicles.length);
    $('#availableVehicles').text(statusCount.available || 0);
    $('#maintenanceVehicles').text(statusCount.maintenance || 0);
    
    // Create availability cards
    fleetData.vehicles.slice(0, 4).forEach(vehicle => {
        const statusColor = vehicle.status === 'available' ? 'success' :
                          vehicle.status === 'in_transit' ? 'warning' :
                          vehicle.status === 'maintenance' ? 'danger' : 'secondary';
        
        fleetAvailability.append(`
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-truck fa-2x text-${statusColor}"></i>
                        </div>
                        <h6 class="card-title">${vehicle.vehicle_number}</h6>
                        <p class="card-text">
                            <small class="text-muted">${vehicle.vehicle_type || 'N/A'}</small><br>
                            <small>Capacity: ${vehicle.capacity || 'N/A'}</small>
                        </p>
                        <span class="badge bg-${statusColor}">${vehicle.status}</span>
                    </div>
                </div>
            </div>
        `);
    });
}

function loadFleetManagementData() {
    $.get('<?= base_url('fleet/management-data') ?>', function(response) {
        if (response.status === 'success') {
            updateVehicleList(response.vehicles);
            updateDriverList(response.drivers);
            updateMaintenanceList(response.maintenance);
            
            // Update driver count
            $('#totalDrivers').text(response.drivers.length);
        }
    });
}

function updateVehicleList(vehicles) {
    const vehicleList = $('#vehicleList');
    let html = `
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Vehicle #</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Last Maintenance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    vehicles.forEach(vehicle => {
        const statusColor = vehicle.status === 'available' ? 'success' :
                          vehicle.status === 'in_transit' ? 'warning' :
                          vehicle.status === 'maintenance' ? 'danger' : 'secondary';
        
        html += `
            <tr>
                <td>${vehicle.vehicle_number}</td>
                <td>${vehicle.vehicle_type || 'N/A'}</td>
                <td>${vehicle.capacity || 'N/A'}</td>
                <td><span class="badge bg-${statusColor}">${vehicle.status}</span></td>
                <td>${vehicle.last_maintenance_date ? new Date(vehicle.last_maintenance_date).toLocaleDateString() : 'Never'}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editVehicle(${vehicle.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="scheduleMaintenance(${vehicle.id})">
                        <i class="fas fa-tools"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    `;
    
    vehicleList.html(html);
}

function updateDriverList(drivers) {
    const driverList = $('#driverList');
    let html = `
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>License #</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Assigned Vehicle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    drivers.forEach(driver => {
        const statusColor = driver.status === 'available' ? 'success' :
                          driver.status === 'busy' ? 'warning' :
                          driver.status === 'off_duty' ? 'secondary' : 'info';
        
        html += `
            <tr>
                <td>${driver.name}</td>
                <td>${driver.license_number || 'N/A'}</td>
                <td>${driver.phone || 'N/A'}</td>
                <td><span class="badge bg-${statusColor}">${driver.status}</span></td>
                <td>${driver.assigned_vehicle_id || 'Not assigned'}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editDriver(${driver.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="viewDriverSchedule(${driver.id})">
                        <i class="fas fa-calendar"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    `;
    
    driverList.html(html);
}

function updateMaintenanceList(maintenanceRecords) {
    const maintenanceList = $('#maintenanceList');
    
    if (!maintenanceRecords || maintenanceRecords.length === 0) {
        maintenanceList.html('<p class="text-muted text-center">No upcoming maintenance scheduled</p>');
        return;
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Maintenance Type</th>
                        <th>Date Performed</th>
                        <th>Next Due Date</th>
                        <th>Technician</th>
                        <th>Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    maintenanceRecords.forEach(record => {
        const dueSoon = new Date(record.next_due_date) < new Date(Date.now() + 7 * 24 * 60 * 60 * 1000); // Due within 7 days
        const overdue = new Date(record.next_due_date) < new Date();
        
        html += `
            <tr class="${overdue ? 'table-danger' : dueSoon ? 'table-warning' : ''}">
                <td>${record.vehicle?.vehicle_number || 'N/A'}</td>
                <td>${record.maintenance_type}</td>
                <td>${new Date(record.date_performed).toLocaleDateString()}</td>
                <td>
                    ${new Date(record.next_due_date).toLocaleDateString()}
                    ${overdue ? '<span class="badge bg-danger ms-1">Overdue</span>' : 
                      dueSoon ? '<span class="badge bg-warning ms-1">Due Soon</span>' : ''}
                </td>
                <td>${record.technician || 'N/A'}</td>
                <td>₱${parseFloat(record.cost || 0).toFixed(2)}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewMaintenanceDetails(${record.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-success" onclick="markMaintenanceDone(${record.id})">
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    maintenanceList.html(html);
}

function addVehicle() {
    $('#vehicleModalContent').html(`
        <form id="vehicleForm">
            <div class="mb-3">
                <label class="form-label">Vehicle Number *</label>
                <input type="text" class="form-control" name="vehicle_number" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Vehicle Type *</label>
                <select class="form-control" name="vehicle_type" required>
                    <option value="">Select type</option>
                    <option value="small_truck">Small Truck</option>
                    <option value="medium_truck">Medium Truck</option>
                    <option value="large_truck">Large Truck</option>
                    <option value="van">Van</option>
                    <option value="car">Car</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Capacity</label>
                <input type="text" class="form-control" name="capacity" placeholder="e.g., 5000 kg, 10 pallets">
            </div>
            <div class="mb-3">
                <label class="form-label">Make</label>
                <input type="text" class="form-control" name="make">
            </div>
            <div class="mb-3">
                <label class="form-label">Model</label>
                <input type="text" class="form-control" name="model">
            </div>
            <div class="mb-3">
                <label class="form-label">Year</label>
                <input type="number" class="form-control" name="year" min="2000" max="2024">
            </div>
            <div class="mb-3">
                <label class="form-label">Fuel Type</label>
                <select class="form-control" name="fuel_type">
                    <option value="diesel">Diesel</option>
                    <option value="petrol">Petrol</option>
                    <option value="electric">Electric</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Mileage</label>
                <input type="number" class="form-control" name="current_mileage" step="0.1">
            </div>
        </form>
    `);
    
    $('#vehicleModal').modal('show');
}

function editVehicle(vehicleId) {
    $.get('<?= base_url('fleet/vehicle/') ?>' + vehicleId, function(response) {
        if (response.status === 'success') {
            const vehicle = response.vehicle;
            $('#vehicleModalContent').html(`
                <form id="vehicleForm">
                    <input type="hidden" name="id" value="${vehicle.id}">
                    <div class="mb-3">
                        <label class="form-label">Vehicle Number *</label>
                        <input type="text" class="form-control" name="vehicle_number" value="${vehicle.vehicle_number}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle Type *</label>
                        <select class="form-control" name="vehicle_type" required>
                            <option value="">Select type</option>
                            <option value="small_truck" ${vehicle.vehicle_type === 'small_truck' ? 'selected' : ''}>Small Truck</option>
                            <option value="medium_truck" ${vehicle.vehicle_type === 'medium_truck' ? 'selected' : ''}>Medium Truck</option>
                            <option value="large_truck" ${vehicle.vehicle_type === 'large_truck' ? 'selected' : ''}>Large Truck</option>
                            <option value="van" ${vehicle.vehicle_type === 'van' ? 'selected' : ''}>Van</option>
                            <option value="car" ${vehicle.vehicle_type === 'car' ? 'selected' : ''}>Car</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="available" ${vehicle.status === 'available' ? 'selected' : ''}>Available</option>
                            <option value="in_transit" ${vehicle.status === 'in_transit' ? 'selected' : ''}>In Transit</option>
                            <option value="maintenance" ${vehicle.status === 'maintenance' ? 'selected' : ''}>Maintenance</option>
                            <option value="reserved" ${vehicle.status === 'reserved' ? 'selected' : ''}>Reserved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Maintenance Date</label>
                        <input type="date" class="form-control" name="last_maintenance_date" value="${vehicle.last_maintenance_date || ''}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Next Maintenance Date</label>
                        <input type="date" class="form-control" name="next_maintenance_date" value="${vehicle.next_maintenance_date || ''}">
                    </div>
                </form>
            `);
            
            $('#vehicleModal').modal('show');
        }
    });
}

function scheduleMaintenance(vehicleId) {
    $('#vehicleModalContent').html(`
        <form id="maintenanceForm">
            <input type="hidden" name="vehicle_id" value="${vehicleId}">
            <div class="mb-3">
                <label class="form-label">Maintenance Type *</label>
                <select class="form-control" name="maintenance_type" required>
                    <option value="">Select type</option>
                    <option value="routine">Routine Maintenance</option>
                    <option value="repair">Repair</option>
                    <option value="inspection">Inspection</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Estimated Cost</label>
                <input type="number" class="form-control" name="cost" step="0.01" min="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Scheduled Date *</label>
                <input type="date" class="form-control" name="scheduled_date" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Technician</label>
                <input type="text" class="form-control" name="technician">
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2"></textarea>
            </div>
        </form>
    `);
    
    // Set default date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('input[name="scheduled_date"]').val(tomorrow.toISOString().split('T')[0]);
    
    $('#vehicleModal').modal('show');
}

function addDriver() {
    $('#driverModalContent').html(`
        <form id="driverForm">
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">License Number *</label>
                <input type="text" class="form-control" name="license_number" required>
            </div>
            <div class="mb-3">
                <label class="form-label">License Expiry Date *</label>
                <input type="date" class="form-control" name="license_expiry" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone *</label>
                <input type="tel" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="available">Available</option>
                    <option value="off_duty">Off Duty</option>
                    <option value="on_leave">On Leave</option>
                </select>
            </div>
        </form>
    `);
    
    $('#driverModal').modal('show');
}

function editDriver(driverId) {
    $.get('<?= base_url('fleet/driver/') ?>' + driverId, function(response) {
        if (response.status === 'success') {
            const driver = response.driver;
            $('#driverModalContent').html(`
                <form id="driverForm">
                    <input type="hidden" name="id" value="${driver.id}">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="name" value="${driver.name}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">License Number *</label>
                        <input type="text" class="form-control" name="license_number" value="${driver.license_number}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">License Expiry Date *</label>
                        <input type="date" class="form-control" name="license_expiry" value="${driver.license_expiry}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="tel" class="form-control" name="phone" value="${driver.phone}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="available" ${driver.status === 'available' ? 'selected' : ''}>Available</option>
                            <option value="busy" ${driver.status === 'busy' ? 'selected' : ''}>Busy</option>
                            <option value="off_duty" ${driver.status === 'off_duty' ? 'selected' : ''}>Off Duty</option>
                            <option value="on_leave" ${driver.status === 'on_leave' ? 'selected' : ''}>On Leave</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned Vehicle</label>
                        <select class="form-control" name="assigned_vehicle_id">
                            <option value="">None</option>
                            <!-- Vehicles would be populated here -->
                        </select>
                    </div>
                </form>
            `);
            
            $('#driverModal').modal('show');
        }
    });
}

function viewDriverSchedule(driverId) {
    // This would show the driver's schedule for the week/month
    alert('Driver schedule view coming soon!');
}

function viewMaintenanceDetails(maintenanceId) {
    $.get('<?= base_url('fleet/maintenance/') ?>' + maintenanceId, function(response) {
        if (response.status === 'success') {
            const record = response.maintenance;
            $('#vehicleModalContent').html(`
                <div class="info-detail-box">
                    <div class="info-label">Maintenance Details</div>
                    <p><strong>Vehicle:</strong> ${record.vehicle?.vehicle_number || 'N/A'}</p>
                    <p><strong>Type:</strong> ${record.maintenance_type}</p>
                    <p><strong>Description:</strong> ${record.description}</p>
                    <p><strong>Date Performed:</strong> ${new Date(record.date_performed).toLocaleDateString()}</p>
                    <p><strong>Next Due Date:</strong> ${new Date(record.next_due_date).toLocaleDateString()}</p>
                    <p><strong>Technician:</strong> ${record.technician || 'N/A'}</p>
                    <p><strong>Cost:</strong> ₱${parseFloat(record.cost || 0).toFixed(2)}</p>
                    ${record.notes ? `<p><strong>Notes:</strong> ${record.notes}</p>` : ''}
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" onclick="editMaintenance(${record.id})">
                        <i class="fas fa-edit"></i> Edit Record
                    </button>
                    <button class="btn btn-success" onclick="markMaintenanceDone(${record.id})">
                        <i class="fas fa-check"></i> Mark as Done
                    </button>
                </div>
            `);
            
            $('#vehicleModal').modal('show');
        }
    });
}

function markMaintenanceDone(maintenanceId) {
    if (confirm('Mark this maintenance as completed?')) {
        const today = new Date().toISOString().split('T')[0];
        
        $.ajax({
            url: '<?= base_url('fleet/maintenance/') ?>' + maintenanceId + '/complete',
            method: 'POST',
            data: { date_performed: today },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Maintenance marked as completed');
                    loadFleetManagementData();
                    $('#vehicleModal').modal('hide');
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            }
        });
    }
}

// ===== UTILITY FUNCTIONS =====
function generateDeliveryReport() {
    const startDate = prompt('Enter start date (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
    if (!startDate) return;
    
    const endDate = prompt('Enter end date (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
    if (!endDate) return;
    
    const reportType = prompt('Enter report type (daily, weekly, monthly):', 'weekly');
    
    $.ajax({
        url: '<?= base_url('reports/generate-delivery') ?>',
        method: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate,
            report_type: reportType
        },
        xhrFields: {
            responseType: 'blob' // Important for file download
        },
        success: function(blob) {
            // Create a download link for the file
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `delivery-report-${startDate}-to-${endDate}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },
        error: function() {
            alert('Error generating report');
        }
    });
}

function viewOrderHistory(orderId) {
    // This would show the complete history of an order
    alert('Order history feature coming soon!');
}

function viewDeliveryHistory(deliveryId) {
    // This would show the complete history of a delivery
    alert('Delivery history feature coming soon!');
}

// Helper function for string manipulation (from PHP)
function strtolower(str) {
    return str ? str.toLowerCase() : '';
}

function ucwords(str) {
    return str ? str.replace(/\b\w/g, char => char.toUpperCase()) : '';
}

function str_replace(search, replace, subject) {
    return subject ? subject.split(search).join(replace) : '';
}
</script>
</body>
</html>