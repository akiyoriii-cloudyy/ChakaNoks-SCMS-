<?php /** @var array $items */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title>Inventory Management — CHAKANOKS SCMS</title>
    
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
        /* Professional gradient background matching purchase requests */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .dashboard-main {
            background: transparent;
        }

        /* Enhanced page header matching purchase requests */
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

        .dashboard-header .btn-primary {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Enhanced content card matching purchase requests */
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
        }

        /* Enhanced table styling matching purchase requests */
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
            transform: scale(1.01);
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

        /* Enhanced filter card */
        .filter-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            margin-bottom: 1.5rem;
        }

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

        /* Pagination Styles */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #f8faf7 0%, #f0f4ed 100%);
            border-top: 1px solid #e0e0e0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .pagination-info strong {
            color: #2d5016;
            font-weight: 600;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover:not(.disabled):not(.active) {
            background: #f3f4f6;
            border-color: #4a7c2a;
            color: #2d5016;
        }

        .pagination-btn.active {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            border-color: #2d5016;
            color: white;
            font-weight: 600;
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f3f4f6;
        }

        .pagination-btn i {
            font-size: 0.75rem;
        }

        .pagination-ellipsis {
            padding: 0 8px;
            color: #9ca3af;
            font-weight: 500;
        }

        @media (max-width: 640px) {
            .pagination-wrapper {
                flex-direction: column;
                text-align: center;
            }
            
            .pagination-controls {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?= view('components/barcode_scanner') ?>
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
                $currentSection = $currentSection ?? 'inventory';
                $baseUrl = base_url('staff/dashboard');
                ?>
                <a href="<?= $baseUrl ?>?section=inventory" class="nav-item <?= $currentSection === 'inventory' ? 'active' : '' ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
                <a href="<?= $baseUrl ?>?section=stock-out" class="nav-item <?= $currentSection === 'stock-out' ? 'active' : '' ?>">
                    <i class="fas fa-arrow-down"></i>
                    <span>Stock Out</span>
                </a>
                <a href="<?= $baseUrl ?>?section=deliveries" class="nav-item <?= $currentSection === 'deliveries' ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="<?= base_url('purchase/request/new') ?>" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                </a>
                <?php if (in_array($me['role'] ?? '', ['branch_manager', 'manager'])): ?>
                    <a href="<?= base_url('manager/dashboard') ?>" class="nav-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Manager Dashboard</span>
                    </a>
                <?php endif; ?>
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
                        <h2 class="page-title">Inventory Management</h2>
                        <p class="page-subtitle">Manage and track your inventory items</p>
                    </div>
                </div>
                <div class="header-right" style="display: flex; align-items: center; gap: 12px;">
                    <button onclick="openBarcodeScanner()" class="btn btn-success">
                        <i class="fas fa-barcode"></i>
                        <span>Scan Barcode</span>
                    </button>
                    <button id="btnAdd" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Add New Item</span>
                    </button>
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <div style="display: flex; gap: 5px; align-items: center;">
                            <label style="font-size: 0.9rem; color: #fff; font-weight: 600; white-space: nowrap; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">Month:</label>
                            <input type="month" id="reportMonthFilter" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.875rem;">
                        </div>
                        <button id="btnPrintAll" class="btn btn-secondary">
                            <i class="fas fa-print"></i>
                            <span>Print Monthly Report</span>
                        </button>
                    </div>
                    <?= view('components/notifications') ?>
                </div>
            </header>

            <div class="dashboard-content" style="padding: 0 20px;">
                <!-- Welcome Banner -->
                <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15); display: inline-flex; align-items: center; gap: 12px; max-width: fit-content;">
                    <div style="width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-check" style="font-size: 18px; color: #28a745;"></i>
                    </div>
                    <div>
                        <div style="color: white; font-size: 1rem; font-weight: 700; line-height: 1.2;">Welcome to Inventory Staff</div>
                        <div style="color: rgba(255, 255, 255, 0.95); font-size: 0.875rem; font-weight: 500; line-height: 1.2;">Dashboard</div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="filter-card">
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
                        <div style="flex: 1; min-width: 250px;">
                            <div style="position: relative;">
                                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                                <input id="searchBox" type="text" placeholder="Search items..." style="width: 100%; padding: 10px 12px 10px 40px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 0.875rem;">
                            </div>
                        </div>
                        <select id="filterBranch" class="form-select" style="width: auto; min-width: 200px;" <?= !empty($branchScope['enforced']) ? 'disabled' : '' ?>>
                            <?php if (empty($branchScope['enforced'])): ?>
                                <option value="all">All Branches</option>
                            <?php endif; ?>
                            <?php foreach ($branches as $b): ?>
                                <?php
                                    $value = (string)$b['id'];
                                    $selected = false;
                                    if (!empty($branchScope['enforced'])) {
                                        $selected = (int)$branchScope['branch_id'] === (int)$b['id'];
                                    } else {
                                        $selected = (string)($filters['branch_id'] ?? 'all') === $value;
                                    }
                                ?>
                                <option value="<?= esc($value) ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= esc($b['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="filterStatus" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="all">All Status</option>
                            <option value="Critical">Critical</option>
                            <option value="Low Stock">Low Stock</option>
                            <option value="Good">Good</option>
                        </select>
                        <input type="date" id="filterDate" class="form-control" style="width: auto; min-width: 150px;">
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="content-card" style="display: <?= ($currentSection ?? 'inventory') === 'inventory' ? 'block' : 'none' ?>;">
                    <div class="card-header">
                        <h3 class="card-title">Inventory Items</h3>
                        <div class="card-actions">
                            <span id="itemCount" style="color: var(--text-secondary); font-size: 0.875rem;">0 items</span>
                        </div>
                    </div>
                    <div class="table-responsive" style="padding: 0;">
                        <table class="table" id="invTable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Branch</th>
                                    <th>Stock Level</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="invBody"></tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <?php if (isset($pager) && $pager->pageCount > 1): ?>
                        <?= render_pagination($pager, 'page') ?>
                    <?php endif; ?>
                    
                    <!-- Summary Cards -->
                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0; flex-wrap: wrap;">
                        <div class="summary-card" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
                            <div class="summary-value" style="color: #991b1b;" id="pillCritical">0</div>
                            <div class="summary-label" style="color: #991b1b;">Critical Items</div>
                        </div>
                        <div class="summary-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                            <div class="summary-value" style="color: #92400e;" id="pillLow">0</div>
                            <div class="summary-label" style="color: #92400e;">Low Stock</div>
                        </div>
                        <div class="summary-card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                            <div class="summary-value" style="color: #065f46;" id="pillGood">0</div>
                            <div class="summary-label" style="color: #065f46;">Good Stock</div>
                        </div>
                        <div class="summary-card" style="background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);">
                            <div class="summary-value" style="color: #2d5016;" id="pillTotal">0</div>
                            <div class="summary-label" style="color: #374151;">Total</div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- End Inventory Section -->

                <!-- Stock Out Section -->
                <div id="stock-out-section" class="dashboard-section" style="display: <?= ($currentSection ?? 'inventory') === 'stock-out' ? 'block' : 'none' ?>;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-arrow-down"></i> Stock Out</h3>
                        </div>
                        <div style="padding: 20px;">
                            <form id="stockOutForm">
                                <div class="mb-3">
                                    <label for="stockOutReason" class="form-label">Reason</label>
                                    <select class="form-control" id="stockOutReason" required>
                                        <option value="">Select reason...</option>
                                        <option value="sale">Sale</option>
                                        <option value="damaged">Damaged</option>
                                        <option value="expired">Expired</option>
                                        <option value="waste">Waste</option>
                                        <option value="transfer">Transfer to Another Branch</option>
                                        <option value="adjustment">Inventory Adjustment</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="stockOutOtherReasonDiv" style="display: none;">
                                    <label for="stockOutOtherReason" class="form-label">Specify Reason</label>
                                    <input type="text" class="form-control" id="stockOutOtherReason" placeholder="Enter reason...">
                                </div>
                                <div class="mb-3" id="stockOutTransferBranchDiv" style="display: none;">
                                    <label for="stockOutTransferBranch" class="form-label">Transfer To Branch</label>
                                    <select class="form-control" id="stockOutTransferBranch">
                                        <option value="">Select branch...</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="stockOutProduct" class="form-label">Select Product</label>
                                    <select class="form-control" id="stockOutProduct" required>
                                        <option value="">Select a product...</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="stockOutQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="stockOutQuantity" min="1" required>
                                    <small class="form-text text-muted">Available stock: <span id="stockOutAvailable">0</span></small>
                                </div>
                                <div class="mb-3">
                                    <label for="stockOutNotes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control" id="stockOutNotes" rows="3" placeholder="Additional notes..."></textarea>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="submitStockOut()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                                    <i class="fas fa-check"></i> Record Stock Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Stock Out Section -->

                <!-- Deliveries Section -->
                <div id="deliveries-section" class="dashboard-section" style="display: <?= ($currentSection ?? 'inventory') === 'deliveries' ? 'block' : 'none' ?>;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-truck"></i> Pending Deliveries</h3>
                            <span style="color: var(--text-secondary); font-size: 0.875rem;"><?= count($pendingDeliveries ?? []) ?> deliveries</span>
                        </div>
                        <div class="table-responsive" style="padding: 0;">
                            <table class="table" id="deliveriesTable">
                                <thead>
                                    <tr>
                                        <th>Delivery #</th>
                                        <th>Purchase Order</th>
                                        <th>Supplier</th>
                                        <th>Scheduled Date</th>
                                        <th>Status</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="deliveriesBody">
                                    <?php if (empty($pendingDeliveries)): ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 20px; color: #999;">No pending deliveries found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($pendingDeliveries as $delivery): ?>
                                            <?php
                                            $status = strtolower($delivery['status'] ?? 'scheduled');
                                            $statusBadge = $status === 'scheduled' ? 'badge-warning' : 
                                                          ($status === 'in_transit' ? 'badge-info' : 
                                                          ($status === 'received' ? 'badge-success' :
                                                          ($status === 'delivered' ? 'badge-success' :
                                                          ($status === 'delayed' ? 'badge-danger' : 'badge-secondary'))));
                                            $statusText = ucwords(str_replace('_', ' ', $status));
                                            
                                            // Check payment status
                                            $paymentStatus = strtolower($delivery['payment_status'] ?? 'unpaid');
                                            $isPaid = $paymentStatus === 'paid';
                                            $paymentBadge = $paymentStatus === 'paid' ? 'badge-success' : 
                                                           ($paymentStatus === 'partial' ? 'badge-warning' : 'badge-danger');
                                            ?>
                                            <tr>
                                                <td><?= esc($delivery['delivery_number']) ?></td>
                                                <td><?= esc($delivery['purchase_order']['order_number'] ?? 'N/A') ?></td>
                                                <td><?= esc($delivery['supplier']['name'] ?? 'N/A') ?></td>
                                                <td><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'N/A' ?></td>
                                                <td>
                                                    <span class="badge <?= $statusBadge ?>"><?= $statusText ?></span>
                                                    <?php if (isset($delivery['payment_status'])): ?>
                                                        <br><small class="badge <?= $paymentBadge ?>" style="margin-top: 4px; display: inline-block;">
                                                            Payment: <?= ucfirst($paymentStatus) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php if ($isPaid): ?>
                                                        <button class="btn btn-sm btn-primary" onclick="receiveDelivery(<?= $delivery['id'] ?>)">
                                                            <i class="fas fa-check"></i> Receive
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-secondary" disabled title="Payment not completed. Full payment required before receiving delivery.">
                                                            <i class="fas fa-lock"></i> Payment Required
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Deliveries Section -->
            </div>
        </main>
    </div>

    <!-- Add Item Modal -->
    <div class="modal" id="addItemModal" hidden style="position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center;">
        <div class="backdrop" style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);" onclick="document.getElementById('addItemModal').hidden = true;"></div>
        <div class="modal-card" style="background: white; border-radius: var(--radius-lg); padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative; z-index: 1; box-shadow: var(--shadow-xl);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700;">Add New Item</h3>
                <button class="icon-btn" id="addClose" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary);">&times;</button>
            </div>
            <form id="addForm">
                <input type="hidden" name="id" value="">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Barcode (Optional)</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" name="barcode" id="addBarcode" placeholder="Scan or enter barcode" style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <button type="button" onclick="openBarcodeScanner()" style="padding: 10px 20px; background: #2d5016; color: white; border: none; border-radius: var(--radius-md); cursor: pointer; font-weight: 500;">
                                <i class="fas fa-barcode"></i> Scan
                            </button>
                        </div>
                        <small style="color: var(--text-secondary); font-size: 0.875rem;">Use barcode scanner or click Scan button</small>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Category</label>
                        <select name="category" id="addCategory" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);" onchange="updateItemOptions()">
                            <option value="">Select Category</option>
                            <?php foreach ($categories ?? [] as $cat): ?>
                                <option value="<?= esc($cat['name']) ?>"><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Item Name</label>
                        <select name="name" id="addItemName" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <option value="">Select Category First</option>
                        </select>
                        <input type="text" name="custom_name" id="addCustomName" placeholder="Or enter custom item name..." style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md); margin-top: 8px; display: none;">
                        <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px; font-size: 0.875rem; color: var(--text-secondary); cursor: pointer;">
                            <input type="checkbox" id="useCustomName" onchange="toggleCustomName()"> Enter custom item name
                        </label>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Branch</label>
                        <select name="branch_id" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);" <?= !empty($branchScope['enforced']) ? 'disabled' : '' ?>>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $b): ?>
                                <?php
                                    $value = (string)$b['id'];
                                    $selected = !empty($branchScope['enforced'])
                                        ? ((int)$branchScope['branch_id'] === (int)$b['id'])
                                        : ((string)($filters['branch_id'] ?? $branchScope['branch_id'] ?? '') === $value);
                                ?>
                                <option value="<?= esc($value) ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= esc($b['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($branchScope['enforced'])): ?>
                            <input type="hidden" name="branch_id" value="<?= esc($branchScope['branch_id']) ?>">
                        <?php endif; ?>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Stock</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="number" name="stock" required min="1" placeholder="Enter quantity" style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <select name="unit" id="addUnit" required style="width: 120px; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                                <option value="pcs">pcs</option>
                                <option value="kg">kg</option>
                                <option value="liters">liters</option>
                                <option value="packs">packs</option>
                                <option value="boxes">boxes</option>
                                <option value="bottles">bottles</option>
                                <option value="cans">cans</option>
                                <option value="bags">bags</option>
                                <option value="rolls">rolls</option>
                                <option value="dozen">dozen</option>
                            </select>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Min Stock</label>
                            <input type="number" name="min_stock" required min="1" placeholder="e.g. 100" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Max Stock</label>
                            <input type="number" name="max_stock" required min="1" placeholder="e.g. 300" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Price (₱)</label>
                        <input type="number" name="price" required min="0" step="0.01" placeholder="e.g. 150.00" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Expiry Date</label>
                        <input type="date" name="expiry" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 1.5rem; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="addClose2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Item Modal - Enhanced to match accounts payable details modal -->
    <div class="modal" id="viewItemModal" hidden style="position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center;">
        <div class="backdrop" style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);" onclick="document.getElementById('viewItemModal').hidden = true;"></div>
        <div class="modal-card" style="background: white; border-radius: var(--radius-lg); padding: 2rem; max-width: 700px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative; z-index: 1; box-shadow: var(--shadow-xl);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h5 style="margin: 0; color: #2d5016; font-weight: 700;"><i class="fas fa-box" style="margin-right: 8px;"></i>Item Information</h5>
                <button class="icon-btn" id="viewClose" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary);">&times;</button>
            </div>
            
            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                    <div><strong>Item Name:</strong><br><span id="viewItemTitle" style="font-size: 1.1rem; font-weight: 600; color: #2d5016;"></span></div>
                    <div><strong>Status:</strong><br><span id="viewStatus" class="badge badge-success" style="font-size: 0.9rem; padding: 6px 12px;"></span></div>
                    <div><strong>Category:</strong><br><span id="viewCategory"></span></div>
                    <div><strong>Branch:</strong><br><span id="viewBranch"></span></div>
                    <div><strong>Price:</strong><br><span id="viewPrice" style="font-weight: 600; color: #2d5016;"></span></div>
                    <div><strong>Last Updated:</strong><br><span id="viewUpdated"></span></div>
                </div>
                
                <!-- Barcode Display Section -->
                <hr style="margin: 20px 0; border: none; border-top: 2px solid #e5e7eb;">
                <h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-barcode" style="margin-right: 8px;"></i>Barcode:</h6>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb; margin-bottom: 15px;">
                    <div id="viewBarcode" style="font-size: 1.5rem; font-weight: 700; color: #2d5016; font-family: 'Courier New', monospace; letter-spacing: 2px; margin-bottom: 10px;">-</div>
                    <div id="viewBarcodeLabel" style="color: #6c757d; font-size: 0.85rem; margin-top: 5px;">Scan this barcode to view item details</div>
                    <div id="viewBarcodeGenerate" style="margin-top: 10px; display: none;">
                        <button class="btn btn-sm btn-primary" id="btnGenerateBarcode" style="padding: 8px 16px; background: #2d5016; color: white; border: none; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-qrcode"></i> Generate Barcode
                        </button>
                    </div>
                </div>
                
                <hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">
                
                <h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-warehouse" style="margin-right: 8px;"></i>Stock Information:</h6>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #2d5016;" id="viewStock"></div>
                        <div style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Current Stock</div>
                    </div>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb;">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #ffc107;" id="viewMinMax"></div>
                        <div style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Min / Max Stock</div>
                    </div>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb;">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #17a2b8;" id="viewExpiry"></div>
                        <div style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Expiry Date</div>
                    </div>
                </div>
                
                <div id="updateStockContainer" style="display: none; gap: 10px; align-items: center; margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <input type="number" id="updateStockInput" style="padding: 10px; flex: 1; border: 1px solid var(--border-color); border-radius: var(--radius-md);" min="0" placeholder="Enter new stock quantity">
                    <button class="btn btn-primary" id="saveStockBtn" style="padding: 10px 20px; background: #2d5016; color: white; border: none; border-radius: 6px; cursor: pointer;">Save</button>
                    <button class="btn btn-secondary" id="cancelStockBtn" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
                <button class="btn btn-primary" id="btnUpdateStock" style="padding: 10px 20px; background: #2d5016; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;"><i class="fas fa-edit"></i> Update Stock</button>
                <button class="btn btn-success" id="btnReceiveDelivery" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;"><i class="fas fa-truck"></i> Receive Delivery</button>
                <button class="btn btn-info" id="btnTrackInventory" style="padding: 10px 20px; background: #17a2b8; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;"><i class="fas fa-chart-line"></i> Track Inventory</button>
                <button class="btn btn-warning" id="btnCheckExpiry" style="padding: 10px 20px; background: #ffc107; color: #000; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;"><i class="fas fa-calendar-check"></i> Check Expiry</button>
                <button class="btn btn-secondary" id="btnPrintReport" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;"><i class="fas fa-print"></i> Print Report</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Initial data for JS -->
    <script id="initial-items" type="application/json"><?= json_encode($items) ?></script>
    
    <!-- Category Items Data -->
    <script src="<?= base_url('assets/js/categoryItems.js') ?>?v=<?= time() ?>"></script>
    
    <!-- INVENTORY STAFF JS -->
    <script src="<?= base_url('assets/js/inventorystaff.js') ?>?v=<?= time() ?>"></script>
    
    <script>
        // Modal handlers
        document.getElementById('btnAdd')?.addEventListener('click', function() {
            document.getElementById('addItemModal').hidden = false;
            document.getElementById('addItemModal').style.display = 'flex';
        });
        
        document.getElementById('addClose')?.addEventListener('click', function() {
            document.getElementById('addItemModal').hidden = true;
            document.getElementById('addItemModal').style.display = 'none';
        });
        
        document.getElementById('addClose2')?.addEventListener('click', function() {
            document.getElementById('addItemModal').hidden = true;
            document.getElementById('addItemModal').style.display = 'none';
        });

        // Use shared category items from categoryItems.js (CHAKANOKS_CATEGORY_ITEMS)
        const categoryItems = typeof CHAKANOKS_CATEGORY_ITEMS !== 'undefined' ? CHAKANOKS_CATEGORY_ITEMS : {};
        const categoryUnits = typeof CHAKANOKS_CATEGORY_UNITS !== 'undefined' ? CHAKANOKS_CATEGORY_UNITS : {};
        const categoryAllowedUnits = typeof CHAKANOKS_CATEGORY_ALLOWED_UNITS !== 'undefined' ? CHAKANOKS_CATEGORY_ALLOWED_UNITS : {};
        const itemUnits = typeof CHAKANOKS_ITEM_UNITS !== 'undefined' ? CHAKANOKS_ITEM_UNITS : {};
        
        // Inventory products for price lookup
        let inventoryProducts = [];
        
        // Load inventory products for price auto-population
        function loadInventoryProducts() {
            const url = '<?= base_url('inventory/items') ?>';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.items && Array.isArray(data.items)) {
                    inventoryProducts = data.items;
                    console.log(`✅ Loaded ${inventoryProducts.length} inventory products for price lookup`);
                }
            })
            .catch(error => {
                console.error('Error loading inventory products:', error);
            });
        }
        
        // Find product price by name and category
        function findProductPrice(productName, category) {
            if (!productName || inventoryProducts.length === 0) return 0;
            
            const normalizedName = productName.trim().toLowerCase();
            const normalizedCategory = category ? category.trim().toLowerCase() : '';
            
            // Strategy 1: Exact match with category
            if (normalizedCategory) {
                const exactMatch = inventoryProducts.find(function(product) {
                    const prodName = (product.name || '').trim().toLowerCase();
                    const prodCategory = (product.category || '').trim().toLowerCase();
                    return prodName === normalizedName && prodCategory === normalizedCategory;
                });
                if (exactMatch) {
                    return parseFloat(exactMatch.price) || 0;
                }
            }
            
            // Strategy 2: Exact match without category
            const exactMatchNoCat = inventoryProducts.find(function(product) {
                const prodName = (product.name || '').trim().toLowerCase();
                return prodName === normalizedName;
            });
            if (exactMatchNoCat) {
                return parseFloat(exactMatchNoCat.price) || 0;
            }
            
            // Strategy 3: Match without parentheses
            const nameWithoutParens = normalizedName.replace(/\([^)]*\)/g, '').trim();
            if (nameWithoutParens && nameWithoutParens !== normalizedName) {
                const matchNoParens = inventoryProducts.find(function(product) {
                    const prodName = (product.name || '').trim().toLowerCase().replace(/\([^)]*\)/g, '').trim();
                    return prodName === nameWithoutParens;
                });
                if (matchNoParens) {
                    return parseFloat(matchNoParens.price) || 0;
                }
            }
            
            // Strategy 4: Partial match (contains)
            const partialMatch = inventoryProducts.find(function(product) {
                const prodName = (product.name || '').trim().toLowerCase();
                return prodName.includes(normalizedName) || normalizedName.includes(prodName);
            });
            if (partialMatch) {
                return parseFloat(partialMatch.price) || 0;
            }
            
            return 0;
        }
        
        // Load products on page load
        loadInventoryProducts();

        // Update item options based on selected category
        function updateItemOptions() {
            const categorySelect = document.getElementById('addCategory');
            const itemSelect = document.getElementById('addItemName');
            const unitSelect = document.getElementById('addUnit');
            const priceInput = document.querySelector('input[name="price"]');
            const selectedCategory = categorySelect.value;
            
            // Clear current options and reset price
            itemSelect.innerHTML = '<option value="">Select Item</option>';
            if (priceInput) {
                priceInput.value = '';
            }
            
            if (selectedCategory && categoryItems[selectedCategory]) {
                categoryItems[selectedCategory].forEach(function(item) {
                    const option = document.createElement('option');
                    option.value = item;
                    option.textContent = item;
                    
                    // Pre-load price if available
                    if (inventoryProducts.length > 0) {
                        const itemPrice = findProductPrice(item, selectedCategory);
                        if (itemPrice > 0) {
                            option.setAttribute('data-price', itemPrice.toFixed(2));
                        }
                    }
                    
                    itemSelect.appendChild(option);
                });
                
                // Add "Other" option for custom items
                const otherOption = document.createElement('option');
                otherOption.value = '__other__';
                otherOption.textContent = '-- Other (Custom Item) --';
                itemSelect.appendChild(otherOption);
                
                // Update unit options based on category
                updateUnitOptionsForCategory(selectedCategory);
            }
        }
        
        // Update unit options based on category (when category changes)
        function updateUnitOptionsForCategory(category) {
            const unitSelect = document.getElementById('addUnit');
            if (!unitSelect) return;
            
            const allowedUnits = categoryAllowedUnits[category] || ['pcs', 'kg', 'liters', 'packs', 'boxes', 'bottles', 'cans', 'bags', 'rolls', 'dozen'];
            const defaultUnit = categoryUnits[category] || 'pcs';
            
            // Clear and repopulate
            unitSelect.innerHTML = '';
            
            allowedUnits.forEach(function(unit) {
                const option = document.createElement('option');
                option.value = unit;
                option.textContent = unit;
                unitSelect.appendChild(option);
            });
            
            // Set default unit for category
            if (allowedUnits.includes(defaultUnit)) {
                unitSelect.value = defaultUnit;
            }
        }
        
        // Update unit options based on selected item (more specific)
        function updateUnitOptionsForItem(itemName, category) {
            const unitSelect = document.getElementById('addUnit');
            if (!unitSelect) return;
            
            let allowedUnits;
            
            // Check if item has specific units defined
            if (itemUnits[itemName]) {
                allowedUnits = itemUnits[itemName];
            } else if (categoryAllowedUnits[category]) {
                allowedUnits = categoryAllowedUnits[category];
            } else {
                allowedUnits = ['pcs', 'kg', 'liters', 'packs', 'boxes', 'bottles', 'cans', 'bags', 'rolls', 'dozen'];
            }
            
            const currentValue = unitSelect.value;
            
            // Clear and repopulate
            unitSelect.innerHTML = '';
            
            allowedUnits.forEach(function(unit) {
                const option = document.createElement('option');
                option.value = unit;
                option.textContent = unit;
                unitSelect.appendChild(option);
            });
            
            // Try to keep current selection if valid, otherwise use first option
            if (allowedUnits.includes(currentValue)) {
                unitSelect.value = currentValue;
            } else {
                unitSelect.value = allowedUnits[0];
            }
        }
        
        // Handle item selection to show custom input when "Other" is selected and update units
        document.getElementById('addItemName')?.addEventListener('change', function() {
            const customInput = document.getElementById('addCustomName');
            const useCustomCheckbox = document.getElementById('useCustomName');
            const categorySelect = document.getElementById('addCategory');
            const priceInput = document.querySelector('input[name="price"]');
            const selectedCategory = categorySelect ? categorySelect.value : '';
            const selectedItem = this.value;
            
            if (selectedItem === '__other__') {
                customInput.style.display = 'block';
                customInput.required = true;
                useCustomCheckbox.checked = true;
                // Reset to category default units for custom items
                updateUnitOptionsForCategory(selectedCategory);
                // Clear price for custom items
                if (priceInput) {
                    priceInput.value = '';
                }
            } else {
                customInput.style.display = 'none';
                customInput.required = false;
                useCustomCheckbox.checked = false;
                // Update units based on selected item
                if (selectedItem) {
                    updateUnitOptionsForItem(selectedItem, selectedCategory);
                    
                    // Auto-populate price from data attribute or lookup
                    if (priceInput) {
                        const selectedOption = this.options[this.selectedIndex];
                        const dataPrice = selectedOption.getAttribute('data-price');
                        
                        if (dataPrice) {
                            priceInput.value = dataPrice;
                        } else if (inventoryProducts.length > 0) {
                            // Try to find price if not in data attribute
                            const itemPrice = findProductPrice(selectedItem, selectedCategory);
                            if (itemPrice > 0) {
                                priceInput.value = itemPrice.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
        
        // Toggle custom name input
        function toggleCustomName() {
            const customInput = document.getElementById('addCustomName');
            const itemSelect = document.getElementById('addItemName');
            const useCustom = document.getElementById('useCustomName').checked;
            const priceInput = document.querySelector('input[name="price"]');
            const categorySelect = document.getElementById('addCategory');
            const selectedCategory = categorySelect ? categorySelect.value : '';
            
            if (useCustom) {
                customInput.style.display = 'block';
                customInput.required = true;
                itemSelect.required = false;
                // Clear price when switching to custom
                if (priceInput) {
                    priceInput.value = '';
                }
            } else {
                customInput.style.display = 'none';
                customInput.required = false;
                itemSelect.required = true;
                // Try to populate price if item is selected
                if (itemSelect.value && itemSelect.value !== '__other__' && priceInput) {
                    const itemPrice = findProductPrice(itemSelect.value, selectedCategory);
                    if (itemPrice > 0) {
                        priceInput.value = itemPrice.toFixed(2);
                    }
                }
            }
        }
        
        // Handle custom name input changes to auto-populate price
        document.getElementById('addCustomName')?.addEventListener('input', function() {
            const priceInput = document.querySelector('input[name="price"]');
            const categorySelect = document.getElementById('addCategory');
            const selectedCategory = categorySelect ? categorySelect.value : '';
            const customName = this.value.trim();
            
            // Debounce: only search after user stops typing for 500ms
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            if (customName.length >= 2 && priceInput && inventoryProducts.length > 0) {
                this.searchTimeout = setTimeout(function() {
                    const productPrice = findProductPrice(customName, selectedCategory);
                    if (productPrice > 0) {
                        priceInput.value = productPrice.toFixed(2);
                        console.log(`💰 Auto-filled price for "${customName}": ₱${productPrice.toFixed(2)}`);
                    }
                }, 500);
            } else if (priceInput && (!customName || customName.length === 0)) {
                // Clear price if custom name is empty
                priceInput.value = '';
            }
        });
        
        document.getElementById('viewClose')?.addEventListener('click', function() {
            document.getElementById('viewItemModal').hidden = true;
            document.getElementById('viewItemModal').style.display = 'none';
        });

        // Stock action handlers are now in inventorystaff.js
        // The base URL is passed via meta tag for the JS to use

        // Function to generate monthly report
        function generateMonthlyReport(filteredItems, monthName, formattedDate, formattedDateShort, branchInfo, userInfo) {
            if (!filteredItems || filteredItems.length === 0) {
                alert('No items found for the selected month.');
                return;
            }
            
            // Get branch information
            const branchName = branchInfo ? (branchInfo.name || 'N/A') : 'N/A';
            const branchAddress = branchInfo ? (branchInfo.address || 'N/A') : 'N/A';
            
            // Get user information (prepared by)
            const preparedBy = userInfo ? (userInfo.email || 'N/A') : 'N/A';

            // Group items by category
            const itemsByCategory = {};
            filteredItems.forEach(item => {
                const category = item.category || 'Uncategorized';
                if (!itemsByCategory[category]) {
                    itemsByCategory[category] = [];
                }
                itemsByCategory[category].push(item);
            });

            let reportHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>CHAKANOKS - Monthly Inventory Report</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    html, body { margin: 0; padding: 0; background: white; font-family: Arial, sans-serif; }
                    body { padding: 20px; }
                    .report-container { max-width: 210mm; width: 100%; margin: 0 auto; padding: 20mm; background: white; }
                    .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #2d5016; }
                    .header img { max-height: 50px; max-width: 50px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto 10px; }
                    .header .company-name { font-weight: bold; font-size: 24pt; letter-spacing: 2px; margin-bottom: 5px; color: #2d5016; }
                    .header .tagline { font-size: 12pt; color: #666; margin-bottom: 10px; }
                    .header .report-title { font-weight: bold; font-size: 18pt; text-transform: uppercase; margin-top: 10px; color: #2d5016; }
                    .info-section { margin-bottom: 20px; font-size: 11pt; }
                    .info-section .date { text-align: center; margin-bottom: 10px; font-weight: bold; }
                    .info-section .branch-info { text-align: center; margin-top: 10px; }
                    .info-section .prepared-by { text-align: right; margin-top: 15px; font-size: 10pt; }
                    table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 10pt; }
                    table th { background: #2d5016; color: white; padding: 10px; border: 1px solid #1a3a0e; font-weight: bold; text-align: left; }
                    table td { padding: 8px; border: 1px solid #ddd; }
                    table tr:nth-child(even) { background-color: #f9f9f9; }
                    .category-header { background: #4a7c2a !important; color: white !important; font-weight: bold; font-size: 11pt; }
                    .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #2d5016; font-size: 10pt; color: #666; }
                    @page { size: A4; margin: 20mm; }
                    @media print { 
                        html, body { margin: 0; padding: 0; } 
                        .report-container { padding: 0; margin: 0; }
                        @page { size: A4; margin: 20mm; }
                    }
                </style>
            </head>
            <body>
                <div class="report-container">
                    <div class="header">
                        <img src="<?= base_url('assets/images/529947519_1269388418065636_7025202690109522655_n.png') ?>" alt="CHAKANOKS Logo">
                        <div class="company-name">CHAKANOKS</div>
                        <div class="tagline">Supply Chain Management System</div>
                        <div class="report-title">Monthly Inventory Report</div>
                        <div style="font-size: 12pt; color: #666; margin-top: 5px; font-weight: normal;">Period: ${monthName}</div>
                    </div>
                    <div class="info-section">
                        <div class="date">Generated: ${formattedDateShort}</div>
                        <div class="branch-info">
                            <div style="font-weight: bold; font-size: 12pt; margin-top: 5px;">Branch: ${branchName}</div>
                            <div style="font-size: 10pt; color: #666; margin-top: 3px;">${branchAddress}</div>
                        </div>
                        <div class="prepared-by">
                            <strong>Prepared by:</strong> ${preparedBy}
                        </div>
                    </div>
            `;

            // Generate table grouped by category
            Object.keys(itemsByCategory).sort().forEach(category => {
                reportHTML += `
                    <table>
                        <thead>
                            <tr>
                                <th class="category-header" colspan="7">${category}</th>
                            </tr>
                            <tr>
                                <th>Product</th>
                                <th>Stock In</th>
                                <th>Stock Out</th>
                                <th>Current Stock</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                itemsByCategory[category].forEach(item => {
                    const lastUpdated = item.last_updated || item.updated_at ? new Date(item.last_updated || item.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    reportHTML += `
                    <tr>
                        <td style="font-weight: bold;">${item.name || 'N/A'}</td>
                        <td>${item.stock_in || 0}</td>
                        <td>${item.stock_out || 0}</td>
                        <td>${item.stock_qty || 0}</td>
                        <td>${item.unit || 'N/A'}</td>
                        <td>₱${parseFloat(item.price || 0).toFixed(2)}</td>
                        <td>${lastUpdated}</td>
                    </tr>
                    `;
                });
                
                reportHTML += `
                        </tbody>
                    </table>
                `;
            });

            reportHTML += `
                    <div class="footer">
                        <div style="margin-bottom: 5px;">Report Period: ${monthName}</div>
                        <div style="margin-bottom: 5px;">Branch: ${branchName}</div>
                        <div style="margin-bottom: 5px;">Total Items: ${filteredItems.length}</div>
                        <div style="margin-bottom: 5px;">Prepared by: ${preparedBy}</div>
                        <div>Generated: ${formattedDate}</div>
                    </div>
                </div>
            </body>
            </html>
            `;

            const printWindow = window.open('', '_blank', 'width=800,height=1000');
            if (!printWindow) {
                alert('Please allow popups to print the report');
                return;
            }
            
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                    printWindow.print();
                }, 500);
            };
            
            setTimeout(function() {
                if (printWindow.document.readyState === 'complete') {
                    printWindow.focus();
                    printWindow.print();
                }
            }, 1000);
        }
        
        // Function to send notification to central admin when report is generated
        function sendReportNotification(branchInfo, monthName, itemCount) {
            $.ajax({
                url: '<?= base_url('staff/api/notify-report-generated') ?>',
                method: 'POST',
                data: {
                    branch_id: branchInfo.id,
                    branch_name: branchInfo.name,
                    branch_address: branchInfo.address,
                    month: monthName,
                    item_count: itemCount
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Notification sent:', response);
                },
                error: function(xhr) {
                    console.error('Failed to send notification:', xhr);
                    // Don't show error to user, just log it
                }
            });
        }

        // Print All Reports - Monthly Report Format
        document.getElementById('btnPrintAll')?.addEventListener('click', function() {
            console.log('Print Monthly Report button clicked');
            
            // Get month from filter
            const reportMonthFilter = document.getElementById('reportMonthFilter');
            if (!reportMonthFilter) {
                alert('Month filter not found. Please refresh the page.');
                console.error('reportMonthFilter element not found');
                return;
            }
            
            const reportMonth = reportMonthFilter.value; // Format: YYYY-MM
            console.log('Selected month:', reportMonth);
            
            // Determine the month to filter
            let selectedMonth, selectedYear;
            if (reportMonth) {
                const [year, month] = reportMonth.split('-');
                selectedYear = parseInt(year);
                selectedMonth = parseInt(month);
            } else {
                // Use current month if not specified
                const now = new Date();
                selectedYear = now.getFullYear();
                selectedMonth = now.getMonth() + 1;
            }
            
            const monthName = new Date(selectedYear, selectedMonth - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            const formattedDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedDateShort = new Date().toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
            
            // Show loading message
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            
            // Fetch ALL items from the database for the selected month via AJAX
            const monthParam = reportMonth || (selectedYear + '-' + String(selectedMonth).padStart(2, '0'));
            console.log('Fetching items for month:', monthParam);
            
            $.ajax({
                url: '<?= base_url('staff/api/monthly-items') ?>',
                method: 'GET',
                data: {
                    month: monthParam
                },
                dataType: 'json',
                success: function(response) {
                    console.log('AJAX response:', response);
                    console.log('Month requested:', monthParam);
                    console.log('Date range:', response.start_date, 'to', response.end_date);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    
                    if (response.status === 'success' && response.items) {
                        const filteredItems = response.items;
                        const branchInfo = response.branch || null;
                        const userInfo = response.user || null;
                        console.log('Items loaded for month ' + monthParam + ':', filteredItems.length);
                        console.log('Branch info:', branchInfo);
                        console.log('User info:', userInfo);
                        
                        if (filteredItems.length === 0) {
                            alert('No products with stock transactions found for ' + monthName + '. Please select a different month.');
                            return;
                        }
                        
                        // Generate report with all items, branch info, and user info
                        generateMonthlyReport(filteredItems, monthName, formattedDate, formattedDateShort, branchInfo, userInfo);
                        
                        // Send notification to central admin
                        if (branchInfo) {
                            sendReportNotification(branchInfo, monthName, filteredItems.length);
                        }
                    } else {
                        alert('Error loading monthly data: ' + (response.message || 'Failed to load items'));
                        console.error('Error response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.error('XHR:', xhr);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    alert('Error loading monthly data. Please check your connection and try again.\n\nError: ' + error);
                }
            });
        });

        // Print Report for Individual Item - Enhanced to match accounts-payable receipt design
        document.getElementById('btnPrintReport')?.addEventListener('click', function() {
            const itemName = document.getElementById('viewItemTitle').textContent;
            const itemCategory = document.getElementById('viewCategory').textContent;
            const itemBranch = document.getElementById('viewBranch').textContent;
            const itemStock = document.getElementById('viewStock').textContent;
            const itemMinMax = document.getElementById('viewMinMax').textContent;
            const itemPrice = document.getElementById('viewPrice') ? document.getElementById('viewPrice').textContent : 'N/A';
            const itemStatus = document.getElementById('viewStatus').textContent;
            const itemUpdated = document.getElementById('viewUpdated').textContent;
            const itemExpiry = document.getElementById('viewExpiry').textContent;

            // Get month from filter
            const reportMonth = document.getElementById('reportMonthFilter').value;
            
            // Determine the month
            let selectedYear, selectedMonth;
            if (reportMonth) {
                const [year, month] = reportMonth.split('-');
                selectedYear = parseInt(year);
                selectedMonth = parseInt(month);
            } else {
                const now = new Date();
                selectedYear = now.getFullYear();
                selectedMonth = now.getMonth() + 1;
            }
            
            const monthName = new Date(selectedYear, selectedMonth - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            const formattedDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedDateShort = new Date().toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });

            // Determine status color
            let statusColor = '#28a745'; // Default green
            const statusText = itemStatus.toLowerCase();
            if (statusText.includes('low') || statusText.includes('warning')) {
                statusColor = '#ffc107'; // Orange/Yellow
            } else if (statusText.includes('out') || statusText.includes('critical')) {
                statusColor = '#dc3545'; // Red
            }

            // Helper function to format date
            function formatDate(dateString) {
                if (!dateString || dateString === 'N/A') return 'N/A';
                try {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                } catch (e) {
                    return dateString;
                }
            }

            let reportHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>CHAKANOKS - Inventory Item Report</title>
                <meta name="viewport" content="width=80mm">
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    html, body { width: 80mm; margin: 0; padding: 0; background: white; overflow: hidden; }
                    body { font-family: "Courier New", monospace; margin: 0; padding: 0; }
                    .receipt-container { max-width: 80mm; width: 80mm; min-width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-family: "Courier New", monospace; font-size: 10pt; line-height: 1.3; color: #000; }
                    img { max-height: 30px; max-width: 30px; object-fit: contain; }
                    @page { size: 80mm auto; margin: 0; width: 80mm; }
                    @media print { 
                        html, body { width: 80mm !important; margin: 0 !important; padding: 0 !important; } 
                        .receipt-container { max-width: 80mm !important; width: 80mm !important; min-width: 80mm !important; padding: 10mm 5mm !important; } 
                        @page { size: 80mm auto !important; margin: 0 !important; width: 80mm !important; } 
                    }
                </style>
            </head>
            <body>
                <div class="receipt-container">
                    <!-- Company Header - Compact (matching accounts payable exactly) -->
                    <div style="text-align: center; margin-bottom: 8mm; padding-bottom: 5mm; border-bottom: 1px dashed #000;">
                        <img src="<?= base_url('assets/images/529947519_1269388418065636_7025202690109522655_n.png') ?>" alt="CHAKANOKS Logo" style="max-height: 30px; max-width: 30px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto 3mm;">
                        <div style="font-weight: bold; font-size: 14pt; letter-spacing: 1px; margin-bottom: 2mm;">CHAKANOKS</div>
                        <div style="font-size: 8pt; color: #666; margin-bottom: 3mm;">Supply Chain Management System</div>
                        <div style="font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-top: 3mm;">INVENTORY ITEM REPORT</div>
                    </div>
                    
                    <!-- Item Information - Compact single column (matching accounts payable structure) -->
                    <div style="text-align: center; margin-bottom: 5mm; padding-bottom: 3mm; border-bottom: 1px dashed #000;">
                        <div style="font-weight: bold; font-size: 9pt; margin-bottom: 2mm;">ITEM: ${itemName}</div>
                        <div style="font-size: 8pt; margin-bottom: 1mm;">Status: <strong style="color: ${statusColor};">${itemStatus.toUpperCase()}</strong></div>
                    </div>
                    
                    <!-- Item Details (matching accounts payable info section) -->
                    <div style="margin-bottom: 4mm; font-size: 9pt;">
                        <div style="margin-bottom: 2mm;"><strong>Category:</strong> ${itemCategory}</div>
                        <div style="margin-bottom: 2mm;"><strong>Branch:</strong> ${itemBranch}</div>
                        ${itemExpiry && itemExpiry !== 'N/A' ? '<div style="margin-bottom: 2mm;"><strong>Expiry Date:</strong> ' + formatDate(itemExpiry) + '</div>' : ''}
                        <div style="margin-bottom: 2mm;"><strong>Last Updated:</strong> ${itemUpdated}</div>
                    </div>
                    
                    <!-- Stock Information - Compact (matching accounts payable amounts section) -->
                    <div style="text-align: center; margin-bottom: 4mm; padding: 3mm 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">
                        <div style="margin-bottom: 2mm;"><span style="font-size: 8pt;">Current Stock:</span><br><span style="font-size: 12pt; font-weight: bold; color: #2d5016;">${itemStock}</span></div>
                        <div style="margin-bottom: 2mm;"><span style="font-size: 8pt;">Min/Max Stock:</span><br><span style="font-size: 10pt; font-weight: bold;">${itemMinMax}</span></div>
                        <div><span style="font-size: 8pt;">Price:</span><br><span style="font-size: 12pt; font-weight: bold; color: #2d5016;">${itemPrice}</span></div>
                    </div>
                    
                    <!-- Item Details Section (matching accounts payable payment details section) -->
                    <div style="margin-bottom: 4mm; font-size: 9pt;">
                        <div style="text-align: center; font-weight: bold; margin-bottom: 2mm; padding-bottom: 2mm; border-bottom: 1px dashed #000;">ITEM DETAILS</div>
                        <div style="margin-bottom: 2mm;"><strong>Unit:</strong> ${itemStock.split(' ').slice(1).join(' ') || 'N/A'}</div>
                        <div style="margin-bottom: 2mm;"><strong>Category:</strong> ${itemCategory}</div>
                        <div style="margin-bottom: 2mm;"><strong>Branch:</strong> ${itemBranch}</div>
                    </div>
                    
                    <!-- Receipt Footer - Compact (matching accounts payable footer exactly) -->
                    <div style="text-align: center; margin-top: 5mm; padding-top: 3mm; border-top: 1px dashed #000; font-size: 8pt; color: #666;">
                        <div style="margin-bottom: 1mm;">Report Period: ${monthName}</div>
                        <div style="margin-bottom: 1mm;">Generated: ${formattedDate}</div>
                        <div style="margin-top: 3mm; font-size: 7pt;">CHAKANOKS Supply Chain Management System</div>
                    </div>
                    
                    <!-- Thank you message (matching accounts payable) -->
                    <div style="text-align: center; margin-top: 5mm; padding-top: 3mm; border-top: 1px dashed #000; font-size: 9pt; font-weight: bold;">
                        Thank you!
                    </div>
                </div>
            </body>
            </html>
            `;

            const printWindow = window.open('', '_blank', 'width=400,height=600');
            if (!printWindow) {
                alert('Please allow popups to print the report');
                return;
            }
            
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                    printWindow.print();
                }, 250);
            };
            
            setTimeout(function() {
                if (printWindow.document.readyState === 'complete') {
                    printWindow.focus();
                    printWindow.print();
                }
            }, 500);
        });

        // Load section-specific data on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize month filter with current month
            const today = new Date();
            const monthStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            
            const reportMonthFilter = document.getElementById('reportMonthFilter');
            const reportTimeFilter = document.getElementById('reportTimeFilter');
            if (reportMonthFilter) reportMonthFilter.value = monthStr;
            if (reportTimeFilter) reportTimeFilter.value = timeStr;
            
            const urlParams = new URLSearchParams(window.location.search);
            const currentSection = urlParams.get('section') || '<?= $currentSection ?? 'inventory' ?>';
            
            // Ensure sections are properly shown/hidden
            const sections = ['inventory', 'stock-out', 'deliveries'];
            sections.forEach(function(section) {
                const sectionEl = document.getElementById(section + '-section');
                if (sectionEl) {
                    sectionEl.style.display = (currentSection === section) ? 'block' : 'none';
                }
            });
            
            // Show/hide filter card only for inventory section
            const filterCard = document.querySelector('.filter-card');
            if (filterCard && currentSection !== 'inventory') {
                filterCard.style.display = 'none';
            }
            
            // Show/hide inventory table card only for inventory section
            const inventoryCard = document.querySelector('#inventory-section .content-card');
            if (inventoryCard && currentSection !== 'inventory') {
                inventoryCard.style.display = 'none';
            }
            
            // Load section-specific data
            // Deliveries are now loaded server-side, no need to call loadDeliveries()
            if (currentSection === 'stock-out') {
                loadProductsForSection(currentSection);
            }
            
            // Ensure branch filter is disabled for inventory staff
            <?php if (!empty($branchScope['enforced'])): ?>
            const branchSelect = document.getElementById('filterBranch');
            if (branchSelect) {
                branchSelect.disabled = true;
                branchSelect.style.cursor = 'not-allowed';
                branchSelect.style.opacity = '0.7';
            }
            <?php endif; ?>

            const branchFilter = document.getElementById('filterBranch');
            if (branchFilter) {
                branchFilter.addEventListener('change', function() {
                    if (document.getElementById('deliveries-section') && document.getElementById('deliveries-section').style.display !== 'none') {
                        loadDeliveries();
                    }
                });
            }
        });
        
        // Load products for stock out section
        function loadProductsForSection(section) {
            const productSelect = document.getElementById('stockOutProduct');
            
            if (!productSelect) return;
            
            $.ajax({
                url: '<?= base_url('staff/api/get-branch-products') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        productSelect.innerHTML = '<option value="">Select a product...</option>';
                        response.products.forEach(function(product) {
                            const option = document.createElement('option');
                            option.value = product.id;
                            // Remove units from product name (e.g., "(1.5L)", "(500ml)", etc.)
                            const cleanName = product.name.replace(/\s*\([^)]*\)\s*/g, '').trim();
                            option.textContent = cleanName + ' (Stock: ' + product.stock_qty + ')';
                            option.setAttribute('data-stock', product.stock_qty);
                            productSelect.appendChild(option);
                        });
                    }
                },
                error: function() {
                    alert('Error loading products');
                }
            });
        }
        
        // Stock quantity change handlers
        $(document).on('change', '#stockOutProduct', function() {
            const selectedOption = $(this).find('option:selected');
            const availableStock = selectedOption.attr('data-stock') || 0;
            $('#stockOutAvailable').text(availableStock);
            $('#stockOutQuantity').attr('max', availableStock);
        });
        
        // Reason change handlers
        $(document).on('change', '#stockOutReason', function() {
            const reason = $(this).val();
            
            if (reason === 'other') {
                $('#stockOutOtherReasonDiv').show();
                $('#stockOutOtherReason').prop('required', true);
                $('#stockOutTransferBranchDiv').hide();
                $('#stockOutTransferBranch').prop('required', false);
            } else if (reason === 'transfer') {
                $('#stockOutOtherReasonDiv').hide();
                $('#stockOutOtherReason').prop('required', false);
                $('#stockOutTransferBranchDiv').show();
                $('#stockOutTransferBranch').prop('required', true);
                loadTransferBranches();
            } else {
                $('#stockOutOtherReasonDiv').hide();
                $('#stockOutOtherReason').prop('required', false);
                $('#stockOutTransferBranchDiv').hide();
                $('#stockOutTransferBranch').prop('required', false);
            }
        });
        
        // Load branches for transfer
        function loadTransferBranches() {
            $.ajax({
                url: '<?= base_url('staff/api/get-transfer-branches') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const select = $('#stockOutTransferBranch');
                        select.empty();
                        select.append('<option value="">Select branch...</option>');
                        response.branches.forEach(function(branch) {
                            select.append(`<option value="${branch.id}">${branch.name} (${branch.code})</option>`);
                        });
                    } else {
                        alert('Error loading branches: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error loading branches';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        // Submit Stock Out
        function submitStockOut() {
            const productId = $('#stockOutProduct').val();
            const quantity = parseInt($('#stockOutQuantity').val());
            const reason = $('#stockOutReason').val();
            const otherReason = $('#stockOutOtherReason').val();
            const notes = $('#stockOutNotes').val();
            
            if (!productId || !quantity || quantity <= 0) {
                alert('Please select a product and enter a valid quantity');
                return;
            }
            
            if (!reason) {
                alert('Please select a reason');
                return;
            }
            
            const finalReason = reason === 'other' ? otherReason : reason;
            if (reason === 'other' && !finalReason) {
                alert('Please specify the reason');
                return;
            }
            
            // Validate transfer branch if reason is transfer
            const transferBranchId = $('#stockOutTransferBranch').val();
            if (reason === 'transfer' && !transferBranchId) {
                alert('Please select a branch to transfer to');
                return;
            }
            
            $.ajax({
                url: '<?= base_url('staff/api/stock-out') ?>',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    reason: finalReason,
                    notes: notes,
                    transfer_branch_id: transferBranchId || null
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Stock out recorded successfully!');
                        $('#stockOutForm')[0].reset();
                        $('#stockOutOtherReasonDiv').hide();
                        $('#stockOutTransferBranchDiv').hide();
                        loadProductsForSection('stock-out');
                        // Refresh inventory if on inventory section
                        if (document.getElementById('inventory-section').style.display !== 'none') {
                            location.reload();
                        }
                    } else {
                        alert('Error: ' + (response.message || 'Failed to record stock out'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error recording stock out';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        // Load deliveries
        function loadDeliveries() {
            const tbody = $('#deliveriesBody');
            tbody.html('<tr><td colspan="6" style="text-align: center; padding: 20px; color: #999;">Loading deliveries...</td></tr>');

            const branchSelect = document.getElementById('filterBranch');
            const selectedBranch = branchSelect ? branchSelect.value : null;
            const requestData = (selectedBranch && selectedBranch !== 'all') ? { branch_id: selectedBranch } : {};

            $.ajax({
                url: '<?= base_url('staff/api/get-deliveries') ?>',
                method: 'GET',
                data: requestData,
                dataType: 'json',
                success: function(response) {
                    const tbody = $('#deliveriesBody');

                    if (response.status !== 'success') {
                        const errorMsg = response.message || 'Unable to load deliveries.';
                        tbody.html(`<tr><td colspan="6" style="text-align: center; padding: 20px; color: #dc3545;">${errorMsg}</td></tr>`);
                        return;
                    }

                    tbody.empty();

                    if (!response.deliveries || response.deliveries.length === 0) {
                        tbody.html('<tr><td colspan="6" style="text-align: center; padding: 20px; color: #999;">No pending deliveries found</td></tr>');
                        return;
                    }

                    response.deliveries.forEach(function(delivery) {
                        const normalizedStatus = (delivery.status || 'scheduled').toLowerCase();
                        const statusBadge = normalizedStatus === 'scheduled' ? 'badge-warning' : 
                                           normalizedStatus === 'in_transit' ? 'badge-info' : 
                                           normalizedStatus === 'received' ? 'badge-success' :
                                           normalizedStatus === 'delivered' ? 'badge-success' :
                                           normalizedStatus === 'delayed' ? 'badge-danger' : 'badge-secondary';
                        const statusText = normalizedStatus.charAt(0).toUpperCase() + normalizedStatus.slice(1).replace('_', ' ');
                        
                        // Check payment status
                        const paymentStatus = (delivery.payment_status || 'unpaid').toLowerCase();
                        const isPaid = paymentStatus === 'paid';
                        const paymentBadge = paymentStatus === 'paid' ? 'badge-success' : 
                                           paymentStatus === 'partial' ? 'badge-warning' : 'badge-danger';
                        const paymentText = paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1);
                        
                        const row = `
                            <tr>
                                <td>${delivery.delivery_number}</td>
                                <td>${delivery.purchase_order?.order_number || 'N/A'}</td>
                                <td>${delivery.supplier?.name || 'N/A'}</td>
                                <td>${delivery.scheduled_date || 'N/A'}</td>
                                <td>
                                    <span class="badge ${statusBadge}">${statusText}</span>
                                    ${delivery.payment_status ? `
                                        <br><small class="badge ${paymentBadge}" style="margin-top: 4px; display: inline-block;">
                                            Payment: ${paymentText}
                                        </small>
                                    ` : ''}
                                </td>
                                <td style="text-align: right;">
                                    ${isPaid ? `
                                        <button class="btn btn-sm btn-primary" onclick="receiveDelivery(${delivery.id})">
                                            <i class="fas fa-check"></i> Receive
                                        </button>
                                    ` : `
                                        <div style="display: flex; gap: 5px; align-items: center; justify-content: flex-end;">
                                            <button class="btn btn-sm btn-secondary" disabled title="Payment not completed. Full payment required before receiving delivery.">
                                                <i class="fas fa-lock"></i> Payment Required
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="checkPaymentStatus(${delivery.id})" title="Check if payment has been completed">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    `}
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                },
                error: function(xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error loading deliveries';
                    $('#deliveriesBody').html(`<tr><td colspan="6" style="text-align: center; padding: 20px; color: #dc3545;">${errorMsg}</td></tr>`);
                }
            });
        }
        
        // Check payment status for a delivery
        function checkPaymentStatus(deliveryId) {
            const refreshBtn = $(`button[onclick*="checkPaymentStatus(${deliveryId})"]`);
            const originalHtml = refreshBtn.html();
            refreshBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '<?= base_url('delivery/') ?>' + deliveryId + '/track',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    refreshBtn.prop('disabled', false).html(originalHtml);
                    
                    if (response.status === 'success' && response.delivery) {
                        const paymentStatus = (response.delivery.payment_status || 'unpaid').toLowerCase();
                        const isPaid = paymentStatus === 'paid';
                        
                        if (isPaid) {
                            alert('Payment completed! Refreshing page to update delivery status...');
                            location.reload();
                        } else {
                            alert('Payment status: ' + paymentStatus.toUpperCase() + '. Full payment is still required.');
                        }
                    } else {
                        alert('Error checking payment status. Please refresh the page.');
                    }
                },
                error: function(xhr) {
                    refreshBtn.prop('disabled', false).html(originalHtml);
                    alert('Error checking payment status. Please try again or refresh the page.');
                }
            });
        }
        
        // Auto-refresh payment status every 30 seconds for unpaid deliveries
        $(document).ready(function() {
            setInterval(function() {
                $('#deliveriesBody tr').each(function() {
                    const row = $(this);
                    const paymentRequiredBtn = row.find('button:contains("Payment Required")');
                    
                    if (paymentRequiredBtn.length > 0) {
                        const refreshBtn = row.find('button[onclick*="checkPaymentStatus"]');
                        if (refreshBtn.length > 0) {
                            const onclickAttr = refreshBtn.attr('onclick');
                            if (onclickAttr) {
                                const match = onclickAttr.match(/checkPaymentStatus\((\d+)\)/);
                                if (match) {
                                    const deliveryId = match[1];
                                    checkPaymentStatusSilent(deliveryId);
                                }
                            }
                        }
                    }
                });
            }, 30000); // Check every 30 seconds
        });
        
        function checkPaymentStatusSilent(deliveryId) {
            $.ajax({
                url: '<?= base_url('delivery/') ?>' + deliveryId + '/track',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.delivery) {
                        const paymentStatus = (response.delivery.payment_status || 'unpaid').toLowerCase();
                        const isPaid = paymentStatus === 'paid';
                        
                        if (isPaid) {
                            // Reload to show updated status
                            location.reload();
                        }
                    }
                },
                error: function() {
                    // Silently fail
                }
            });
        }
        
        // Receive delivery
        function receiveDelivery(deliveryId) {
            console.log('Loading delivery details for ID:', deliveryId);
            $('#receiveDeliveryContent').html('<p style="text-align: center; color: #6b7280; margin: 20px 0;"><i class="fas fa-spinner fa-spin"></i> Loading delivery details...</p>');
            $('#receiveDeliveryModal').modal('show');
            
            $.ajax({
                url: '<?= base_url('delivery/') ?>' + deliveryId + '/details',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Delivery details response:', response);
                    if (response.status === 'success' && response.delivery) {
                        const delivery = response.delivery;
                        let itemsHtml = '<form id="receiveDeliveryForm">';
                        itemsHtml += '<input type="hidden" name="delivery_id" value="' + delivery.id + '">';
                        
                        // Delivery info header
                        itemsHtml += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">';
                        itemsHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">';
                        itemsHtml += '<div><strong>Delivery #:</strong> ' + (delivery.delivery_number || 'N/A') + '</div>';
                        itemsHtml += '<div><strong>Supplier:</strong> ' + (delivery.supplier?.name || 'N/A') + '</div>';
                        itemsHtml += '<div><strong>Scheduled:</strong> ' + (delivery.scheduled_date || 'N/A') + '</div>';
                        itemsHtml += '<div><strong>Status:</strong> ' + (delivery.status || 'N/A') + '</div>';
                        itemsHtml += '</div></div>';
                        
                        itemsHtml += '<h6 style="margin-bottom: 15px; font-weight: 600;">Delivery Items:</h6>';
                        
                        if (delivery.items && delivery.items.length > 0) {
                            itemsHtml += '<table class="table table-sm table-bordered">';
                            itemsHtml += '<thead style="background: #e9ecef;"><tr><th>Product</th><th>Expected</th><th>Received</th><th>Condition</th><th>Notes</th></tr></thead>';
                            itemsHtml += '<tbody>';
                            
                            delivery.items.forEach(function(item) {
                                const productName = item.product ? item.product.name : ('Product ID: ' + item.product_id);
                                itemsHtml += '<tr>';
                                itemsHtml += '<td>' + productName + '</td>';
                                itemsHtml += '<td>' + (item.expected_quantity || 0) + '</td>';
                                itemsHtml += '<td><input type="number" name="items[' + item.product_id + '][received_quantity]" value="' + (item.expected_quantity || 0) + '" min="0" class="form-control form-control-sm" style="width: 80px;" required></td>';
                                itemsHtml += '<td><select name="items[' + item.product_id + '][condition_status]" class="form-control form-control-sm"><option value="good">Good</option><option value="damaged">Damaged</option><option value="expired">Expired</option><option value="partial">Partial</option></select></td>';
                                itemsHtml += '<td><input type="text" name="items[' + item.product_id + '][notes]" class="form-control form-control-sm" placeholder="Notes..."></td>';
                                itemsHtml += '<input type="hidden" name="items[' + item.product_id + '][product_id]" value="' + item.product_id + '">';
                                itemsHtml += '</tr>';
                            });
                            
                            itemsHtml += '</tbody></table>';
                        } else {
                            itemsHtml += '<div class="alert alert-warning">No items found for this delivery.</div>';
                        }
                        
                        itemsHtml += '<div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #dee2e6; text-align: right;">';
                        itemsHtml += '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-right: 10px;">Cancel</button>';
                        if (delivery.items && delivery.items.length > 0) {
                            itemsHtml += '<button type="button" class="btn btn-primary" onclick="submitReceiveDelivery()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;"><i class="fas fa-check"></i> Confirm Receive</button>';
                        }
                        itemsHtml += '</div>';
                        itemsHtml += '</form>';
                        
                        $('#receiveDeliveryContent').html(itemsHtml);
                    } else {
                        $('#receiveDeliveryContent').html('<div class="alert alert-danger">Error loading delivery details: ' + (response.message || 'Unknown error') + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading delivery:', status, error, xhr.responseText);
                    $('#receiveDeliveryContent').html('<div class="alert alert-danger">Error loading delivery details. Please try again.</div>');
                }
            });
        }
        
        // Submit receive delivery
        function submitReceiveDelivery() {
            const deliveryId = $('#receiveDeliveryForm input[name="delivery_id"]').val();
            
            if (!deliveryId) {
                alert('Error: Delivery ID not found');
                return;
            }
            
            // Convert form data to proper format
            const items = [];
            $('#receiveDeliveryForm input[name^="items["]').each(function() {
                const name = $(this).attr('name');
                const matches = name.match(/items\[(\d+)\]\[(\w+)\]/);
                if (matches) {
                    const productId = matches[1];
                    const field = matches[2];
                    let item = items.find(i => i.product_id == productId);
                    if (!item) {
                        item = { product_id: parseInt(productId) };
                        items.push(item);
                    }
                    if (field === 'received_quantity') {
                        item.received_quantity = parseInt($(this).val()) || 0;
                    } else if (field === 'notes') {
                        item.notes = $(this).val();
                    }
                }
            });
            
            // Also get condition_status from selects
            $('#receiveDeliveryForm select[name^="items["]').each(function() {
                const name = $(this).attr('name');
                const matches = name.match(/items\[(\d+)\]\[(\w+)\]/);
                if (matches && matches[2] === 'condition_status') {
                    const productId = matches[1];
                    const item = items.find(i => i.product_id == productId);
                    if (item) {
                        item.condition_status = $(this).val();
                    }
                }
            });
            
            console.log('Submitting receive delivery:', deliveryId, items);
            
            if (items.length === 0) {
                alert('Error: No items to receive');
                return;
            }
            
            // Show loading state
            const submitBtn = $('#receiveDeliveryForm button[onclick="submitReceiveDelivery()"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
            
            $.ajax({
                url: '<?= base_url('delivery/') ?>' + deliveryId + '/receive',
                method: 'POST',
                data: { items: items },
                dataType: 'json',
                success: function(response) {
                    console.log('Receive response:', response);
                    if (response.status === 'success') {
                        alert('Delivery received successfully! Stock updated.');
                        $('#receiveDeliveryModal').modal('hide');
                        // Reload page to refresh deliveries list (server-rendered)
                        location.reload();
                    } else {
                        let errorMsg = response.message || 'Failed to receive delivery';
                        if (response.payment_status) {
                            errorMsg += '\n\nPayment Status: ' + response.payment_status.toUpperCase();
                            if (response.balance) {
                                errorMsg += '\nOutstanding Balance: ₱' + parseFloat(response.balance).toFixed(2);
                            }
                        }
                        alert('Error: ' + errorMsg);
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Receive error:', status, error, xhr.responseText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error receiving delivery';
                    alert('Error: ' + errorMsg);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        }
    </script>

    <!-- Receive Delivery Modal -->
    <div class="modal fade" id="receiveDeliveryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" style="margin: 0;"><i class="fas fa-truck" style="margin-right: 8px;"></i>Receive Delivery</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiveDeliveryContent" style="padding: 20px;">
                    <p style="text-align: center; color: #6b7280; margin: 0;">Loading delivery details...</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
