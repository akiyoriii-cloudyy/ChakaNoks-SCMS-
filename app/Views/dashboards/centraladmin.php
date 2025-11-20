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
            0 24px 70px rgba(0, 0, 0, 0.12),
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
        color: #f9fafb;
        box-shadow: 0 10px 25px rgba(45, 80, 22, 0.3);
        transform: translateY(-2px);
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
            <h2>Central Office Dashboard</h2>
            <p class="text-muted mb-0">Real-time monitoring of all branches and operations</p>
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

    <!-- Row 3: Branch Inventory Overview -->
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

    <!-- Convert to PO Modal -->
    <div class="modal fade" id="convertPoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Convert to Purchase Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="poSupplierSelect" class="form-label">Supplier</label>
                        <select id="poSupplierSelect" class="form-select">
                            <option value="">Loading suppliers...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="poExpectedDate" class="form-label">Expected Delivery Date</label>
                        <input type="date" id="poExpectedDate" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitConvertToPO()">Create Purchase Order</button>
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
                        <button class="btn btn-sm btn-warning" onclick="convertToPO(${req.id})">Convert to PO</button>
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

    // Load suppliers into modal dropdown
    $.get('<?= base_url('supplier/list') ?>', function(supplierResponse) {
        if (supplierResponse.status === 'success') {
            let optionsHtml = '<option value="">Select Supplier</option>';
            supplierResponse.suppliers.forEach(function(supplier) {
                if (supplier.status === 'active') {
                    optionsHtml += `<option value="${supplier.id}">${supplier.name}</option>`;
                }
            });

            $('#poSupplierSelect').html(optionsHtml);

            const defaultDate = new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0];
            $('#poExpectedDate').val(defaultDate);

            $('#convertPoModal').modal('show');
        } else {
            alert('❌ Failed to load suppliers');
        }
    }).fail(function(error) {
        alert('❌ Failed to load suppliers: ' + error.statusText);
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

