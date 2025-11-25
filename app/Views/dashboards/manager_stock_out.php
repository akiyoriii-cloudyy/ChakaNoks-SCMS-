<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out — Branch Manager Dashboard — CHAKANOKS SCMS</title>
    
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
        .stock-out-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .product-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
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
                <a href="<?= base_url('manager/deliveries') ?>" class="nav-item">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                    <?php if (isset($data['deliveries']['pending_deliveries']) && $data['deliveries']['pending_deliveries'] > 0): ?>
                        <span class="badge" style="background: #dc3545; color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; margin-left: auto;">
                            <?= $data['deliveries']['pending_deliveries'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="<?= base_url('manager/stock-out') ?>" class="nav-item active">
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
                        <h2 class="page-title">Stock Out</h2>
                        <p class="page-subtitle">Record stock out transactions for inventory management</p>
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
                <div class="stock-out-card">
                    <h3 style="margin-bottom: 20px; color: #2d5016; font-weight: 700;">Record Stock Out</h3>
                    
                    <form id="stockOutForm">
                        <div class="mb-3">
                            <label for="productSearch" class="form-label">Search Product</label>
                            <input type="text" class="form-control" id="productSearch" placeholder="Type product name or code to search..." autocomplete="off">
                            <div id="productSearchResults" style="position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; border-radius: 8px; max-height: 300px; overflow-y: auto; display: none; width: 100%; margin-top: 5px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);"></div>
                        </div>
                        
                        <div id="selectedProducts" style="margin-top: 20px;"></div>
                        
                        <div class="mb-3">
                            <label for="stockOutReason" class="form-label">Reason for Stock Out</label>
                            <select class="form-control" id="stockOutReason" required>
                                <option value="">Select reason...</option>
                                <option value="sale">Sale</option>
                                <option value="damaged">Damaged</option>
                                <option value="expired">Expired</option>
                                <option value="waste">Waste</option>
                                <option value="transfer">Transfer to Another Branch</option>
                                <option value="adjustment">Inventory Adjustment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="otherReasonDiv" style="display: none;">
                            <label for="otherReason" class="form-label">Specify Reason</label>
                            <input type="text" class="form-control" id="otherReason" placeholder="Enter reason...">
                        </div>
                        
                        <div class="mb-3">
                            <label for="stockOutNotes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="stockOutNotes" rows="3" placeholder="Additional notes..."></textarea>
                        </div>
                        
                        <button type="button" class="btn btn-primary" id="submitStockOutBtn" onclick="submitStockOut()" style="background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-check"></i> Record Stock Out
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedProducts = [];
        
        $('#productSearch').on('input', function() {
            const query = $(this).val();
            if (query.length < 2) {
                $('#productSearchResults').hide();
                return;
            }
            
            $.ajax({
                url: '<?= base_url('manager/api/search-products') ?>',
                method: 'GET',
                data: { q: query, branch_id: <?= $me['branch_id'] ?> },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.products.length > 0) {
                        let html = '';
                        response.products.forEach(function(product) {
                            html += '<div class="product-search-item" style="padding: 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;" onclick="selectProduct(' + product.id + ', \'' + product.name.replace(/'/g, "\\'") + '\', ' + product.stock_qty + ')">';
                            html += '<strong>' + product.name + '</strong><br>';
                            html += '<small style="color: #666;">Stock: ' + product.stock_qty + ' ' + (product.unit || '') + '</small>';
                            html += '</div>';
                        });
                        $('#productSearchResults').html(html).show();
                    } else {
                        $('#productSearchResults').html('<div style="padding: 12px; color: #999;">No products found</div>').show();
                    }
                },
                error: function() {
                    $('#productSearchResults').hide();
                }
            });
        });
        
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#productSearch, #productSearchResults').length) {
                $('#productSearchResults').hide();
            }
        });
        
        function selectProduct(productId, productName, currentStock) {
            // Check if product already selected
            if (selectedProducts.find(p => p.id === productId)) {
                alert('Product already added');
                return;
            }
            
            selectedProducts.push({
                id: productId,
                name: productName,
                currentStock: currentStock,
                quantity: 1
            });
            
            renderSelectedProducts();
            $('#productSearch').val('');
            $('#productSearchResults').hide();
        }
        
        function renderSelectedProducts() {
            let html = '<h5 style="margin-bottom: 15px; color: #2d5016;">Selected Products</h5>';
            
            if (selectedProducts.length === 0) {
                html += '<p style="color: #999; font-style: italic;">No products selected</p>';
            } else {
                selectedProducts.forEach(function(product, index) {
                    html += '<div class="product-item">';
                    html += '<div style="flex: 1;">';
                    html += '<strong>' + product.name + '</strong><br>';
                    html += '<small style="color: #666;">Available Stock: ' + product.currentStock + '</small>';
                    html += '</div>';
                    html += '<div style="display: flex; align-items: center; gap: 10px;">';
                    html += '<label style="margin: 0;">Qty:</label>';
                    html += '<input type="number" class="form-control" style="width: 80px;" min="1" max="' + product.currentStock + '" value="' + product.quantity + '" onchange="updateQuantity(' + index + ', this.value)">';
                    html += '<button type="button" class="btn btn-sm btn-danger" onclick="removeProduct(' + index + ')"><i class="fas fa-times"></i></button>';
                    html += '</div>';
                    html += '</div>';
                });
            }
            
            $('#selectedProducts').html(html);
        }
        
        function updateQuantity(index, quantity) {
            const qty = parseInt(quantity);
            if (qty > 0 && qty <= selectedProducts[index].currentStock) {
                selectedProducts[index].quantity = qty;
            } else {
                alert('Quantity must be between 1 and ' + selectedProducts[index].currentStock);
                renderSelectedProducts();
            }
        }
        
        function removeProduct(index) {
            selectedProducts.splice(index, 1);
            renderSelectedProducts();
        }
        
        $('#stockOutReason').on('change', function() {
            if ($(this).val() === 'other') {
                $('#otherReasonDiv').show();
                $('#otherReason').prop('required', true);
            } else {
                $('#otherReasonDiv').hide();
                $('#otherReason').prop('required', false);
            }
        });
        
        function submitStockOut() {
            if (selectedProducts.length === 0) {
                alert('Please select at least one product');
                return;
            }
            
            const reason = $('#stockOutReason').val();
            if (!reason) {
                alert('Please select a reason for stock out');
                return;
            }
            
            const finalReason = reason === 'other' ? $('#otherReason').val() : reason;
            if (reason === 'other' && !finalReason) {
                alert('Please specify the reason');
                return;
            }
            
            const notes = $('#stockOutNotes').val();
            
            // Prepare stock out data
            const stockOutData = selectedProducts.map(function(product) {
                return {
                    product_id: product.id,
                    quantity: product.quantity,
                    reason: finalReason,
                    notes: notes
                };
            });
            
            // Show loading state
            const submitBtn = $('#submitStockOutBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Recording...');
            
            $.ajax({
                url: '<?= base_url('manager/api/stock-out') ?>',
                method: 'POST',
                data: {
                    items: stockOutData
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Stock out recorded successfully!');
                        // Reset form
                        selectedProducts = [];
                        renderSelectedProducts();
                        $('#stockOutForm')[0].reset();
                        $('#otherReasonDiv').hide();
                    } else {
                        submitBtn.prop('disabled', false).html(originalText);
                        alert('Error: ' + (response.message || 'Failed to record stock out'));
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error recording stock out';
                    alert('Error: ' + errorMsg);
                }
            });
        }
    </script>
</body>
</html>

