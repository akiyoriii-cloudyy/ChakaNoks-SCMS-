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
                            <div style="display: flex; gap: 5px; align-items: center; padding: 0 5px;">
                                <label style="font-size: 0.9rem; color: #2d5016; font-weight: 600; white-space: nowrap;">Month:</label>
                                <input type="month" id="receiptMonthFilter" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.875rem; width: 140px;">
                            </div>
                            <button id="btnPrintMonthlyReport" class="btn btn-sm btn-success" style="background: #28a745; border: none; padding: 8px 16px; border-radius: 6px; color: white; font-weight: 600;">
                                <i class="fas fa-print"></i> Print Monthly Report
                            </button>
                            <button class="btn btn-sm btn-info" onclick="location.reload()" style="background: #17a2b8; border: none; padding: 8px 16px; border-radius: 6px; color: white;">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                            <?php if (in_array(strtolower($me['role'] ?? ''), ['central_admin', 'centraladmin', 'superadmin'])): ?>
                                <button class="btn btn-sm btn-warning" onclick="backfillAccountsPayable()" style="background: #ffc107; border: none; padding: 8px 16px; border-radius: 6px; color: #000; font-weight: 600;" title="Create AP entries for approved POs that don't have AP records">
                                    <i class="fas fa-database"></i> Backfill Missing AP
                                </button>
                            <?php endif; ?>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($accounts_payable)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No accounts payable found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($accounts_payable as $ap): ?>
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
                                            <td><strong>₱<?= number_format($ap['total_amount'] ?? 0, 2) ?></strong></td>
                                            <td>
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                    <button class="btn btn-sm btn-info" onclick="viewAccountsPayable(<?= $ap['id'] ?>)" title="View details" style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 6px;">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <?php 
                                                    $paymentStatus = strtolower($ap['payment_status'] ?? 'unpaid');
                                                    $paidAmount = (float)($ap['paid_amount'] ?? $ap['amount_paid'] ?? 0);
                                                    $hasPayment = $paidAmount > 0 || $paymentStatus === 'paid' || $paymentStatus === 'partial';
                                                    ?>
                                                    <?php if ($paymentStatus !== 'paid' && ($ap['balance'] ?? 0) > 0): ?>
                                                        <button class="btn btn-sm btn-success recordPaymentBtn" data-id="<?= $ap['id'] ?>" data-balance="<?= $ap['balance'] ?? 0 ?>" title="Record payment" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px;">
                                                            <i class="fas fa-money-bill-wave"></i> Pay
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if ($hasPayment): ?>
                                                        <button class="btn btn-sm btn-primary viewReceiptBtn" data-id="<?= $ap['id'] ?>" title="View receipt" style="background: #2d5016; color: white; border: none; padding: 6px 12px; border-radius: 6px;">
                                                            <i class="fas fa-receipt"></i> Receipt
                                                        </button>
                                                        <button class="btn btn-sm btn-success printReceiptBtn" data-id="<?= $ap['id'] ?>" title="Print receipt" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px;">
                                                            <i class="fas fa-print"></i> Print Receipt
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
            // Initialize month filter with current month
            const today = new Date();
            const monthStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            
            $('#receiptMonthFilter').val(monthStr);
            
            // Filter change handlers
            $('#apStatusFilter, #apInvoiceFilter').on('change', function() {
                filterAccountsPayable();
            });
            
            // Print Monthly Report button handler
            $('#btnPrintMonthlyReport').on('click', function() {
                printMonthlyReport();
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
            // Payment reference will be auto-generated on the backend
            
            $.ajax({
                url: '<?= base_url('accounts-payable/') ?>' + apId + '/record-payment',
                method: 'POST',
                data: {
                    payment_amount: paymentAmount,
                    payment_method: paymentMethod
                    // payment_reference will be auto-generated on backend
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
                    
                    // Helper function to format payment method
                    function formatPaymentMethod(method) {
                        if (!method || method.trim() === '') {
                            return 'Not specified';
                        }
                        
                        // Normalize the method string
                        const normalizedMethod = method.trim().toLowerCase();
                        
                        // Map database ENUM values and common variations to standardized format
                        const methodMap = {
                            // Database ENUM values
                            'cash': 'Cash',
                            'check': 'Check',
                            'bank_transfer': 'Bank Transfer',
                            'credit_card': 'Credit Card',
                            'online': 'Online Payment',
                            'other': 'Other',
                            // Common variations
                            'cheque': 'Check',
                            'bank transfer': 'Bank Transfer',
                            'banktransfer': 'Bank Transfer',
                            'transfer': 'Bank Transfer',
                            'bank': 'Bank Transfer',
                            'credit card': 'Credit Card',
                            'creditcard': 'Credit Card',
                            'card': 'Credit Card',
                            'online payment': 'Online Payment',
                            'onlinepayment': 'Online Payment',
                            'paypal': 'PayPal',
                            'gcash': 'GCash',
                            'maya': 'Maya'
                        };
                        
                        // Check if method matches any key in the map
                        if (methodMap[normalizedMethod]) {
                            return methodMap[normalizedMethod];
                        }
                        
                        // If not found in map, try to match partial strings
                        for (const key in methodMap) {
                            if (normalizedMethod.includes(key) || key.includes(normalizedMethod)) {
                                return methodMap[key];
                            }
                        }
                        
                        // If not found, capitalize first letter of each word
                        return method.split(/[\s_-]+/)
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                            .join(' ');
                    }
                    
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;"><i class="fas fa-file-invoice" style="margin-right: 8px;"></i>Invoice Information</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Invoice Number:</strong><br>' + (ap.invoice_number || 'N/A') + '</div>';
                    html += '<div><strong>Payment Status:</strong><br><span class="badge ' + (ap.payment_status === 'paid' ? 'badge-success' : ap.payment_status === 'overdue' ? 'badge-danger' : ap.payment_status === 'partial' ? 'badge-warning' : 'badge-secondary') + '" style="font-size: 0.9rem; padding: 6px 12px;">' + (ap.payment_status ? ap.payment_status.toUpperCase() : 'UNPAID') + '</span></div>';
                    html += '<div><strong>Supplier:</strong><br>' + (ap.supplier_name || 'N/A') + '</div>';
                    html += '<div><strong>Purchase Order:</strong><br>' + (ap.order_number || 'N/A') + '</div>';
                    if (ap.invoice_date) {
                        html += '<div><strong>Invoice Date:</strong><br>' + new Date(ap.invoice_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + '</div>';
                    }
                    html += '<div><strong>Due Date:</strong><br>' + (ap.due_date ? new Date(ap.due_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A') + '</div>';
                    html += '</div>';
                    
                    html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                    html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-dollar-sign" style="margin-right: 8px;"></i>Payment Details:</h6>';
                    html += '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb;">';
                    html += '<div style="font-size: 1.5rem; font-weight: 700; color: #2d5016;">₱' + parseFloat(ap.amount || 0).toFixed(2) + '</div>';
                    html += '<div style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Total Amount</div>';
                    html += '</div>';
                    html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb;">';
                    html += '<div style="font-size: 1.5rem; font-weight: 700; color: #28a745;">₱' + parseFloat(ap.paid_amount || ap.amount_paid || 0).toFixed(2) + '</div>';
                    html += '<div style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Paid Amount</div>';
                    html += '</div>';
                    html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #e5e7eb;">';
                    const balance = parseFloat(ap.amount || 0) - parseFloat(ap.paid_amount || ap.amount_paid || 0);
                    html += '<div style="font-size: 1.5rem; font-weight: 700; color: ' + (balance > 0 ? '#dc3545' : '#28a745') + ';">₱' + balance.toFixed(2) + '</div>';
                    html += '<div style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">Balance</div>';
                    html += '</div>';
                    html += '</div>';
                    
                    html += '<hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">';
                    html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-credit-card" style="margin-right: 8px;"></i>Payment Information:</h6>';
                    
                    // Show latest payment information if available
                    if (ap.payment_transactions && ap.payment_transactions.length > 0) {
                        // Show all payment transactions in a table
                        html += '<div style="margin-bottom: 20px;">';
                        html += '<table class="table table-sm" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">';
                        html += '<thead style="background: #f8f9fa;"><tr>';
                        html += '<th style="padding: 10px; border-bottom: 2px solid #e5e7eb;">Date</th>';
                        html += '<th style="padding: 10px; border-bottom: 2px solid #e5e7eb;">Amount</th>';
                        html += '<th style="padding: 10px; border-bottom: 2px solid #e5e7eb;">Method</th>';
                        html += '<th style="padding: 10px; border-bottom: 2px solid #e5e7eb;">Reference</th>';
                        html += '</tr></thead><tbody>';
                        
                        ap.payment_transactions.forEach(function(payment) {
                            html += '<tr>';
                            html += '<td style="padding: 10px; border-bottom: 1px solid #f0f0f0;">' + (payment.payment_date ? new Date(payment.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A') + '</td>';
                            html += '<td style="padding: 10px; border-bottom: 1px solid #f0f0f0; font-weight: 600; color: #28a745;">₱' + parseFloat(payment.payment_amount || 0).toFixed(2) + '</td>';
                            html += '<td style="padding: 10px; border-bottom: 1px solid #f0f0f0;">' + formatPaymentMethod(payment.payment_method) + '</td>';
                            html += '<td style="padding: 10px; border-bottom: 1px solid #f0f0f0; font-family: monospace; color: #2d5016; font-weight: 600;">' + (payment.payment_reference || 'N/A') + '</td>';
                            html += '</tr>';
                        });
                        
                        html += '</tbody></table>';
                        html += '</div>';
                        
                        // Show latest payment summary
                        const latestPayment = ap.payment_transactions[0];
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #28a745;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Latest Payment Method</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem; font-weight: 600;">' + formatPaymentMethod(latestPayment.payment_method) + '</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #17a2b8;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Latest Payment Reference</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem; font-weight: 600; font-family: monospace; color: #2d5016;">' + (latestPayment.payment_reference || 'Not specified') + '</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #ffc107;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Latest Payment Date</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem; font-weight: 600;">' + (latestPayment.payment_date ? new Date(latestPayment.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not paid') + '</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 4px solid #6c757d;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Total Payments</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem; font-weight: 600;">' + ap.payment_transactions.length + ' transaction(s)</div>';
                        html += '</div>';
                        html += '</div>';
                    } else {
                        // No payments yet
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Payment Method</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem;">' + (ap.payment_method || '<span style="color: #999; font-style: italic;">Not specified</span>') + '</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Payment Reference</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem;">' + (ap.payment_reference || '<span style="color: #999; font-style: italic;">Not specified</span>') + '</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Payment Date</strong><br>';
                        html += '<div style="margin-top: 5px; font-size: 1rem;">' + (ap.payment_date ? new Date(ap.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '<span style="color: #999; font-style: italic;">Not paid</span>') + '</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Days Until Due</strong><br>';
                        if (ap.due_date) {
                            const today = new Date();
                            const dueDate = new Date(ap.due_date);
                            const daysDiff = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
                            const daysText = daysDiff < 0 ? Math.abs(daysDiff) + ' days overdue' : daysDiff === 0 ? 'Due today' : daysDiff + ' days remaining';
                            html += '<div style="margin-top: 5px; font-size: 1rem; color: ' + (daysDiff < 0 ? '#dc3545' : daysDiff <= 7 ? '#ffc107' : '#28a745') + '; font-weight: 600;">' + daysText + '</div>';
                        } else {
                            html += '<div style="margin-top: 5px; font-size: 1rem; color: #999; font-style: italic;">N/A</div>';
                        }
                        html += '</div>';
                        html += '</div>';
                    }
                    
                    // Always show days until due
                    if (ap.due_date) {
                        html += '<div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-top: 15px;">';
                        html += '<strong style="color: #6c757d; font-size: 0.85rem; text-transform: uppercase;">Days Until Due</strong><br>';
                        const today = new Date();
                        const dueDate = new Date(ap.due_date);
                        const daysDiff = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
                        const daysText = daysDiff < 0 ? Math.abs(daysDiff) + ' days overdue' : daysDiff === 0 ? 'Due today' : daysDiff + ' days remaining';
                        html += '<div style="margin-top: 5px; font-size: 1rem; color: ' + (daysDiff < 0 ? '#dc3545' : daysDiff <= 7 ? '#ffc107' : '#28a745') + '; font-weight: 600;">' + daysText + '</div>';
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
        
        // Backfill accounts payable for missing approved POs
        function backfillAccountsPayable() {
            if (!confirm('This will create accounts payable entries for all approved purchase orders that don\'t have AP records yet. This may take a few moments. Continue?')) {
                return;
            }
            
            // Show loading state
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            $.ajax({
                url: '<?= base_url('accounts-payable/backfill') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    
                    if (response.status === 'success') {
                        alert('Success! Created ' + response.created + ' accounts payable entries.' + (response.errors && response.errors.length > 0 ? '\n\nErrors: ' + response.errors.join(', ') : ''));
                        location.reload(); // Reload the page to show new entries
                    } else {
                        alert('Error: ' + (response.message || 'Failed to backfill'));
                    }
                },
                error: function(xhr) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error backfilling accounts payable';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        // Print Receipt Functions
        function showReceipt(apId, paymentTransactionId = null) {
            let url = '<?= base_url('accounts-payable/') ?>' + apId + '/receipt';
            if (paymentTransactionId) {
                url += '/' + paymentTransactionId;
            }
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.receipt) {
                        renderReceipt(response.receipt);
                        $('#receiptModal').modal('show');
                    } else {
                        alert('Error loading receipt: ' + (response.message || 'Failed to load receipt'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error loading receipt';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function printReceiptDirect(apId, paymentTransactionId = null) {
            let url = '<?= base_url('accounts-payable/') ?>' + apId + '/receipt';
            if (paymentTransactionId) {
                url += '/' + paymentTransactionId;
            }
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.receipt) {
                        renderReceipt(response.receipt);
                        setTimeout(function() {
                            const printContent = document.getElementById('receiptContent');
                            if (printContent) {
                                const printWindow = window.open('', '_blank', 'width=800,height=600');
                                
                                if (!printWindow) {
                                    alert('Please allow popups to print the receipt');
                                    return;
                                }
                                
                                printWindow.document.write('<!DOCTYPE html><html><head><title>CHAKANOKS - Payment Receipt</title>');
                                printWindow.document.write('<meta name="viewport" content="width=80mm">');
                                printWindow.document.write('<style>');
                                printWindow.document.write('* { margin: 0; padding: 0; box-sizing: border-box; }');
                                printWindow.document.write('html, body { width: 80mm; margin: 0; padding: 0; background: white; overflow: hidden; }');
                                printWindow.document.write('body { font-family: "Courier New", monospace; margin: 0; padding: 0; }');
                                printWindow.document.write('.receipt-container { max-width: 80mm; width: 80mm; min-width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-size: 10pt; line-height: 1.3; color: #000; }');
                                printWindow.document.write('img { max-height: 30px; max-width: 30px; object-fit: contain; }');
                                printWindow.document.write('@page { size: 80mm auto; margin: 0; width: 80mm; }');
                                printWindow.document.write('@media print { html, body { width: 80mm !important; margin: 0 !important; padding: 0 !important; } .receipt-container { max-width: 80mm !important; width: 80mm !important; min-width: 80mm !important; padding: 10mm 5mm !important; } @page { size: 80mm auto !important; margin: 0 !important; width: 80mm !important; } }');
                                printWindow.document.write('</style></head><body>');
                                printWindow.document.write(printContent.innerHTML);
                                printWindow.document.write('</body></html>');
                                printWindow.document.close();
                                
                                // Trigger print after content is loaded
                                printWindow.onload = function() {
                                    setTimeout(function() {
                                        printWindow.focus();
                                        printWindow.print();
                                        // Don't close immediately - let user interact with print dialog
                                    }, 250);
                                };
                                
                                // Fallback if onload doesn't fire
                                setTimeout(function() {
                                    if (printWindow.document.readyState === 'complete') {
                                        printWindow.focus();
                                        printWindow.print();
                                    }
                                }, 500);
                            } else {
                                alert('Receipt content not found');
                            }
                        }, 100);
                    } else {
                        alert('Error loading receipt: ' + (response.message || 'Failed to load receipt'));
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error loading receipt';
                    alert('Error: ' + errorMsg);
                }
            });
        }
        
        function renderReceipt(receipt) {
            // Helper function to format dates
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            }
            
            // Helper function to format payment method
            function formatPaymentMethod(method) {
                if (!method || method.trim() === '') {
                    return 'Not specified';
                }
                
                const normalizedMethod = method.trim().toLowerCase();
                const methodMap = {
                    'cash': 'Cash',
                    'check': 'Check',
                    'bank_transfer': 'Bank Transfer',
                    'credit_card': 'Credit Card',
                    'online': 'Online Payment',
                    'other': 'Other',
                    'cheque': 'Check',
                    'bank transfer': 'Bank Transfer',
                    'banktransfer': 'Bank Transfer',
                    'transfer': 'Bank Transfer',
                    'bank': 'Bank Transfer',
                    'credit card': 'Credit Card',
                    'creditcard': 'Credit Card',
                    'card': 'Credit Card',
                    'online payment': 'Online Payment',
                    'onlinepayment': 'Online Payment',
                    'paypal': 'PayPal',
                    'gcash': 'GCash',
                    'maya': 'Maya'
                };
                
                if (methodMap[normalizedMethod]) {
                    return methodMap[normalizedMethod];
                }
                
                for (const key in methodMap) {
                    if (normalizedMethod.includes(key) || key.includes(normalizedMethod)) {
                        return methodMap[key];
                    }
                }
                
                return method.split(/[\s_-]+/)
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                    .join(' ');
            }
            
            const statusClass = receipt.payment_status === 'paid' ? 'badge-success' : 
                               receipt.payment_status === 'partial' ? 'badge-warning' : 
                               receipt.payment_status === 'overdue' ? 'badge-danger' : 'badge-secondary';
            const statusText = receipt.payment_status === 'paid' ? 'PAID' : 
                              receipt.payment_status === 'partial' ? 'PARTIAL' : 
                              receipt.payment_status === 'overdue' ? 'OVERDUE' : 'UNPAID';
            
            // Grocery store receipt style - narrow width (80mm/3 inches)
            let html = '<div class="receipt-container" id="receiptContent" style="max-width: 80mm; width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-family: "Courier New", monospace; font-size: 10pt; line-height: 1.3; color: #000;">';
            
            // Company Header - Compact
            html += '<div style="text-align: center; margin-bottom: 8mm; padding-bottom: 5mm; border-bottom: 1px dashed #000;">';
            html += '<img src="<?= base_url('assets/images/529947519_1269388418065636_7025202690109522655_n.png') ?>" alt="CHAKANOKS Logo" style="max-height: 30px; max-width: 30px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto 3mm;">';
            html += '<div style="font-weight: bold; font-size: 14pt; letter-spacing: 1px; margin-bottom: 2mm;">CHAKANOKS</div>';
            html += '<div style="font-size: 8pt; color: #666; margin-bottom: 3mm;">Supply Chain Management System</div>';
            html += '<div style="font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-top: 3mm;">PAYMENT RECEIPT</div>';
            html += '</div>';
            
            // Invoice Information - Compact single column
            html += '<div style="text-align: center; margin-bottom: 5mm; padding-bottom: 3mm; border-bottom: 1px dashed #000;">';
            html += '<div style="font-weight: bold; font-size: 9pt; margin-bottom: 2mm;">INVOICE #: ' + (receipt.invoice_number || 'N/A') + '</div>';
            html += '<div style="font-size: 8pt; margin-bottom: 1mm;">Status: <strong>' + statusText + '</strong></div>';
            html += '</div>';
            
            // Supplier and Order Info
            html += '<div style="margin-bottom: 4mm; font-size: 9pt;">';
            html += '<div style="margin-bottom: 2mm;"><strong>Supplier:</strong> ' + (receipt.supplier ? receipt.supplier.name : 'N/A') + '</div>';
            html += '<div style="margin-bottom: 2mm;"><strong>PO #:</strong> ' + (receipt.purchase_order ? receipt.purchase_order.order_number : 'N/A') + '</div>';
            if (receipt.invoice_date) {
                html += '<div style="margin-bottom: 2mm;"><strong>Invoice Date:</strong> ' + formatDate(receipt.invoice_date) + '</div>';
            }
            if (receipt.due_date) {
                html += '<div style="margin-bottom: 2mm;"><strong>Due Date:</strong> ' + formatDate(receipt.due_date) + '</div>';
            }
            html += '</div>';
            
            // Payment Amounts - Compact
            html += '<div style="text-align: center; margin-bottom: 4mm; padding: 3mm 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000;">';
            html += '<div style="margin-bottom: 2mm;"><span style="font-size: 8pt;">Total Amount:</span><br><span style="font-size: 12pt; font-weight: bold;">₱' + parseFloat(receipt.amounts.total_amount || 0).toFixed(2) + '</span></div>';
            html += '<div style="margin-bottom: 2mm;"><span style="font-size: 8pt;">Paid Amount:</span><br><span style="font-size: 12pt; font-weight: bold; color: #28a745;">₱' + parseFloat(receipt.amounts.total_paid || 0).toFixed(2) + '</span></div>';
            const balance = parseFloat(receipt.amounts.balance || 0);
            html += '<div><span style="font-size: 8pt;">Balance:</span><br><span style="font-size: 12pt; font-weight: bold; color: ' + (balance > 0 ? '#dc3545' : '#28a745') + ';">₱' + balance.toFixed(2) + '</span></div>';
            html += '</div>';
            
            // Payment Information - Compact
            html += '<div style="margin-bottom: 4mm; font-size: 9pt;">';
            html += '<div style="text-align: center; font-weight: bold; margin-bottom: 2mm; padding-bottom: 2mm; border-bottom: 1px dashed #000;">PAYMENT DETAILS</div>';
            
            if (receipt.all_payments && receipt.all_payments.length > 0) {
                const latestPayment = receipt.all_payments[0];
                html += '<div style="margin-bottom: 2mm;"><strong>Method:</strong> ' + formatPaymentMethod(latestPayment.payment_method || '') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Reference:</strong> ' + (latestPayment.payment_reference || 'N/A') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Date:</strong> ' + formatDate(latestPayment.payment_date) + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Amount:</strong> ₱' + parseFloat(latestPayment.payment_amount || 0).toFixed(2) + '</div>';
                if (receipt.all_payments.length > 1) {
                    html += '<div style="margin-top: 2mm; font-size: 8pt; color: #666;">Total Payments: ' + receipt.all_payments.length + ' transaction(s)</div>';
                }
            } else if (receipt.payment_details) {
                html += '<div style="margin-bottom: 2mm;"><strong>Method:</strong> ' + formatPaymentMethod(receipt.payment_details.payment_method || '') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Reference:</strong> ' + (receipt.payment_details.payment_reference || 'N/A') + '</div>';
                html += '<div style="margin-bottom: 2mm;"><strong>Date:</strong> ' + (receipt.payment_details.payment_date ? formatDate(receipt.payment_details.payment_date) : 'N/A') + '</div>';
            }
            html += '</div>';
            
            // Receipt Footer - Compact
            html += '<div style="text-align: center; margin-top: 5mm; padding-top: 3mm; border-top: 1px dashed #000; font-size: 8pt; color: #666;">';
            html += '<div style="margin-bottom: 1mm;">Receipt #: ' + (receipt.receipt_number || 'N/A') + '</div>';
            html += '<div style="margin-bottom: 1mm;">Date: ' + formatDate(receipt.receipt_date) + '</div>';
            if (receipt.recorded_by) {
                html += '<div style="margin-bottom: 1mm;">Recorded by: ' + (receipt.recorded_by.name || 'N/A') + '</div>';
            }
            // Get month from filter
            const receiptMonth = $('#receiptMonthFilter').val(); // Format: YYYY-MM
            
            // Get month name for display
            let monthName = '';
            if (receiptMonth) {
                const [year, month] = receiptMonth.split('-');
                monthName = new Date(parseInt(year), parseInt(month) - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            } else {
                const now = new Date();
                monthName = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            }
            
            const generatedDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            html += '<div style="margin-top: 3mm; font-size: 7pt;">Report Period: ' + monthName + '</div>';
            html += '<div style="margin-top: 1mm; font-size: 7pt;">Generated: ' + generatedDate + '</div>';
            html += '</div>';
            
            // Thank you message
            html += '<div style="text-align: center; margin-top: 5mm; padding-top: 3mm; border-top: 1px dashed #000; font-size: 9pt; font-weight: bold;">';
            html += 'Thank you for your payment!';
            html += '</div>';
            
            html += '</div>';
            
            $('#receiptModalBody').html(html);
        }
        
        function printReceipt() {
            const printContent = document.getElementById('receiptContent');
            if (!printContent) {
                alert('Receipt content not found');
                return;
            }
            
            // Create a new window for printing (better approach - doesn't affect current page)
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            
            if (!printWindow) {
                alert('Please allow popups to print the receipt');
                return;
            }
            
            // Write the print content with narrow receipt styling (80mm thermal receipt)
            printWindow.document.write('<!DOCTYPE html><html><head><title>CHAKANOKS - Payment Receipt</title>');
            printWindow.document.write('<meta name="viewport" content="width=80mm">');
            printWindow.document.write('<style>');
            printWindow.document.write('* { margin: 0; padding: 0; box-sizing: border-box; }');
            printWindow.document.write('html, body { width: 80mm; margin: 0; padding: 0; background: white; overflow: hidden; }');
            printWindow.document.write('body { font-family: "Courier New", monospace; margin: 0; padding: 0; }');
            printWindow.document.write('.receipt-container { max-width: 80mm; width: 80mm; min-width: 80mm; margin: 0 auto; padding: 10mm 5mm; font-size: 10pt; line-height: 1.3; color: #000; }');
            printWindow.document.write('img { max-height: 30px; max-width: 30px; object-fit: contain; }');
            printWindow.document.write('@page { size: 80mm auto; margin: 0; width: 80mm; }');
            printWindow.document.write('@media print { html, body { width: 80mm !important; margin: 0 !important; padding: 0 !important; } .receipt-container { max-width: 80mm !important; width: 80mm !important; min-width: 80mm !important; padding: 10mm 5mm !important; } @page { size: 80mm auto !important; margin: 0 !important; width: 80mm !important; } }');
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(printContent.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            // Wait for content to load, then trigger print
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                    printWindow.print();
                    // Don't close immediately - let user see the print dialog
                }, 250);
            };
            
            // Fallback if onload doesn't fire
            setTimeout(function() {
                if (printWindow.document.readyState === 'complete') {
                    printWindow.focus();
                    printWindow.print();
                }
            }, 500);
        }
        
        // Attach event handlers for receipt buttons
        $(document).on('click', '.viewReceiptBtn', function() {
            const apId = $(this).data('id');
            showReceipt(apId);
        });
        
        $(document).on('click', '.printReceiptBtn', function() {
            const apId = $(this).data('id');
            printReceiptDirect(apId);
        });
        
        // Print Monthly Report Function
        function printMonthlyReport() {
            console.log('Print Monthly Report button clicked');
            
            // Get month from filter
            const reportMonthFilter = document.getElementById('receiptMonthFilter');
            if (!reportMonthFilter) {
                alert('Month filter not found. Please refresh the page.');
                console.error('receiptMonthFilter element not found');
                return;
            }
            
            const reportMonth = reportMonthFilter.value; // Format: YYYY-MM
            console.log('Selected month:', reportMonth);
            
            // Determine the month to filter
            let selectedMonth, selectedYear;
            if (reportMonth) {
                const [year, month] = reportMonth.split('-');
                selectedYear = parseInt(year);
                selectedMonth = parseInt(month);
            } else {
                // Use current month if not specified
                const now = new Date();
                selectedYear = now.getFullYear();
                selectedMonth = now.getMonth() + 1;
            }
            
            const monthName = new Date(selectedYear, selectedMonth - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            const formattedDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedDateShort = new Date().toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
            
            // Show loading message
            const btn = document.getElementById('btnPrintMonthlyReport');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            
            // Fetch ALL accounts payable from the database for the selected month via AJAX
            const monthParam = reportMonth || (selectedYear + '-' + String(selectedMonth).padStart(2, '0'));
            console.log('Fetching accounts payable for month:', monthParam);
            
            $.ajax({
                url: '<?= base_url('accounts-payable/api/monthly') ?>',
                method: 'GET',
                data: {
                    month: monthParam
                },
                dataType: 'json',
                success: function(response) {
                    console.log('AJAX response:', response);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    
                    if (response.status === 'success' && response.accounts_payable) {
                        const filteredAP = response.accounts_payable;
                        console.log('Accounts payable loaded:', filteredAP.length);
                        
                        // Generate report with all accounts payable
                        generateMonthlyAPReport(filteredAP, monthName, formattedDate, formattedDateShort);
                    } else {
                        alert('Error loading monthly data: ' + (response.message || 'Failed to load accounts payable'));
                        console.error('Error response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('XHR Error:', xhr);
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    
                    let errorMessage = 'Error loading monthly data. Please check your connection and try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = 'Error: ' + xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'Error: API endpoint not found. Please check the route configuration.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Error: You are not authorized to access this resource.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Error: Server error. Please contact the administrator.';
                    }
                    
                    alert(errorMessage);
                }
            });
        }
        
        // Function to generate monthly accounts payable report
        function generateMonthlyAPReport(accountsPayable, monthName, formattedDate, formattedDateShort) {
            if (!accountsPayable || accountsPayable.length === 0) {
                alert('No accounts payable found for the selected month.');
                return;
            }
            
            // Calculate totals
            let totalAmount = 0;
            let totalPaid = 0;
            let totalBalance = 0;
            let paidCount = 0;
            let unpaidCount = 0;
            let partialCount = 0;
            
            accountsPayable.forEach(function(ap) {
                const amount = parseFloat(ap.total_amount || ap.amount || 0);
                const paid = parseFloat(ap.paid_amount || 0);
                const balance = parseFloat(ap.balance || (amount - paid));
                
                totalAmount += amount;
                totalPaid += paid;
                totalBalance += balance;
                
                const status = (ap.payment_status || 'unpaid').toLowerCase();
                if (status === 'paid') {
                    paidCount++;
                } else if (status === 'partial') {
                    partialCount++;
                } else {
                    unpaidCount++;
                }
            });
            
            // Helper function to format payment method
            function formatPaymentMethod(method) {
                if (!method || method.trim() === '') {
                    return 'N/A';
                }
                const normalizedMethod = method.trim().toLowerCase();
                const methodMap = {
                    'cash': 'Cash',
                    'check': 'Check',
                    'bank_transfer': 'Bank Transfer',
                    'credit_card': 'Credit Card',
                    'online': 'Online Payment',
                    'other': 'Other',
                    'cheque': 'Check',
                    'bank transfer': 'Bank Transfer',
                    'banktransfer': 'Bank Transfer',
                    'transfer': 'Bank Transfer',
                    'bank': 'Bank Transfer',
                    'credit card': 'Credit Card',
                    'creditcard': 'Credit Card',
                    'card': 'Credit Card',
                    'online payment': 'Online Payment',
                    'onlinepayment': 'Online Payment',
                    'paypal': 'PayPal',
                    'gcash': 'GCash',
                    'maya': 'Maya'
                };
                
                if (methodMap[normalizedMethod]) {
                    return methodMap[normalizedMethod];
                }
                
                for (const key in methodMap) {
                    if (normalizedMethod.includes(key) || key.includes(normalizedMethod)) {
                        return methodMap[key];
                    }
                }
                
                return method.split(/[\s_-]+/)
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                    .join(' ');
            }
            
            // Group by branch for summary
            const branchSummary = {};
            accountsPayable.forEach(function(ap) {
                const branchName = ap.branch ? ap.branch.name : 'N/A';
                if (!branchSummary[branchName]) {
                    branchSummary[branchName] = {
                        count: 0,
                        totalAmount: 0,
                        totalPaid: 0,
                        totalBalance: 0
                    };
                }
                branchSummary[branchName].count++;
                branchSummary[branchName].totalAmount += parseFloat(ap.total_amount || ap.amount || 0);
                branchSummary[branchName].totalPaid += parseFloat(ap.paid_amount || 0);
                branchSummary[branchName].totalBalance += parseFloat(ap.balance || (parseFloat(ap.total_amount || ap.amount || 0) - parseFloat(ap.paid_amount || 0)));
            });
            
            let reportHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>CHAKANOKS - Monthly Accounts Payable Report</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    html, body { margin: 0; padding: 0; background: white; font-family: "Arial", "Helvetica", sans-serif; }
                    .report-container { max-width: 210mm; width: 210mm; margin: 0 auto; padding: 20mm; font-size: 11pt; line-height: 1.5; color: #000; }
                    .header { text-align: center; margin-bottom: 15mm; padding-bottom: 10mm; border-bottom: 2px solid #2d5016; }
                    .header img { max-height: 50px; max-width: 50px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto 5mm; }
                    .company-name { font-weight: bold; font-size: 24pt; letter-spacing: 2px; margin-bottom: 5mm; color: #2d5016; }
                    .tagline { font-size: 11pt; color: #666; margin-bottom: 5mm; }
                    .report-title { font-weight: bold; font-size: 18pt; text-transform: uppercase; margin-top: 5mm; color: #2d5016; }
                    .info-section { margin-bottom: 10mm; font-size: 11pt; }
                    .info-section div { margin-bottom: 3mm; }
                    .branch-info { background: #f8f9fa; padding: 8mm; border-radius: 5px; margin-bottom: 10mm; border-left: 5px solid #2d5016; }
                    .branch-info h3 { color: #2d5016; margin-bottom: 3mm; font-size: 14pt; }
                    table { width: 100%; border-collapse: collapse; margin-top: 5mm; font-size: 10pt; }
                    table th, table td { padding: 8mm 5mm; text-align: left; border-bottom: 1px solid #ddd; }
                    table th { background: #2d5016; color: white; font-weight: bold; }
                    table tr:hover { background-color: #f8f9fa; }
                    table tr:last-child td { border-bottom: none; }
                    .summary { margin-top: 10mm; padding: 10mm; background: #f8f9fa; border-radius: 5px; font-size: 11pt; }
                    .summary-row { display: flex; justify-content: space-between; margin-bottom: 5mm; padding: 3mm 0; }
                    .summary-label { font-weight: bold; color: #2d5016; }
                    .summary-value { font-weight: bold; }
                    .branch-summary { margin-top: 5mm; padding-top: 5mm; border-top: 1px solid #ddd; }
                    .branch-summary h4 { color: #2d5016; margin-bottom: 3mm; font-size: 12pt; }
                    .footer { text-align: center; margin-top: 15mm; padding-top: 10mm; border-top: 2px solid #2d5016; font-size: 10pt; color: #666; }
                    @page { size: A4; margin: 20mm; }
                    @media print { 
                        html, body { width: 210mm !important; margin: 0 !important; padding: 0 !important; } 
                        .report-container { max-width: 210mm !important; width: 210mm !important; padding: 20mm !important; } 
                        @page { size: A4 !important; margin: 20mm !important; }
                        .no-print { display: none !important; }
                    }
                </style>
            </head>
            <body>
                <div class="report-container">
                    <div class="header">
                        <img src="<?= base_url('assets/images/529947519_1269388418065636_7025202690109522655_n.png') ?>" alt="CHAKANOKS Logo">
                        <div class="company-name">CHAKANOKS</div>
                        <div class="tagline">Supply Chain Management System</div>
                        <div class="report-title">Monthly Accounts Payable Report</div>
                        <div style="font-size: 12pt; color: #666; margin-top: 5mm; font-weight: normal;">Period: ${monthName}</div>
                    </div>
                    <div class="info-section">
                        <div><strong>Generated:</strong> ${formattedDate}</div>
                        <div><strong>Total Records:</strong> ${accountsPayable.length}</div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Branch</th>
                                <th>Supplier</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            accountsPayable.forEach(function(ap) {
                const invoiceNum = ap.invoice_number || 'N/A';
                const branch = ap.branch ? ap.branch.name : 'N/A';
                const supplier = ap.supplier ? ap.supplier.name : 'N/A';
                const amount = parseFloat(ap.total_amount || ap.amount || 0);
                const paid = parseFloat(ap.paid_amount || 0);
                const balance = parseFloat(ap.balance || (amount - paid));
                const status = (ap.payment_status || 'unpaid').toUpperCase();
                
                reportHTML += `
                            <tr>
                                <td>${invoiceNum}</td>
                                <td>${branch}</td>
                                <td>${supplier}</td>
                                <td>₱${amount.toFixed(2)}</td>
                                <td>₱${paid.toFixed(2)}</td>
                                <td style="color: ${balance > 0 ? '#dc3545' : '#28a745'}; font-weight: bold;">₱${balance.toFixed(2)}</td>
                                <td>${status}</td>
                            </tr>
                `;
            });
            
            reportHTML += `
                        </tbody>
                    </table>
                    <div class="summary">
                        <h3 style="color: #2d5016; margin-bottom: 5mm; font-size: 14pt;">Overall Summary</h3>
                        <div class="summary-row">
                            <span class="summary-label">Total Amount:</span>
                            <span class="summary-value">₱${totalAmount.toFixed(2)}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Total Paid:</span>
                            <span class="summary-value" style="color: #28a745;">₱${totalPaid.toFixed(2)}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Total Balance:</span>
                            <span class="summary-value" style="color: ${totalBalance > 0 ? '#dc3545' : '#28a745'}; font-size: 12pt;">₱${totalBalance.toFixed(2)}</span>
                        </div>
                        <div style="margin-top: 5mm; padding-top: 5mm; border-top: 1px solid #ddd; font-size: 10pt;">
                            <div class="summary-row">
                                <span><strong>Paid:</strong> ${paidCount}</span>
                                <span><strong>Partial:</strong> ${partialCount}</span>
                                <span><strong>Unpaid:</strong> ${unpaidCount}</span>
                            </div>
                        </div>
                        <div class="branch-summary">
                            <h4>Summary by Branch</h4>
            `;
            
            // Add branch summaries
            for (const branchName in branchSummary) {
                const branchData = branchSummary[branchName];
                reportHTML += `
                            <div style="margin-bottom: 3mm; padding: 3mm; background: white; border-left: 3px solid #2d5016;">
                                <div style="font-weight: bold; color: #2d5016; margin-bottom: 2mm;">${branchName}</div>
                                <div style="display: flex; justify-content: space-between; font-size: 10pt;">
                                    <span>Records: ${branchData.count}</span>
                                    <span>Total: ₱${branchData.totalAmount.toFixed(2)}</span>
                                    <span>Paid: ₱${branchData.totalPaid.toFixed(2)}</span>
                                    <span style="color: ${branchData.totalBalance > 0 ? '#dc3545' : '#28a745'};">Balance: ₱${branchData.totalBalance.toFixed(2)}</span>
                                </div>
                            </div>
                `;
            }
            
            reportHTML += `
                        </div>
                    </div>
                    <div class="footer">
                        <div style="margin-bottom: 3mm; font-size: 11pt;"><strong>Report Period:</strong> ${monthName}</div>
                        <div style="margin-bottom: 3mm; font-size: 11pt;"><strong>Generated:</strong> ${formattedDate}</div>
                        <div style="margin-top: 5mm; font-weight: bold; font-size: 14pt; color: #2d5016;">CHAKANOKS SCMS</div>
                        <div style="margin-top: 3mm; font-size: 11pt;">Thank you!</div>
                    </div>
                </div>
            </body>
            </html>
            `;
            
            const printWindow = window.open('', '_blank', 'width=800,height=1000');
            if (!printWindow) {
                alert('Please allow popups to print the report');
                return;
            }
            
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                    printWindow.print();
                }, 500);
            };
            
            setTimeout(function() {
                if (printWindow.document.readyState === 'complete') {
                    printWindow.focus();
                    printWindow.print();
                }
            }, 1000);
        }
        
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
    
    <!-- Payment Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="fas fa-receipt"></i> Payment Receipt
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiptModalBody" style="padding: 0;">
                    <!-- Receipt content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
