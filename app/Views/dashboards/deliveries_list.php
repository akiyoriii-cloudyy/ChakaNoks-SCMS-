<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deliveries — CHAKANOKS SCMS</title>
    
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
                        <h2 class="page-title">Deliveries</h2>
                        <p class="page-subtitle">View and manage deliveries</p>
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
                                    <th>Delivery #</th>
                                    <th>Purchase Order</th>
                                    <th>Supplier</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Scheduled Date</th>
                                    <th>Actual Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($deliveries)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No deliveries found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($deliveries as $delivery): ?>
                                        <?php
                                        $statusClass = 'badge-secondary';
                                        if ($delivery['status'] === 'delivered') {
                                            $statusClass = 'badge-success';
                                        } elseif ($delivery['status'] === 'in_transit') {
                                            $statusClass = 'badge-info';
                                        } elseif ($delivery['status'] === 'scheduled') {
                                            $statusClass = 'badge-warning';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= esc($delivery['delivery_number'] ?? 'DLV-' . str_pad($delivery['id'], 5, '0', STR_PAD_LEFT)) ?></td>
                                            <td><?= esc($delivery['purchase_order']['order_number'] ?? 'N/A') ?></td>
                                            <td><?= esc($delivery['supplier']['name'] ?? 'N/A') ?></td>
                                            <td><?= esc($delivery['branch']['name'] ?? 'N/A') ?></td>
                                            <td><span class="badge <?= $statusClass ?>"><?= esc($delivery['status'] ?? 'scheduled') ?></span></td>
                                            <td><?= esc($delivery['scheduled_date'] ?? 'N/A') ?></td>
                                            <td><?= esc($delivery['actual_delivery_date'] ?? 'N/A') ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="viewDelivery(<?= $delivery['id'] ?>)" title="View details">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
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

    <!-- Delivery Details Modal -->
    <div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white;">
                    <h5 class="modal-title" id="deliveryModalLabel">
                        <i class="fas fa-truck"></i> Delivery Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="deliveryModalBody">
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
        window.viewDelivery = function(deliveryId) {
            $.get('<?= base_url('delivery/') ?>' + deliveryId + '/track', function(response) {
                if (response.status === 'success' && response.delivery) {
                    const delivery = response.delivery;
                    let html = '<div style="padding: 20px;">';
                    html += '<h5 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Delivery Information</h5>';
                    html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">';
                    html += '<div><strong>Delivery Number:</strong><br>' + (delivery.delivery_number || 'N/A') + '</div>';
                    html += '<div><strong>Status:</strong><br><span class="badge ' + (delivery.status === 'delivered' ? 'badge-success' : delivery.status === 'scheduled' ? 'badge-warning' : 'badge-info') + '">' + (delivery.status || 'scheduled') + '</span></div>';
                    html += '<div><strong>Purchase Order:</strong><br>' + (delivery.purchase_order ? (delivery.purchase_order.order_number || 'N/A') : 'N/A') + '</div>';
                    html += '<div><strong>Supplier:</strong><br>' + (delivery.supplier ? delivery.supplier.name : 'N/A') + '</div>';
                    html += '<div><strong>Branch:</strong><br>' + (delivery.branch ? delivery.branch.name : 'N/A') + '</div>';
                    html += '<div><strong>Scheduled Date:</strong><br>' + (delivery.scheduled_date || 'N/A') + '</div>';
                    html += '<div><strong>Actual Delivery Date:</strong><br>' + (delivery.actual_delivery_date || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Driver Name:</strong><br>' + (delivery.driver_name || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Vehicle Info:</strong><br>' + (delivery.vehicle_info || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Received By:</strong><br>' + (delivery.received_by ? 'User ID: ' + delivery.received_by : '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '<div><strong>Received At:</strong><br>' + (delivery.received_at || '<span style="color: #999; font-style: italic;">Not set</span>') + '</div>';
                    html += '</div>';
                    
                    if (delivery.items && delivery.items.length > 0) {
                        html += '<h6 style="margin-top: 20px; margin-bottom: 10px; color: #2d5016; font-weight: 600;">Delivery Items:</h6>';
                        html += '<table class="table" style="margin-top: 10px;"><thead><tr style="background: #f8f9fa;"><th>Product</th><th>Quantity</th><th>Received Quantity</th></tr></thead><tbody>';
                        delivery.items.forEach(function(item) {
                            const productName = item.product ? item.product.name : 'Product ID: ' + item.product_id;
                            html += '<tr>';
                            html += '<td>' + productName + '</td>';
                            html += '<td>' + item.quantity + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                            html += '<td>' + (item.received_quantity || 0) + ' ' + (item.product ? (item.product.unit || '') : '') + '</td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                    }
                    
                    if (delivery.notes) {
                        html += '<div class="mt-3"><strong>Notes:</strong><br>' + delivery.notes + '</div>';
                    }
                    
                    if (delivery.tracking) {
                        html += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e5e7eb;">';
                        html += '<h6 style="margin-bottom: 15px; color: #2d5016; font-weight: 600;"><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Tracking Information:</h6>';
                        html += '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
                        html += '<div><strong>Current Status:</strong><br>' + delivery.tracking.current_status + '</div>';
                        if (delivery.tracking.days_until_delivery !== null) {
                            html += '<div><strong>Days Until Delivery:</strong><br>' + Math.round(delivery.tracking.days_until_delivery) + ' days</div>';
                        }
                        if (delivery.tracking.is_overdue) {
                            html += '<div><strong>Status:</strong><br><span class="badge badge-danger">Overdue</span></div>';
                        }
                        if (delivery.tracking.is_delayed) {
                            html += '<div><strong>Status:</strong><br><span class="badge badge-warning">Delayed</span></div>';
                        }
                        html += '</div>';
                    }
                    
                    html += '</div>';
                    
                    $('#deliveryModalBody').html(html);
                    $('#deliveryModal').modal('show');
                } else {
                    alert('Failed to load delivery details: ' + (response.message || 'Unknown error'));
                }
            }).fail(function() {
                alert('Error loading delivery details');
            });
        };
    </script>
</body>
</html>
