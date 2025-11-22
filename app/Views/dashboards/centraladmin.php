<<<<<<< HEAD
<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    :root {
        --green-1: #2d5016;
        --green-2: #4a7c2a;
        --orange-1: #ff6b35;
        --orange-2: #f7931e;
        --gold: #ffd700;
        --card-radius: 18px;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
        background:
            linear-gradient(135deg, var(--green-1) 0%, var(--green-2) 45%, #ffffff 45%, #f8f9fa 100%),
            radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(74, 124, 42, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 10% 10%, rgba(255, 255, 255, 0.1) 0%, transparent 30%),
            radial-gradient(circle at 90% 90%, rgba(255, 255, 255, 0.1) 0%, transparent 30%),
            radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
        background-attachment: fixed;
        min-height: 100vh;
        color: #111827;
    }

    .template-container {
        min-height: 100vh;
    }

    .template-header {
        padding: 20px 32px 10px;
        background: transparent;
        color: #f9fafb;
    }

    .template-body {
        padding: 0 32px 32px;
        background: transparent;
    }

    .dashboard-widget {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.97) 0%, rgba(255, 255, 255, 0.9) 100%);
        border-radius: var(--card-radius);
        padding: 22px 24px;
        box-shadow:
            0 20px 60px rgba(0, 0, 0, 0.08),
            0 10px 25px rgba(0, 0, 0, 0.06),
            0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-widget::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 0 0, rgba(255, 107, 53, 0.08), transparent 60%),
                    radial-gradient(circle at 100% 100%, rgba(45, 80, 22, 0.12), transparent 55%);
        opacity: 0.9;
        pointer-events: none;
    }

    .dashboard-widget > * {
        position: relative;
        z-index: 1;
    }

    .dashboard-widget:hover {
        transform: translateY(-3px);
        box-shadow:
            0 24px 70px rgba(249, 248, 248, 0.12),
            0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .widget-icon {
        font-size: 2.4rem;
        opacity: 0.9;
    }

    .widget-value {
        font-size: 2.1rem;
        font-weight: 800;
        margin: 6px 0 4px;
    }

    .widget-label {
        color: #111827;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .alert-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .alert-critical { background: #dc2626; color: #fff; }
    .alert-warning { background: #facc15; color: #92400e; }
    .alert-success { background: #22c55e; color: #052e16; }
    .alert-info { background: #0ea5e9; color: #f0f9ff; }

    .branch-row {
        border-bottom: 1px solid rgba(229, 231, 235, 0.9);
        padding: 12px 0;
    }

    .branch-row:last-child {
        border-bottom: none;
    }

    .refresh-indicator {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .nav-tabs {
        border-bottom: none;
        background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        margin-bottom: 24px;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 8px;
        color: #6b7280;
        font-weight: 600;
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        position: relative;
    }

    .nav-tabs .nav-link:hover {
        background: rgba(45, 80, 22, 0.08);
        color: #2d5016;
        transform: translateY(-2px);
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--green-1), var(--green-2));
        color: #ffffff !important;
        box-shadow: 0 10px 25px rgba(45, 80, 22, 0.3);
        transform: translateY(-2px);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    .nav-tabs .nav-link.active i {
        color: #ffffff !important;
    }

    .nav-tabs .nav-link i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    .nav-tabs .badge {
        background: linear-gradient(135deg, #dc2626, #ef4444) !important;
        margin-left: 6px;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 999px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<?php
// centraladmin.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Central Admin Dashboard - Chakanoks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="<?= base_url('assets/css/centraladmin.css') ?>">

  <style>
    body {
      background:
        linear-gradient(135deg, #2d5016 0%, #4a7c2a 45%, #ffffff 45%, #f8f9fa 100%),
        radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(74, 124, 42, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 10% 10%, rgba(255, 255, 255, 0.1) 0%, transparent 30%),
        radial-gradient(circle at 90% 90%, rgba(255, 255, 255, 0.1) 0%, transparent 30%),
        radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
      background-attachment: fixed;
      color: #111827;
    }
    .sidebar { height: 100vh; background: #146214; color: #fff; padding-top: 20px; }
    .sidebar a { color: #fff; display: block; padding: 10px 20px; margin: 5px 0; border-radius: 8px; text-decoration: none; font-weight: 600; }
    .sidebar a:hover { background: #198754; }
    .logout { color: red; font-weight: bold; }
  </style>

</head>
<body>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 style="color: #ffffff !important; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">Central Office Dashboard</h2>
            <p class="mb-0" style="color: rgba(255, 255, 255, 0.8) !important; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Real-time monitoring of all branches and operations</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <span class="refresh-indicator" id="lastRefresh">Last updated: Just now</span>
            <div class="text-end">
                <div style="font-weight: 600; color: #2ecc71;"><?= esc(session()->get('email') ?? '') ?></div>
                <small class="text-muted"><?= esc(session()->get('role') ?? '') ?></small>
            </div>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('nav') ?>
<div class="container-fluid">
    <?php $activeTab = $activeTab ?? 'dashboard'; ?>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'dashboard' ? 'active' : '' ?>" href="#" data-tab="dashboard" onclick="goToDashboard(); return false;">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab === 'purchaseRequests' ? 'active' : '' ?>" href="#" data-tab="purchaseRequests" onclick="showPendingRequests(); return false;">
                <i class="fas fa-shopping-cart"></i> Purchase Requests
                <?php if ($data['purchaseRequests']['pending_approvals'] > 0): ?>
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
            <a class="nav-link <?= $activeTab === 'reports' ? 'active' : '' ?>" href="#" data-tab="reports" onclick="showReports(); return false;">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid mt-4">
    
    <!-- Row 1: Key Metrics -->
    <div class="row mb-4" id="dashboardSection">
        <!-- Inventory Summary Widget -->
        <div class="col-md-3">
            <div class="dashboard-widget">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="widget-label">Total Items</div>
                        <div class="widget-value text-primary" id="totalItems"><?= $data['inventory']['total_items'] ?></div>
                        <div class="widget-label">Stock Value: ₱<?= number_format($data['inventory']['total_stock_value'], 2) ?></div>
                    </div>
                    <div class="widget-icon text-primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-md-3">
            <div class="dashboard-widget">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="widget-label">Low Stock Items</div>
                        <div class="widget-value text-warning" id="lowStockCount"><?= $data['inventory']['low_stock_count'] ?></div>
                        <div class="widget-label">Critical: <span class="alert-badge alert-critical"><?= $data['inventory']['critical_items_count'] ?></span></div>
                    </div>
                    <div class="widget-icon text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Requests -->
        <div class="col-md-3">
            <div class="dashboard-widget">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="widget-label">Pending Approvals</div>
                        <div class="widget-value text-danger" id="pendingApprovals"><?= $data['purchaseRequests']['pending_approvals'] ?></div>
                        <div class="widget-label">Value: ₱<?= number_format($data['purchaseRequests']['total_pending_value'], 2) ?></div>
                    </div>
                    <div class="widget-icon text-danger">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deliveries -->
        <div class="col-md-3">
            <div class="dashboard-widget">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="widget-label">In Transit</div>
                        <div class="widget-value text-info" id="inTransitDeliveries"><?= $data['deliveries']['in_transit_deliveries'] ?></div>
                        <div class="widget-label">Delayed: <span class="alert-badge alert-warning"><?= $data['deliveries']['delayed_deliveries'] ?></span></div>
                    </div>
                    <div class="widget-icon text-info">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Supplier Reports & Branch Overview -->
    <div class="row mb-4">
        <!-- Supplier Reports Widget -->
        <div class="col-md-6">
            <div class="dashboard-widget" id="suppliersSection">
                <h5 class="mb-3"><i class="fas fa-building text-primary"></i> Supplier Reports</h5>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="widget-label">Active Suppliers</div>
                        <div class="widget-value text-success" style="font-size: 1.5rem;" id="activeSuppliers"><?= $data['suppliers']['active_suppliers'] ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="widget-label">Pending Orders</div>
                        <div class="widget-value text-warning" style="font-size: 1.5rem;" id="pendingOrders"><?= $data['suppliers']['pending_orders'] ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="widget-label">On-Time Delivery Rate</div>
                        <div class="widget-value text-info" style="font-size: 1.5rem;" id="onTimeRate"><?= $data['suppliers']['on_time_delivery_rate'] ?>%</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="widget-label">Total Deliveries</div>
                        <div class="widget-value" style="font-size: 1.5rem;" id="totalDeliveries"><?= $data['suppliers']['total_deliveries'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Tracking Widget -->
        <div class="col-md-6">
            <div class="dashboard-widget" id="deliveriesSection">
                <h5 class="mb-3"><i class="fas fa-shipping-fast text-info"></i> Delivery Tracking</h5>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="widget-label">Scheduled</div>
                        <div class="widget-value text-primary" style="font-size: 1.5rem;" id="scheduledDeliveries"><?= $data['deliveries']['scheduled_deliveries'] ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="widget-label">Completed Today</div>
                        <div class="widget-value text-success" style="font-size: 1.5rem;" id="completedToday"><?= $data['deliveries']['completed_today'] ?></div>
                    </div>
                    <div class="col-12">
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $data['deliveries']['completed_today'] > 0 ? 100 : 0 ?>%">
                                Completed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Purchase Orders Section -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-widget" id="purchaseOrdersSection">
                <h5 class="mb-3"><i class="fas fa-file-invoice text-warning"></i> Purchase Orders</h5>
                <div class="table-responsive">
                    <div id="purchaseOrdersContent">
                        <p class="text-muted">Click on Purchase Orders tab to load data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Branch Inventory Overview -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-widget" id="reportsSection">
                <h5 class="mb-3"><i class="fas fa-sitemap text-success"></i> Branch Inventory Overview</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Total Products</th>
                                <th>Low Stock Items</th>
                                <th>Critical Alerts</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="branchTableBody">
                            <?php foreach ($data['branches'] as $branch): ?>
                            <tr class="branch-row">
                                <td><strong><?= esc($branch['branch_name']) ?></strong><br><small class="text-muted"><?= esc($branch['branch_code']) ?></small></td>
                                <td><?= $branch['total_products'] ?></td>
                                <td>
                                    <?php if ($branch['low_stock_items'] > 0): ?>
                                        <span class="alert-badge alert-warning"><?= $branch['low_stock_items'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($branch['critical_alerts'] > 0): ?>
                                        <span class="alert-badge alert-critical"><?= $branch['critical_alerts'] ?></span>
                                    <?php else: ?>
                                        <span class="text-success">✓</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($branch['critical_alerts'] > 0): ?>
                                        <span class="badge bg-danger">Critical</span>
                                    <?php elseif ($branch['low_stock_items'] > 0): ?>
                                        <span class="badge bg-warning">Warning</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Good</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Modal -->
    <div class="modal fade" id="pendingRequestsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pending Purchase Requests</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="pendingRequestsContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Purchase Order Modal -->
    <div class="modal fade" id="editPoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Purchase Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Purchase Request #:</strong></label>
                        <input type="text" class="form-control" id="editPoRequestNumber" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Branch:</strong></label>
                        <input type="text" class="form-control" id="editPoBranch" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Total Amount:</strong></label>
                        <input type="text" class="form-control" id="editPoAmount" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Select Supplier:</strong></label>
                        <select class="form-control" id="editPoSupplierSelect" onchange="displayEditSupplierDetails()">
                            <option value="">-- Choose a Supplier --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Expected Delivery Date:</strong></label>
                        <input type="date" class="form-control" id="editPoExpectedDate">
                    </div>
                    <div id="editSupplierDetailsCard" class="card mt-3" style="display: none;">
                        <div class="card-header" style="background: #f3f4f6;">
                            <strong>Supplier Details</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Contact Person:</strong> <span id="editSupplierContactPerson">-</span></p>
                            <p><strong>Email:</strong> <span id="editSupplierEmail">-</span></p>
                            <p><strong>Phone:</strong> <span id="editSupplierPhone">-</span></p>
                            <p><strong>Payment Terms:</strong> <span id="editSupplierPaymentTerms">-</span></p>
                            <p><strong>Address:</strong> <span id="editSupplierAddress">-</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitEditPO()"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Convert to PO Modal -->
    <div class="modal fade" id="convertPoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title"><i class="fas fa-file-invoice"></i> Convert to Purchase Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <!-- Supplier Selection -->
                    <div class="mb-4">
                        <label for="poSupplierSelect" class="form-label fw-bold">Select Supplier</label>
                        <select id="poSupplierSelect" class="form-select" onchange="displaySupplierDetails()" style="border: 2px solid #e0e0e0; padding: 12px;">
                            <option value="">-- Choose a Supplier --</option>
                        </select>
                    </div>

                    <!-- Supplier Details Card -->
                    <div id="supplierDetailsCard" style="display: none; background: #f8f9fa; border-left: 4px solid #2d5016; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <h6 class="mb-3" style="color: #2d5016; font-weight: 700;"><i class="fas fa-building"></i> Supplier Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Contact Person</small>
                                <div id="supplierContactPerson" style="font-weight: 600; color: #333;">-</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Email</small>
                                <div id="supplierEmail" style="font-weight: 600; color: #333;">-</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Phone</small>
                                <div id="supplierPhone" style="font-weight: 600; color: #333;">-</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Payment Terms</small>
                                <div id="supplierPaymentTerms" style="font-weight: 600; color: #333;">-</div>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">Address</small>
                                <div id="supplierAddress" style="font-weight: 600; color: #333;">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Expected Delivery Date -->
                    <div class="mb-4">
                        <label for="poExpectedDate" class="form-label fw-bold">Expected Delivery Date</label>
                        <input type="date" id="poExpectedDate" class="form-control" style="border: 2px solid #e0e0e0; padding: 12px;">
                    </div>

                    <!-- Request Summary -->
                    <div id="requestSummaryCard" style="background: #e8f5e9; border-left: 4px solid #4a7c2a; padding: 15px; border-radius: 8px;">
                        <h6 class="mb-2" style="color: #2d5016; font-weight: 700;"><i class="fas fa-list"></i> Request Summary</h6>
                        <div id="requestSummaryContent" style="font-size: 0.95rem; color: #333;">
                            <p class="mb-1"><strong>Request #:</strong> <span id="poRequestNumber">-</span></p>
                            <p class="mb-1"><strong>Branch:</strong> <span id="poRequestBranch">-</span></p>
                            <p class="mb-0"><strong>Total Amount:</strong> <span id="poRequestAmount" style="color: #2d5016; font-weight: 700;">₱0.00</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #e0e0e0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitConvertToPO()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                        <i class="fas fa-check-circle"></i> Create Purchase Order
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Auto-refresh dashboard every 30 seconds
let refreshInterval;
let currentRequestIdForPO = null;

function refreshDashboard() {
    $.get('<?= base_url('centraladmin/dashboard/data') ?>', function(response) {
        if (response.status === 'success') {
            const data = response.data;
            
            // Update widgets
            $('#totalItems').text(data.inventory.total_items);
            $('#lowStockCount').text(data.inventory.low_stock_count);
            $('#pendingApprovals').text(data.purchaseRequests.pending_approvals);
            $('#inTransitDeliveries').text(data.deliveries.in_transit_deliveries);
            $('#activeSuppliers').text(data.suppliers.active_suppliers);
            $('#pendingOrders').text(data.suppliers.pending_orders);
            $('#onTimeRate').text(data.suppliers.on_time_delivery_rate + '%');
            $('#scheduledDeliveries').text(data.deliveries.scheduled_deliveries);
            $('#completedToday').text(data.deliveries.completed_today);
            
            // Update last refresh time
            $('#lastRefresh').text('Last updated: ' + new Date().toLocaleTimeString());
        }
    });
}

function showPendingRequests() {
    setActiveTab('purchaseRequests');
    $.get('<?= base_url('purchase/request/pending') ?>', function(response) {
        if (response.status === 'success') {
            let html = '<table class="table"><thead><tr><th>Request #</th><th>Branch</th><th>Priority</th><th>Amount</th><th>Actions</th></tr></thead><tbody>';
            
            response.requests.forEach(function(req) {
                html += `<tr>
                    <td>${req.request_number}</td>
                    <td>${req.branch ? req.branch.name : 'N/A'}</td>
                    <td><span class="badge bg-${req.priority === 'urgent' ? 'danger' : req.priority === 'high' ? 'warning' : 'info'}">${req.priority}</span></td>
                    <td>₱${parseFloat(req.total_amount || 0).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="approveRequest(${req.id})">Approve</button>
                        <button class="btn btn-sm btn-danger" onclick="rejectRequest(${req.id})">Reject</button>
                        <button class="btn btn-sm btn-info" onclick="viewRequest(${req.id})">View</button>
                    </td>
                </tr>`;
            });
            
            html += '</tbody></table>';
            $('#pendingRequestsContent').html(html);
            $('#pendingRequestsModal').modal('show');
        }
    });
}

function convertToPO(requestId) {
    currentRequestIdForPO = requestId;

    // Load request details for summary
    $.get('<?= base_url('purchase/request/') ?>' + requestId, function(requestResponse) {
        if (requestResponse.status === 'success') {
            const req = requestResponse.request;
            $('#poRequestNumber').text(req.request_number);
            $('#poRequestBranch').text(req.branch ? req.branch.name : 'N/A');
            $('#poRequestAmount').text('₱' + parseFloat(req.total_amount || 0).toFixed(2));
        }
    });

    // Load suppliers into modal dropdown
    $.get('<?= base_url('supplier/list') ?>', function(supplierResponse) {
        if (supplierResponse.status === 'success') {
            let optionsHtml = '<option value="">-- Choose a Supplier --</option>';
            supplierResponse.suppliers.forEach(function(supplier) {
                if (supplier.status === 'active') {
                    optionsHtml += `<option value="${supplier.id}">${supplier.name}</option>`;
                }
            });

            $('#poSupplierSelect').html(optionsHtml);

            const defaultDate = new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0];
            $('#poExpectedDate').val(defaultDate);

            // Hide supplier details initially
            $('#supplierDetailsCard').hide();
            $('#convertPoModal').modal('show');
        } else {
            alert('❌ Failed to load suppliers');
        }
    }).fail(function(error) {
        alert('❌ Failed to load suppliers: ' + error.statusText);
    });
}

function displaySupplierDetails() {
    const supplierId = $('#poSupplierSelect').val();
    
    if (!supplierId) {
        $('#supplierDetailsCard').hide();
        return;
    }

    // Fetch supplier details
    $.get('<?= base_url('supplier/') ?>' + supplierId, function(response) {
        if (response.status === 'success') {
            const supplier = response.supplier;
            
            $('#supplierContactPerson').text(supplier.contact_person || '-');
            $('#supplierEmail').text(supplier.email || '-');
            $('#supplierPhone').text(supplier.phone || '-');
            $('#supplierPaymentTerms').text(supplier.payment_terms || '-');
            $('#supplierAddress').text(supplier.address || '-');
            
            $('#supplierDetailsCard').show();
        } else {
            $('#supplierDetailsCard').hide();
        }
    }).fail(function() {
        $('#supplierDetailsCard').hide();
    });
}

function submitConvertToPO() {
    if (!currentRequestIdForPO) {
        alert('❌ No purchase request selected.');
        return;
    }

    const supplierId = $('#poSupplierSelect').val();
    const expectedDate = $('#poExpectedDate').val();

    if (!supplierId) {
        alert('❌ Please select a supplier.');
        return;
    }

    if (!expectedDate) {
        alert('❌ Please choose an expected delivery date.');
        return;
    }

    $.post('<?= base_url('purchase/request/') ?>' + currentRequestIdForPO + '/convert-to-po', {
        supplier_id: supplierId,
        expected_delivery_date: expectedDate,
        notes: 'Converted from purchase request'
    }, function(response) {
        if (response.status === 'success') {
            $('#convertPoModal').modal('hide');
            alert('✅ Purchase Order created successfully! Delivery record auto-created.');
            showPendingRequests();
            refreshDashboard();
        } else {
            alert('❌ Error: ' + (response.message || 'Unknown error'));
        }
    }).fail(function(error) {
        alert('❌ Request failed: ' + error.statusText);
    });
}

function viewRequest(id) {
    $.get('<?= base_url('purchase/request/') ?>' + id, function(response) {
        if (response.status === 'success') {
            const req = response.request;
            let html = `
                <div class="mb-3">
                    <strong>Request Number:</strong> ${req.request_number}<br>
                    <strong>Branch:</strong> ${req.branch ? req.branch.name : 'N/A'}<br>
                    <strong>Status:</strong> <span class="badge bg-${req.status === 'pending' ? 'warning' : req.status === 'approved' ? 'success' : 'danger'}">${req.status}</span><br>
                    <strong>Priority:</strong> ${req.priority}<br>
                    <strong>Total Amount:</strong> ₱${parseFloat(req.total_amount || 0).toFixed(2)}<br>
                    <strong>Date:</strong> ${new Date(req.created_at).toLocaleDateString()}
                </div>
                <h6>Items:</h6>
                <table class="table table-sm">
                    <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
            `;
            req.items.forEach(item => {
                html += `<tr>
                    <td>${item.product ? item.product.name : 'N/A'}</td>
                    <td>${item.quantity}</td>
                    <td>₱${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                    <td>₱${parseFloat(item.subtotal || 0).toFixed(2)}</td>
                </tr>`;
            });
            html += `</tbody></table>`;
            if (req.notes) {
                html += `<div class="mt-2"><strong>Notes:</strong><br>${req.notes}</div>`;
            }
            $('#pendingRequestsContent').html(html);
            $('#pendingRequestsModal').modal('show');
        }
    });
}

function approveRequest(id) {
    if (confirm('Approve this purchase request?')) {
        $.post('<?= base_url('purchase/request/') ?>' + id + '/approve', function(response) {
            if (response.status === 'success') {
                alert('✅ Request approved!');
                showPendingRequests();
                refreshDashboard();
            } else {
                alert('❌ Error: ' + (response.message || 'Unknown error'));
            }
        }).fail(function(error) {
            alert('❌ Request failed: ' + error.statusText);
        });
    }
}

function rejectRequest(id) {
    const reason = prompt('Enter rejection reason:');
    if (reason) {
        $.post('<?= base_url('purchase/request/') ?>' + id + '/reject', {reason: reason}, function(response) {
            if (response.status === 'success') {
                alert('✅ Request rejected!');
                showPendingRequests();
                refreshDashboard();
            } else {
                alert('❌ Error: ' + (response.message || 'Unknown error'));
            }
        }).fail(function(error) {
            alert('❌ Request failed: ' + error.statusText);
        });
    }
}

function setActiveTab(tabName) {
    $('.nav-tabs .nav-link').removeClass('active');
    $('.nav-tabs .nav-link[data-tab="' + tabName + '"]').addClass('active');
}

function scrollToSection(sectionId) {
    const el = document.getElementById(sectionId);
    if (!el) return;
    $('html, body').animate({
        scrollTop: $(el).offset().top - 80
    }, 500);
}

function goToDashboard() {
    setActiveTab('dashboard');
    scrollToSection('dashboardSection');
}

function showDeliveries() {
    setActiveTab('deliveries');
    scrollToSection('deliveriesSection');
}

function showSuppliers() {
    setActiveTab('suppliers');
    scrollToSection('suppliersSection');
}

function showReports() {
    setActiveTab('reports');
    scrollToSection('reportsSection');
}

function showPurchaseOrders() {
    setActiveTab('purchaseOrders');
    scrollToSection('purchaseOrdersSection');
    
    // Show loading message
    $('#purchaseOrdersContent').html('<p class="text-muted"><i class="fas fa-spinner fa-spin"></i> Loading purchase orders...</p>');
    
    $.get('<?= base_url('purchase/order/list') ?>', function(response) {
        if (response.status === 'success') {
            let html = '<div class="table-responsive"><table class="table table-hover table-striped"><thead class="table-light"><tr><th>PO #</th><th>Request #</th><th>Supplier</th><th>Branch</th><th>Status</th><th>Amount</th><th>Expected Delivery</th><th>Actions</th></tr></thead><tbody>';
            
            if (response.orders && response.orders.length > 0) {
                response.orders.forEach(function(order) {
                    const statusBadge = order.status === 'pending' ? 'warning' : order.status === 'approved' ? 'success' : 'secondary';
                    const statusIcon = order.status === 'pending' ? '⏳' : order.status === 'approved' ? '✅' : '❓';
                    html += `<tr>
                        <td><strong>${order.order_number || 'PO-' + order.id}</strong></td>
                        <td><span class="badge bg-light text-dark">PR-${order.purchase_request_id}</span></td>
                        <td>${order.supplier ? order.supplier.name : 'N/A'}</td>
                        <td>${order.branch ? order.branch.name : 'N/A'}</td>
                        <td><span class="badge bg-${statusBadge}">${statusIcon} ${order.status.toUpperCase()}</span></td>
                        <td><strong>₱${parseFloat(order.total_amount || 0).toFixed(2)}</strong></td>
                        <td>${order.expected_delivery_date || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewPurchaseOrder(${order.id})"><i class="fas fa-eye"></i> View</button>
                            ${order.status === 'pending' ? `<button class="btn btn-sm btn-warning" onclick="showEditPoModal(${order.id}, 'PR-${order.purchase_request_id}', '${order.branch ? order.branch.name : 'N/A'}', ${order.total_amount}, ${order.supplier_id})"><i class="fas fa-edit"></i> Edit</button>` : ''}
                            ${order.status === 'pending' ? `<button class="btn btn-sm btn-success" onclick="approvePurchaseOrder(${order.id})"><i class="fas fa-check"></i> Approve</button>` : ''}
                        </td>
                    </tr>`;
                });
            } else {
                html += '<tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> No purchase orders found</td></tr>';
            }
            
            html += '</tbody></table></div>';
            $('#purchaseOrdersContent').html(html);
        } else {
            $('#purchaseOrdersContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Error loading purchase orders: ' + response.message + '</div>');
        }
    }).fail(function(error) {
        $('#purchaseOrdersContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Failed to load purchase orders</div>');
        console.error('Error:', error);
    });
}

function viewPurchaseOrder(id) {
    $.get('<?= base_url('purchase/order/') ?>' + id, function(response) {
        if (response.status === 'success') {
            const order = response.order;
            let details = `PO #: ${order.order_number || 'PO-' + order.id}\n`;
            details += `Status: ${order.status}\n`;
            details += `Supplier: ${order.supplier ? order.supplier.name : 'N/A'}\n`;
            details += `Branch: ${order.branch ? order.branch.name : 'N/A'}\n`;
            details += `Amount: ₱${parseFloat(order.total_amount || 0).toFixed(2)}\n`;
            details += `Expected Delivery: ${order.expected_delivery_date || 'N/A'}`;
            alert(details);
        }
    });
}

let currentEditPoId = null;

function showEditPoModal(poId, requestNumber, branch, amount, currentSupplierId) {
    currentEditPoId = poId;
    $('#editPoRequestNumber').val(requestNumber);
    $('#editPoBranch').val(branch);
    $('#editPoAmount').val('₱' + parseFloat(amount || 0).toFixed(2));
    
    // Load suppliers
    $.get('<?= base_url('supplier/list') ?>', function(response) {
        if (response.status === 'success') {
            let optionsHtml = '<option value="">-- Choose a Supplier --</option>';
            response.suppliers.forEach(function(supplier) {
                if (supplier.status === 'active') {
                    const selected = supplier.id === currentSupplierId ? 'selected' : '';
                    optionsHtml += `<option value="${supplier.id}" ${selected}>${supplier.name}</option>`;
                }
            });
            $('#editPoSupplierSelect').html(optionsHtml);
            
            // Set default delivery date (7 days from now)
            const defaultDate = new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0];
            $('#editPoExpectedDate').val(defaultDate);
            
            $('#editPoModal').modal('show');
        } else {
            alert('❌ Failed to load suppliers');
        }
    }).fail(function() {
        alert('❌ Failed to load suppliers');
    });
}

function displayEditSupplierDetails() {
    const supplierId = $('#editPoSupplierSelect').val();
    
    if (!supplierId) {
        $('#editSupplierDetailsCard').hide();
        return;
    }

    $.get('<?= base_url('supplier/') ?>' + supplierId, function(response) {
        if (response.status === 'success') {
            const supplier = response.supplier;
            
            $('#editSupplierContactPerson').text(supplier.contact_person || '-');
            $('#editSupplierEmail').text(supplier.email || '-');
            $('#editSupplierPhone').text(supplier.phone || '-');
            $('#editSupplierPaymentTerms').text(supplier.payment_terms || '-');
            $('#editSupplierAddress').text(supplier.address || '-');
            
            $('#editSupplierDetailsCard').show();
        } else {
            $('#editSupplierDetailsCard').hide();
        }
    }).fail(function() {
        $('#editSupplierDetailsCard').hide();
    });
}

function submitEditPO() {
    if (!currentEditPoId) {
        alert('❌ No purchase order selected.');
        return;
    }

    const supplierId = $('#editPoSupplierSelect').val();
    const expectedDate = $('#editPoExpectedDate').val();

    if (!supplierId) {
        alert('❌ Please select a supplier.');
        return;
    }

    if (!expectedDate) {
        alert('❌ Please choose an expected delivery date.');
        return;
    }

    $.post('<?= base_url('purchase/order/') ?>' + currentEditPoId + '/update', {
        supplier_id: supplierId,
        expected_delivery_date: expectedDate
    }, function(response) {
        if (response.status === 'success') {
            $('#editPoModal').modal('hide');
            alert('✅ Purchase Order updated successfully!');
            showPurchaseOrders();
            refreshDashboard();
        } else {
            alert('❌ Error: ' + (response.message || 'Unknown error'));
        }
    }).fail(function(error) {
        alert('❌ Request failed: ' + error.statusText);
    });
}

function approvePurchaseOrder(id) {
    if (confirm('Approve this purchase order?')) {
        const url = '<?= base_url('purchase/order/') ?>' + id + '/approve';
        console.log('Approving PO with URL:', url);
        
        $.post(url, function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert('✅ ' + response.message);
                showPurchaseOrders();
                refreshDashboard();
            } else {
                alert('❌ Error: ' + (response.message || 'Unknown error'));
                console.error('Error response:', response);
            }
        }).fail(function(error) {
            console.error('Request failed:', error);
            alert('❌ Request failed: ' + error.statusText + '. Check console for details.');
        });
    }
}

// Start auto-refresh
$(document).ready(function() {
    refreshDashboard();
    refreshInterval = setInterval(refreshDashboard, 30000); // 30 seconds
});
</script>
<?= $this->endSection() ?>

</body>

  <a href="<?= base_url('/auth/logout') ?>">Logout</a>
</body>
</html>

</html>

