<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Payable — CHAKANOKS SCMS</title>
    
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
        .pagination-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; margin-top: 1rem; border-top: 1px solid #e0e0e0; flex-wrap: wrap; gap: 1rem; }
        .pagination-info { color: #6b7280; font-size: 0.9rem; }
        .pagination-info strong { color: #2d5016; font-weight: 600; }
        .pagination-controls { display: flex; align-items: center; gap: 0.5rem; }
        .pagination-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 12px; border: 1px solid #d1d5db; background: white; color: #374151; font-size: 0.875rem; font-weight: 500; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; }
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
                        <h2 class="page-title">Accounts Payable</h2>
                        <p class="page-subtitle">View and manage accounts payable</p>
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
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 class="card-title" style="margin: 0; color: #2d5016; font-weight: 700;">Accounts Payable</h3>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <select id="apStatusFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd;">
                                <?php 
                                $request = \Config\Services::request();
                                $currentStatus = $request->getGet('payment_status') ?? 'all';
                                ?>
                                <option value="all" <?= $currentStatus === 'all' ? 'selected' : '' ?>>All Status</option>
                                <option value="unpaid" <?= $currentStatus === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                <option value="partial" <?= $currentStatus === 'partial' ? 'selected' : '' ?>>Partial</option>
                                <option value="paid" <?= $currentStatus === 'paid' ? 'selected' : '' ?>>Paid</option>
                                <option value="overdue" <?= $currentStatus === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                            </select>
                            <select id="apInvoiceFilter" class="form-select" style="width: auto; min-width: 150px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ddd;">
                                <?php 
                                $currentInvoiceFilter = $request->getGet('invoice_filter') ?? 'all';
                                ?>
                                <option value="all" <?= $currentInvoiceFilter === 'all' ? 'selected' : '' ?>>All Invoices</option>
                                <option value="with_invoice" <?= $currentInvoiceFilter === 'with_invoice' ? 'selected' : '' ?>>With Invoice #</option>
                                <option value="without_invoice" <?= $currentInvoiceFilter === 'without_invoice' ? 'selected' : '' ?>>Without Invoice #</option>
                            </select>
                            <button class="btn btn-sm btn-info" onclick="location.reload()" style="background: #17a2b8; border: none; padding: 8px 16px; border-radius: 6px; color: white;">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Supplier</th>
                                    <th>Purchase Order</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Payment Method</th>
                                    <th>Payment Reference</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($accounts_payable)): ?>
                                    <tr>
                                        <td colspan="12" class="text-center">No accounts payable found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($accounts_payable as $ap): ?>
                                        <?php
                                        $statusClass = 'badge-secondary';
                                        if ($ap['payment_status'] === 'paid') {
                                            $statusClass = 'badge-success';
                                        } elseif ($ap['payment_status'] === 'partial') {
                                            $statusClass = 'badge-warning';
                                        } elseif ($ap['payment_status'] === 'unpaid') {
                                            $statusClass = 'badge-danger';
                                        } elseif ($ap['payment_status'] === 'overdue') {
                                            $statusClass = 'badge-danger';
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($ap['invoice_number'])): ?>
                                                    <span class="editable-invoice" data-id="<?= $ap['id'] ?>" data-invoice="<?= esc($ap['invoice_number'], 'attr') ?>" style="cursor: pointer; padding: 4px 8px; border-radius: 4px; transition: all 0.2s;" onmouseover="this.style.background='#f0f0f0';" onmouseout="this.style.background='transparent';">
                                                        <?= esc($ap['invoice_number']) ?> <i class="fas fa-edit" style="font-size: 0.7rem; color: #6c757d;"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="editable-invoice" data-id="<?= $ap['id'] ?>" data-invoice="" style="cursor: pointer; padding: 4px 8px; border-radius: 4px; color: #999; font-style: italic; transition: all 0.2s;" onmouseover="this.style.background='#f0f0f0';" onmouseout="this.style.background='transparent';">
                                                        Click to add <i class="fas fa-plus" style="font-size: 0.7rem; color: #6c757d;"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($ap['supplier']['name'] ?? 'N/A') ?></td>
                                            <td><?= esc($ap['purchase_order']['order_number'] ?? 'N/A') ?></td>
                                            <td>₱<?= number_format($ap['total_amount'] ?? 0, 2) ?></td>
                                            <td>₱<?= number_format($ap['paid_amount'] ?? 0, 2) ?></td>
                                            <td>₱<?= number_format($ap['balance'] ?? 0, 2) ?></td>
                                            <td><span class="badge <?= $statusClass ?>"><?= esc(ucfirst($ap['payment_status'] ?? 'unpaid')) ?></span></td>
                                            <td><?= !empty($ap['payment_date']) ? date('M d, Y', strtotime($ap['payment_date'])) : '<span style="color: #999; font-style: italic;">-</span>' ?></td>
                                            <td><?= !empty($ap['payment_method']) ? esc($ap['payment_method']) : '<span style="color: #999; font-style: italic;">-</span>' ?></td>
                                            <td><?= !empty($ap['payment_reference']) ? esc($ap['payment_reference']) : '<span style="color: #999; font-style: italic;">-</span>' ?></td>
                                            <td><?= !empty($ap['due_date']) ? date('M d, Y', strtotime($ap['due_date'])) : 'N/A' ?></td>
                                            <td>
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                    <button class="btn btn-sm btn-info" onclick="viewAccountsPayable(<?= $ap['id'] ?>)" title="View details" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px;">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <?php if (($ap['payment_status'] ?? 'unpaid') !== 'paid'): ?>
                                                        <button class="btn btn-sm btn-success recordPaymentBtn" data-id="<?= $ap['id'] ?>" data-balance="<?= $ap['balance'] ?? 0 ?>" title="Record payment" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px;">
                                                            <i class="fas fa-money-bill-wave"></i> Pay
                                                        </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-warning updateInvoiceBtn" data-id="<?= $ap['id'] ?>" data-invoice="<?= esc($ap['invoice_number'] ?? '', 'attr') ?>" title="Update invoice" style="background: #ffc107; color: #000; border: none; padding: 6px 12px; border-radius: 6px;">
                                                        <i class="fas fa-file-invoice"></i> Invoice
                                                    </button>
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

    <!-- Accounts Payable Details Modal -->
    <div class="modal fade" id="accountsPayableModal" tabindex="-1" aria-labelledby="accountsPayableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="accountsPayableModalLabel">
                        <i class="fas fa-money-check-alt"></i> Accounts Payable Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="accountsPayableModalBody">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Filter change handlers
            $('#apStatusFilter, #apInvoiceFilter').on('change', function() {
                filterAccountsPayable();
            });
            
            // Editable invoice number handler
            $(document).on('click', '.editable-invoice', function() {
                const apId = $(this).data('id');
                const currentInvoice = $(this).data('invoice') || '';
                const $this = $(this);
                
                // Create input field
                const input = $('<input>', {
                    type: 'text',
                    class: 'form-control',
                    value: currentInvoice,
                    style: 'width: 150px; display: inline-block; padding: 4px 8px; border: 2px solid #2d5016; border-radius: 4px;',
                    onblur: function() {
                        const newInvoice = $(this).val().trim();
                        saveInvoiceNumber(apId, newInvoice, $this);
                    },
                    onkeypress: function(e) {
                        if (e.which === 13) { // Enter key
                            $(this).blur();
                        }
                    }
                });
                
                $this.html(input);
                input.focus();
                input.select();
            });
            
            // Record payment handler
            $(document).on('click', '.recordPaymentBtn', function() {
                const apId = $(this).data('id');
                const balance = $(this).data('balance');
                recordPayment(apId, balance);
            });
            
            // Update invoice handler
            $(document).on('click', '.updateInvoiceBtn', function() {
                const apId = $(this).data('id');
                const currentInvoice = $(this).data('invoice') || '';
                updateInvoice(apId, currentInvoice);
            });
        });
        
        function filterAccountsPayable() {
            const statusFilter = $('#apStatusFilter').val() || 'all';
            const invoiceFilter = $('#apInvoiceFilter').val() || 'all';
            
            const params = [];
            if (statusFilter !== 'all') {
                params.push('payment_status=' + encodeURIComponent(statusFilter));
            }
            if (invoiceFilter !== 'all') {
                params.push('invoice_filter=' + encodeURIComponent(invoiceFilter));
            }
            
            let url = '<?= base_url('accounts-payable/list') ?>';
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            
            window.location.href = url;
        }
        
        function saveInvoiceNumber(apId, invoiceNumber, $element) {
            // If invoice number is empty, let the server generate it
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/update-invoice',
                method: 'POST',
                data: { 
                    invoice_number: invoiceNumber || '',
                    invoice_date: '' // Let server use current date if empty
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Use generated invoice number from response if available, otherwise use the one sent
                        const finalInvoiceNumber = response.invoice_number || invoiceNumber;
                        if (finalInvoiceNumber) {
                            $element.html(finalInvoiceNumber + ' <i class="fas fa-edit" style="font-size: 0.7rem; color: #6c757d;"></i>');
                            $element.data('invoice', finalInvoiceNumber);
                        } else {
                            $element.html('Click to add <i class="fas fa-plus" style="font-size: 0.7rem; color: #6c757d;"></i>');
                            $element.data('invoice', '');
                        }
                        // Update the button data as well
                        $('.updateInvoiceBtn[data-id="' + apId + '"]').data('invoice', finalInvoiceNumber);
                        // Reload to show updated data
                        setTimeout(function() { location.reload(); }, 500);
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update invoice number'));
                        location.reload();
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating invoice number';
                    alert('Error: ' + errorMsg);
                    location.reload();
                }
            });
        }
        
        function recordPayment(apId, balance) {
            const paymentAmount = prompt('Enter payment amount (Balance: ₱' + parseFloat(balance).toFixed(2) + '):');
            
            if (!paymentAmount || parseFloat(paymentAmount) <= 0) {
                return;
            }
            
            const paymentMethod = prompt('Enter payment method (e.g., Bank Transfer, Cash, Check):') || 'Bank Transfer';
            const paymentReference = prompt('Enter payment reference (e.g., Check #, Transaction ID):') || '';
            
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/record-payment',
                method: 'POST',
                data: {
                    payment_amount: paymentAmount,
                    payment_method: paymentMethod,
                    payment_reference: paymentReference
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Payment recorded successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to record payment'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error recording payment';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function updateInvoice(apId, currentInvoice) {
            const invoiceNumber = prompt('Enter invoice number:', currentInvoice || '');
            if (invoiceNumber === null) return; // User cancelled
            
            const invoiceDate = prompt('Enter invoice date (YYYY-MM-DD):', '');
            if (invoiceDate === null) return; // User cancelled
            
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/update-invoice',
                method: 'POST',
                data: {
                    invoice_number: invoiceNumber || '',
                    invoice_date: invoiceDate || null
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Invoice information updated successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update invoice'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error updating invoice';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        window.viewAccountsPayable = function(apId) {
            $.get('<?= base_url('accounts-payable/') ?>' + apId, function(response) {
                if (response.status === 'success' && response.accounts_payable) {
                    const ap = response.accounts_payable;
                    const order = response.purchase_order || null;
                    
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Invoice Information</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Invoice Number:</strong><br>' + (ap.invoice_number || 'N/A') + '</div>';
                    html += '<div><strong>Payment Status:</strong><br><span class="badge ' + (ap.payment_status === 'paid' ? 'badge-success' : ap.payment_status === 'overdue' ? 'badge-danger' : ap.payment_status === 'partial' ? 'badge-warning' : 'badge-secondary') + '">' + (ap.payment_status || 'unpaid') + '</span></div>';
                    html += '<div><strong>Supplier:</strong><br>' + (ap.supplier_name || 'N/A') + '</div>';
                    html += '<div><strong>Purchase Order:</strong><br>' + (ap.order_number || 'N/A') + '</div>';
                    if (ap.invoice_date) {
                        html += '<div><strong>Invoice Date:</strong><br>' + ap.invoice_date + '</div>';
                    }
                    html += '<div><strong>Due Date:</strong><br>' + (ap.due_date || 'N/A') + '</div>';
                    html += '</div>';
                    
                    html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                    html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-dollar-sign" style="margin-right: 8px;"></i>Payment Details:</h6>';
                    html += '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">';
                    html += '<div style="font-size: 1.5rem; font-weight: 700; color: #2d5016;">₱' + parseFloat(ap.amount || 0).toFixed(2) + '</div>';
                    html += '<div style="color: #6c757d; font-size: 0.9rem;">Total Amount</div>';
                    html += '</div>';
                    html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">';
                    html += '<div style="font-size: 1.5rem; font-weight: 700; color: #28a745;">₱' + parseFloat(ap.paid_amount || 0).toFixed(2) + '</div>';
                    html += '<div style="color: #6c757d; font-size: 0.9rem;">Paid Amount</div>';
                    html += '</div>';
                    html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">';
                    const balance = parseFloat(ap.amount || 0) - parseFloat(ap.paid_amount || 0);
                    html += '<div style="font-size: 1.5rem; font-weight: 700; color: ' + (balance > 0 ? '#dc3545' : '#28a745') + ';">₱' + balance.toFixed(2) + '</div>';
                    html += '<div style="color: #6c757d; font-size: 0.9rem;">Balance</div>';
                    html += '</div>';
                    html += '</div>';
                    
                    if (ap.payment_method || ap.payment_reference || ap.payment_date) {
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        if (ap.payment_method) {
                            html += '<div><strong>Payment Method:</strong><br>' + ap.payment_method + '</div>';
                        }
                        if (ap.payment_reference) {
                            html += '<div><strong>Payment Reference:</strong><br>' + ap.payment_reference + '</div>';
                        }
                        if (ap.payment_date) {
                            html += '<div><strong>Payment Date:</strong><br>' + ap.payment_date + '</div>';
                        }
                        html += '</div>';
                    }
                    
                    if (order && order.items && order.items.length > 0) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-shopping-cart" style="margin-right: 8px;"></i>Purchase Order Items:</h6>';
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
                    
                    if (ap.notes) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<div><strong>Notes:</strong><br>' + ap.notes + '</div>';
                    }
                    
                    if (ap.created_at) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<div style="color: #6c757d; font-size: 0.9rem;">Created: ' + new Date(ap.created_at).toLocaleDateString() + '</div>';
                    }
                    
                    html += '</div>';
                    
                    $('#accountsPayableModalBody').html(html);
                    $('#accountsPayableModal').modal('show');
                } else {
                    alert('Failed to load accounts payable details: ' + (response.message || 'Unknown error'));
                }
            }).fail(function() {
                alert('Error loading accounts payable details');
            });
        };
        
        // Pagination
        (function initPagination() {
            const ITEMS_PER_PAGE = 10;
            let currentPage = 1;
            const $rows = $('.table tbody tr').not(':has(td[colspan])');
            const totalItems = $rows.length;
            
            if (totalItems > ITEMS_PER_PAGE) { renderPagination(); showPage(1); }
            
            function showPage(page) {
                currentPage = page;
                $rows.hide().slice((page - 1) * ITEMS_PER_PAGE, page * ITEMS_PER_PAGE).show();
                renderPagination();
            }
            
            function renderPagination() {
                const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
                if (totalPages <= 1) { $('#paginationContainer').empty(); return; }
                let html = '<div class="pagination-info">Showing <strong>' + ((currentPage - 1) * ITEMS_PER_PAGE + 1) + '-' + Math.min(currentPage * ITEMS_PER_PAGE, totalItems) + '</strong> of <strong>' + totalItems + '</strong></div>';
                html += '<div class="pagination-controls"><button class="pagination-btn ' + (currentPage === 1 ? 'disabled' : '') + '" data-page="' + (currentPage - 1) + '" ' + (currentPage === 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i> Prev</button>';
                for (let i = 1; i <= totalPages; i++) { if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) { html += '<button class="pagination-btn ' + (i === currentPage ? 'active' : '') + '" data-page="' + i + '">' + i + '</button>'; } else if (i === currentPage - 3 || i === currentPage + 3) { html += '<span class="pagination-ellipsis">...</span>'; } }
                html += '<button class="pagination-btn ' + (currentPage === totalPages ? 'disabled' : '') + '" data-page="' + (currentPage + 1) + '" ' + (currentPage === totalPages ? 'disabled' : '') + '>Next <i class="fas fa-chevron-right"></i></button></div>';
                $('#paginationContainer').html(html);
                $('.pagination-btn:not(.disabled)').off('click').on('click', function() { showPage(parseInt($(this).data('page'))); $('html, body').animate({ scrollTop: $('.table').offset().top - 100 }, 300); });
            }
        })();
    </script>
</body>
</html>
