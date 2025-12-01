<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders — CHAKANOKS SCMS</title>
    
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
        
        /* Pagination Styles */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            margin-top: 1rem;
            border-top: 1px solid #e0e0e0;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .pagination-info { color: #6b7280; font-size: 0.9rem; }
        .pagination-info strong { color: #2d5016; font-weight: 600; }
        .pagination-controls { display: flex; align-items: center; gap: 0.5rem; }
        .pagination-btn {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 12px;
            border: 1px solid #d1d5db; background: white; color: #374151;
            font-size: 0.875rem; font-weight: 500; border-radius: 8px;
            cursor: pointer; transition: all 0.2s ease;
        }
        .pagination-btn:hover:not(.disabled):not(.active) { background: #f3f4f6; border-color: #4a7c2a; color: #2d5016; }
        .pagination-btn.active { background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border-color: #2d5016; color: white; font-weight: 600; }
        .pagination-btn.disabled { opacity: 0.5; cursor: not-allowed; background: #f3f4f6; }
        .pagination-ellipsis { padding: 0 8px; color: #9ca3af; }
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
                // Determine dashboard URL based on user role
                $role = strtolower($me['role'] ?? '');
                $dashboardUrl = 'manager/dashboard'; // default
                
                if (in_array($role, ['central_admin', 'centraladmin', 'superadmin'])) {
                    $dashboardUrl = 'centraladmin/dashboard';
                } elseif (in_array($role, ['branch_manager', 'manager', 'branchmanager'])) {
                    $dashboardUrl = 'manager/dashboard';
                } elseif (in_array($role, ['inventory_staff', 'inventorystaff', 'staff'])) {
                    $dashboardUrl = 'staff/dashboard';
                }
                
                $currentUrl = current_url();
                $isDashboard = strpos($currentUrl, $dashboardUrl) !== false && strpos($currentUrl, '?tab=') === false;
                ?>
                <a href="<?= base_url($dashboardUrl) ?>" class="nav-item <?= $isDashboard ? 'active' : '' ?>">
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
                        <h2 class="page-title">Purchase Orders</h2>
                        <p class="page-subtitle">View and manage purchase orders</p>
                    </div>
                </div>
                <div class="header-right">
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
                                    <th>Order #</th>
                                    <th>Supplier</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Order Date</th>
                                    <th>Expected Delivery</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No purchase orders found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): ?>
                                        <?php
                                        $statusClass = 'badge-secondary';
                                        if ($order['status'] === 'approved') {
                                            $statusClass = 'badge-success';
                                        } elseif ($order['status'] === 'pending') {
                                            $statusClass = 'badge-warning';
                                        } elseif ($order['status'] === 'delivered') {
                                            $statusClass = 'badge-info';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= esc($order['order_number']) ?></td>
                                            <td><?= esc($order['supplier']['name'] ?? 'N/A') ?></td>
                                            <td><?= esc($order['branch']['name'] ?? 'N/A') ?></td>
                                            <td><span class="badge <?= $statusClass ?>"><?= esc($order['status'] ?? 'pending') ?></span></td>
                                            <td>₱<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                            <td><?= esc($order['order_date'] ?? $order['created_at'] ?? 'N/A') ?></td>
                                            <td><?= esc($order['expected_delivery_date'] ?? 'N/A') ?></td>
                                            <td>
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                    <button class="btn btn-sm btn-info" onclick="viewOrder(<?= $order['id'] ?>)" title="View details">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <?php if (($order['status'] ?? 'pending') === 'pending' || ($order['status'] ?? 'pending') === 'approved'): ?>
                                                        <button class="btn btn-sm btn-warning" onclick="editOrder(<?= $order['id'] ?>, <?= $order['supplier']['id'] ?? 'null' ?>, '<?= esc($order['expected_delivery_date'] ?? '', 'js') ?>')" title="Edit order">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if (($order['status'] ?? 'pending') === 'pending'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="approveOrder(<?= $order['id'] ?>)" title="Approve order">
                                                            <i class="fas fa-check"></i> Approve
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
                    <div id="paginationContainer" class="pagination-wrapper"></div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="orderModalLabel">
                        <i class="fas fa-file-invoice"></i> Purchase Order Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderModalBody">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="editOrderModalLabel">
                        <i class="fas fa-edit"></i> Edit Purchase Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editOrderForm">
                        <input type="hidden" id="editOrderId" name="order_id">
                        <div class="mb-3">
                            <label for="editSupplierId" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" id="editSupplierId" name="supplier_id" required>
                                <option value="">Loading suppliers...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editExpectedDeliveryDate" class="form-label">Expected Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editExpectedDeliveryDate" name="expected_delivery_date" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveOrderEdit()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let suppliersList = [];
        
        $(document).ready(function() {
            loadSuppliers();
        });
        
        function loadSuppliers() {
            $.ajax({
                url: '<?= base_url('supplier/') ?>?status=active',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.suppliers) {
                        suppliersList = response.suppliers;
                        const supplierSelect = $('#editSupplierId');
                        supplierSelect.html('<option value="">Select Supplier</option>');
                        response.suppliers.forEach(function(supplier) {
                            supplierSelect.append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                        });
                    }
                },
                error: function() {
                    console.error('Error loading suppliers');
                    $('#editSupplierId').html('<option value="">Error loading suppliers</option>');
                }
            });
        }
        
        window.viewOrder = function(orderId) {
            $.get('<?= base_url('purchase/order/') ?>' + orderId, function(response) {
                if (response.status === 'success' && response.order) {
                    const order = response.order;
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Purchase Order Details</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Order Number:</strong><br>' + (order.order_number || 'N/A') + '</div>';
                    html += '<div><strong>Status:</strong><br><span class="badge ' + (order.status === 'approved' ? 'badge-success' : order.status === 'pending' ? 'badge-warning' : 'badge-info') + '">' + (order.status || 'pending') + '</span></div>';
                    html += '<div><strong>Supplier:</strong><br>' + (order.supplier ? order.supplier.name : 'N/A') + '</div>';
                    html += '<div><strong>Branch:</strong><br>' + (order.branch ? order.branch.name : 'N/A') + '</div>';
                    html += '<div><strong>Total Amount:</strong><br>₱' + parseFloat(order.total_amount || 0).toFixed(2) + '</div>';
                    html += '<div><strong>Order Date:</strong><br>' + (order.order_date || order.created_at || 'N/A') + '</div>';
                    html += '<div><strong>Expected Delivery:</strong><br>' + (order.expected_delivery_date || 'N/A') + '</div>';
                    html += '<div><strong>Actual Delivery:</strong><br>' + (order.actual_delivery_date || 'N/A') + '</div>';
                    if (order.approved_by) {
                        html += '<div><strong>Approved By:</strong><br>User ID: ' + order.approved_by + '</div>';
                    }
                    if (order.approved_at) {
                        html += '<div><strong>Approved At:</strong><br>' + order.approved_at + '</div>';
                    }
                    html += '</div>';
                    
                    if (order.items && order.items.length > 0) {
                        html += '<h6 style="margin-top: 20px; margin-bottom: 10px; color: #2d5016; font-weight: 600;">Order Items:</h6>';
                        html += '<table class="table" style="margin-top: 10px;"><thead><tr style="background: #f8f9fa;"><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr></thead><tbody>';
                        order.items.forEach(function(item) {
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
                    
                    if (order.delivery) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-truck" style="margin-right: 8px;"></i>Delivery Information:</h6>';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
                        html += '<div><strong>Delivery Number:</strong><br>' + (order.delivery.delivery_number || 'N/A') + '</div>';
                        html += '<div><strong>Status:</strong><br>' + (order.delivery.status || 'N/A') + '</div>';
                        html += '<div><strong>Scheduled Date:</strong><br>' + (order.delivery.scheduled_date || 'N/A') + '</div>';
                        html += '<div><strong>Actual Date:</strong><br>' + (order.delivery.actual_delivery_date || 'N/A') + '</div>';
                        if (order.delivery.driver_name) {
                            html += '<div><strong>Driver:</strong><br>' + order.delivery.driver_name + '</div>';
                        }
                        if (order.delivery.vehicle_info) {
                            html += '<div><strong>Vehicle:</strong><br>' + order.delivery.vehicle_info + '</div>';
                        }
                        html += '</div>';
                    }
                    
                    html += '</div>';
                    
                    $('#orderModalBody').html(html);
                    $('#orderModal').modal('show');
                } else {
                    alert('Failed to load order details: ' + (response.message || 'Unknown error'));
                }
            }).fail(function() {
                alert('Error loading order details');
            });
        };
        
        window.editOrder = function(orderId, currentSupplierId, currentDeliveryDate) {
            $('#editOrderId').val(orderId);
            $('#editSupplierId').val(currentSupplierId || '');
            $('#editExpectedDeliveryDate').val(currentDeliveryDate || '');
            
            const modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
            modal.show();
        };
        
        window.saveOrderEdit = function() {
            const orderId = $('#editOrderId').val();
            const supplierId = $('#editSupplierId').val();
            const expectedDeliveryDate = $('#editExpectedDeliveryDate').val();
            
            if (!supplierId || !expectedDeliveryDate) {
                alert('Please fill in all required fields');
                return;
            }
            
            const saveBtn = $('#editOrderModal .btn-primary');
            const originalText = saveBtn.html();
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            $.ajax({
                url: '<?= base_url('purchase/order/') ?>' + orderId + '/update',
                method: 'POST',
                data: {
                    supplier_id: supplierId,
                    expected_delivery_date: expectedDeliveryDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
                        location.reload();
                    } else {
                        saveBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Failed to update purchase order'));
                    }
                },
                error: function(xhr) {
                    saveBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating purchase order';
                    alert('Error: ' + errorMsg);
                }
            });
        };
        
        window.approveOrder = function(orderId) {
            if (!confirm('Are you sure you want to approve this purchase order? This will create an accounts payable entry.')) {
                return;
            }
            
            const approveBtn = $('button[onclick="approveOrder(' + orderId + ')"]');
            const originalText = approveBtn.html();
            approveBtn.prop('disabled', true);
            approveBtn.html('<i class="fas fa-spinner fa-spin"></i> Approving...');
            
            $.ajax({
                url: '<?= base_url('purchase/order/') ?>' + orderId + '/approve',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Purchase order approved successfully! ' + (response.message || ''));
                        location.reload();
                    } else {
                        approveBtn.prop('disabled', false);
                        approveBtn.html(originalText);
                        alert('Error: ' + (response.message || 'Failed to approve purchase order'));
                    }
                },
                error: function(xhr) {
                    approveBtn.prop('disabled', false);
                    approveBtn.html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error approving purchase order';
                    alert('Error: ' + errorMsg);
                }
            });
        };
        
        // Pagination
        (function() {
            const ITEMS_PER_PAGE = 10;
            let currentPage = 1;
            const $table = $('.table tbody');
            const $rows = $table.find('tr').not(':has(td[colspan])');
            const totalItems = $rows.length;
            
            if (totalItems > ITEMS_PER_PAGE) {
                renderPagination();
                showPage(1);
            }
            
            function showPage(page) {
                currentPage = page;
                $rows.hide().slice((page - 1) * ITEMS_PER_PAGE, page * ITEMS_PER_PAGE).show();
                renderPagination();
            }
            
            function renderPagination() {
                const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
                if (totalPages <= 1) { $('#paginationContainer').empty(); return; }
                
                let html = '<div class="pagination-info">Showing <strong>' + ((currentPage - 1) * ITEMS_PER_PAGE + 1) + '-' + Math.min(currentPage * ITEMS_PER_PAGE, totalItems) + '</strong> of <strong>' + totalItems + '</strong></div>';
                html += '<div class="pagination-controls">';
                html += '<button class="pagination-btn ' + (currentPage === 1 ? 'disabled' : '') + '" data-page="' + (currentPage - 1) + '" ' + (currentPage === 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i> Prev</button>';
                for (let i = 1; i <= totalPages; i++) {
                    if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                        html += '<button class="pagination-btn ' + (i === currentPage ? 'active' : '') + '" data-page="' + i + '">' + i + '</button>';
                    } else if (i === currentPage - 3 || i === currentPage + 3) {
                        html += '<span class="pagination-ellipsis">...</span>';
                    }
                }
                html += '<button class="pagination-btn ' + (currentPage === totalPages ? 'disabled' : '') + '" data-page="' + (currentPage + 1) + '" ' + (currentPage === totalPages ? 'disabled' : '') + '>Next <i class="fas fa-chevron-right"></i></button>';
                html += '</div>';
                $('#paginationContainer').html(html);
                $('.pagination-btn:not(.disabled)').off('click').on('click', function() {
                    showPage(parseInt($(this).data('page')));
                    $('html, body').animate({ scrollTop: $('.table').offset().top - 100 }, 300);
                });
            }
        })();
    </script>
</body>
</html>
