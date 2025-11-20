<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    /* Professional gradient background */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .purchase-request-form {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        background: white;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        border: none;
        border-radius: 12px 12px 0 0;
        padding: 25px;
        color: white;
    }

    .card-header h5 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .card-body {
        padding: 30px;
    }

    /* Form controls */
    .form-label {
        font-weight: 600;
        color: #2d5016;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .form-select, .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: #4a7c2a;
        box-shadow: 0 0 0 3px rgba(74, 124, 42, 0.1);
        outline: none;
    }

    /* Item row styling */
    .item-row {
        border: 2px solid #f0f0f0;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 10px;
        background: #fafafa;
        transition: all 0.3s ease;
    }

    .item-row:hover {
        border-color: #4a7c2a;
        background: #f5f9f0;
    }

    .item-row:last-child {
        margin-bottom: 0;
    }

    /* Button styling */
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

    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #ef4444;
        border: none;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
    }

    /* Total amount styling */
    #totalAmount {
        color: #2d5016;
        font-weight: 700;
        font-size: 1.3rem;
    }

    /* Page header */
    .page-header {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        color: white;
        padding: 30px 40px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(45, 80, 22, 0.2);
    }

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    /* Container styling */
    .container-fluid {
        padding: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<div class="page-header">
    <h2><i class="fas fa-shopping-cart" style="margin-right: 12px;"></i>Create Purchase Request</h2>
    <p>Request products for your branch</p>
</div>
<?= $this->endSection() ?>

<?= $this->section('nav') ?>
<div class="container-fluid" style="padding: 0;">
    <ul class="nav nav-tabs" style="background: white; border-bottom: 2px solid #e0e0e0; border-radius: 0;">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('manager/dashboard') ?>" style="color: #666; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#" style="color: #2d5016; border-bottom: 3px solid #4a7c2a; font-weight: 600;">
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
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="branch" class="form-label">Request For Branch</label>
                            <select class="form-select" id="branch" name="branch" required>
                                <option value="">Select Branch</option>
                                <option value="BUHANGIN Branch, Davao City">BUHANGIN</option>
                                <option value="TORIL Branch, Davao City">TORIL</option>
                                <option value="LANANG Branch, Davao City">LANANG</option>
                                <option value="AGDAO Branch, Davao City">AGDAO</option>
                                <option value="MATINA Branch, Davao City">MATINA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                <button type="button" class="btn btn-sm btn-primary" id="addItemBtn" onclick="addItemRow()">
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

        <div class="d-flex justify-content-end gap-2" style="margin-top: 30px;">
            <a href="<?= base_url('manager/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
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
window.addEventListener('load', function() {
    console.log('=== Purchase Request Form Initializing ===');
    loadProducts();
});

function loadProducts() {
    const url = '<?= base_url('inventory/items') ?>';
    console.log('📦 Loading products from:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('📡 Response status:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ API Response:', data);
        
        if (data.status === 'success' && data.items && Array.isArray(data.items) && data.items.length > 0) {
            products = data.items;
            console.log(`✅ Loaded ${products.length} products`);
            console.log('First product:', JSON.stringify(products[0]));
            addItemRow();
        } else {
            console.warn('⚠️ No products returned');
            console.log('Response status:', data.status);
            console.log('Items:', data.items);
            addItemRow();
        }
    })
    .catch(error => {
        console.error('❌ Error loading products:', error);
        addItemRow();
    });
}

function addItemRow() {
    itemCount++;
    console.log('Adding item row', itemCount, 'with', products.length, 'products available');
    
    // Build product options
    let productOptions = '<option value="">Select Product</option>';
    if (products.length > 0) {
        products.forEach(function(p) {
            const productName = p.name || 'Unknown';
            const price = p.price || 0;
            productOptions += `<option value="${p.id}" data-price="${price}">${productName}</option>`;
        });
    } else {
        productOptions += '<option disabled>No products available</option>';
    }
    
    const row = `
        <div class="item-row" id="itemRow${itemCount}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Product</label>
                    <select class="form-select product-select" name="items[${itemCount}][product_id]" required>
                        ${productOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control quantity-input" name="items[${itemCount}][quantity]" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" class="form-control price-input" name="items[${itemCount}][unit_price]" step="0.01" min="0" value="0" required>
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
    document.getElementById('itemsContainer').appendChild(createElementFromHTML(row));
    
    // Auto-fill price when product selected
    const selectElement = document.querySelector(`#itemRow${itemCount} .product-select`);
    if (selectElement) {
        selectElement.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            console.log('💰 Product selected, price:', price);
            const priceInput = this.closest('.item-row').querySelector('.price-input');
            if (priceInput) {
                priceInput.value = price.toFixed(2);
                calculateTotal();
            }
        });
    }
    
    // Add event listeners for quantity and price changes
    const qtyInput = document.querySelector(`#itemRow${itemCount} .quantity-input`);
    const priceInput = document.querySelector(`#itemRow${itemCount} .price-input`);
    
    if (qtyInput) {
        qtyInput.addEventListener('change', calculateTotal);
        qtyInput.addEventListener('input', calculateTotal);
    }
    
    if (priceInput) {
        priceInput.addEventListener('change', calculateTotal);
        priceInput.addEventListener('input', calculateTotal);
    }
}

function removeItemRow(id) {
    const row = document.getElementById(`itemRow${id}`);
    if (row) {
        row.remove();
        calculateTotal();
    }
}

function createElementFromHTML(htmlString) {
    const div = document.createElement('div');
    div.innerHTML = htmlString.trim();
    return div.firstChild;
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(function(row) {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        total += subtotal;
        console.log(`Row: ${qty} x ${price} = ${subtotal}`);
    });
    console.log(`Total: ${total}`);
    document.getElementById('totalAmount').textContent = '₱' + total.toFixed(2);
}

document.getElementById('purchaseRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('📝 Submitting purchase request...');
    
    const selectedBranch = document.getElementById('branch').value;
    if (!selectedBranch) {
        alert('❌ Please select a branch');
        return;
    }
    
    const formData = {
        branch: selectedBranch,
        priority: document.getElementById('priority').value,
        notes: document.getElementById('notes').value,
        items: []
    };
    
    document.querySelectorAll('.item-row').forEach(function(row) {
        const productId = row.querySelector('.product-select').value;
        const quantity = row.querySelector('.quantity-input').value;
        const unitPrice = row.querySelector('.price-input').value;
        const notesInput = row.querySelector('input[name*="[notes]"]');
        const notes = notesInput ? notesInput.value : '';
        
        console.log(`Processing row: productId=${productId}, qty=${quantity}, price=${unitPrice}`);
        
        // Validate that we have a product selected and quantity > 0
        if (productId && productId !== '' && quantity && parseFloat(quantity) > 0) {
            formData.items.push({
                product_id: parseInt(productId),
                quantity: parseInt(quantity),
                unit_price: parseFloat(unitPrice) || 0,
                notes: notes || ''
            });
            console.log(`✅ Added item: ${productId}`);
        } else {
            console.warn(`⚠️ Skipped row: productId=${productId}, qty=${quantity}`);
        }
    });
    
    console.log(`📊 Total items collected: ${formData.items.length}`);
    
    if (formData.items.length === 0) {
        alert('❌ Please add at least one item with a product selected and quantity > 0');
        return;
    }
    
    console.log('📤 Sending data:', JSON.stringify(formData));
    
    fetch('<?= base_url('purchase/request/create') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify(formData)
    })
    .then(response => {
        console.log('📡 Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('✅ Server response:', data);
        if (data.status === 'success') {
            alert('✅ Purchase request created successfully!');
            window.location.href = '<?= base_url('manager/dashboard') ?>';
        } else {
            console.error('Server error details:', data);
            alert('❌ Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('❌ Error submitting request:', error);
        console.error('Full error:', error);
        alert('❌ Error submitting request: ' + error.message);
    });
});
</script>
<?= $this->endSection() ?>

