<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — CHAKANOKS SCMS</title>
    
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
                        <h2 class="page-title">Reports</h2>
                        <p class="page-subtitle">View system reports and analytics</p>
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
                    <div class="card-header" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white; padding: 20px 24px; border-radius: 12px 12px 0 0; margin: -30px -30px 20px -30px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h3 class="card-title" style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-chart-line" style="font-size: 1.75rem;"></i>
                                    Comprehensive Monthly Reports
                                </h3>
                                <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 0.95rem;">General reports from all departments</p>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <input type="month" id="reportsMonthFilter" class="form-control" 
                                       style="width: 200px; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.1); color: white;"
                                       value="<?= date('Y-m') ?>">
                                <button class="btn btn-light" onclick="loadReports()" style="padding: 8px 20px; border-radius: 6px; font-weight: 500;">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                                <button class="btn btn-light" onclick="printAllReports()" style="padding: 8px 20px; border-radius: 6px; font-weight: 500;">
                                    <i class="fas fa-print"></i> Print Report
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="reportsContent" style="padding: 20px; min-height: 200px;">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Loading reports...</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        console.log('Reports script loaded');
        
        // Ensure jQuery is loaded
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded!');
        } else {
            console.log('jQuery version:', jQuery.fn.jquery);
        }
        
        function loadReports() {
            console.log('loadReports called');
            const contentDiv = $('#reportsContent');
            if (!contentDiv.length) {
                console.error('reportsContent div not found!');
                return;
            }
            
            const month = $('#reportsMonthFilter').val() || new Date().toISOString().slice(0, 7);
            contentDiv.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading reports...</p></div>');
            
            const apiUrl = '<?= base_url('centraladmin/api/monthly-reports') ?>';
            console.log('Fetching reports from:', apiUrl, 'for month:', month);
            
            $.ajax({
                url: apiUrl,
                method: 'GET',
                data: { month: month },
                dataType: 'json',
                timeout: 30000, // 30 second timeout
                success: function(response) {
                    console.log('Reports loaded successfully:', response);
                    if (response && response.status === 'success') {
                        renderReports(response.reports, month, response.prepared_by);
                    } else {
                        contentDiv.html('<div class="alert alert-danger">Error: ' + (response ? (response.message || 'Failed to load reports') : 'Invalid response from server') + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading reports:', {xhr: xhr, status: status, error: error, responseText: xhr.responseText});
                    let errorMsg = 'Error loading reports. Please try again.';
                    
                    if (status === 'timeout') {
                        errorMsg = 'Request timed out. The server is taking too long to respond.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        errorMsg = 'Network error. Please check your connection.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'Reports endpoint not found. Please contact administrator.';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Server error. Please try again later.';
                    } else if (xhr.responseText) {
                        // Try to extract error message from response
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {
                            // Not JSON, use default message
                        }
                    }
                    
                    contentDiv.html('<div class="alert alert-danger"><strong>Error:</strong> ' + errorMsg + '<br><small>Status: ' + status + ' | HTTP: ' + (xhr.status || 'N/A') + '</small></div>');
                }
            });
        }
        
        // Pagination helper function
        function paginateArray(array, page, perPage) {
            const totalItems = array.length;
            const totalPages = Math.ceil(totalItems / perPage);
            const startIndex = (page - 1) * perPage;
            const endIndex = startIndex + perPage;
            const paginatedData = array.slice(startIndex, endIndex);
            
            return {
                data: paginatedData,
                currentPage: page,
                totalPages: totalPages,
                totalItems: totalItems,
                perPage: perPage,
                startIndex: startIndex + 1,
                endIndex: Math.min(endIndex, totalItems)
            };
        }
        
        // Generate pagination controls
        function generatePaginationControls(sectionId, currentPage, totalPages, totalItems) {
            if (totalPages <= 1) return '';
            
            let html = '<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding: 12px; background: #f8fafc; border-radius: 8px;">';
            html += '<div style="color: #64748b; font-size: 0.875rem;">';
            html += 'Showing ' + ((currentPage - 1) * 10 + 1) + ' to ' + Math.min(currentPage * 10, totalItems) + ' of ' + totalItems + ' entries';
            html += '</div>';
            html += '<div style="display: flex; gap: 8px; align-items: center;">';
            
            // Previous button
            if (currentPage > 1) {
                html += '<button onclick="changePage(\'' + sectionId + '\', ' + (currentPage - 1) + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer; color: #1e293b;">Previous</button>';
            } else {
                html += '<button disabled style="padding: 6px 12px; border: 1px solid #e2e8f0; background: #f1f5f9; border-radius: 4px; color: #94a3b8; cursor: not-allowed;">Previous</button>';
            }
            
            // Page numbers
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                html += '<button onclick="changePage(\'' + sectionId + '\', 1)" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer; color: #1e293b;">1</button>';
                if (startPage > 2) {
                    html += '<span style="padding: 6px; color: #64748b;">...</span>';
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    html += '<button style="padding: 6px 12px; border: 1px solid #2d5016; background: #2d5016; color: white; border-radius: 4px; font-weight: 600;">' + i + '</button>';
                } else {
                    html += '<button onclick="changePage(\'' + sectionId + '\', ' + i + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer; color: #1e293b;">' + i + '</button>';
                }
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += '<span style="padding: 6px; color: #64748b;">...</span>';
                }
                html += '<button onclick="changePage(\'' + sectionId + '\', ' + totalPages + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer; color: #1e293b;">' + totalPages + '</button>';
            }
            
            // Next button
            if (currentPage < totalPages) {
                html += '<button onclick="changePage(\'' + sectionId + '\', ' + (currentPage + 1) + ')" style="padding: 6px 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px; cursor: pointer; color: #1e293b;">Next</button>';
            } else {
                html += '<button disabled style="padding: 6px 12px; border: 1px solid #e2e8f0; background: #f1f5f9; border-radius: 4px; color: #94a3b8; cursor: not-allowed;">Next</button>';
            }
            
            html += '</div>';
            html += '</div>';
            return html;
        }
        
        // Store pagination state
        let paginationState = {};
        
        // Change page function
        function changePage(sectionId, page) {
            if (!paginationState[sectionId]) return;
            paginationState[sectionId].currentPage = page;
            renderReportsSection(sectionId);
        }
        
        // Render a specific paginated section
        function renderReportsSection(sectionId) {
            const state = paginationState[sectionId];
            if (!state) return;
            
            const paginated = paginateArray(state.data, state.currentPage, 10);
            const container = $('#' + sectionId + '_container');
            const paginationContainer = $('#' + sectionId + '_pagination');
            
            if (!container.length || !paginationContainer.length) return;
            
            let html = '';
            if (state.renderFunction) {
                html = state.renderFunction(paginated.data);
            }
            
            container.html(html);
            paginationContainer.html(generatePaginationControls(sectionId, paginated.currentPage, paginated.totalPages, paginated.totalItems));
        }
        
        function renderReports(reports, month, preparedBy) {
            const contentDiv = $('#reportsContent');
            const monthName = new Date(month + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            
            // Reset pagination state
            paginationState = {};
            
            let html = '<div style="display: flex; flex-direction: column; gap: 24px;">';
            
            // Prepared By Information
            if (preparedBy) {
                html += '<div style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white; border-radius: 12px; padding: 16px 24px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">';
                html += '<div style="display: flex; align-items: center; gap: 12px;">';
                html += '<i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>';
                html += '<div>';
                html += '<div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 4px;">Prepared By:</div>';
                html += '<div style="font-size: 1.1rem; font-weight: 600;">' + (preparedBy.name || preparedBy.email || 'N/A') + '</div>';
                if (preparedBy.role) {
                    html += '<div style="font-size: 0.8rem; opacity: 0.8; margin-top: 2px;">' + (preparedBy.role.charAt(0).toUpperCase() + preparedBy.role.slice(1).replace('_', ' ')) + '</div>';
                }
                html += '</div>';
                html += '</div>';
                html += '<div style="text-align: right;">';
                html += '<div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 4px;">Generated:</div>';
                html += '<div style="font-size: 1rem; font-weight: 500;">' + new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            }
            
            // Branch Manager Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #17a2b8;">';
            html += '<h4 style="color: #17a2b8; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-building"></i> Branch Manager Reports</h4>';
            
            // Sales Report
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-chart-line" style="color: #10b981; margin-right: 8px;"></i>Sales Report - ' + monthName + '</h5>';
            if (reports.branch_manager.sales && reports.branch_manager.sales.length > 0) {
                const sectionId = 'sales_report';
                paginationState[sectionId] = {
                    data: reports.branch_manager.sales,
                    currentPage: 1,
                    renderFunction: function(salesData) {
                        let tableHtml = '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                        tableHtml += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Sales</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Transactions</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Avg Transaction</th></tr></thead><tbody>';
                        let totalSales = 0;
                        salesData.forEach(function(sale) {
                            totalSales += parseFloat(sale.total_sales || 0);
                            tableHtml += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (sale.branch_name || 'N/A') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #10b981;">₱' + parseFloat(sale.total_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (sale.transaction_count || 0) + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">₱' + parseFloat(sale.avg_transaction || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>';
                        });
                        tableHtml += '<tr style="background: #f8fafc; font-weight: 700;"><td style="padding: 12px; border-top: 2px solid #e2e8f0;">Total</td>';
                        tableHtml += '<td style="padding: 12px; border-top: 2px solid #e2e8f0; color: #2d5016;">₱' + totalSales.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                        tableHtml += '<td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td><td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td></tr>';
                        tableHtml += '</tbody></table></div>';
                        return tableHtml;
                    }
                };
                
                const paginated = paginateArray(reports.branch_manager.sales, 1, 10);
                html += '<div id="' + sectionId + '_container">' + paginationState[sectionId].renderFunction(paginated.data) + '</div>';
                html += '<div id="' + sectionId + '_pagination">' + generatePaginationControls(sectionId, paginated.currentPage, paginated.totalPages, paginated.totalItems) + '</div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No sales data available for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Inventory Summary
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-warehouse" style="color: #3b82f6; margin-right: 8px;"></i>Inventory Summary - ' + monthName + '</h5>';
            if (reports.branch_manager.inventory && reports.branch_manager.inventory.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Items</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Value</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Low Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Expired</th></tr></thead><tbody>';
                reports.branch_manager.inventory.forEach(function(inv) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_stock || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #10b981;">₱' + parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.low_stock_items > 0 ? '#f59e0b' : '#64748b') + ';">' + (inv.low_stock_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.expired_items > 0 ? '#ef4444' : '#64748b') + ';">' + (inv.expired_items || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No inventory data available for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Product Details (Branch → Category → Product)
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-list" style="color: #6366f1; margin-right: 8px;"></i>Product Details - ' + monthName + '</h5>';
            if (reports.branch_manager.inventory_details && Object.keys(reports.branch_manager.inventory_details).length > 0) {
                // Flatten the nested structure for pagination
                let allProducts = [];
                Object.keys(reports.branch_manager.inventory_details).sort().forEach(function(branchName) {
                    Object.keys(reports.branch_manager.inventory_details[branchName]).sort().forEach(function(categoryName) {
                        reports.branch_manager.inventory_details[branchName][categoryName].forEach(function(product) {
                            allProducts.push({
                                branch_name: branchName,
                                category_name: categoryName,
                                product_name: product.product_name,
                                total_stock: product.total_stock,
                                total_value: product.total_value,
                                is_low_stock: product.is_low_stock,
                                is_expired: product.is_expired
                            });
                        });
                    });
                });
                
                if (allProducts.length > 0) {
                    const sectionId = 'branch_product_details';
                    paginationState[sectionId] = {
                        data: allProducts,
                        currentPage: 1,
                        renderFunction: function(productsData) {
                            let tableHtml = '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                            tableHtml += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Category</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Product</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Value</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Low Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Expired</th></tr></thead><tbody>';
                            productsData.forEach(function(product) {
                                tableHtml += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (product.branch_name || 'N/A') + '</td>';
                                tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (product.category_name || 'N/A') + '</td>';
                                tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">' + (product.product_name || 'N/A') + '</td>';
                                tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (product.total_stock || 0) + '</td>';
                                tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #10b981;">₱' + parseFloat(product.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                                tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (product.is_low_stock ? '#f59e0b' : '#64748b') + ';">' + (product.is_low_stock ? 'Yes' : 'No') + '</td>';
                                tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (product.is_expired ? '#ef4444' : '#64748b') + ';">' + (product.is_expired ? 'Yes' : 'No') + '</td></tr>';
                            });
                            tableHtml += '</tbody></table></div>';
                            return tableHtml;
                        }
                    };
                    
                    const paginated = paginateArray(allProducts, 1, 10);
                    html += '<div id="' + sectionId + '_container">' + paginationState[sectionId].renderFunction(paginated.data) + '</div>';
                    html += '<div id="' + sectionId + '_pagination">' + generatePaginationControls(sectionId, paginated.currentPage, paginated.totalPages, paginated.totalItems) + '</div>';
                } else {
                    html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No product details available for ' + monthName + '</p>';
                }
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No product details available for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Damage Products Report
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 8px;"></i>Damage Products Report - ' + monthName + '</h5>';
            if (reports.branch_manager.damage_products && reports.branch_manager.damage_products.length > 0) {
                const sectionId = 'branch_damage_products';
                paginationState[sectionId] = {
                    data: reports.branch_manager.damage_products,
                    currentPage: 1,
                    renderFunction: function(damageData) {
                        let tableHtml = '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                        tableHtml += '<thead style="background: #fef2f2;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Product</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Category</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Damaged</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Incidents</th></tr></thead><tbody>';
                        damageData.forEach(function(damage) {
                            tableHtml += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.branch_name || 'N/A') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.product_name || 'N/A') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.category || 'N/A') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #ef4444; font-weight: 600;">' + (damage.total_damaged || 0) + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.damage_count || 0) + '</td></tr>';
                        });
                        tableHtml += '</tbody></table></div>';
                        return tableHtml;
                    }
                };
                
                const paginated = paginateArray(reports.branch_manager.damage_products, 1, 10);
                html += '<div id="' + sectionId + '_container">' + paginationState[sectionId].renderFunction(paginated.data) + '</div>';
                html += '<div id="' + sectionId + '_pagination">' + generatePaginationControls(sectionId, paginated.currentPage, paginated.totalPages, paginated.totalItems) + '</div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No damaged products reported for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            // Inventory Staff Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">';
            html += '<h4 style="color: #10b981; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-boxes"></i> Inventory Staff Reports</h4>';
            
            // Inventory Summary
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-warehouse" style="color: #3b82f6; margin-right: 8px;"></i>Inventory Summary - ' + monthName + '</h5>';
            if (reports.inventory_staff.inventory && reports.inventory_staff.inventory.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Items</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Value</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Low Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Expired</th></tr></thead><tbody>';
                reports.inventory_staff.inventory.forEach(function(inv) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (inv.total_stock || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #2d5016;">₱' + parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.low_stock_items > 0 ? '#ef4444' : '#10b981') + ';">' + (inv.low_stock_items || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (inv.expired_items > 0 ? '#ef4444' : '#10b981') + ';">' + (inv.expired_items || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No inventory data available</p>';
            }
            html += '</div>';
            
            // Detailed Product Listing (Branch → Category → Product)
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-list" style="color: #3b82f6; margin-right: 8px;"></i>Product Details - ' + monthName + '</h5>';
            if (reports.inventory_staff.inventory_details && Object.keys(reports.inventory_staff.inventory_details).length > 0) {
                // Flatten the nested structure for pagination
                const flattenedProducts = [];
                Object.keys(reports.inventory_staff.inventory_details).sort().forEach(function(branchName) {
                    const branchData = reports.inventory_staff.inventory_details[branchName];
                    Object.keys(branchData).sort().forEach(function(categoryName) {
                        branchData[categoryName].forEach(function(product) {
                            flattenedProducts.push({
                                branch_name: branchName,
                                category_name: categoryName,
                                product: product
                            });
                        });
                    });
                });
                
                const sectionId = 'product_details';
                paginationState[sectionId] = {
                    data: flattenedProducts,
                    currentPage: 1,
                    renderFunction: function(productsData) {
                        let tableHtml = '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                        tableHtml += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Category</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Product</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Value</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Low Stock</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Expired</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Last Updated</th></tr></thead><tbody>';
                        
                        productsData.forEach(function(item) {
                            const product = item.product;
                            const lastUpdated = product.last_updated ? new Date(product.last_updated).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                            tableHtml += '<tr>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; background: #f1f5f9;">' + (item.branch_name || 'N/A') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 500; background: #f8fafc;">' + (item.category_name || 'Uncategorized') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (product.product_name || 'N/A') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + parseFloat(product.total_stock || 0).toLocaleString('en-US') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #2d5016;">₱' + parseFloat(product.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (product.is_low_stock == 1 ? '#ef4444' : '#10b981') + '; font-weight: ' + (product.is_low_stock == 1 ? '600' : '400') + ';">' + (product.is_low_stock == 1 ? 'Yes' : 'No') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: ' + (product.is_expired == 1 ? '#ef4444' : '#10b981') + '; font-weight: ' + (product.is_expired == 1 ? '600' : '400') + ';">' + (product.is_expired == 1 ? 'Yes' : 'No') + '</td>';
                            tableHtml += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + lastUpdated + '</td>';
                            tableHtml += '</tr>';
                        });
                        
                        tableHtml += '</tbody></table></div>';
                        return tableHtml;
                    }
                };
                
                const paginated = paginateArray(flattenedProducts, 1, 10);
                html += '<div id="' + sectionId + '_container">' + paginationState[sectionId].renderFunction(paginated.data) + '</div>';
                html += '<div id="' + sectionId + '_pagination">' + generatePaginationControls(sectionId, paginated.currentPage, paginated.totalPages, paginated.totalItems) + '</div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No products with activity found for ' + monthName + '. Products are included if they were updated or had stock transactions during this month.</p>';
            }
            html += '</div>';
            
            // Damage Products Report (Inventory Staff)
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 8px;"></i>Damage Products Report - ' + monthName + '</h5>';
            if (reports.inventory_staff.damage_products && reports.inventory_staff.damage_products.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #fef2f2;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Product</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Category</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Damaged</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Incidents</th></tr></thead><tbody>';
                reports.inventory_staff.damage_products.forEach(function(damage) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.product_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.category || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #ef4444; font-weight: 600;">' + (damage.total_damaged || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (damage.damage_count || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No damaged products reported for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            // Franchise Manager Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #8b5cf6;">';
            html += '<h4 style="color: #8b5cf6; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-handshake"></i> Franchise Manager Reports</h4>';
            
            // Applications
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-file-alt" style="color: #6366f1; margin-right: 8px;"></i>Franchise Applications - ' + monthName + '</h5>';
            if (reports.franchise_manager.applications && reports.franchise_manager.applications.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Status</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Date</th></tr></thead><tbody>';
                reports.franchise_manager.applications.forEach(function(app) {
                    const statusColor = app.status === 'approved' ? '#10b981' : app.status === 'rejected' ? '#ef4444' : '#f59e0b';
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (app.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;"><span style="color: ' + statusColor + '; font-weight: 600;">' + (app.status || 'N/A').toUpperCase() + '</span></td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (app.created_at ? new Date(app.created_at).toLocaleDateString() : 'N/A') + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No franchise applications for ' + monthName + '</p>';
            }
            html += '</div>';
            
            // Royalties
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-money-bill-wave" style="color: #f59e0b; margin-right: 8px;"></i>Royalty Payments - ' + monthName + '</h5>';
            if (reports.franchise_manager.royalties && reports.franchise_manager.royalties.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Gross Sales</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Royalty Amount</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Due</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Status</th></tr></thead><tbody>';
                let totalRoyalties = 0;
                reports.franchise_manager.royalties.forEach(function(royalty) {
                    totalRoyalties += parseFloat(royalty.total_due || 0);
                    const statusColor = royalty.status === 'paid' ? '#10b981' : royalty.status === 'overdue' ? '#ef4444' : '#f59e0b';
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (royalty.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">₱' + parseFloat(royalty.gross_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">₱' + parseFloat(royalty.royalty_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">₱' + parseFloat(royalty.total_due || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0;"><span style="color: ' + statusColor + '; font-weight: 600;">' + (royalty.status || 'N/A').toUpperCase() + '</span></td></tr>';
                });
                html += '<tr style="background: #f8fafc; font-weight: 700;"><td style="padding: 12px; border-top: 2px solid #e2e8f0;">Total</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td><td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0; color: #2d5016;">₱' + totalRoyalties.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>';
                html += '<td style="padding: 12px; border-top: 2px solid #e2e8f0;">-</td></tr>';
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No royalty payments for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            // Logistics Coordinator Reports Section
            html += '<div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">';
            html += '<h4 style="color: #f59e0b; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><i class="fas fa-truck"></i> Logistics Coordinator Reports</h4>';
            
            // Delivery Summary
            html += '<div style="margin-bottom: 24px;">';
            html += '<h5 style="color: #1e293b; margin-bottom: 12px; font-weight: 600;"><i class="fas fa-shipping-fast" style="color: #3b82f6; margin-right: 8px;"></i>Delivery Summary - ' + monthName + '</h5>';
            if (reports.logistics_coordinator.delivery_summary && reports.logistics_coordinator.delivery_summary.length > 0) {
                html += '<div style="overflow-x: auto;"><table class="table" style="margin-bottom: 0; border: 1px solid #e2e8f0;">';
                html += '<thead style="background: #f8fafc;"><tr><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Branch</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Deliveries</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Completed</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Pending</th><th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">In Transit</th></tr></thead><tbody>';
                reports.logistics_coordinator.delivery_summary.forEach(function(summary) {
                    html += '<tr><td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">' + (summary.branch_name || 'N/A') + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">' + (summary.total_deliveries || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #10b981;">' + (summary.completed_deliveries || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #f59e0b;">' + (summary.pending_deliveries || 0) + '</td>';
                    html += '<td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #3b82f6;">' + (summary.in_transit_deliveries || 0) + '</td></tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p style="color: #64748b; padding: 20px; text-align: center; background: #f8fafc; border-radius: 8px;">No delivery data for ' + monthName + '</p>';
            }
            html += '</div>';
            html += '</div>';
            
            html += '</div>';
            
            contentDiv.html(html);
        }
        
        function printAllReports() {
            const month = $('#reportsMonthFilter').val() || new Date().toISOString().slice(0, 7);
            const monthName = new Date(month + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            
            // Fetch reports data for printing
            $.ajax({
                url: '<?= base_url('centraladmin/api/monthly-reports') ?>',
                method: 'GET',
                data: { month: month },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        generatePrintReport(response.reports, monthName, response.prepared_by);
                    } else {
                        alert('Error: ' + (response.message || 'Failed to load reports'));
                    }
                },
                error: function() {
                    alert('Error loading reports for printing');
                }
            });
        }
        
        function generatePrintReport(reports, monthName, preparedBy) {
            const printWindow = window.open('', '_blank');
            const reportHTML = generateReportHTML(reports, monthName, preparedBy);
            printWindow.document.write(reportHTML);
            printWindow.document.close();
            printWindow.print();
        }
        
        function generateReportHTML(reports, monthName, preparedBy) {
            let preparedByHTML = '';
            if (preparedBy) {
                const name = preparedBy.name || preparedBy.email || 'N/A';
                let roleText = '';
                if (preparedBy.role) {
                    const roleFormatted = preparedBy.role.charAt(0).toUpperCase() + preparedBy.role.slice(1).replace('_', ' ');
                    roleText = ' (' + roleFormatted + ')';
                }
                preparedByHTML = '<p><strong>Prepared By:</strong> ' + name + roleText + '</p>';
            }
            
            // Generate date string separately to avoid template literal parsing issues
            const generatedDate = new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const branchManagerHTML = generateSectionHTML('Branch Manager Reports', reports.branch_manager, monthName);
            const inventoryStaffHTML = generateSectionHTML('Inventory Staff Reports', reports.inventory_staff, monthName);
            const franchiseManagerHTML = generateSectionHTML('Franchise Manager Reports', reports.franchise_manager, monthName);
            const logisticsCoordinatorHTML = generateSectionHTML('Logistics Coordinator Reports', reports.logistics_coordinator, monthName);
            
            return '<!DOCTYPE html>\n' +
                '<html>\n' +
                '<head>\n' +
                '    <title>CHAKANOKS - Comprehensive Monthly Report - ' + monthName + '</title>\n' +
                '    <style>\n' +
                '        body { font-family: Arial, sans-serif; margin: 20px; }\n' +
                '        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #2d5016; padding-bottom: 20px; }\n' +
                '        .header h1 { color: #2d5016; margin: 0; }\n' +
                '        .section { margin-bottom: 40px; page-break-inside: avoid; }\n' +
                '        .section-title { background: #2d5016; color: white; padding: 10px; font-weight: bold; margin-bottom: 15px; }\n' +
                '        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }\n' +
                '        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }\n' +
                '        th { background: #f8f9fa; font-weight: bold; }\n' +
                '        .total-row { background: #f8f9fa; font-weight: bold; }\n' +
                '    </style>\n' +
                '</head>\n' +
                '<body>\n' +
                '    <div class="header">\n' +
                '        <h1>CHAKANOKS SUPPLY CHAIN MANAGEMENT SYSTEM</h1>\n' +
                '        <h2>Comprehensive Monthly Report</h2>\n' +
                '        <p><strong>Report Period: ' + monthName + '</strong></p>\n' +
                '        <p>Generated: ' + generatedDate + '</p>\n' +
                '        ' + preparedByHTML + '\n' +
                '    </div>\n' +
                '    \n' +
                '    ' + branchManagerHTML + '\n' +
                '    ' + inventoryStaffHTML + '\n' +
                '    ' + franchiseManagerHTML + '\n' +
                '    ' + logisticsCoordinatorHTML + '\n' +
                '</body>\n' +
                '</html>';
        }
        
        function generateSectionHTML(title, data, monthName) {
            let html = `<div class="section"><div class="section-title">${title} - ${monthName}</div>`;
            
            if (title.includes('Branch Manager')) {
                // Sales Report
                if (data.sales && data.sales.length > 0) {
                    html += '<h3>Sales Report</h3><table><tr><th>Branch</th><th>Total Sales</th><th>Transactions</th><th>Avg Transaction</th></tr>';
                    data.sales.forEach(sale => {
                        html += `<tr><td>${sale.branch_name || 'N/A'}</td><td>₱${parseFloat(sale.total_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${sale.transaction_count || 0}</td><td>₱${parseFloat(sale.avg_transaction || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Inventory Summary
                if (data.inventory && data.inventory.length > 0) {
                    html += '<h3>Inventory Summary</h3><table><tr><th>Branch</th><th>Total Items</th><th>Total Stock</th><th>Total Value</th><th>Low Stock</th><th>Expired</th></tr>';
                    data.inventory.forEach(inv => {
                        html += `<tr><td>${inv.branch_name || 'N/A'}</td><td>${inv.total_items || 0}</td><td>${inv.total_stock || 0}</td><td>₱${parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${inv.low_stock_items || 0}</td><td>${inv.expired_items || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Product Details
                if (data.inventory_details && Object.keys(data.inventory_details).length > 0) {
                    html += '<h3>Product Details</h3>';
                    Object.keys(data.inventory_details).sort().forEach(branchName => {
                        html += `<h4>Branch: ${branchName}</h4>`;
                        Object.keys(data.inventory_details[branchName]).sort().forEach(categoryName => {
                            html += `<h5>Category: ${categoryName}</h5><table><tr><th>Product</th><th>Total Stock</th><th>Total Value</th><th>Low Stock</th><th>Expired</th></tr>`;
                            data.inventory_details[branchName][categoryName].forEach(product => {
                                html += `<tr><td>${product.product_name || 'N/A'}</td><td>${product.total_stock || 0}</td><td>₱${parseFloat(product.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${product.is_low_stock ? 'Yes' : 'No'}</td><td>${product.is_expired ? 'Yes' : 'No'}</td></tr>`;
                            });
                            html += '</table>';
                        });
                    });
                }
                // Damage Products
                if (data.damage_products && data.damage_products.length > 0) {
                    html += '<h3>Damage Products Report</h3><table><tr><th>Branch</th><th>Product</th><th>Category</th><th>Total Damaged</th><th>Incidents</th></tr>';
                    data.damage_products.forEach(damage => {
                        html += `<tr><td>${damage.branch_name || 'N/A'}</td><td>${damage.product_name || 'N/A'}</td><td>${damage.category || 'N/A'}</td><td>${damage.total_damaged || 0}</td><td>${damage.damage_count || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
            } else if (title.includes('Inventory Staff')) {
                // Inventory Summary
                if (data.inventory && data.inventory.length > 0) {
                    html += '<h3>Inventory Summary</h3><table><tr><th>Branch</th><th>Total Items</th><th>Total Stock</th><th>Total Value</th><th>Low Stock</th><th>Expired</th></tr>';
                    data.inventory.forEach(inv => {
                        html += `<tr><td>${inv.branch_name || 'N/A'}</td><td>${inv.total_items || 0}</td><td>${inv.total_stock || 0}</td><td>₱${parseFloat(inv.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${inv.low_stock_items || 0}</td><td>${inv.expired_items || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Detailed Product Listing
                if (data.inventory_details && Object.keys(data.inventory_details).length > 0) {
                    html += '<h3>Product Details</h3><table><tr><th>Branch</th><th>Category</th><th>Product</th><th>Total Stock</th><th>Total Value</th><th>Low Stock</th><th>Expired</th><th>Last Updated</th></tr>';
                    Object.keys(data.inventory_details).sort().forEach(branchName => {
                        const branchData = data.inventory_details[branchName];
                        Object.keys(branchData).sort().forEach(categoryName => {
                            branchData[categoryName].forEach(product => {
                                const lastUpdated = product.last_updated ? new Date(product.last_updated).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                                html += `<tr><td>${branchName || 'N/A'}</td><td>${categoryName || 'Uncategorized'}</td><td>${product.product_name || 'N/A'}</td><td>${parseFloat(product.total_stock || 0).toLocaleString('en-US')}</td><td>₱${parseFloat(product.total_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${product.is_low_stock == 1 ? 'Yes' : 'No'}</td><td>${product.is_expired == 1 ? 'Yes' : 'No'}</td><td>${lastUpdated}</td></tr>`;
                            });
                        });
                    });
                    html += '</table>';
                } else {
                    html += '<p style="color: #64748b; padding: 20px; text-align: center;">No products with activity found for ' + monthName + '. Products are included if they were updated or had stock transactions during this month.</p>';
                }
                // Damage Products
                if (data.damage_products && data.damage_products.length > 0) {
                    html += '<h3>Damage Products Report</h3><table><tr><th>Branch</th><th>Product</th><th>Category</th><th>Total Damaged</th><th>Incidents</th></tr>';
                    data.damage_products.forEach(damage => {
                        html += `<tr><td>${damage.branch_name || 'N/A'}</td><td>${damage.product_name || 'N/A'}</td><td>${damage.category || 'N/A'}</td><td>${damage.total_damaged || 0}</td><td>${damage.damage_count || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
            } else if (title.includes('Franchise Manager')) {
                // Applications
                if (data.applications && data.applications.length > 0) {
                    html += '<h3>Franchise Applications</h3><table><tr><th>Branch</th><th>Status</th><th>Date</th></tr>';
                    data.applications.forEach(app => {
                        html += `<tr><td>${app.branch_name || 'N/A'}</td><td>${(app.status || 'N/A').toUpperCase()}</td><td>${app.created_at ? new Date(app.created_at).toLocaleDateString() : 'N/A'}</td></tr>`;
                    });
                    html += '</table>';
                }
                // Royalties
                if (data.royalties && data.royalties.length > 0) {
                    html += '<h3>Royalty Payments</h3><table><tr><th>Branch</th><th>Gross Sales</th><th>Royalty Amount</th><th>Total Due</th><th>Status</th></tr>';
                    data.royalties.forEach(royalty => {
                        html += `<tr><td>${royalty.branch_name || 'N/A'}</td><td>₱${parseFloat(royalty.gross_sales || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>₱${parseFloat(royalty.royalty_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>₱${parseFloat(royalty.total_due || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td><td>${(royalty.status || 'N/A').toUpperCase()}</td></tr>`;
                    });
                    html += '</table>';
                }
            } else if (title.includes('Logistics Coordinator')) {
                // Delivery Summary
                if (data.delivery_summary && data.delivery_summary.length > 0) {
                    html += '<h3>Delivery Summary</h3><table><tr><th>Branch</th><th>Total Deliveries</th><th>Completed</th><th>Pending</th><th>In Transit</th></tr>';
                    data.delivery_summary.forEach(summary => {
                        html += `<tr><td>${summary.branch_name || 'N/A'}</td><td>${summary.total_deliveries || 0}</td><td>${summary.completed_deliveries || 0}</td><td>${summary.pending_deliveries || 0}</td><td>${summary.in_transit_deliveries || 0}</td></tr>`;
                    });
                    html += '</table>';
                }
            }
            
            html += '</div>';
            return html;
        }
        
        // Load reports as soon as possible
        (function() {
            function executeLoad() {
                if (typeof jQuery === 'undefined') {
                    console.log('jQuery not ready, waiting...');
                    setTimeout(executeLoad, 50);
                    return;
                }
                
                var $ = jQuery;
                console.log('jQuery available, initializing...');
                
                $(function() {
                    console.log('DOM ready, calling loadReports()');
                    try {
                        loadReports();
                        console.log('loadReports() called successfully');
                    } catch (e) {
                        console.error('Error calling loadReports:', e);
                        $('#reportsContent').html('<div class="alert alert-danger">Error: ' + e.message + '</div>');
                    }
                    
                    // Auto-load when month filter changes
                    $('#reportsMonthFilter').on('change', function() {
                        console.log('Month filter changed, reloading...');
                        loadReports();
                    });
                });
            }
            
            // Start execution
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                executeLoad();
            } else {
                document.addEventListener('DOMContentLoaded', executeLoad);
                // Also try immediately in case DOMContentLoaded already fired
                setTimeout(executeLoad, 100);
            }
        })();
    </script>
</body>
</html>
