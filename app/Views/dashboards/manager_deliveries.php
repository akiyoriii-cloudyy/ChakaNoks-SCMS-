<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deliveries — Branch Manager Dashboard — CHAKANOKS SCMS</title>
    
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
        .delivery-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #2d5016;
            transition: all 0.3s ease;
        }
        .delivery-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-scheduled { background: #fff3cd; color: #856404; }
        .status-in_transit { background: #cfe2ff; color: #084298; }
        .status-received { background: #17a2b8; color: #fff; }
        .status-delivered { background: #28a745; color: #fff; }
        .status-pending { background: #f8d7da; color: #842029; }
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
                <a href="<?= base_url('manager/dashboard') ?>" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('staff/dashboard') ?>" class="nav-item">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory Dashboard</span>
                </a>
                <a href="<?= base_url('purchase/request/new') ?>" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                </a>
                <a href="<?= base_url('purchase/request/list') ?>" class="nav-item">
                    <i class="fas fa-list"></i>
                    <span>My Requests</span>
                </a>
                <a href="<?= base_url('manager/deliveries') ?>" class="nav-item active">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                    <?php if (isset($data['deliveries']['pending_deliveries']) && $data['deliveries']['pending_deliveries'] > 0): ?>
                        <span class="badge" style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; margin-left: auto;">
                            <?= $data['deliveries']['pending_deliveries'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="<?= base_url('manager/stock-out') ?>" class="nav-item">
                    <i class="fas fa-arrow-down"></i>
                    <span>Stock Out</span>
                </a>
                <a href="<?= base_url('manager/settings') ?>" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
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
                        <h2 class="page-title">Deliveries</h2>
                        <p class="page-subtitle">Confirm deliveries received at your branch</p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= base_url('manager/dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </header>

            <div class="dashboard-content" style="padding: var(--spacing-lg);">
                <?php if (empty($deliveries)): ?>
                    <div class="content-card" style="text-align: center; padding: 60px 20px;">
                        <i class="fas fa-truck" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                        <h3 style="color: #666; margin-bottom: 10px;">No Pending Deliveries</h3>
                        <p style="color: #999;">All deliveries have been confirmed or there are no scheduled deliveries.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($deliveries as $delivery): ?>
                        <div class="delivery-card">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                <div>
                                    <h4 style="margin: 0; color: #2d5016; font-weight: 700;">
                                        <?= esc($delivery['delivery_number']) ?>
                                    </h4>
                                    <p style="margin: 5px 0 0 0; color: #666; font-size: 0.9rem;">
                                        Purchase Order: <?= esc($delivery['purchase_order']['order_number']) ?>
                                    </p>
                                </div>
                                <span class="status-badge status-<?= esc($delivery['status']) ?>">
                                    <?= esc(ucwords(str_replace('_', ' ', $delivery['status']))) ?>
                                </span>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 15px;">
                                <div>
                                    <strong style="color: #333;">Supplier:</strong>
                                    <p style="margin: 5px 0; color: #666;"><?= esc($delivery['supplier']['name']) ?></p>
                                </div>
                                <div>
                                    <strong style="color: #333;">Scheduled Date:</strong>
                                    <p style="margin: 5px 0; color: #666;">
                                        <?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'N/A' ?>
                                    </p>
                                </div>
                                <?php if ($delivery['driver_name']): ?>
                                <div>
                                    <strong style="color: #333;">Driver:</strong>
                                    <p style="margin: 5px 0; color: #666;"><?= esc($delivery['driver_name']) ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if ($delivery['vehicle_info']): ?>
                                <div>
                                    <strong style="color: #333;">Vehicle:</strong>
                                    <p style="margin: 5px 0; color: #666;"><?= esc($delivery['vehicle_info']) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                                <button class="btn btn-primary" onclick="confirmDelivery(<?= $delivery['id'] ?>)" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Confirm Delivery
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Delivery Confirmation Modal -->
    <div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-labelledby="confirmDeliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="confirmDeliveryModalLabel">
                        <i class="fas fa-check-circle"></i> Confirm Delivery
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3" style="color: #666;">Please confirm that you have received this delivery. This will update the delivery status and record the receipt details.</p>
                    <form id="confirmDeliveryForm">
                        <input type="hidden" id="confirmDeliveryId" name="delivery_id">
                        <div class="mb-3">
                            <label for="actualDeliveryDate" class="form-label">Actual Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="actualDeliveryDate" name="actual_delivery_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="receivedBy" class="form-label">Received By (Reference) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="receivedBy" name="received_by" value="<?= esc($me['email'] ?? '') ?>" required placeholder="Enter your name or email">
                            <small class="form-text text-muted">This is for reference only. Your user account will be recorded as the receiver.</small>
                        </div>
                        <div class="mb-3">
                            <label for="receivedAt" class="form-label">Received At (Date & Time) <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="receivedAt" name="received_at" value="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitConfirmBtn" onclick="submitDeliveryConfirmation()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                        <i class="fas fa-check"></i> Confirm Delivery
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelivery(deliveryId) {
            $('#confirmDeliveryId').val(deliveryId);
            $('#confirmDeliveryModal').modal('show');
        }
        
        function submitDeliveryConfirmation() {
            const deliveryId = $('#confirmDeliveryId').val();
            const actualDeliveryDate = $('#actualDeliveryDate').val();
            const receivedBy = $('#receivedBy').val();
            const receivedAt = $('#receivedAt').val();
            
            if (!actualDeliveryDate || !receivedBy || !receivedAt) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Show loading state
            const submitBtn = $('#submitConfirmBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Confirming...');
            
            $.ajax({
                url: '<?= base_url('delivery/') ?>' + deliveryId + '/confirm',
                method: 'POST',
                data: {
                    actual_delivery_date: actualDeliveryDate,
                    received_by: receivedBy,
                    received_at: receivedAt
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#confirmDeliveryModal').modal('hide');
                        alert('Delivery confirmed successfully!');
                        location.reload();
                    } else {
                        submitBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Failed to confirm delivery'));
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error confirming delivery';
                    alert('Error: ' + errorMsg);
                }
            });
        }
    </script>
</body>
</html>

