<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    /* Professional gradient background */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    /* Page header styling */
    .page-header {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        color: white;
        padding: 30px 40px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(45, 80, 22, 0.2);
    }

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        background: white;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 30px;
    }

    /* Table styling */
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

    /* Badge styling */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        text-transform: capitalize;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .status-pending { 
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: #000;
    }

    .status-approved { 
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: #fff;
    }

    .status-rejected { 
        background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
        color: #fff;
    }

    .status-converted { 
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        color: #fff;
    }

    .priority-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .priority-urgent { 
        background: linear-gradient(135deg, #dc3545 0%, #ff6b6b 100%);
        color: #fff;
    }

    .priority-high { 
        background: linear-gradient(135deg, #fd7e14 0%, #ff9f43 100%);
        color: #fff;
    }

    .priority-normal { 
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        color: #fff;
    }

    .priority-low { 
        background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
        color: #fff;
    }

    /* Button styling */
    .btn-primary {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(45, 80, 22, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(45, 80, 22, 0.4);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-info {
        background: #17a2b8;
        border: none;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #28a745;
        border: none;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #dc3545;
        border: none;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        color: #ddd;
        margin-bottom: 20px;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        color: white;
        border: none;
        border-radius: 12px 12px 0 0;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Container */
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-list" style="margin-right: 12px;"></i>Purchase Requests</h2>
            <p>View and manage purchase requests</p>
        </div>
        <div>
            <a href="<?= base_url('purchase/request/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Request
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('nav') ?>
<div class="container-fluid" style="padding: 0;">
    <ul class="nav nav-tabs" style="background: white; border-bottom: 2px solid #e0e0e0; border-radius: 0;">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('manager/dashboard') ?>" style="color: #666; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#" style="color: #2d5016; border-bottom: 3px solid #4a7c2a; font-weight: 600;">
                <i class="fas fa-list"></i> My Requests
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid" style="padding: 0 20px;">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Branch</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox fa-4x"></i>
                                        <h5 style="margin-top: 20px; color: #666;">No purchase requests found</h5>
                                        <p style="color: #999;">Create your first purchase request to get started</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><strong><?= esc($request['request_number']) ?></strong></td>
                                <td><?= esc($request['branch']['name'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="priority-badge priority-<?= esc($request['priority']) ?>">
                                        <?= ucfirst(esc($request['priority'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= esc($request['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', esc($request['status']))) ?>
                                    </span>
                                </td>
                                <td>₱<?= number_format($request['total_amount'] ?? 0, 2) ?></td>
                                <td><?= date('M d, Y', strtotime($request['created_at'])) ?></td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <button class="btn btn-sm btn-info" onclick="viewRequest(<?= $request['id'] ?>)" title="View details">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <?php if (in_array($me['role'], ['central_admin', 'superadmin']) && $request['status'] === 'pending'): ?>
                                            <button class="btn btn-sm btn-success" onclick="approveRequest(<?= $request['id'] ?>)" title="Approve request">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="rejectRequest(<?= $request['id'] ?>)" title="Reject request">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Request Details Modal -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Purchase Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="requestModalBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function viewRequest(id) {
    $.get('<?= base_url('purchase/request/') ?>' + id, function(response) {
        if (response.status === 'success') {
            const req = response.request;
            let html = `
                <div class="mb-3">
                    <strong>Request Number:</strong> ${req.request_number}<br>
                    <strong>Branch:</strong> ${req.branch ? req.branch.name : 'N/A'}<br>
                    <strong>Status:</strong> <span class="status-badge status-${req.status}">${req.status.replace('_', ' ')}</span><br>
                    <strong>Priority:</strong> <span class="priority-badge priority-${req.priority}">${req.priority}</span><br>
                    <strong>Total Amount:</strong> ₱${parseFloat(req.total_amount || 0).toFixed(2)}<br>
                    <strong>Date:</strong> ${new Date(req.created_at).toLocaleDateString()}
                </div>
                <h6>Items:</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            req.items.forEach(item => {
                html += `
                    <tr>
                        <td>${item.product ? item.product.name : 'N/A'}</td>
                        <td>${item.quantity}</td>
                        <td>₱${parseFloat(item.unit_price || 0).toFixed(2)}</td>
                        <td>₱${parseFloat(item.subtotal || 0).toFixed(2)}</td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
            `;
            
            if (req.notes) {
                html += `<div class="mt-3"><strong>Notes:</strong><br>${req.notes}</div>`;
            }
            
            $('#requestModalBody').html(html);
            $('#requestModal').modal('show');
        }
    });
}

function approveRequest(id) {
    if (confirm('Approve this purchase request?')) {
        $.post('<?= base_url('purchase/request/') ?>' + id + '/approve', function(response) {
            if (response.status === 'success') {
                alert('Request approved!');
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
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
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        });
    }
}
</script>
<?= $this->endSection() ?>

