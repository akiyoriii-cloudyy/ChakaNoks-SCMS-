<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: bold;
    }
    .status-pending { background: #ffc107; color: #000; }
    .status-approved { background: #28a745; color: #fff; }
    .status-rejected { background: #dc3545; color: #fff; }
    .status-converted { background: #17a2b8; color: #fff; }
    .priority-badge {
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
    }
    .priority-urgent { background: #dc3545; color: #fff; }
    .priority-high { background: #fd7e14; color: #fff; }
    .priority-normal { background: #17a2b8; color: #fff; }
    .priority-low { background: #6c757d; color: #fff; }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Purchase Requests</h2>
            <p class="text-muted mb-0">View and manage purchase requests</p>
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
<div class="container-fluid">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('manager/dashboard') ?>">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-list"></i> My Requests
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
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
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    No purchase requests found
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
                                    <button class="btn btn-sm btn-info" onclick="viewRequest(<?= $request['id'] ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <?php if (in_array($me['role'], ['central_admin', 'superadmin']) && $request['status'] === 'pending'): ?>
                                        <button class="btn btn-sm btn-success" onclick="approveRequest(<?= $request['id'] ?>)">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectRequest(<?= $request['id'] ?>)">
                                            <i class="fas fa-times"></i> Reject
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

