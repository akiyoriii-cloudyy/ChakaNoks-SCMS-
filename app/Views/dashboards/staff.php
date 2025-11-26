<?php /** @var array $items */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                $currentSection = $currentSection ?? 'inventory';
                $baseUrl = base_url('staff/dashboard');
                ?>
                <a href="<?= $baseUrl ?>?section=inventory" class="nav-item <?= $currentSection === 'inventory' ? 'active' : '' ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
                <a href="<?= $baseUrl ?>?section=stock-in" class="nav-item <?= $currentSection === 'stock-in' ? 'active' : '' ?>">
                    <i class="fas fa-arrow-up"></i>
                    <span>Stock In</span>
                </a>
                <a href="<?= $baseUrl ?>?section=stock-out" class="nav-item <?= $currentSection === 'stock-out' ? 'active' : '' ?>">
                    <i class="fas fa-arrow-down"></i>
                    <span>Stock Out</span>
                </a>
                <a href="<?= $baseUrl ?>?section=deliveries" class="nav-item <?= $currentSection === 'deliveries' ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="<?= $baseUrl ?>?section=damaged" class="nav-item <?= $currentSection === 'damaged' ? 'active' : '' ?>">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Damaged/Expired</span>
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
                <div class="header-right">
                    <button id="btnAdd" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Add New Item</span>
                    </button>
                    <button id="btnPrintAll" class="btn btn-secondary">
                        <i class="fas fa-print"></i>
                        <span>Print Reports</span>
                    </button>
                </div>
            </header>

            <div class="dashboard-content" style="padding: 0 20px;">
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

                <!-- Stock In Section -->
                <div id="stock-in-section" class="dashboard-section" style="display: <?= ($currentSection ?? 'inventory') === 'stock-in' ? 'block' : 'none' ?>;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-arrow-up"></i> Stock In</h3>
                        </div>
                        <div style="padding: 20px;">
                            <form id="stockInForm">
                                <div class="mb-3">
                                    <label for="stockInProduct" class="form-label">Select Product</label>
                                    <select class="form-control" id="stockInProduct" required>
                                        <option value="">Select a product...</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="stockInQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="stockInQuantity" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stockInReference" class="form-label">Reference (Optional)</label>
                                    <input type="text" class="form-control" id="stockInReference" placeholder="e.g., Delivery #, Adjustment, etc.">
                                </div>
                                <div class="mb-3">
                                    <label for="stockInNotes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control" id="stockInNotes" rows="3" placeholder="Additional notes..."></textarea>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="submitStockIn()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                                    <i class="fas fa-check"></i> Record Stock In
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Stock In Section -->

                <!-- Stock Out Section -->
                <div id="stock-out-section" class="dashboard-section" style="display: <?= ($currentSection ?? 'inventory') === 'stock-out' ? 'block' : 'none' ?>;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-arrow-down"></i> Stock Out</h3>
                        </div>
                        <div style="padding: 20px;">
                            <form id="stockOutForm">
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
                                                          ($status === 'delayed' ? 'badge-danger' : 'badge-secondary'));
                                            $statusText = ucwords(str_replace('_', ' ', $status));
                                            ?>
                                            <tr>
                                                <td><?= esc($delivery['delivery_number']) ?></td>
                                                <td><?= esc($delivery['purchase_order']['order_number'] ?? 'N/A') ?></td>
                                                <td><?= esc($delivery['supplier']['name'] ?? 'N/A') ?></td>
                                                <td><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'N/A' ?></td>
                                                <td><span class="badge <?= $statusBadge ?>"><?= $statusText ?></span></td>
                                                <td style="text-align: right;">
                                                    <button class="btn btn-sm btn-primary" onclick="receiveDelivery(<?= $delivery['id'] ?>)">
                                                        <i class="fas fa-check"></i> Receive
                                                    </button>
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

                <!-- Damaged/Expired Section -->
                <div id="damaged-section" class="dashboard-section" style="display: <?= ($currentSection ?? 'inventory') === 'damaged' ? 'block' : 'none' ?>;">
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Report Damaged/Expired Goods</h3>
                        </div>
                        <div style="padding: 20px;">
                            <form id="damagedForm">
                                <div class="mb-3">
                                    <label for="damagedProduct" class="form-label">Select Product</label>
                                    <select class="form-control" id="damagedProduct" required>
                                        <option value="">Select a product...</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="damagedQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="damagedQuantity" min="1" required>
                                    <small class="form-text text-muted">Available stock: <span id="damagedAvailable">0</span></small>
                                </div>
                                <div class="mb-3">
                                    <label for="damagedReason" class="form-label">Reason</label>
                                    <select class="form-control" id="damagedReason" required>
                                        <option value="">Select reason...</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Expired">Expired</option>
                                        <option value="Damaged/Expired">Damaged/Expired</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="damagedOtherReasonDiv" style="display: none;">
                                    <label for="damagedOtherReason" class="form-label">Specify Reason</label>
                                    <input type="text" class="form-control" id="damagedOtherReason" placeholder="Enter reason...">
                                </div>
                                <div class="mb-3">
                                    <label for="damagedNotes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control" id="damagedNotes" rows="3" placeholder="Additional notes..."></textarea>
                                </div>
                                <button type="button" class="btn btn-warning" onclick="submitDamaged()" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: white;">
                                    <i class="fas fa-exclamation-triangle"></i> Report Damaged/Expired
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Damaged/Expired Section -->
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
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Item Name</label>
                        <select name="name" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <option value="">Select Item</option>
                            <optgroup label="Chicken Parts">
                                <option>Whole Chicken</option>
                                <option>Chicken Breast</option>
                                <option>Chicken Thigh</option>
                                <option>Chicken Wings</option>
                                <option>Chicken Drumstick</option>
                                <option>Chicken Liver</option>
                                <option>Chicken Gizzard</option>
                                <option>Chicken Feet</option>
                                <option>Chicken Head</option>
                                <option>Chicken Neck</option>
                                <option>Chicken Back</option>
                                <option>Chicken Heart</option>
                                <option>Chicken Kidney</option>
                                <option>Chicken Intestine</option>
                                <option>Chicken Blood</option>
                                <option>Chicken Skin</option>
                                <option>Chicken Fat</option>
                                <option>Chicken Bones</option>
                                <option>Chicken Tail</option>
                                <option>Chicken Leg Quarter</option>
                                <option>Chicken Breast Fillet</option>
                                <option>Chicken Tenderloin</option>
                                <option>Chicken Wing Tip</option>
                                <option>Chicken Wing Flat</option>
                                <option>Chicken Wing Drumlette</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Category</label>
                        <input type="text" name="category" value="Chicken Parts" readonly style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary);">
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
                            <select name="unit" required style="width: 120px; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                                <option value="pcs">pcs</option>
                                <option value="kg">kg</option>
                                <option value="liters">liters</option>
                                <option value="packs">packs</option>
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

    <!-- View Item Modal -->
    <div class="modal" id="viewItemModal" hidden style="position: fixed; inset: 0; z-index: 2000; display: none; align-items: center; justify-content: center;">
        <div class="backdrop" style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);" onclick="document.getElementById('viewItemModal').hidden = true;"></div>
        <div class="modal-card" style="background: white; border-radius: var(--radius-lg); padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative; z-index: 1; box-shadow: var(--shadow-xl);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 id="viewItemTitle" style="margin: 0; font-size: 1.5rem; font-weight: 700;">Item Name</h3>
                <button class="icon-btn" id="viewClose" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary);">&times;</button>
            </div>
            <div style="display: flex; flex-direction: column; gap: 15px; padding: 20px 0;">
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Category:</b> <span id="viewCategory"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Branch:</b> <span id="viewBranch"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Stock:</b> <span id="viewStock"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Min/Max:</b> <span id="viewMinMax"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Price:</b> <span id="viewPrice"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Status:</b> <span id="viewStatus" class="badge badge-success"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                    <b>Last Updated:</b> <span id="viewUpdated"></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <b>Expiry Date:</b> <span id="viewExpiry"></span>
                </div>

                <div id="updateStockContainer" style="display: none; gap: 10px; align-items: center; margin-top: 10px;">
                    <input type="number" id="updateStockInput" style="padding: 8px; flex: 1; border: 1px solid var(--border-color); border-radius: var(--radius-md);" min="0">
                    <button class="btn btn-primary" id="saveStockBtn">Save</button>
                    <button class="btn btn-secondary" id="cancelStockBtn">Cancel</button>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <button class="btn btn-primary" id="btnUpdateStock">Update Stock</button>
                <button class="btn btn-success" id="btnReceiveDelivery">Receive Delivery</button>
                <button class="btn btn-warning" id="btnReportDamaged">Report Damaged</button>
                <button class="btn btn-info" id="btnTrackInventory">Track Inventory</button>
                <button class="btn btn-secondary" id="btnCheckExpiry">Check Expiry</button>
                <button class="btn btn-secondary" id="btnPrintReport">Print Report</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Initial data for JS -->
    <script id="initial-items" type="application/json"><?= json_encode($items) ?></script>
    
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
        
        document.getElementById('viewClose')?.addEventListener('click', function() {
            document.getElementById('viewItemModal').hidden = true;
            document.getElementById('viewItemModal').style.display = 'none';
        });

        // View Item Modal Action Buttons - Override inventorystaff.js handlers
        let currentViewProductId = null;
        
        // Store product ID when viewing item - override the openViewModal function from inventorystaff.js
        const viewModal = document.getElementById('viewItemModal');
        if (viewModal) {
            // Intercept when modal is shown to store product ID
            const originalOpenViewModal = window.openViewModal;
            if (originalOpenViewModal) {
                window.openViewModal = function(itemId) {
                    currentViewProductId = itemId;
                    if (viewModal) {
                        viewModal.setAttribute('data-current-id', itemId);
                    }
                    return originalOpenViewModal(itemId);
                };
            }
            
            // Also watch for changes to data-current-id attribute
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'data-current-id') {
                        currentViewProductId = viewModal.getAttribute('data-current-id');
                    }
                });
            });
            observer.observe(viewModal, { attributes: true, attributeFilter: ['data-current-id'] });
            
            // Get initial value if already set
            if (viewModal.getAttribute('data-current-id')) {
                currentViewProductId = viewModal.getAttribute('data-current-id');
            }
        }
        
        // Update Stock button
        document.getElementById('btnUpdateStock')?.addEventListener('click', function() {
            const container = document.getElementById('updateStockContainer');
            if (container.style.display === 'none' || !container.style.display) {
                container.style.display = 'flex';
                const stockText = document.getElementById('viewStock').textContent;
                const currentStock = parseInt(stockText.split(' ')[0]) || 0;
                document.getElementById('updateStockInput').value = currentStock;
            } else {
                container.style.display = 'none';
            }
        });

        // Save Stock button
        document.getElementById('saveStockBtn')?.addEventListener('click', function() {
            const newQty = parseInt(document.getElementById('updateStockInput').value);
            if (isNaN(newQty) || newQty < 0) {
                alert('Please enter a valid stock quantity');
                return;
            }
            
            const productId = currentViewProductId || (viewModal ? viewModal.getAttribute('data-current-id') : null);
            if (!productId) {
                alert('Product ID not found');
                return;
            }

            $.ajax({
                url: '<?= base_url('staff/updateStock/') ?>' + productId,
                method: 'POST',
                data: { stock_qty: newQty },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Stock updated successfully!');
                        document.getElementById('viewItemModal').hidden = true;
                        document.getElementById('viewItemModal').style.display = 'none';
                        location.reload();
                    } else {
                        alert('Error: ' + (response.error || 'Failed to update stock'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Error updating stock';
                    alert('Error: ' + errorMsg);
                }
            });
        });

        // Cancel Stock Update button
        document.getElementById('cancelStockBtn')?.addEventListener('click', function() {
            document.getElementById('updateStockContainer').style.display = 'none';
        });

        // Receive Delivery button - override inventorystaff.js handler
        setTimeout(function() {
            const btnReceiveDelivery = document.getElementById('btnReceiveDelivery');
            const viewModal = document.getElementById('viewItemModal');
            
            if (btnReceiveDelivery && viewModal) {
                // Clone button to remove existing listeners
                const newBtn = btnReceiveDelivery.cloneNode(true);
                btnReceiveDelivery.parentNode.replaceChild(newBtn, btnReceiveDelivery);
                
                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (!viewModal.getAttribute('data-current-id')) {
                        alert('Please select a product first');
                        return;
                    }
                    
                    const productId = viewModal.getAttribute('data-current-id');
                    const stockText = document.getElementById('viewStock').textContent;
                    const currentStock = parseInt(stockText.split(' ')[0]) || 0;
                    
                    // Prompt for quantity
                    const qty = prompt('Enter quantity received (Current stock: ' + currentStock + '):');
                    const n = parseInt(qty);
                    if (!qty || isNaN(n) || n <= 0) {
                        return;
                    }
                    
                    $.ajax({
                        url: '<?= base_url('staff/receiveDelivery/') ?>' + productId,
                        method: 'POST',
                        data: { quantity: n },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert('Delivery received successfully! Stock updated.');
                                viewModal.hidden = true;
                                viewModal.style.display = 'none';
                                location.reload();
                            } else {
                                alert('Error: ' + (response.error || 'Failed to receive delivery'));
                            }
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Error receiving delivery';
                            alert('Error: ' + errorMsg);
                        }
                    });
                });
            }
        }, 1000);

        // Report Damaged button - override inventorystaff.js handler
        setTimeout(function() {
            const btnReportDamaged = document.getElementById('btnReportDamaged');
            const viewModal = document.getElementById('viewItemModal');
            
            if (btnReportDamaged && viewModal) {
                // Clone button to remove existing listeners
                const newBtn = btnReportDamaged.cloneNode(true);
                btnReportDamaged.parentNode.replaceChild(newBtn, btnReportDamaged);
                
                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (!viewModal.getAttribute('data-current-id')) {
                        alert('Please select a product first');
                        return;
                    }
                    
                    const productId = viewModal.getAttribute('data-current-id');
                    const stockText = document.getElementById('viewStock').textContent;
                    const currentStock = parseInt(stockText.split(' ')[0]) || 0;
                    
                    // Prompt for quantity
                    const qty = prompt('Enter damaged/expired quantity (Available: ' + currentStock + '):');
                    const n = parseInt(qty);
                    if (!qty || isNaN(n) || n <= 0) {
                        return;
                    }
                    if (n > currentStock) {
                        alert('Quantity cannot exceed available stock');
                        return;
                    }
                    
                    // Prompt for reason
                    const reason = prompt('Enter reason (Damaged/Expired/Other):') || 'Damaged/Expired';
                    
                    $.ajax({
                        url: '<?= base_url('staff/reportDamage/') ?>' + productId,
                        method: 'POST',
                        data: {
                            quantity: n,
                            reason: reason
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert('Damaged/expired goods reported successfully!');
                                viewModal.hidden = true;
                                viewModal.style.display = 'none';
                                location.reload();
                            } else {
                                alert('Error: ' + (response.error || 'Failed to report damaged goods'));
                            }
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Error reporting damaged goods';
                            alert('Error: ' + errorMsg);
                        }
                    });
                });
            }
        }, 1000);

        // Track Inventory button
        document.getElementById('btnTrackInventory')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            alert('Inventory tracking feature coming soon!');
        });

        // Check Expiry button
        document.getElementById('btnCheckExpiry')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = currentViewProductId || (viewModal ? viewModal.getAttribute('data-current-id') : null);
            if (!productId) {
                alert('Product ID not found');
                return;
            }
            
            $.ajax({
                url: '<?= base_url('staff/checkExpiry/') ?>' + productId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let message = 'Expiry Status: ' + response.state;
                        if (response.days !== null) {
                            if (response.days < 0) {
                                message += '\nExpired ' + Math.abs(response.days) + ' days ago';
                            } else if (response.days === 0) {
                                message += '\nExpires today!';
                            } else {
                                message += '\nExpires in ' + response.days + ' days';
                            }
                        }
                        if (response.expiry) {
                            message += '\nExpiry Date: ' + response.expiry;
                        }
                        alert(message);
                    } else {
                        alert('Error: ' + (response.error || 'Failed to check expiry'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Error checking expiry';
                    alert('Error: ' + errorMsg);
                }
            });
        });

        // Print All Reports
        document.getElementById('btnPrintAll')?.addEventListener('click', function() {
            const items = JSON.parse(document.getElementById('initial-items').textContent);
            const today = new Date();
            const formattedDate = today.toLocaleDateString() + ' ' + today.toLocaleTimeString();

            let reportHTML = `
            <html>
            <head>
                <title>Inventory Report — CHAKANOKS</title>
                <style>
                    @page { size: A4; margin: 20mm; }
                    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                    h1, h2 { text-align: center; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
                    th { background-color: #f0f0f0; }
                    .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #555; }
                </style>
            </head>
            <body>
                <h1>CHAKANOKS</h1>
                <h2>Inventory Report</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Branch</th>
                            <th>Stock Quantity</th>
                            <th>Min Stock</th>
                            <th>Max Stock</th>
                            <th>Unit</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            items.forEach(item => {
                reportHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.category}</td>
                    <td>${item.branch_name || item.branch_label || ''}</td>
                    <td>${item.stock_qty}</td>
                    <td>${item.min_stock}</td>
                    <td>${item.max_stock}</td>
                    <td>${item.unit}</td>
                    <td>${item.expiry}</td>
                </tr>
                `;
            });

            reportHTML += `
                    </tbody>
                </table>
                <div class="footer">Generated on ${formattedDate}</div>
            </body>
            </html>
            `;

            const printWindow = window.open('', '', 'width=900,height=700');
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        });

        // Print Report for Individual Item
        document.getElementById('btnPrintReport')?.addEventListener('click', function() {
            const itemName = document.getElementById('viewItemTitle').textContent;
            const itemCategory = document.getElementById('viewCategory').textContent;
            const itemBranch = document.getElementById('viewBranch').textContent;
            const itemStock = document.getElementById('viewStock').textContent;
            const itemMinMax = document.getElementById('viewMinMax').textContent;
            const itemStatus = document.getElementById('viewStatus').textContent;
            const itemUpdated = document.getElementById('viewUpdated').textContent;
            const itemExpiry = document.getElementById('viewExpiry').textContent;

            const today = new Date();
            const formattedDate = today.toLocaleDateString() + ' ' + today.toLocaleTimeString();

            let reportHTML = `
            <html>
            <head>
                <title>Item Report — CHAKANOKS</title>
                <style>
                    @page { size: A4; margin: 20mm; }
                    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                    h1, h2 { text-align: center; margin: 5px 0; }
                    .item-details { margin: 20px 0; }
                    .detail-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px; border-bottom: 1px solid #eee; }
                    .detail-label { font-weight: bold; }
                    .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #555; }
                </style>
            </head>
            <body>
                <h1>CHAKANOKS</h1>
                <h2>Item Report</h2>
                <div class="item-details">
                    <div class="detail-row"><span class="detail-label">Item Name:</span><span>${itemName}</span></div>
                    <div class="detail-row"><span class="detail-label">Category:</span><span>${itemCategory}</span></div>
                    <div class="detail-row"><span class="detail-label">Branch:</span><span>${itemBranch}</span></div>
                    <div class="detail-row"><span class="detail-label">Current Stock:</span><span>${itemStock}</span></div>
                    <div class="detail-row"><span class="detail-label">Min/Max Stock:</span><span>${itemMinMax}</span></div>
                    <div class="detail-row"><span class="detail-label">Status:</span><span>${itemStatus}</span></div>
                    <div class="detail-row"><span class="detail-label">Last Updated:</span><span>${itemUpdated}</span></div>
                    <div class="detail-row"><span class="detail-label">Expiry Date:</span><span>${itemExpiry}</span></div>
                </div>
                <div class="footer">Generated on ${formattedDate}</div>
            </body>
            </html>
            `;

            const printWindow = window.open('', '', 'width=900,height=700');
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        });

        // Load section-specific data on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSection = urlParams.get('section') || '<?= $currentSection ?? 'inventory' ?>';
            
            // Ensure sections are properly shown/hidden
            const sections = ['inventory', 'stock-in', 'stock-out', 'deliveries', 'damaged'];
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
            if (currentSection === 'stock-in' || currentSection === 'stock-out' || currentSection === 'damaged') {
                loadProductsForSection(currentSection);
                
                // Pre-select product if coming from view item modal
                if (currentSection === 'damaged') {
                    const damagedProductId = sessionStorage.getItem('damagedProductId');
                    if (damagedProductId) {
                        setTimeout(function() {
                            $('#damagedProduct').val(damagedProductId).trigger('change');
                            sessionStorage.removeItem('damagedProductId');
                        }, 500);
                    }
                }
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
        
        // Load products for stock in/out/damaged sections
        function loadProductsForSection(section) {
            const productSelect = document.getElementById(section === 'stock-in' ? 'stockInProduct' : 
                                                          section === 'stock-out' ? 'stockOutProduct' : 
                                                          'damagedProduct');
            
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
                            option.textContent = product.name + ' (Stock: ' + product.stock_qty + ' ' + (product.unit || '') + ')';
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
        $(document).on('change', '#stockOutProduct, #damagedProduct', function() {
            const selectedOption = $(this).find('option:selected');
            const availableStock = selectedOption.attr('data-stock') || 0;
            const availableSpan = $(this).attr('id') === 'stockOutProduct' ? $('#stockOutAvailable') : $('#damagedAvailable');
            availableSpan.text(availableStock);
            const qtyInput = $(this).attr('id') === 'stockOutProduct' ? $('#stockOutQuantity') : $('#damagedQuantity');
            qtyInput.attr('max', availableStock);
        });
        
        // Reason change handlers
        $(document).on('change', '#stockOutReason', function() {
            if ($(this).val() === 'other') {
                $('#stockOutOtherReasonDiv').show();
                $('#stockOutOtherReason').prop('required', true);
            } else {
                $('#stockOutOtherReasonDiv').hide();
                $('#stockOutOtherReason').prop('required', false);
            }
        });
        
        $(document).on('change', '#damagedReason', function() {
            if ($(this).val() === 'Other') {
                $('#damagedOtherReasonDiv').show();
                $('#damagedOtherReason').prop('required', true);
            } else {
                $('#damagedOtherReasonDiv').hide();
                $('#damagedOtherReason').prop('required', false);
            }
        });
        
        // Submit Stock In
        function submitStockIn() {
            const productId = $('#stockInProduct').val();
            const quantity = parseInt($('#stockInQuantity').val());
            const reference = $('#stockInReference').val();
            const notes = $('#stockInNotes').val();
            
            if (!productId || !quantity || quantity <= 0) {
                alert('Please select a product and enter a valid quantity');
                return;
            }
            
            $.ajax({
                url: '<?= base_url('staff/api/stock-in') ?>',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    reference: reference,
                    notes: notes
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Stock in recorded successfully!');
                        $('#stockInForm')[0].reset();
                        loadProductsForSection('stock-in');
                        // Refresh inventory if on inventory section
                        if (document.getElementById('inventory-section').style.display !== 'none') {
                            location.reload();
                        }
                    } else {
                        alert('Error: ' + (response.message || 'Failed to record stock in'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error recording stock in';
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
            
            $.ajax({
                url: '<?= base_url('staff/api/stock-out') ?>',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    reason: finalReason,
                    notes: notes
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Stock out recorded successfully!');
                        $('#stockOutForm')[0].reset();
                        $('#stockOutOtherReasonDiv').hide();
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
        
        // Submit Damaged/Expired
        function submitDamaged() {
            const productId = $('#damagedProduct').val();
            const quantity = parseInt($('#damagedQuantity').val());
            const reason = $('#damagedReason').val();
            const otherReason = $('#damagedOtherReason').val();
            const notes = $('#damagedNotes').val();
            
            if (!productId || !quantity || quantity <= 0) {
                alert('Please select a product and enter a valid quantity');
                return;
            }
            
            if (!reason) {
                alert('Please select a reason');
                return;
            }
            
            const finalReason = reason === 'Other' ? otherReason : reason;
            if (reason === 'Other' && !finalReason) {
                alert('Please specify the reason');
                return;
            }
            
            $.ajax({
                url: '<?= base_url('staff/reportDamage/') ?>' + productId,
                method: 'POST',
                data: {
                    quantity: quantity,
                    reason: finalReason + (notes ? ': ' + notes : '')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Damaged/expired goods reported successfully!');
                        $('#damagedForm')[0].reset();
                        $('#damagedOtherReasonDiv').hide();
                        loadProductsForSection('damaged');
                        // Refresh inventory if on inventory section
                        if (document.getElementById('inventory-section').style.display !== 'none') {
                            location.reload();
                        }
                    } else {
                        alert('Error: ' + (response.error || 'Failed to report damaged goods'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Error reporting damaged goods';
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
                                           normalizedStatus === 'delayed' ? 'badge-danger' : 'badge-secondary';
                        const statusText = normalizedStatus.charAt(0).toUpperCase() + normalizedStatus.slice(1).replace('_', ' ');
                        
                        const row = `
                            <tr>
                                <td>${delivery.delivery_number}</td>
                                <td>${delivery.purchase_order?.order_number || 'N/A'}</td>
                                <td>${delivery.supplier?.name || 'N/A'}</td>
                                <td>${delivery.scheduled_date || 'N/A'}</td>
                                <td><span class="badge ${statusBadge}">${statusText}</span></td>
                                <td style="text-align: right;">
                                    <button class="btn btn-sm btn-primary" onclick="receiveDelivery(${delivery.id})">
                                        <i class="fas fa-check"></i> Receive
                                    </button>
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
                        alert('Error: ' + (response.message || 'Failed to receive delivery'));
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
