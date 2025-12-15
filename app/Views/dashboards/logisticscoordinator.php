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
        
        .status-received { 
            background: #17a2b8; 
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

                    <!-- Quick Actions & Recent Activity Row -->
                    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <!-- Quick Actions -->
                        <div class="content-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                                <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-bolt" style="font-size: 1.5rem;"></i>
                                    Quick Actions
                                </h3>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                <button class="btn btn-primary w-100" onclick="showSection('schedule', event);" style="padding: 15px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <i class="fas fa-calendar-plus" style="font-size: 1.5rem;"></i>
                                    <span>Schedule Delivery</span>
                                </button>
                                <button class="btn btn-info w-100" onclick="showSection('track', event);" style="padding: 15px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <i class="fas fa-search" style="font-size: 1.5rem;"></i>
                                    <span>Track Order</span>
                                </button>
                                <button class="btn btn-success w-100" onclick="exportDeliveries()" style="padding: 15px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <i class="fas fa-file-export" style="font-size: 1.5rem;"></i>
                                    <span>Export Data</span>
                                </button>
                                <button class="btn btn-warning w-100" onclick="printReport()" style="padding: 15px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <i class="fas fa-print" style="font-size: 1.5rem;"></i>
                                    <span>Print Report</span>
                                </button>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="content-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                                <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-history" style="font-size: 1.5rem;"></i>
                                    Recent Activity
                                </h3>
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <?php 
                                $recentActivities = [];
                                if (!empty($scheduledDeliveries)) {
                                    foreach (array_slice($scheduledDeliveries, 0, 3) as $delivery) {
                                        $recentActivities[] = [
                                            'type' => 'scheduled',
                                            'message' => 'Delivery ' . esc($delivery['delivery_number']) . ' scheduled',
                                            'time' => !empty($delivery['created_at']) ? $delivery['created_at'] : 'Recently',
                                            'icon' => 'fa-calendar-check',
                                            'color' => 'info'
                                        ];
                                    }
                                }
                                if (!empty($inTransitDeliveries)) {
                                    foreach (array_slice($inTransitDeliveries, 0, 2) as $delivery) {
                                        $recentActivities[] = [
                                            'type' => 'in_transit',
                                            'message' => 'Delivery ' . esc($delivery['delivery_number']) . ' in transit',
                                            'time' => !empty($delivery['updated_at']) ? $delivery['updated_at'] : 'Recently',
                                            'icon' => 'fa-truck',
                                            'color' => 'warning'
                                        ];
                                    }
                                }
                                if (empty($recentActivities)):
                                ?>
                                    <div style="text-align: center; padding: 30px 20px; color: #64748b;">
                                        <i class="fas fa-inbox" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                        <p style="margin: 0; font-size: 0.9rem;">No recent activity</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($recentActivities as $activity): ?>
                                    <div style="display: flex; align-items: start; gap: 12px; padding: 12px; border-bottom: 1px solid #e5e7eb; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa';" onmouseout="this.style.background='transparent';">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: <?= $activity['color'] === 'info' ? '#0dcaf0' : '#ffc107' ?>20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas <?= $activity['icon'] ?>" style="color: <?= $activity['color'] === 'info' ? '#0dcaf0' : '#ffc107' ?>;"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <p style="margin: 0; font-size: 0.9rem; font-weight: 500; color: #1e293b;"><?= $activity['message'] ?></p>
                                            <small style="color: #64748b; font-size: 0.75rem;">
                                                <i class="fas fa-clock"></i> <?= $activity['time'] ?>
                                            </small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Delivery Calendar Widget -->
                        <div class="content-card">
                            <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                                <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-calendar-alt" style="font-size: 1.5rem;"></i>
                                    Upcoming Deliveries
                                </h3>
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <?php 
                                $upcomingDeliveries = [];
                                if (!empty($scheduledDeliveries)) {
                                    foreach ($scheduledDeliveries as $delivery) {
                                        if (!empty($delivery['scheduled_date'])) {
                                            $upcomingDeliveries[] = $delivery;
                                        }
                                    }
                                }
                                usort($upcomingDeliveries, function($a, $b) {
                                    return strtotime($a['scheduled_date'] ?? '') - strtotime($b['scheduled_date'] ?? '');
                                });
                                $upcomingDeliveries = array_slice($upcomingDeliveries, 0, 5);
                                
                                if (empty($upcomingDeliveries)):
                                ?>
                                    <div style="text-align: center; padding: 30px 20px; color: #64748b;">
                                        <i class="fas fa-calendar-times" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                        <p style="margin: 0; font-size: 0.9rem;">No upcoming deliveries</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($upcomingDeliveries as $delivery): ?>
                                    <div style="display: flex; align-items: start; gap: 12px; padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                        <div style="width: 50px; text-align: center; flex-shrink: 0;">
                                            <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary-green);">
                                                <?= date('d', strtotime($delivery['scheduled_date'])) ?>
                                            </div>
                                            <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase;">
                                                <?= date('M', strtotime($delivery['scheduled_date'])) ?>
                                            </div>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <p style="margin: 0; font-size: 0.9rem; font-weight: 600; color: #1e293b;">
                                                <?= esc($delivery['delivery_number']) ?>
                                            </p>
                                            <small style="color: #64748b; font-size: 0.75rem;">
                                                <i class="fas fa-building"></i> <?= esc($delivery['branch']['name'] ?? 'N/A') ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-info" style="font-size: 0.7rem;">Scheduled</span>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-check" style="color: var(--info); margin-right: 8px;"></i>
                                    Scheduled Deliveries
                                </h3>
                                <button class="btn btn-sm btn-info" onclick="loadScheduledDeliveriesSection()" title="Refresh">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                            </div>
                            <div id="scheduledDeliveriesContainer">
                                <!-- Content will be loaded dynamically -->
                            </div>
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
                        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-calendar-plus" style="font-size: 1.5rem;"></i>
                                Schedule Delivery
                            </h3>
                            <button class="btn btn-sm btn-light" onclick="loadPendingPOs()" title="Refresh">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                        <div id="scheduleDeliveryContent">
                            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p style="margin-top: 15px; font-size: 1rem;">Loading pending purchase orders...</p>
                            </div>
                        </div>
                        <?php if (false): // Changed to false to use dynamic loading ?>
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
                                <input type="text" class="form-control" placeholder="Search by Order Number, Supplier, or Branch..." id="searchOrders" onkeypress="handleSearchKeyPress(event)">
                                <button class="btn btn-primary" onclick="searchOrders()">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <button class="btn btn-secondary" onclick="clearSearch()">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                        
                        <div id="trackingResults">
                            <!-- Content will be loaded dynamically via JavaScript -->
                            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p style="margin-top: 15px; font-size: 1rem;">Loading orders...</p>
                            </div>
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
                        
                        <!-- Calendar view loads deliveries dynamically -->
                        <!-- No need for static PHP content here -->
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
                                    <p class="stat-label">Total Drivers</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                                    <div class="stat-card-header">
                                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="confirmedDrivers" style="color: white;">0</h3>
                                    <p class="stat-label" style="color: rgba(255,255,255,0.9);">Confirmed Drivers</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                                    <div class="stat-card-header">
                                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="availableConfirmedDrivers" style="color: white;">0</h3>
                                    <p class="stat-label" style="color: rgba(255,255,255,0.9);">Available & Confirmed</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white;">
                                    <div class="stat-card-header">
                                        <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <h3 class="stat-value" id="pendingDrivers" style="color: white;">0</h3>
                                    <p class="stat-label" style="color: rgba(255,255,255,0.9);">Pending Confirmation</p>
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

<!-- Day Deliveries Modal -->
<div class="modal fade" id="dayDeliveriesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-day"></i> Deliveries for <span id="modalDayDate"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="dayDeliveriesContent">
                <!-- Deliveries will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    
    // Load initial data for track orders section if it's the active section
    if ($('#track-section').hasClass('active')) {
        loadAllOrders();
    }
    
    // Load scheduled deliveries on page load if dashboard section is active
    if ($('#dashboard-section').hasClass('active') || $('#dashboard-section').is(':visible')) {
        if (typeof loadScheduledDeliveriesSection === 'function') {
            loadScheduledDeliveriesSection();
        }
    }
    
    // Load schedule delivery section data on page load
    if ($('#schedule-section').hasClass('active') || $('#schedule-section').is(':visible')) {
        if (typeof loadPendingPOs === 'function') {
            loadPendingPOs();
        }
    }
    
    // Load deliveries section data on page load
    if ($('#deliveries-section').hasClass('active') || $('#deliveries-section').is(':visible')) {
        if (typeof loadAllDeliveries === 'function') {
            loadAllDeliveries();
        }
    }
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
    
    // Load specific data based on section
    switch(sectionId) {
        case 'dashboard':
            // Load scheduled deliveries when dashboard is shown
            if (typeof loadScheduledDeliveriesSection === 'function') {
                loadScheduledDeliveriesSection();
            }
            break;
        case 'schedule':
            loadPendingPOs();
            break;
        case 'track':
            // Always load orders when track section is opened
            loadAllOrders();
            break;
        case 'deliveries':
            loadAllDeliveries();
            break;
        case 'schedules':
            // Initialize calendar when schedules section is opened
            if (typeof initializeCalendar === 'function') {
                initializeCalendar();
            }
            break;
        case 'fleet':
            loadFleetManagementData();
            break;
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
    // Load existing delivery data
    $.get('<?= base_url('delivery/') ?>' + deliveryId + '/track', function(response) {
        if (response.status === 'success') {
            const delivery = response.delivery;
            
            // Create reschedule modal
            const modalHtml = `
                <div class="modal fade" id="rescheduleModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                                <h5 class="modal-title"><i class="fas fa-calendar-alt"></i> Reschedule Delivery</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="rescheduleForm">
                                    <input type="hidden" id="rescheduleDeliveryId" value="${deliveryId}">
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Delivery Number:</strong></label>
                                        <input type="text" class="form-control" value="${delivery.delivery_number || 'N/A'}" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Current Scheduled Date:</strong></label>
                                        <input type="text" class="form-control" value="${delivery.scheduled_date || 'N/A'}" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>New Scheduled Date <span class="text-danger">*</span>:</strong></label>
                                        <input type="date" class="form-control" id="newScheduledDate" required 
                                               value="${delivery.scheduled_date || ''}" 
                                               min="${new Date().toISOString().split('T')[0]}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Driver Name:</strong></label>
                                        <input type="text" class="form-control" id="rescheduleDriverName" 
                                               value="${delivery.driver_name || ''}" 
                                               placeholder="Enter driver name">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Vehicle Info:</strong></label>
                                        <input type="text" class="form-control" id="rescheduleVehicleInfo" 
                                               value="${delivery.vehicle_info || ''}" 
                                               placeholder="e.g., Plate #, Model">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Notes:</strong></label>
                                        <textarea class="form-control" id="rescheduleNotes" rows="3" 
                                                  placeholder="Reason for rescheduling or additional notes">${delivery.notes || ''}</textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="submitReschedule()">
                                    <i class="fas fa-save"></i> Reschedule Delivery
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#rescheduleModal').remove();
            $('body').append(modalHtml);
            $('#rescheduleModal').modal('show');
        } else {
            alert('Error loading delivery details: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading delivery details');
    });
}

function submitReschedule() {
    const deliveryId = $('#rescheduleDeliveryId').val();
    const scheduledDate = $('#newScheduledDate').val();
    const driverName = $('#rescheduleDriverName').val();
    const vehicleInfo = $('#rescheduleVehicleInfo').val();
    const notes = $('#rescheduleNotes').val();
    
    if (!scheduledDate) {
        alert('Please select a new scheduled date');
        return;
    }
    
    const formData = {
        scheduled_date: scheduledDate,
        driver_name: driverName || null,
        vehicle_info: vehicleInfo || null,
        notes: notes || null
    };
    
    $.ajax({
        url: '<?= base_url('logisticscoordinator/delivery/') ?>' + deliveryId + '/reschedule',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            if (response.status === 'success') {
                alert('Delivery rescheduled successfully!');
                $('#rescheduleModal').modal('hide');
                // Reload scheduled deliveries section if it exists
                if (typeof loadScheduledDeliveriesSection === 'function') {
                    loadScheduledDeliveriesSection();
                } else {
                    location.reload();
                }
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            alert('Error rescheduling delivery: ' + error);
        }
    });
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
    $.get('<?= base_url('delivery/') ?>' + deliveryId + '/track', function(response) {
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
    $.get('<?= base_url('logisticscoordinator/delivery/') ?>' + deliveryId + '/driver-contact', function(response) {
        if (response.status === 'success') {
            const driver = response.driver;
            
            let html = `
                <div class="modal fade" id="driverContactModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                                <h5 class="modal-title"><i class="fas fa-user-tie"></i> Driver Contact Information</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="info-detail-box mb-3">
                                    <div class="info-label">Driver Name</div>
                                    <div class="info-value">${driver.name || 'N/A'}</div>
                                </div>
                                
                                <div class="info-detail-box mb-3">
                                    <div class="info-label">Vehicle Information</div>
                                    <div class="info-value">${driver.vehicle_info || 'N/A'}</div>
                                </div>
                                
                                <div class="info-detail-box mb-3">
                                    <div class="info-label">Active Deliveries</div>
                                    <div class="info-value">${driver.active_deliveries || 0} delivery(ies)</div>
                                </div>
                                
                                ${driver.deliveries && driver.deliveries.length > 0 ? `
                                    <div class="mt-3">
                                        <h6><strong>Current Schedule:</strong></h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Delivery #</th>
                                                        <th>Scheduled Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${driver.deliveries.map(d => `
                                                        <tr>
                                                            <td>${d.delivery_number || 'N/A'}</td>
                                                            <td>${new Date(d.scheduled_date).toLocaleDateString()}</td>
                                                            <td><span class="status-badge status-${d.status}">${d.status}</span></td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                ` : ''}
                                
                                <div class="mt-3">
                                    <button class="btn btn-primary" onclick="viewDriverScheduleByName('${driver.name}')">
                                        <i class="fas fa-calendar"></i> View Full Schedule
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#driverContactModal').remove();
            $('body').append(html);
            $('#driverContactModal').modal('show');
        } else {
            alert('Error: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading driver contact information');
    });
}

function viewDriverScheduleByName(driverName) {
    $('#driverContactModal').modal('hide');
    viewDriverSchedule(null, driverName);
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
                    // Reload scheduled deliveries section if it exists
                    if (typeof loadScheduledDeliveriesSection === 'function') {
                        loadScheduledDeliveriesSection();
                    } else {
                        location.reload();
                    }
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

// Removed duplicate function - using the one defined above

// ===== SEARCH AND FILTER FUNCTIONS =====
// searchOrders is now defined in the section data loading functions above

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
    loadAllOrders(); // Reload all orders without search filter
}

// filterDeliveries is now defined in the section data loading functions above

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
        
        const dayDateStr = day.toISOString().split('T')[0];
        calendarView.append(`
            <div class="text-center calendar-day ${isToday ? 'bg-light' : ''}" style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; min-height: 150px;" data-date="${dayDateStr}">
                <div class="fw-bold ${isToday ? 'text-primary' : 'text-muted'}">${dayName}</div>
                <div class="h4 ${isToday ? 'text-primary' : ''}" style="cursor: pointer; user-select: none; transition: all 0.2s; padding: 5px; border-radius: 50%; display: inline-block; min-width: 40px;" 
                     onclick="showDayDeliveries('${dayDateStr}')" 
                     onmouseover="this.style.backgroundColor='#e7f3ff'; this.style.transform='scale(1.1)'" 
                     onmouseout="this.style.backgroundColor='transparent'; this.style.transform='scale(1)'"
                     title="Click to view all deliveries for this day">
                    ${dayNumber}
                </div>
                <div id="deliveries-${dayDateStr}" class="mt-2" style="min-height: 60px;">
                    <!-- Deliveries for this day will be added here -->
                </div>
                <div id="delivery-count-${dayDateStr}" class="mt-1" style="font-size: 0.7rem; color: #64748b;">
                    <!-- Delivery count will be shown here -->
                </div>
            </div>
        `);
    }
    
    // Load deliveries for this week
    loadWeekDeliveries(start, end);
}

function loadWeekDeliveries(startDate, endDate) {
    // Clear all day elements first
    for (let i = 0; i < 7; i++) {
        const day = new Date(startDate);
        day.setDate(day.getDate() + i);
        const dayElement = $(`#deliveries-${day.toISOString().split('T')[0]}`);
        if (dayElement.length) {
            dayElement.empty();
        }
    }
    
    $.get('<?= base_url('delivery/week-deliveries') ?>', {
        start_date: startDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0]
    }, function(response) {
        if (response.status === 'success') {
            const deliveries = response.deliveries || [];
            
            if (deliveries.length === 0) {
                // Show message if no deliveries
                return;
            }
            
            // Group deliveries by date
            const deliveriesByDate = {};
            deliveries.forEach(delivery => {
                if (!delivery.scheduled_date) return;
                const deliveryDate = new Date(delivery.scheduled_date).toISOString().split('T')[0];
                if (!deliveriesByDate[deliveryDate]) {
                    deliveriesByDate[deliveryDate] = [];
                }
                deliveriesByDate[deliveryDate].push(delivery);
            });
            
            // Display deliveries for each day (show first 2, rest will be in modal)
            Object.keys(deliveriesByDate).forEach(date => {
                const dayDeliveries = deliveriesByDate[date];
                const dayElement = $(`#deliveries-${date}`);
                const countElement = $(`#delivery-count-${date}`);
                
                if (dayElement.length) {
                    // Show delivery count
                    countElement.html(`<i class="fas fa-truck"></i> ${dayDeliveries.length} delivery${dayDeliveries.length !== 1 ? 'ies' : ''}`);
                    
                    // Show first 2 deliveries in calendar, rest will be in modal
                    const deliveriesToShow = dayDeliveries.slice(0, 2);
                    const remainingCount = dayDeliveries.length - 2;
                    
                    deliveriesToShow.forEach(delivery => {
                        const statusColor = delivery.status === 'scheduled' ? 'primary' : 
                                          delivery.status === 'in_transit' ? 'warning' : 
                                          delivery.status === 'delivered' ? 'success' : 'secondary';
                        
                        const branchName = delivery.branch ? delivery.branch.name : 'N/A';
                        const supplierName = delivery.supplier ? delivery.supplier.name : 'N/A';
                        const timeDisplay = delivery.scheduled_time ? delivery.scheduled_time.substring(0, 5) : '';
                        
                        dayElement.append(`
                            <div class="mb-2 p-2 delivery-item" style="background: ${statusColor === 'primary' ? '#e7f3ff' : statusColor === 'warning' ? '#fff3cd' : '#d1e7dd'}; border-left: 3px solid ${statusColor === 'primary' ? '#0d6efd' : statusColor === 'warning' ? '#ffc107' : '#198754'}; border-radius: 4px; cursor: pointer;" onclick="event.stopPropagation(); viewDeliveryDetails(${delivery.id})" title="Click to view details">
                                <small style="font-size: 0.75rem; display: block;">
                                    <strong style="color: #1e293b;">${delivery.delivery_number || 'N/A'}</strong>
                                    ${timeDisplay ? `<br><i class="fas fa-clock text-muted"></i> ${timeDisplay}` : ''}
                                    <br><span class="text-muted" style="font-size: 0.7rem;">${branchName}</span>
                                </small>
                            </div>
                        `);
                    });
                    
                    // Show "View more" if there are more deliveries
                    if (remainingCount > 0) {
                        dayElement.append(`
                            <div class="text-center mt-1">
                                <button class="btn btn-sm btn-link p-0" style="font-size: 0.7rem; color: #0d6efd;" onclick="event.stopPropagation(); showDayDeliveries('${date}')">
                                    +${remainingCount} more
                                </button>
                            </div>
                        `);
                    }
                }
            });
            
            // Store deliveries data for modal
            window.weekDeliveriesData = deliveriesByDate;
        } else {
            console.error('Error loading week deliveries:', response.message);
        }
    }).fail(function(xhr, status, error) {
        console.error('Failed to load week deliveries:', error);
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

// Show all deliveries for a specific day
function showDayDeliveries(dateStr) {
    const date = new Date(dateStr);
    const dateFormatted = date.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    $('#modalDayDate').text(dateFormatted);
    
    // Get deliveries for this day
    const deliveries = window.weekDeliveriesData && window.weekDeliveriesData[dateStr] ? window.weekDeliveriesData[dateStr] : [];
    
    const modalContent = $('#dayDeliveriesContent');
    
    if (deliveries.length === 0) {
        modalContent.html(`
            <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                <i class="fas fa-calendar-times" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                <p style="margin: 0; font-size: 1rem; font-weight: 500;">No deliveries scheduled for this day</p>
            </div>
        `);
    } else {
        let html = `
            <div class="mb-3">
                <p class="text-muted"><strong>${deliveries.length}</strong> delivery${deliveries.length !== 1 ? 'ies' : ''} scheduled for this day</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Delivery #</th>
                            <th>Time</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        deliveries.forEach(delivery => {
            const status = (delivery.status || 'scheduled').toLowerCase();
            const statusColor = status === 'scheduled' ? 'primary' : 
                              status === 'in_transit' ? 'warning' : 
                              status === 'delivered' ? 'success' : 'secondary';
            const statusDisplay = (delivery.status || 'scheduled').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            
            const branchName = delivery.branch ? delivery.branch.name : 'N/A';
            const supplierName = delivery.supplier ? delivery.supplier.name : 'N/A';
            const timeDisplay = delivery.scheduled_time ? delivery.scheduled_time.substring(0, 5) : 'Not set';
            
            html += `
                <tr>
                    <td><strong>${delivery.delivery_number || 'N/A'}</strong></td>
                    <td><i class="fas fa-clock text-muted"></i> ${timeDisplay}</td>
                    <td>${branchName}</td>
                    <td>${supplierName}</td>
                    <td>${delivery.driver_name || 'Not assigned'}</td>
                    <td>${delivery.vehicle_info || 'Not assigned'}</td>
                    <td><span class="badge bg-${statusColor}">${statusDisplay}</span></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewDeliveryDetails(${delivery.id}); $('#dayDeliveriesModal').modal('hide');">
                            <i class="fas fa-eye"></i> View
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
        
        modalContent.html(html);
    }
    
    $('#dayDeliveriesModal').modal('show');
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
            
            // Update driver statistics
            const drivers = response.drivers || [];
            const totalDrivers = drivers.length;
            const confirmedDrivers = drivers.filter(d => d.is_confirmed == 1 || d.is_confirmed === true || d.confirmed == 1 || d.confirmed === true).length;
            const availableConfirmedDrivers = drivers.filter(d => 
                (d.is_confirmed == 1 || d.is_confirmed === true || d.confirmed == 1 || d.confirmed === true) && 
                d.status === 'available'
            ).length;
            const pendingDrivers = totalDrivers - confirmedDrivers;
            
            $('#totalDrivers').text(totalDrivers);
            $('#confirmedDrivers').text(confirmedDrivers);
            $('#availableConfirmedDrivers').text(availableConfirmedDrivers);
            $('#pendingDrivers').text(pendingDrivers);
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
                    <th>Confirmed</th>
                    <th>Assigned Vehicle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    if (!drivers || drivers.length === 0) {
        html += `
            <tr>
                <td colspan="7" class="text-center text-muted">No drivers found</td>
            </tr>
        `;
    } else {
        drivers.forEach(driver => {
            const statusColor = driver.status === 'available' ? 'success' :
                              driver.status === 'busy' ? 'warning' :
                              driver.status === 'off_duty' ? 'secondary' : 'info';
            
            const isConfirmed = driver.is_confirmed == 1 || driver.is_confirmed === true || driver.confirmed == 1 || driver.confirmed === true;
            const confirmedBadge = isConfirmed 
                ? '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Confirmed</span>'
                : '<span class="badge bg-secondary"><i class="fas fa-clock"></i> Pending</span>';
            
            // Highlight row if confirmed and available
            const rowClass = isConfirmed && driver.status === 'available' ? 'table-success' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td><strong>${driver.name}</strong></td>
                    <td>${driver.license_number || 'N/A'}</td>
                    <td>${driver.phone || 'N/A'}</td>
                    <td><span class="badge bg-${statusColor}">${driver.status}</span></td>
                    <td>${confirmedBadge}</td>
                    <td>${driver.assigned_vehicle_id || 'Not assigned'}</td>
                    <td>
                        ${!isConfirmed ? `
                            <button class="btn btn-sm btn-success" onclick="confirmDriver(${driver.id}, '${driver.name}')" title="Confirm Driver">
                                <i class="fas fa-check"></i> Confirm
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-info" onclick="editDriver(${driver.id})" title="Edit Driver">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="viewDriverSchedule(${driver.id})" title="View Schedule">
                            <i class="fas fa-calendar"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
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

function confirmDriver(driverId, driverName) {
    if (!confirm(`Are you sure you want to confirm driver "${driverName}"?\n\nThis will mark the driver as confirmed and available for assignments.`)) {
        return;
    }
    
    $.ajax({
        url: '<?= base_url('logisticscoordinator/driver/') ?>' + driverId + '/confirm',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('Driver confirmed successfully!');
                loadFleetManagementData(); // Reload the driver list
            } else {
                alert('Error: ' + (response.message || 'Failed to confirm driver'));
            }
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                ? xhr.responseJSON.message 
                : 'Error confirming driver';
            alert('Error: ' + errorMsg);
        }
    });
}

function viewDriverSchedule(driverId, driverName = null) {
    // If driverName is provided, use it directly; otherwise prompt
    let targetDriverName = driverName;
    
    if (!targetDriverName) {
        // Get all drivers first
        $.get('<?= base_url('logisticscoordinator/api/drivers') ?>', function(driversResponse) {
            if (driversResponse.status === 'success' && driversResponse.drivers.length > 0) {
                let driverOptions = driversResponse.drivers.map(d => 
                    `<option value="${d.driver_name}">${d.driver_name} (${d.delivery_count} deliveries)</option>`
                ).join('');
                
                let html = `
                    <div class="modal fade" id="driverScheduleModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                                    <h5 class="modal-title"><i class="fas fa-calendar-week"></i> Driver Schedule</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label"><strong>Select Driver:</strong></label>
                                            <select class="form-select" id="driverSelect" onchange="loadDriverSchedule()">
                                                <option value="">-- Select Driver --</option>
                                                ${driverOptions}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label"><strong>From Date:</strong></label>
                                            <input type="date" class="form-control" id="scheduleDateFrom" 
                                                   value="${new Date().toISOString().split('T')[0]}" 
                                                   onchange="loadDriverSchedule()">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label"><strong>To Date:</strong></label>
                                            <input type="date" class="form-control" id="scheduleDateTo" 
                                                   value="${new Date(Date.now() + 30*24*60*60*1000).toISOString().split('T')[0]}" 
                                                   onchange="loadDriverSchedule()">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button class="btn btn-primary w-100" onclick="loadDriverSchedule()">
                                                <i class="fas fa-search"></i> Load
                                            </button>
                                        </div>
                                    </div>
                                    <div id="driverScheduleContent">
                                        <p class="text-center text-muted">Please select a driver to view schedule</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#driverScheduleModal').remove();
                $('body').append(html);
                $('#driverScheduleModal').modal('show');
            } else {
                alert('No drivers found');
            }
        });
    } else {
        // Load schedule directly
        loadDriverScheduleForName(targetDriverName);
    }
}

function loadDriverSchedule() {
    const driverName = $('#driverSelect').val();
    if (!driverName) {
        $('#driverScheduleContent').html('<p class="text-center text-muted">Please select a driver</p>');
        return;
    }
    loadDriverScheduleForName(driverName);
}

function loadDriverScheduleForName(driverName) {
    const dateFrom = $('#scheduleDateFrom').val() || new Date().toISOString().split('T')[0];
    const dateTo = $('#scheduleDateTo').val() || new Date(Date.now() + 30*24*60*60*1000).toISOString().split('T')[0];
    
    $('#driverScheduleContent').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading schedule...</p></div>');
    
    $.get('<?= base_url('logisticscoordinator/driver-schedule') ?>', {
        driver_name: driverName,
        date_from: dateFrom,
        date_to: dateTo
    }, function(response) {
        if (response.status === 'success') {
            const deliveries = response.deliveries || [];
            
            if (deliveries.length === 0) {
                $('#driverScheduleContent').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No deliveries scheduled for ${driverName} in the selected date range.
                    </div>
                `);
                return;
            }
            
            let html = `
                <div class="mb-3">
                    <h6><strong>Schedule for: ${driverName}</strong></h6>
                    <p class="text-muted">Date Range: ${new Date(dateFrom).toLocaleDateString()} to ${new Date(dateTo).toLocaleDateString()}</p>
                    <p class="text-muted">Total Deliveries: ${response.total || deliveries.length}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Delivery #</th>
                                <th>Scheduled Date</th>
                                <th>Status</th>
                                <th>Branch</th>
                                <th>Supplier</th>
                                <th>Vehicle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${deliveries.map(d => `
                                <tr>
                                    <td>${d.delivery_number || 'N/A'}</td>
                                    <td>${new Date(d.scheduled_date).toLocaleDateString()}</td>
                                    <td><span class="status-badge status-${d.status}">${d.status}</span></td>
                                    <td>${d.branch ? d.branch.name : 'N/A'}</td>
                                    <td>${d.supplier ? d.supplier.name : 'N/A'}</td>
                                    <td>${d.vehicle_info || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewDeliveryDetails(${d.id}); $('#driverScheduleModal').modal('hide');">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            
            $('#driverScheduleContent').html(html);
        } else {
            $('#driverScheduleContent').html(`<div class="alert alert-danger">Error: ${response.message || 'Unknown error'}</div>`);
}
    }).fail(function() {
        $('#driverScheduleContent').html('<div class="alert alert-danger">Error loading driver schedule</div>');
    });
}

// viewDeliveryDetails is already defined earlier in the file - using that one

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

// ===== SECTION DATA LOADING FUNCTIONS =====
function loadPendingPOs() {
    const contentDiv = $('#scheduleDeliveryContent');
    if (!contentDiv.length) return;
    
    contentDiv.html(`
        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p style="margin-top: 15px; font-size: 1rem;">Loading pending purchase orders...</p>
        </div>
    `);
    
    $.get('<?= base_url('logisticscoordinator/api/pending-pos') ?>', function(response) {
        if (response.status === 'success') {
            const pendingPOs = response.pendingPOs || [];
            
            if (pendingPOs.length === 0) {
                contentDiv.html(`
                    <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                        <p style="margin: 0; font-size: 1rem; font-weight: 500;">No pending purchase orders to schedule</p>
                    </div>
                `);
            } else {
                // Calculate statistics
                const totalAmount = pendingPOs.reduce((sum, po) => sum + (parseFloat(po.total_amount) || 0), 0);
                const avgAmount = totalAmount / pendingPOs.length;
                const today = new Date();
                let urgentCount = 0;
                
                pendingPOs.forEach(po => {
                    if (po.expected_delivery_date) {
                        const expectedDate = new Date(po.expected_delivery_date);
                        const daysDiff = Math.ceil((expectedDate - today) / (1000 * 60 * 60 * 24));
                        if (daysDiff <= 3 && daysDiff >= 0) {
                            urgentCount++;
                        }
                    }
                });
                
                let html = `
                    <div class="mb-3" style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="info-label">Total Pending</div>
                                <div class="info-value" style="font-size: 1.5rem;">${pendingPOs.length}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Total Amount</div>
                                <div class="info-value" style="font-size: 1.5rem;">₱${totalAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Urgent Orders</div>
                                <div class="info-value" style="font-size: 1.5rem; color: #dc3545;">${urgentCount}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Avg. Amount</div>
                                <div class="info-value" style="font-size: 1.5rem;">₱${avgAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                `;
                
                pendingPOs.forEach(po => {
                    const orderDate = po.created_at ? new Date(po.created_at) : (po.order_date ? new Date(po.order_date) : null);
                    const expectedDate = po.expected_delivery_date ? new Date(po.expected_delivery_date) : null;
                    const today = new Date();
                    let daysLeft = null;
                    let isUrgent = false;
                    let isOverdue = false;
                    
                    if (expectedDate) {
                        daysLeft = Math.ceil((expectedDate - today) / (1000 * 60 * 60 * 24));
                        if (expectedDate < today) {
                            isOverdue = true;
                            daysLeft = -daysLeft;
                        } else if (daysLeft <= 3) {
                            isUrgent = true;
                        }
                    }
                    
                    html += `
                        <div class="col-12">
                            <div class="card" style="border: 2px solid ${isOverdue ? '#dc3545' : (isUrgent ? '#ffc107' : '#d4e8c9')}; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                <div class="card-body" style="padding: 20px;">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="mb-1">${po.order_number || 'N/A'}</h5>
                                            <p class="text-muted mb-2">
                                                Supplier: ${po.supplier ? po.supplier.name : 'N/A'} | 
                                                Branch: ${po.branch ? po.branch.name : 'N/A'}
                                            </p>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-boxes"></i> ${po.items_count || 0} items
                                                </span>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-money-bill-wave"></i> ₱${(parseFloat(po.total_amount) || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
                                                </span>
                                                ${expectedDate ? `
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-calendar"></i> Expected: ${expectedDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                                                    </span>
                                                ` : ''}
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button class="btn btn-primary" onclick="openScheduleModal(${po.id})">
                                                <i class="fas fa-calendar"></i> Schedule Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
                contentDiv.html(html);
            }
        } else {
            contentDiv.html(`
                <div style="text-align: center; padding: 40px 20px; color: #dc3545;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 16px; display: block;"></i>
                    <p style="margin: 0; font-size: 1rem; font-weight: 500;">Error loading pending purchase orders: ${response.message || 'Unknown error'}</p>
                </div>
            `);
        }
    }).fail(function(xhr, status, error) {
        $('#scheduleDeliveryContent').html(`
            <div style="text-align: center; padding: 40px 20px; color: #dc3545;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 16px; display: block;"></i>
                <p style="margin: 0; font-size: 1rem; font-weight: 500;">Error loading pending purchase orders: ${error}</p>
            </div>
        `);
    });
}

function loadAllOrders() {
    const searchTerm = $('#searchOrders').val() || '';
    console.log('loadAllOrders called with search term:', searchTerm); // Debug log
    
    // Show loading indicator
    const trackingResults = $('#trackingResults');
    if (!trackingResults.length) {
        console.error('trackingResults element not found!');
        return;
    }
    
    trackingResults.html(`
        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p style="margin-top: 15px; font-size: 1rem;">Searching orders...</p>
        </div>
    `);
    
    const apiUrl = '<?= base_url('logisticscoordinator/api/all-orders') ?>';
    console.log('Calling API:', apiUrl, 'with search:', searchTerm);
    
    $.get(apiUrl, { search: searchTerm }, function(response) {
        console.log('API Response:', response); // Debug log
        if (response.status === 'success') {
            const orders = response.orders || [];
            
            if (orders.length === 0) {
                trackingResults.html(`
                    <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                        <p style="margin: 0; font-size: 1rem; font-weight: 500;">
                            ${searchTerm ? 'No purchase orders found matching "' + searchTerm + '"' : 'No purchase orders found'}
                        </p>
                        ${searchTerm ? '<button class="btn btn-secondary mt-3" onclick="clearSearch()">Clear Search</button>' : ''}
                    </div>
                `);
            } else {
                let html = `
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
                    const status = (order.status || 'pending').toLowerCase();
                    let statusDisplay = (order.status || 'pending').replace(/_/g, ' ');
                    statusDisplay = statusDisplay.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
                    
                    // Highlight search term in results
                    const highlightText = (text, searchTerm) => {
                        if (!searchTerm) return text;
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        return text.replace(regex, '<mark style="background-color: #ffeb3b; padding: 2px 4px;">$1</mark>');
                    };
                    
                    const orderNumber = order.order_number || 'N/A';
                    const supplierName = order.supplier ? order.supplier.name : (order.supplier_id || 'N/A');
                    const branchName = order.branch ? order.branch.name : (order.branch_id || 'N/A');
                    
                    html += `
                        <tr>
                            <td><span style="font-weight: 600; color: #1e293b;">${highlightText(orderNumber, searchTerm)}</span></td>
                            <td>${highlightText(supplierName, searchTerm)}</td>
                            <td>${highlightText(branchName, searchTerm)}</td>
                            <td><span class="status-badge status-${status}">${statusDisplay}</span></td>
                            <td>
                                ${order.expected_delivery_date ? 
                                    `<i class="fas fa-calendar-alt me-1 text-muted"></i>${new Date(order.expected_delivery_date).toLocaleDateString()}` : 
                                    '<span class="text-muted">Not set</span>'}
                            </td>
                            <td class="text-end" style="font-weight: 600; color: var(--primary-green);">
                                ₱${parseFloat(order.total_amount || 0).toFixed(2)}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info" onclick="trackOrder(${order.id})">
                                    <i class="fas fa-eye me-1"></i> Track
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="viewOrderHistory(${order.id})">
                                    <i class="fas fa-history"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                        ${searchTerm ? `<div class="mt-2 text-muted"><small>Found ${orders.length} order(s) matching "${searchTerm}"</small></div>` : ''}
                    </div>
                `;
                
                trackingResults.html(html);
            }
        } else {
            trackingResults.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error: ${response.message || 'Failed to load orders'}
                </div>
            `);
        }
    }).fail(function(xhr, status, error) {
        console.error('API Error:', status, error, xhr); // Debug log
        trackingResults.html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Error loading orders. Please try again.
                <br><small>Error: ${error}</small>
            </div>
        `);
    });
}

// Handle Enter key press in search input
function handleSearchKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchOrders();
    }
}

// Search function - calls loadAllOrders with search term
function searchOrders() {
    const searchTerm = $('#searchOrders').val().trim();
    console.log('Searching for:', searchTerm); // Debug log
    loadAllOrders();
}

// Clear search function
function clearSearch() {
    $('#searchOrders').val('');
    console.log('Clearing search'); // Debug log
    loadAllOrders();
}

function loadAllDeliveries() {
    const statusFilter = $('#filterStatus').val() || '';
    const dateFilter = $('#filterDate').val() || '';
    const driverFilter = $('#filterDriver').val() || '';
    
    const contentDiv = $('#deliveriesContent');
    if (!contentDiv.length) return;
    
    contentDiv.html(`
        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p style="margin-top: 15px; font-size: 1rem;">Loading deliveries...</p>
        </div>
    `);
    
    // Load drivers for filter dropdown if not already loaded
    if ($('#filterDriver option').length <= 1) {
        loadDriversForFilter();
    }
    
    $.get('<?= base_url('logisticscoordinator/api/all-deliveries') ?>', {
        status: statusFilter,
        date: dateFilter,
        driver: driverFilter
    }, function(response) {
        if (response.status === 'success') {
            const deliveries = response.deliveries || [];
            
            if (deliveries.length === 0) {
                contentDiv.html(`
                    <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                        <i class="fas fa-truck" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                        <p style="margin: 0; font-size: 1rem; font-weight: 500;">No deliveries found</p>
                    </div>
                `);
            } else {
                let html = `
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
                `;
                
                deliveries.forEach(delivery => {
                    const status = (delivery.status || 'pending').toLowerCase();
                    const statusDisplay = (delivery.status || 'pending').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    
                    html += `
                        <tr>
                            <td><strong>${delivery.delivery_number || 'N/A'}</strong></td>
                            <td>${delivery.purchase_order ? delivery.purchase_order.order_number : (delivery.purchase_order_id || 'N/A')}</td>
                            <td>${delivery.supplier ? delivery.supplier.name : (delivery.supplier_id || 'N/A')}</td>
                            <td>${delivery.branch ? delivery.branch.name : (delivery.branch_id || 'N/A')}</td>
                            <td><span class="status-badge status-${status}">${statusDisplay}</span></td>
                            <td>${delivery.scheduled_date ? new Date(delivery.scheduled_date).toLocaleDateString() : 'Not set'}</td>
                            <td>${delivery.driver_name || 'N/A'}</td>
                            <td>${delivery.vehicle_info || 'N/A'}</td>
                            <td>
                                <div class="btn-group">
                                    ${delivery.status === 'scheduled' ? 
                                        `<button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(${delivery.id}, 'in_transit')" title="Mark In Transit">
                                            <i class="fas fa-truck"></i>
                                        </button>` : ''}
                                    ${delivery.status === 'in_transit' ? 
                                        `<button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(${delivery.id}, 'delivered')" title="Mark Delivered">
                                            <i class="fas fa-check"></i>
                                        </button>` : ''}
                                    <button class="btn btn-sm btn-info" onclick="viewDeliveryDetails(${delivery.id})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary" onclick="viewDeliveryHistory(${delivery.id})" title="History">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
                
                contentDiv.html(html);
            }
        } else {
            contentDiv.html(`
                <div style="text-align: center; padding: 40px 20px; color: #dc3545;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 16px; display: block;"></i>
                    <p style="margin: 0; font-size: 1rem; font-weight: 500;">Error loading deliveries: ${response.message || 'Unknown error'}</p>
                </div>
            `);
        }
    }).fail(function(xhr, status, error) {
        $('#deliveriesContent').html(`
            <div style="text-align: center; padding: 40px 20px; color: #dc3545;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 16px; display: block;"></i>
                <p style="margin: 0; font-size: 1rem; font-weight: 500;">Error loading deliveries: ${error}</p>
            </div>
        `);
    });
}

function loadScheduledDeliveries() {
    $.get('<?= base_url('logisticscoordinator/api/scheduled-deliveries') ?>', function(response) {
        if (response.status === 'success') {
            const deliveries = response.deliveries || [];
            // Update calendar view if needed
            if (typeof initializeCalendar === 'function') {
                initializeCalendar();
            }
        }
    });
}

// Load scheduled deliveries section dynamically
function loadScheduledDeliveriesSection() {
    const container = $('#scheduledDeliveriesContainer');
    if (!container.length) return;
    
    container.html(`
        <div style="text-align: center; padding: 20px; color: #64748b;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
            <p style="margin: 0; font-size: 0.9rem;">Loading scheduled deliveries...</p>
        </div>
    `);
    
    $.get('<?= base_url('logisticscoordinator/api/scheduled-deliveries') ?>', function(response) {
        if (response.status === 'success') {
            const deliveries = response.deliveries || [];
            
            if (deliveries.length === 0) {
                container.html(`
                    <div style="text-align: center; padding: 20px; color: #64748b;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                        <p style="margin: 0; font-size: 0.9rem;">No scheduled deliveries</p>
                    </div>
                `);
            } else {
                let html = '';
                deliveries.forEach(function(delivery) {
                    const scheduledDate = delivery.scheduled_date ? new Date(delivery.scheduled_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const scheduledTime = delivery.scheduled_time ? new Date('1970-01-01T' + delivery.scheduled_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : '';
                    const branchName = delivery.branch ? delivery.branch.name : (delivery.branch_id || 'N/A');
                    const supplierName = delivery.supplier ? delivery.supplier.name : (delivery.supplier_id || 'N/A');
                    const poNumber = delivery.purchase_order ? delivery.purchase_order.order_number : (delivery.purchase_order_id || 'N/A');
                    
                    html += `
                        <div class="delivery-card" style="margin-bottom: 20px; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background: white;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>${delivery.delivery_number || 'N/A'}</strong>
                                    <span class="status-badge status-scheduled ms-2">Scheduled</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt"></i> ${scheduledDate}
                                        ${scheduledTime ? ' at ' + scheduledTime : ''}
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
                                            ${delivery.driver_name || 'Not assigned'}
                                            ${delivery.driver_phone ? '<br><small class="text-muted"><i class="fas fa-phone"></i> ' + delivery.driver_phone + '</small>' : ''}
                                        </div>
                                        ${delivery.driver_status ? `
                                            <div class="driver-status mt-1">
                                                <i class="fas fa-circle ${delivery.driver_status === 'available' ? 'driver-online' : (delivery.driver_status === 'busy' ? 'driver-busy' : 'driver-offline')}"></i>
                                                <small class="text-muted">${delivery.driver_status.charAt(0).toUpperCase() + delivery.driver_status.slice(1)}</small>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-detail-box">
                                        <div class="info-label">Vehicle Information</div>
                                        <div class="info-value">
                                            ${delivery.vehicle_info || 'Not assigned'}
                                            ${delivery.vehicle_type ? '<br><small class="text-muted"><i class="fas fa-truck"></i> ' + delivery.vehicle_type.charAt(0).toUpperCase() + delivery.vehicle_type.slice(1) + '</small>' : ''}
                                            ${delivery.vehicle_capacity ? '<br><small class="text-muted"><i class="fas fa-weight-hanging"></i> ' + delivery.vehicle_capacity + '</small>' : ''}
                                        </div>
                                        ${delivery.vehicle_status ? `
                                            <span class="vehicle-status vehicle-${delivery.vehicle_status}">
                                                ${delivery.vehicle_status.charAt(0).toUpperCase() + delivery.vehicle_status.slice(1)}
                                            </span>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-detail-box mt-3">
                                <div class="info-label">Delivery Details</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">PO Number:</small><br>
                                        <strong>${poNumber}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Branch:</small><br>
                                        <strong>${branchName}</strong>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <small class="text-muted">Supplier:</small><br>
                                        <strong>${supplierName}</strong>
                                    </div>
                                </div>
                                ${delivery.notes ? `
                                    <div class="mt-2">
                                        <small class="text-muted">Notes:</small><br>
                                        <small>${delivery.notes}</small>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <div class="mt-2">
                                <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(${delivery.id}, 'in_transit')">
                                    <i class="fas fa-truck"></i> Mark In Transit
                                </button>
                                <button class="btn btn-sm btn-info ms-1" onclick="viewDeliveryDetails(${delivery.id})">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                                <button class="btn btn-sm btn-secondary ms-1" onclick="rescheduleDelivery(${delivery.id})">
                                    <i class="fas fa-calendar-alt"></i> Reschedule
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                container.html(html);
            }
        } else {
            container.html(`
                <div style="text-align: center; padding: 20px; color: #dc3545;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 12px; display: block;"></i>
                    <p style="margin: 0; font-size: 0.9rem;">Error loading scheduled deliveries: ${response.message || 'Unknown error'}</p>
                </div>
            `);
        }
    }).fail(function(xhr, status, error) {
        $('#scheduledDeliveriesContainer').html(`
            <div style="text-align: center; padding: 20px; color: #dc3545;">
                <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 12px; display: block;"></i>
                <p style="margin: 0; font-size: 0.9rem;">Error loading scheduled deliveries: ${error}</p>
            </div>
        `);
    });
}

// searchOrders and filterDeliveries are defined above in the section data loading functions

function resetFilters() {
    $('#filterStatus').val('');
    $('#filterDate').val('');
    $('#filterDriver').val('');
    loadAllDeliveries();
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
    $.get('<?= base_url('logisticscoordinator/order/') ?>' + orderId + '/history', function(response) {
        if (response.status === 'success') {
            const order = response.order;
            const history = response.history || [];
            const deliveries = response.deliveries || [];
            
            let html = `
                <div class="modal fade" id="orderHistoryModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                                <h5 class="modal-title"><i class="fas fa-history"></i> Order History - ${order.order_number || 'N/A'}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="info-detail-box">
                                            <div class="info-label">Order Number</div>
                                            <div class="info-value">${order.order_number || 'N/A'}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-detail-box">
                                            <div class="info-label">Status</div>
                                            <div class="info-value"><span class="status-badge status-${order.status}">${order.status}</span></div>
                                        </div>
                                    </div>
                                </div>
                                
                                ${deliveries.length > 0 ? `
                                    <div class="mb-4">
                                        <h6><strong>Related Deliveries:</strong></h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Delivery #</th>
                                                        <th>Scheduled Date</th>
                                                        <th>Status</th>
                                                        <th>Driver</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${deliveries.map(d => `
                                                        <tr>
                                                            <td>${d.delivery_number || 'N/A'}</td>
                                                            <td>${new Date(d.scheduled_date).toLocaleDateString()}</td>
                                                            <td><span class="status-badge status-${d.status}">${d.status}</span></td>
                                                            <td>${d.driver_name || 'N/A'}</td>
                                                            <td>
                                                                <button class="btn btn-sm btn-info" onclick="viewDeliveryHistory(${d.id})">
                                                                    <i class="fas fa-history"></i> History
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                ` : ''}
                                
                                <div>
                                    <h6><strong>Audit Trail:</strong></h6>
                                    ${history.length > 0 ? `
                                        <div class="timeline">
                                            ${history.map(h => {
                                                const oldVals = h.old_values ? JSON.parse(h.old_values) : {};
                                                const newVals = h.new_values ? JSON.parse(h.new_values) : {};
                                                const changedFields = h.changed_fields ? h.changed_fields.split(',') : [];
                                                
                                                return `
                                                    <div class="timeline-item">
                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between">
                                                                    <strong>${h.action}</strong>
                                                                    <small class="text-muted">${new Date(h.created_at).toLocaleString()}</small>
                                                                </div>
                                                                <p class="mb-1"><small>Changed by: ${h.changed_by_email || 'System'}</small></p>
                                                                ${changedFields.length > 0 ? `
                                                                    <div class="mt-2">
                                                                        <strong>Changes:</strong>
                                                                        <ul class="mb-0">
                                                                            ${changedFields.map(field => {
                                                                                const oldVal = oldVals[field] !== undefined ? oldVals[field] : 'N/A';
                                                                                const newVal = newVals[field] !== undefined ? newVals[field] : 'N/A';
                                                                                return `<li><strong>${field}:</strong> ${oldVal} → ${newVal}</li>`;
                                                                            }).join('')}
                                                                        </ul>
                                                                    </div>
                                                                ` : ''}
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                            }).join('')}
                                        </div>
                                    ` : '<p class="text-muted">No history available</p>'}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#orderHistoryModal').remove();
            $('body').append(html);
            $('#orderHistoryModal').modal('show');
        } else {
            alert('Error: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading order history');
    });
}

function viewDeliveryHistory(deliveryId) {
    $.get('<?= base_url('logisticscoordinator/delivery/') ?>' + deliveryId + '/history', function(response) {
        if (response.status === 'success') {
            const delivery = response.delivery;
            const history = response.history || [];
            
            let html = `
                <div class="modal fade" id="deliveryHistoryModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                                <h5 class="modal-title"><i class="fas fa-history"></i> Delivery History - ${delivery.delivery_number || 'N/A'}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="info-detail-box">
                                            <div class="info-label">Delivery Number</div>
                                            <div class="info-value">${delivery.delivery_number || 'N/A'}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-detail-box">
                                            <div class="info-label">Status</div>
                                            <div class="info-value"><span class="status-badge status-${delivery.status}">${delivery.status}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-detail-box">
                                            <div class="info-label">Scheduled Date</div>
                                            <div class="info-value">${new Date(delivery.scheduled_date).toLocaleDateString()}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h6><strong>Audit Trail:</strong></h6>
                                    ${history.length > 0 ? `
                                        <div class="timeline">
                                            ${history.map(h => {
                                                const oldVals = h.old_values ? JSON.parse(h.old_values) : {};
                                                const newVals = h.new_values ? JSON.parse(h.new_values) : {};
                                                const changedFields = h.changed_fields ? h.changed_fields.split(',') : [];
                                                
                                                return `
                                                    <div class="timeline-item">
                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between">
                                                                    <strong>${h.action}</strong>
                                                                    <small class="text-muted">${new Date(h.created_at).toLocaleString()}</small>
                                                                </div>
                                                                <p class="mb-1"><small>Changed by: ${h.changed_by_email || 'System'}</small></p>
                                                                ${changedFields.length > 0 ? `
                                                                    <div class="mt-2">
                                                                        <strong>Changes:</strong>
                                                                        <ul class="mb-0">
                                                                            ${changedFields.map(field => {
                                                                                const oldVal = oldVals[field] !== undefined ? oldVals[field] : 'N/A';
                                                                                const newVal = newVals[field] !== undefined ? newVals[field] : 'N/A';
                                                                                return `<li><strong>${field}:</strong> ${oldVal} → ${newVal}</li>`;
                                                                            }).join('')}
                                                                        </ul>
                                                                    </div>
                                                                ` : ''}
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                            }).join('')}
                                        </div>
                                    ` : '<p class="text-muted">No history available</p>'}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#deliveryHistoryModal').remove();
            $('body').append(html);
            $('#deliveryHistoryModal').modal('show');
        } else {
            alert('Error: ' + (response.message || 'Unknown error'));
        }
    }).fail(function() {
        alert('Error loading delivery history');
    });
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
    // Export Deliveries Function
    function exportDeliveries() {
        const format = prompt('Export format:\n1. CSV\n2. Excel\n3. PDF\n\nEnter format (1-3):', '1');
        if (!format) return;
        
        let message = 'Exporting deliveries data...\n\n';
        switch(format) {
            case '1':
                message += 'CSV export functionality would be implemented here.';
                break;
            case '2':
                message += 'Excel export functionality would be implemented here.';
                break;
            case '3':
                message += 'PDF export functionality would be implemented here.';
                break;
            default:
                alert('Invalid format selected.');
                return;
        }
        
        alert(message + '\n\nThis feature requires backend implementation.');
    }

    // Print Report Function
    function printReport() {
        const reportType = prompt('Select report type:\n1. Delivery Summary\n2. Pending Orders\n3. Scheduled Deliveries\n4. Full Report\n\nEnter type (1-4):', '1');
        if (!reportType) return;
        
        const reportTypes = {
            '1': 'Delivery Summary',
            '2': 'Pending Orders',
            '3': 'Scheduled Deliveries',
            '4': 'Full Report'
        };
        
        alert('Printing ' + (reportTypes[reportType] || 'Report') + '...\n\nThis will open the print dialog.');
        
        // Create print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>${reportTypes[reportType] || 'Report'}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { color: #2d5016; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #2d5016; color: white; }
                    </style>
                </head>
                <body>
                    <h1>${reportTypes[reportType] || 'Report'}</h1>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                    <p>This is a sample report. Full implementation would include actual data.</p>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }

    // Search Functionality
    function searchDeliveries() {
        const searchTerm = prompt('Enter search term (Order Number, Supplier, Branch, etc.):', '');
        if (!searchTerm) return;
        
        // This would filter the displayed deliveries
        $('.delivery-card, .content-card table tbody tr').each(function() {
            const text = $(this).text().toLowerCase();
            if (text.includes(searchTerm.toLowerCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        alert('Search completed. Showing results for: ' + searchTerm);
    }

    // Filter by Status
    function filterByStatus(status) {
        $('.delivery-card, .content-card table tbody tr').show();
        if (status !== 'all') {
            $('.delivery-card, .content-card table tbody tr').each(function() {
                const hasStatus = $(this).find('.status-badge, .badge').text().toLowerCase().includes(status.toLowerCase());
                if (!hasStatus) {
                    $(this).hide();
                }
            });
        }
    }

    // Auto-refresh functionality
    let autoRefreshInterval;
    function toggleAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
            alert('Auto-refresh disabled.');
        } else {
            autoRefreshInterval = setInterval(function() {
                // Refresh dashboard data
                location.reload();
            }, 300000); // 5 minutes
            alert('Auto-refresh enabled. Page will refresh every 5 minutes.');
        }
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = $('<div class="notification" style="position: fixed; top: 20px; right: 20px; background: ' + 
            (type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#0dcaf0') + 
            '; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; max-width: 300px;">' +
            '<i class="fas fa-' + (type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle') + '"></i> ' +
            message + '</div>');
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Initialize notifications on page load
    $(document).ready(function() {
        // Show welcome notification
        setTimeout(function() {
            showNotification('Welcome to Logistics Coordinator Dashboard', 'success');
        }, 1000);
    });
</script>
</body>
</html>