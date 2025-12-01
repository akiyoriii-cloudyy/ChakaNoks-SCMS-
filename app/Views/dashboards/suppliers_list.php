<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers — CHAKANOKS SCMS</title>
    
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
                        <h2 class="page-title">Suppliers</h2>
                        <p class="page-subtitle">View and manage suppliers</p>
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
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($suppliers)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No suppliers found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <?php
                                        $statusClass = ($supplier['status'] ?? 'inactive') === 'active' ? 'badge-success' : 'badge-secondary';
                                        ?>
                                        <tr>
                                            <td><?= esc($supplier['name'] ?? 'N/A') ?></td>
                                            <td><?= esc($supplier['contact_person'] ?? 'N/A') ?></td>
                                            <td><?= esc($supplier['email'] ?? 'N/A') ?></td>
                                            <td><?= esc($supplier['phone'] ?? 'N/A') ?></td>
                                            <td><span class="badge <?= $statusClass ?>"><?= esc($supplier['status'] ?? 'inactive') ?></span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="viewSupplier(<?= $supplier['id'] ?>)" title="View details">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
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

    <!-- Supplier Details Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="supplierModalLabel">
                        <i class="fas fa-building"></i> Supplier Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="supplierModalBody">
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
        window.viewSupplier = function(supplierId) {
            $.get('<?= base_url('supplier/') ?>' + supplierId, function(response) {
                if (response.status === 'success' && response.supplier) {
                    const supplier = response.supplier;
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Supplier Information</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Supplier Name:</strong><br>' + (supplier.name || 'N/A') + '</div>';
                    html += '<div><strong>Status:</strong><br><span class="badge ' + (supplier.status === 'active' ? 'badge-success' : 'badge-secondary') + '">' + (supplier.status || 'inactive') + '</span></div>';
                    if (supplier.contact_person) {
                        html += '<div><strong>Contact Person:</strong><br>' + supplier.contact_person + '</div>';
                    }
                    if (supplier.email) {
                        html += '<div><strong>Email:</strong><br><a href="mailto:' + supplier.email + '">' + supplier.email + '</a></div>';
                    }
                    if (supplier.phone) {
                        html += '<div><strong>Phone:</strong><br><a href="tel:' + supplier.phone + '">' + supplier.phone + '</a></div>';
                    }
                    if (supplier.address) {
                        html += '<div style="grid-column: 1 / -1;"><strong>Address:</strong><br>' + supplier.address + '</div>';
                    }
                    if (supplier.payment_terms) {
                        html += '<div><strong>Payment Terms:</strong><br>' + supplier.payment_terms + '</div>';
                    }
                    html += '</div>';
                    
                    if (supplier.total_orders !== undefined || supplier.pending_orders !== undefined) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-chart-line" style="margin-right: 8px;"></i>Order Statistics:</h6>';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
                        html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">';
                        html += '<div style="font-size: 2rem; font-weight: 700; color: #2d5016;">' + (supplier.total_orders || 0) + '</div>';
                        html += '<div style="color: #6c757d; font-size: 0.9rem;">Total Orders</div>';
                        html += '</div>';
                        html += '<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">';
                        html += '<div style="font-size: 2rem; font-weight: 700; color: #ff9800;">' + (supplier.pending_orders || 0) + '</div>';
                        html += '<div style="color: #6c757d; font-size: 0.9rem;">Pending Orders</div>';
                        html += '</div>';
                        html += '</div>';
                    }
                    
                    if (supplier.created_at) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<div style="color: #6c757d; font-size: 0.9rem;">Created: ' + new Date(supplier.created_at).toLocaleDateString() + '</div>';
                    }
                    
                    html += '</div>';
                    
                    $('#supplierModalBody').html(html);
                    $('#supplierModal').modal('show');
                } else {
                    alert('Failed to load supplier details: ' + (response.message || 'Unknown error'));
                }
            }).fail(function() {
                alert('Error loading supplier details');
            });
        };
        
        // Pagination
        $(document).ready(function() {
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
        });
    </script>
</body>
</html>
