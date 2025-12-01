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
                <?php $currentPage = $currentPage ?? 'dashboard'; ?>
                <a href="<?= base_url('logisticscoordinator/dashboard') ?>" class="nav-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('logisticscoordinator/schedule') ?>" class="nav-item <?= $currentPage === 'schedule' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Schedule Delivery</span>
                </a>
                <a href="<?= base_url('logisticscoordinator/track-orders') ?>" class="nav-item <?= $currentPage === 'track' ? 'active' : '' ?>">
                    <i class="fas fa-search"></i>
                    <span>Track Orders</span>
                </a>
                <a href="<?= base_url('logisticscoordinator/deliveries') ?>" class="nav-item <?= $currentPage === 'deliveries' ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="<?= base_url('logisticscoordinator/schedules') ?>" class="nav-item <?= $currentPage === 'schedules' ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Schedules</span>
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
                </div>
            </header>

            <div class="dashboard-content">
                <?php $currentPage = $currentPage ?? 'dashboard'; ?>
                
                <?php if ($currentPage === 'dashboard'): ?>
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
                                Need Scheduling
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
                                Active Deliveries
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
                                All Orders
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
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>PO Number</th>
                                                <th>Supplier</th>
                                                <th>Branch</th>
                                                <th>Expected Date</th>
                                                <th>Total Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pendingPOs as $po): ?>
                                            <tr>
                                                <td><strong><?= esc($po['order_number'] ?? 'N/A') ?></strong></td>
                                                <td><?= esc($po['supplier']['name'] ?? ($po['supplier_id'] ?? 'N/A')) ?></td>
                                                <td><?= esc($po['branch']['name'] ?? ($po['branch_id'] ?? 'N/A')) ?></td>
                                                <td><?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : 'Not set' ?></td>
                                                <td>₱<?= number_format($po['total_amount'] ?? 0, 2) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="scheduleDelivery(<?= $po['id'] ?>)">
                                                        <i class="fas fa-calendar"></i> Schedule
                                                    </button>
                                                    <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $po['id'] ?>)">
                                                        <i class="fas fa-eye"></i> View
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
                                <?php foreach ($scheduledDeliveries as $delivery): ?>
                                <div class="delivery-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= esc($delivery['delivery_number']) ?></strong>
                                            <span class="status-badge status-scheduled ms-2">Scheduled</span>
                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <div>Date: <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?></div>
                                        <div>Branch: <?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></div>
                                        <?php if ($delivery['driver_name']): ?>
                                            <div>Driver: <?= esc($delivery['driver_name']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                            <i class="fas fa-truck"></i> Mark In Transit
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
                                <?php foreach ($inTransitDeliveries as $delivery): ?>
                                <div class="delivery-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= esc($delivery['delivery_number']) ?></strong>
                                            <span class="status-badge status-in_transit ms-2">In Transit</span>
                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <div>Scheduled: <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?></div>
                                        <div>Driver: <?= esc($delivery['driver_name'] ?? 'N/A') ?></div>
                                        <div>Vehicle: <?= esc($delivery['vehicle_info'] ?? 'N/A') ?></div>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delivered')">
                                            <i class="fas fa-check"></i> Mark Delivered
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                
                <?php elseif ($currentPage === 'schedule'): ?>
                    <!-- Schedule Delivery View -->
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
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Supplier</th>
                                            <th>Branch</th>
                                            <th>Expected Date</th>
                                            <th>Total Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingPOs as $po): ?>
                                        <tr>
                                            <td><strong><?= esc($po['order_number'] ?? 'N/A') ?></strong></td>
                                            <td><?= esc($po['supplier']['name'] ?? ($po['supplier_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($po['branch']['name'] ?? ($po['branch_id'] ?? 'N/A')) ?></td>
                                            <td><?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : 'Not set' ?></td>
                                            <td>₱<?= number_format($po['total_amount'] ?? 0, 2) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="scheduleDelivery(<?= $po['id'] ?>)">
                                                    <i class="fas fa-calendar"></i> Schedule
                                                </button>
                                                <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $po['id'] ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($currentPage === 'track'): ?>
                    <!-- Track Orders View -->
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-search" style="font-size: 1.5rem;"></i>
                                Order Tracking
                            </h3>
                        </div>
                        <p style="color: #64748b; margin-bottom: 20px;">Monitor and track all purchase orders across the system</p>
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
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($currentPage === 'deliveries'): ?>
                    <!-- All Deliveries View -->
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-truck" style="font-size: 1.5rem;"></i>
                                All Deliveries
                            </h3>
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
                                            <td>
                                                <?php if ($delivery['status'] === 'scheduled'): ?>
                                                    <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                                        <i class="fas fa-truck"></i> Mark In Transit
                                                    </button>
                                                <?php elseif ($delivery['status'] === 'in_transit'): ?>
                                                    <button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delivered')">
                                                        <i class="fas fa-check"></i> Mark Delivered
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $delivery['purchase_order_id'] ?? 0 ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($currentPage === 'schedules'): ?>
                    <!-- Schedules View -->
                    <div class="content-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); padding: 20px; border-radius: 12px 12px 0 0; margin: -20px -20px 20px -20px;">
                            <h3 class="card-title" style="color: white; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-file-contract" style="font-size: 1.5rem;"></i>
                                Scheduled Deliveries
                            </h3>
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
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= esc($delivery['delivery_number']) ?></strong>
                                            <span class="status-badge status-scheduled ms-2">Scheduled</span>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <div><strong>PO Number:</strong> <?= esc($delivery['purchase_order']['order_number'] ?? ($delivery['purchase_order_id'] ?? 'N/A')) ?></div>
                                        <div><strong>Date:</strong> <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?></div>
                                        <div><strong>Branch:</strong> <?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></div>
                                        <?php if (!empty($delivery['scheduled_by_user'])): ?>
                                            <div><strong>Scheduled By:</strong> <?= esc($delivery['scheduled_by_user']['email'] ?? $delivery['scheduled_by_user']['username'] ?? 'User ID: ' . $delivery['scheduled_by_user']['id']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($delivery['driver_name']): ?>
                                            <div><strong>Driver:</strong> <?= esc($delivery['driver_name']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($delivery['vehicle_info']): ?>
                                            <div><strong>Vehicle:</strong> <?= esc($delivery['vehicle_info']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($delivery['notes']): ?>
                                            <div><strong>Notes:</strong> <?= esc($delivery['notes']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                            <i class="fas fa-truck"></i> Mark In Transit
                                        </button>
                                        <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $delivery['purchase_order_id'] ?? 0 ?>)">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Schedule Delivery Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Delivery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleForm">
                        <input type="hidden" id="poId" name="purchase_order_id">
                        <div class="mb-3">
                            <label class="form-label">Scheduled Date</label>
                            <input type="date" class="form-control" id="scheduledDate" name="scheduled_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="driverName" name="driver_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vehicle Info</label>
                            <input type="text" class="form-control" id="vehicleInfo" name="vehicle_info" placeholder="e.g., Truck ABC-123">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="deliveryNotes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitSchedule()">Schedule Delivery</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Tracking Modal -->
    <div class="modal fade" id="trackingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Tracking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="trackingContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    function scheduleDelivery(poId) {
        $('#poId').val(poId);
        // Set default date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        $('#scheduledDate').val(tomorrow.toISOString().split('T')[0]);
        $('#scheduleModal').modal('show');
    }

    function submitSchedule() {
        const formData = {
            purchase_order_id: $('#poId').val(),
            scheduled_date: $('#scheduledDate').val(),
            driver_name: $('#driverName').val(),
            vehicle_info: $('#vehicleInfo').val(),
            notes: $('#deliveryNotes').val()
        };

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
            error: function() {
                alert('Error scheduling delivery');
            }
        });
    }

    function trackOrder(poId) {
        $.get('<?= base_url('purchase/order/') ?>' + poId + '/track', function(response) {
            if (response.status === 'success') {
                const order = response.order;
                let html = `
                    <div class="mb-3">
                        <strong>Order Number:</strong> ${order.order_number}<br>
                        <strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status}</span><br>
                        <strong>Supplier:</strong> ${order.supplier ? order.supplier.name : 'N/A'}<br>
                        <strong>Expected Delivery:</strong> ${order.expected_delivery_date ? new Date(order.expected_delivery_date).toLocaleDateString() : 'Not set'}<br>
                    </div>
                `;
                
                if (order.tracking) {
                    html += `
                        <div class="alert alert-${order.tracking.is_overdue ? 'danger' : 'info'}">
                            <strong>Tracking Info:</strong><br>
                            Current Status: ${order.tracking.current_status}<br>
                            ${order.tracking.is_overdue ? '<span class="text-danger">⚠️ Overdue!</span>' : ''}
                            ${order.tracking.days_until_delivery !== null ? `Days until delivery: ${Math.ceil(order.tracking.days_until_delivery)}` : ''}
                        </div>
                    `;
                }
                
                if (order.delivery) {
                    html += `
                        <div class="mt-3">
                            <h6>Delivery Information:</h6>
                            <p>Delivery #: ${order.delivery.delivery_number}<br>
                            Status: ${order.delivery.status}<br>
                            Scheduled: ${new Date(order.delivery.scheduled_date).toLocaleDateString()}</p>
                        </div>
                    `;
                }
                
                $('#trackingContent').html(html);
                $('#trackingModal').modal('show');
            }
        });
    }

    function updateDeliveryStatus(deliveryId, status) {
        if (confirm(`Update delivery status to ${status}?`)) {
            $.post('<?= base_url('delivery/') ?>' + deliveryId + '/update-status', {
                status: status,
                actual_delivery_date: status === 'delivered' ? new Date().toISOString().split('T')[0] : null
            }, function(response) {
                if (response.status === 'success') {
                    alert('Delivery status updated!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            });
        }
    }
    </script>
</body>
</html>