<<<<<<< HEAD
<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    .delivery-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: white;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: bold;
    }
    .status-scheduled { background: #17a2b8; color: #fff; }
    .status-in_transit { background: #ffc107; color: #000; }
    .status-delivered { background: #28a745; color: #fff; }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Logistics Coordinator Dashboard</h2>
            <p class="text-muted mb-0">Schedule and track deliveries</p>
        </div>
        <div class="d-flex align-items-center gap-3">
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
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showScheduleDelivery()">
                <i class="fas fa-calendar-plus"></i> Schedule Delivery
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showOrderTracking()">
                <i class="fas fa-search"></i> Track Orders
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid mt-4">
    
    <!-- Pending POs for Scheduling -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-shopping-cart"></i> Pending Purchase Orders (Need Scheduling)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($pendingPOs)): ?>
                <p class="text-muted">No pending purchase orders</p>
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

    <!-- Scheduled Deliveries -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-check"></i> Scheduled Deliveries</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($scheduledDeliveries)): ?>
                        <p class="text-muted">No scheduled deliveries</p>
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
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-truck"></i> In Transit Deliveries</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($inTransitDeliveries)): ?>
                        <p class="text-muted">No deliveries in transit</p>
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
        </div>
    </div>
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

function showScheduleDelivery() {
    // Scroll to pending POs section
    $('html, body').animate({
        scrollTop: $('.card:first').offset().top
    }, 500);
}

function showOrderTracking() {
    // Show tracking modal or scroll to deliveries
    alert('Use the "View" button on any purchase order to track it');
}
</script>
<?= $this->endSection() ?>
=======
<?php
// logisticscoordinator.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logistics Coordinator Dashboard - Chakanok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<<<<<<< HEAD
  <link rel="stylesheet" href="<?= base_url('assets/css/logisticscoordinator.css') ?>">
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
<div class="container-fluid">
  <div class="row">
<<<<<<< HEAD
    <!-- Sidebar -->
=======
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
    <div class="col-md-2 sidebar">
      <h4 class="text-center">CHAKANOKS</h4><hr>
      <a href="#"><i class="fa-solid fa-truck"></i> Deliveries</a>
      <a href="#"><i class="fa-solid fa-route"></i> Fleet Tracking</a>
      <a href="#"><i class="fa-solid fa-file-contract"></i> Schedules</a>
      <hr>
      <a href="<?= base_url('/auth/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
<<<<<<< HEAD
    
    <!-- Main Content -->
=======
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
    <div class="col-md-10 p-4">
      <h3>Logistics Coordinator Dashboard</h3>
      <p>Manage deliveries and fleet schedules.</p>
      <div class="row">
<<<<<<< HEAD
        <div class="col-md-6">
          <div class="card p-3 shadow">
            <h5>Ongoing Deliveries</h5>
            <p>12</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-3 shadow">
            <h5>Vehicles in Use</h5>
            <p>7</p>
          </div>
        </div>
=======
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Ongoing Deliveries</h5><p>12</p></div></div>
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Vehicles in Use</h5><p>7</p></div></div>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
      </div>
    </div>
  </div>
</div>
</body>
<<<<<<< HEAD
=======
  <a href="<?= base_url('/auth/logout') ?>">Logout</a>
</body>
</html>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
</html>
>>>>>>> 6e3364b95b5cb4b31807defc91bc8d4193bed064
