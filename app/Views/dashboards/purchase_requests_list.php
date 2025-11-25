<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Requests — CHAKANOKS SCMS</title>
    
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
        .dashboard-sidebar,
        .sidebar-nav,
        .nav-item {
            visibility: visible !important;
            display: block !important;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
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
                $currentUrl = current_url();
                $isDashboard = strpos($currentUrl, 'centraladmin/dashboard') !== false && strpos($currentUrl, '?tab=') === false;
                ?>
                <a href="<?= base_url('centraladmin/dashboard') ?>" class="nav-item <?= $isDashboard ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('purchase/request/list') ?>" class="nav-item <?= strpos($currentUrl, 'purchase/request') !== false ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                    <?php if (($data['purchaseRequests']['pending_approvals'] ?? 0) > 0): ?>
                        <span class="badge badge-danger" style="margin-left: auto; background: rgba(239, 68, 68, 0.2); color: #ef4444;"><?= $data['purchaseRequests']['pending_approvals'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= base_url('purchase/order/list') ?>" class="nav-item <?= strpos($currentUrl, 'purchase/order') !== false ? 'active' : '' ?>">
                    <i class="fas fa-file-invoice"></i>
                    <span>Purchase Orders</span>
                </a>
                <a href="<?= base_url('delivery/branch/list') ?>" class="nav-item <?= strpos($currentUrl, 'delivery') !== false ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="<?= base_url('supplier/list') ?>" class="nav-item <?= strpos($currentUrl, 'supplier') !== false ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Suppliers</span>
                </a>
                <a href="<?= base_url('accounts-payable/list') ?>" class="nav-item <?= strpos($currentUrl, 'accounts-payable') !== false ? 'active' : '' ?>">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Accounts Payable</span>
                </a>
                <a href="<?= base_url('centraladmin/reports') ?>" class="nav-item <?= strpos($currentUrl, 'centraladmin/reports') !== false ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
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
                        <h2 class="page-title">Purchase Requests</h2>
                        <p class="page-subtitle">View and manage purchase requests</p>
                    </div>
                </div>
                <div class="header-right">
                    <?php if (!in_array($me['role'] ?? '', ['central_admin', 'superadmin'])): ?>
                    <a href="<?= base_url('purchase/request/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Request
                    </a>
                    <?php endif; ?>
                    <button class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                </div>
            </header>

            <div class="dashboard-content" style="padding: var(--spacing-lg);">
                <div class="content-card">
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
                                        <td colspan="7" class="text-center" style="padding: 60px 20px;">
                                            <i class="fas fa-inbox fa-4x" style="color: #ddd; margin-bottom: 20px; display: block;"></i>
                                            <h5 style="margin-top: 20px; color: #666;">No purchase requests found</h5>
                                            <p style="color: #999;">Create your first purchase request to get started</p>
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
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
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
        </main>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart"></i> Purchase Request Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="requestModalBody">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Details Modal for Approval -->
    <div class="modal fade" id="deliveryDetailsModal" tabindex="-1" aria-labelledby="deliveryDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="deliveryDetailsModalLabel">
                        <i class="fas fa-truck"></i> Set Delivery Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3" style="color: #666;">Please provide delivery details before approving this purchase request. These details will be used when creating the delivery record.</p>
                    <form id="deliveryDetailsForm">
                        <input type="hidden" id="approveRequestId" name="request_id">
                        <div class="mb-3">
                            <label for="deliveryScheduledDate" class="form-label">Scheduled Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="deliveryScheduledDate" name="scheduled_delivery_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="deliveryDriverName" class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="deliveryDriverName" name="driver_name" placeholder="Enter driver name (optional)">
                        </div>
                        <div class="mb-3">
                            <label for="deliveryVehicleInfo" class="form-label">Vehicle Information</label>
                            <input type="text" class="form-control" id="deliveryVehicleInfo" name="vehicle_info" placeholder="Enter vehicle info (e.g., Plate #, Model) (optional)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitApprovalBtn" onclick="submitApprovalWithDeliveryDetails()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                        <i class="fas fa-check"></i> Approve & Create PO
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewRequest(id) {
            $.get('<?= base_url('purchase/request/') ?>' + id, function(response) {
                if (response.status === 'success') {
                    const req = response.request;
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Purchase Request Details</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Request Number:</strong><br>' + (req.request_number || 'N/A') + '</div>';
                    html += '<div><strong>Branch:</strong><br>' + (req.branch ? req.branch.name : 'N/A') + '</div>';
                    html += '<div><strong>Status:</strong><br><span class="status-badge status-' + req.status + '">' + req.status.replace('_', ' ') + '</span></div>';
                    html += '<div><strong>Priority:</strong><br><span class="priority-badge priority-' + req.priority + '">' + req.priority.toUpperCase() + '</span></div>';
                    html += '<div><strong>Total Amount:</strong><br>₱' + parseFloat(req.total_amount || 0).toFixed(2) + '</div>';
                    html += '<div><strong>Date:</strong><br>' + new Date(req.created_at).toLocaleDateString() + '</div>';
                    html += '</div>';
                    
                    if (req.items && req.items.length > 0) {
                        html += '<h6 style="margin-top: 20px; margin-bottom: 10px; color: #2d5016; font-weight: 600;">Request Items:</h6>';
                        html += '<table class="table" style="margin-top: 10px;"><thead><tr style="background: #f8f9fa;"><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr></thead><tbody>';
                        req.items.forEach(function(item) {
                            const productName = item.product ? item.product.name : 'Product ID: ' + item.product_id;
                            html += '<tr>';
                            html += '<td>' + productName + '</td>';
                            html += '<td>' + item.quantity + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                            html += '<td>₱' + parseFloat(item.unit_price || 0).toFixed(2) + '</td>';
                            html += '<td>₱' + parseFloat(item.subtotal || 0).toFixed(2) + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                    }
                    
                    if (req.notes) {
                        html += '<div class="mt-3"><strong>Notes:</strong><br>' + req.notes + '</div>';
                    }
                    
                    // Add supplier selection section (only for pending requests)
                    if (req.status === 'pending') {
                        const hasSelectedSupplier = req.selected_supplier_id;
                        
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-building" style="margin-right: 8px;"></i>Select Supplier for Delivery:</h6>';
                        
                        if (hasSelectedSupplier) {
                            html += '<div class="alert alert-success" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px; padding: 12px;">';
                            html += '<i class="fas fa-check-circle"></i> Supplier already selected. You can approve this request from the list.';
                            html += '</div>';
                        } else {
                            html += '<div id="supplierSelection_' + id + '" style="margin-bottom: 20px;">';
                            html += '<div class="text-center p-3" style="background: #f8f9fa; border-radius: 8px;">';
                            html += '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';
                            html += '<p class="mt-2 mb-0" style="color: #6c757d; font-size: 0.9rem;">Loading suppliers...</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div id="acceptButtonContainer_' + id + '" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">';
                            html += '<button class="btn btn-primary btn-lg w-100 acceptSupplierBtn" data-id="' + id + '" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none; padding: 12px 30px; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 15px rgba(45, 80, 22, 0.3); color: white;">';
                            html += '<i class="fas fa-check"></i> Accept Supplier';
                            html += '</button>';
                            html += '</div>';
                        }
                    }
                    
                    html += '</div>';
                    
                    $('#requestModalBody').html(html);
                    $('#requestModal').modal('show');
                    
                    // Load suppliers if pending request and no supplier selected yet
                    if (req.status === 'pending' && !req.selected_supplier_id) {
                        loadSuppliersForRequest(id);
                    }
                    
                    // Accept supplier handler
                    $(document).off('click', '.acceptSupplierBtn').on('click', '.acceptSupplierBtn', function() {
                        const requestId = $(this).data('id');
                        const selectedSupplier = $('input[name="supplier_' + requestId + '"]:checked').val();
                        
                        if (!selectedSupplier) {
                            alert('Please select a supplier before accepting.');
                            return;
                        }
                        
                        acceptSupplier(requestId, selectedSupplier);
                    });
                } else {
                    alert('Failed to load request details');
                }
            });
        }
        
        function loadSuppliersForRequest(requestId) {
            $.ajax({
                url: '<?= base_url('supplier/') ?>?status=active',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.suppliers) {
                        let html = '<div style="max-height: 300px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; background: #f8f9fa;">';
                        
                        if (response.suppliers.length === 0) {
                            html += '<p class="text-center text-muted mb-0">No active suppliers available</p>';
                        } else {
                            html += '<div class="supplier-list" style="display: grid; gap: 10px;">';
                            response.suppliers.forEach(function(supplier) {
                                const supplierId = supplier.id;
                                const supplierName = supplier.name || 'Unnamed Supplier';
                                const contactInfo = supplier.phone || supplier.email || 'No contact info';
                                
                                html += '<label class="supplier-option" style="display: flex; align-items: center; padding: 12px; background: white; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; margin: 0;">';
                                html += '<input type="radio" name="supplier_' + requestId + '" value="' + supplierId + '" style="margin-right: 12px; width: 20px; height: 20px; cursor: pointer;" onchange="handleSupplierSelection(' + requestId + ', ' + supplierId + ')">';
                                html += '<div style="flex: 1;">';
                                html += '<div style="font-weight: 600; color: #2d5016; margin-bottom: 4px;">' + supplierName + '</div>';
                                html += '<div style="font-size: 0.85rem; color: #6c757d;">' + contactInfo + '</div>';
                                html += '</div>';
                                html += '<i class="fas fa-check-circle" style="color: #28a745; font-size: 1.2rem; opacity: 0; transition: opacity 0.3s ease;"></i>';
                                html += '</label>';
                            });
                            html += '</div>';
                        }
                        
                        html += '</div>';
                        $('#supplierSelection_' + requestId).html(html);
                        
                        // Add hover effects
                        $('.supplier-option').hover(
                            function() {
                                $(this).css('border-color', '#2d5016');
                                $(this).find('i').css('opacity', '0.3');
                            },
                            function() {
                                if (!$(this).find('input').is(':checked')) {
                                    $(this).css('border-color', '#e5e7eb');
                                    $(this).find('i').css('opacity', '0');
                                }
                            }
                        );
                        
                        // Checked state
                        $('.supplier-option input[type="radio"]').on('change', function() {
                            $('.supplier-option').css('border-color', '#e5e7eb');
                            $('.supplier-option i').css('opacity', '0');
                            if ($(this).is(':checked')) {
                                $(this).closest('.supplier-option').css('border-color', '#28a745');
                                $(this).closest('.supplier-option').find('i').css('opacity', '1');
                            }
                        });
                    }
                },
                error: function() {
                    $('#supplierSelection_' + requestId).html('<div class="alert alert-danger">Error loading suppliers</div>');
                }
            });
        }
        
        function handleSupplierSelection(requestId, supplierId) {
            $('#acceptButtonContainer_' + requestId).fadeIn(300);
        }
        
        function acceptSupplier(requestId, supplierId) {
            const acceptBtn = $('.acceptSupplierBtn[data-id="' + requestId + '"]');
            const originalText = acceptBtn.html();
            acceptBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Accepting...');
            
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId + '/accept-supplier',
                method: 'POST',
                data: { supplier_id: supplierId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#requestModal').modal('hide');
                        location.reload();
                    } else {
                        acceptBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Failed to accept supplier'));
                    }
                },
                error: function(xhr) {
                    acceptBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error accepting supplier';
                    alert('Error: ' + errorMsg);
                }
            });
        }

        function approveRequest(id) {
            // Show delivery details modal first
            $('#approveRequestId').val(id);
            $('#deliveryDetailsModal').modal('show');
        }
        
        function submitApprovalWithDeliveryDetails() {
            const requestId = $('#approveRequestId').val();
            const driverName = $('#deliveryDriverName').val();
            const vehicleInfo = $('#deliveryVehicleInfo').val();
            const scheduledDate = $('#deliveryScheduledDate').val();
            
            if (!scheduledDate) {
                alert('Please enter a scheduled delivery date.');
                return;
            }
            
            // Show loading state
            const submitBtn = $('#submitApprovalBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Approving...');
            
            $.ajax({
                url: '<?= base_url('purchase/request/') ?>' + requestId + '/approve',
                method: 'POST',
                data: {
                    driver_name: driverName,
                    vehicle_info: vehicleInfo,
                    scheduled_delivery_date: scheduledDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deliveryDetailsModal').modal('hide');
                        alert('Request approved! Purchase Order and Delivery created successfully.');
                        location.reload();
                    } else {
                        submitBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error approving request';
                    alert('Error: ' + errorMsg);
                }
            });
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
</body>
</html>
