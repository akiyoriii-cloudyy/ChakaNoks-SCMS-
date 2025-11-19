<<<<<<< HEAD
<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    .dashboard-widget {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .dashboard-widget:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .widget-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .widget-value {
        font-size: 2rem;
        font-weight: bold;
        margin: 10px 0;
    }
    .widget-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .alert-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
    }
    .alert-critical { background: #dc3545; color: white; }
    .alert-warning { background: #ffc107; color: #000; }
    .alert-success { background: #28a745; color: white; }
    .alert-info { background: #17a2b8; color: white; }
    .branch-row {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
    }
    .branch-row:last-child {
        border-bottom: none;
    }
    .refresh-indicator {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
=======
<?php
// centraladmin.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Central Admin Dashboard - Chakanok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<<<<<<< HEAD
  <link rel="stylesheet" href="<?= base_url('assets/css/centraladmin.css') ?>">
=======
  <style>
    body { background: #f8f9fa; }
    .sidebar { height: 100vh; background: #146214; color: #fff; padding-top: 20px; }
    .sidebar a { color: #fff; display: block; padding: 10px 20px; margin: 5px 0; border-radius: 8px; text-decoration: none; }
    .sidebar a:hover { background: #198754; }
    .logout { color: red; font-weight: bold; }
  </style>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
</head>
<body>
>>>>>>> 6e3364b95b5cb4b31807defc91bc8d4193bed064
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
<<<<<<< HEAD
<?= $this->endSection() ?>

<?= $this->section('nav') ?>
<div class="container-fluid">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showPendingRequests()">
                <i class="fas fa-shopping-cart"></i> Purchase Requests
                <?php if ($data['purchaseRequests']['pending_approvals'] > 0): ?>
                    <span class="badge bg-danger"><?= $data['purchaseRequests']['pending_approvals'] ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-truck"></i> Deliveries
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-building"></i> Suppliers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid mt-4">
    
    <!-- Row 1: Key Metrics -->
    <div class="row mb-4">
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
            <div class="dashboard-widget">
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
            <div class="dashboard-widget">
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
            <div class="dashboard-widget">
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

</div>

<script>
// Auto-refresh dashboard every 30 seconds
let refreshInterval;

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
    // Load suppliers
    $.get('<?= base_url('supplier/list') ?>', function(supplierResponse) {
        if (supplierResponse.status === 'success') {
            let supplierOptions = '<option value="">Select Supplier</option>';
            supplierResponse.suppliers.forEach(function(supplier) {
                if (supplier.status === 'active') {
                    supplierOptions += `<option value="${supplier.id}">${supplier.name}</option>`;
                }
            });
            
            const supplierSelect = prompt('Enter Supplier ID (or use the dropdown):\n\n' + 
                supplierResponse.suppliers.filter(s => s.status === 'active')
                    .map(s => `${s.id}: ${s.name}`).join('\n'));
            
            if (!supplierSelect) return;
            
            const supplierId = parseInt(supplierSelect);
            if (isNaN(supplierId)) {
                alert('Invalid supplier ID');
                return;
            }
            
            const expectedDate = prompt('Expected Delivery Date (YYYY-MM-DD):', 
                new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0]);
            
            if (!expectedDate) return;
            
            $.post('<?= base_url('purchase/request/') ?>' + requestId + '/convert-to-po', {
                supplier_id: supplierId,
                expected_delivery_date: expectedDate,
                notes: 'Converted from purchase request'
            }, function(response) {
                if (response.status === 'success') {
                    alert('Purchase Order created successfully! Delivery record auto-created.');
                    showPendingRequests();
                    refreshDashboard();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            });
        }
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
                alert('Request approved!');
                showPendingRequests();
                refreshDashboard();
            }
        });
    }
}

function rejectRequest(id) {
    const reason = prompt('Enter rejection reason:');
    if (reason) {
        $.post('<?= base_url('purchase/request/') ?>' + id + '/reject', {reason: reason}, function(response) {
            if (response.status === 'success') {
                alert('Request rejected!');
                showPendingRequests();
                refreshDashboard();
            }
        });
    }
}

// Start auto-refresh
$(document).ready(function() {
    refreshInterval = setInterval(refreshDashboard, 30000); // 30 seconds
});
</script>
<?= $this->endSection() ?>
=======
</body>
<<<<<<< HEAD
=======
  <a href="<?= base_url('/auth/logout') ?>">Logout</a>
</body>
</html>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
</html>
>>>>>>> 6e3364b95b5cb4b31807defc91bc8d4193bed064
