<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    .purchase-request-form {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .item-row {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    .item-row:last-child {
        margin-bottom: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<div class="container-fluid">
    <h2>Create Purchase Request</h2>
    <p class="text-muted">Request products for your branch</p>
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
                <i class="fas fa-shopping-cart"></i> New Purchase Request
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid purchase-request-form">
    <form id="purchaseRequestForm">
        <div class="card mb-3">
            <div class="card-header">
                <h5>Request Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Items</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
            <div class="card-body">
                <div id="itemsContainer">
                    <!-- Items will be added here dynamically -->
                </div>
                <div class="text-end mt-3">
                    <strong>Total Amount: <span id="totalAmount">₱0.00</span></strong>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="<?= base_url('manager/dashboard') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Submit for Approval
            </button>
        </div>
    </form>
</div>

<script>
let itemCount = 0;
let products = []; // Will be loaded via AJAX

// Load products on page load
$(document).ready(function() {
    loadProducts();
});

function loadProducts() {
    // Always load products via AJAX from inventory API
    $.get('<?= base_url('inventory/items') ?>', function(data) {
        if (data.status === 'success') {
            products = data.items || [];
            addItemRow(); // Add first row after products are loaded
        } else {
            console.error('Failed to load products for purchase request form', data);
        }
    }).fail(function(xhr) {
        console.error('Error calling inventory/items API', xhr.status, xhr.responseText);
    });
}

function addItemRow() {
    itemCount++;
    const row = `
        <div class="item-row" id="itemRow${itemCount}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select class="form-select product-select" name="items[${itemCount}][product_id]" required>
                        <option value="">Select Product</option>
                        ${products.map(p => `<option value="${p.id}" data-price="${p.price || 0}">${p.name} (${p.branch_address || 'N/A'})</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control quantity-input" name="items[${itemCount}][quantity]" min="1" required onchange="calculateTotal()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" class="form-control price-input" name="items[${itemCount}][unit_price]" step="0.01" min="0" required onchange="calculateTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Notes</label>
                    <input type="text" class="form-control" name="items[${itemCount}][notes]" placeholder="Optional">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItemRow(${itemCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#itemsContainer').append(row);
    
    // Auto-fill price when product selected
    $(`#itemRow${itemCount} .product-select`).on('change', function() {
        const price = $(this).find('option:selected').data('price');
        $(this).closest('.item-row').find('.price-input').val(price || 0);
        calculateTotal();
    });
}

function removeItemRow(id) {
    $(`#itemRow${id}`).remove();
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    $('.item-row').each(function() {
        const qty = parseFloat($(this).find('.quantity-input').val()) || 0;
        const price = parseFloat($(this).find('.price-input').val()) || 0;
        total += qty * price;
    });
    $('#totalAmount').text('₱' + total.toFixed(2));
}

$('#purchaseRequestForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        priority: $('#priority').val(),
        notes: $('#notes').val(),
        items: []
    };
    
    $('.item-row').each(function() {
        const productId = $(this).find('.product-select').val();
        const quantity = $(this).find('.quantity-input').val();
        const unitPrice = $(this).find('.price-input').val();
        const notes = $(this).find('input[name*="[notes]"]').val();
        
        if (productId && quantity && unitPrice) {
            formData.items.push({
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice,
                notes: notes || ''
            });
        }
    });
    
    if (formData.items.length === 0) {
        alert('Please add at least one item');
        return;
    }
    
    $.ajax({
        url: '<?= base_url('purchase/request/create') ?>',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            if (response.status === 'success') {
                alert('Purchase request created successfully!');
                window.location.href = '<?= base_url('manager/dashboard') ?>';
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        },
        error: function() {
            alert('Error submitting request');
        }
    });
});
</script>
<?= $this->endSection() ?>

