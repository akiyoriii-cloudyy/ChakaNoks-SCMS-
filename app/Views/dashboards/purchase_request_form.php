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
            const branch = p.branch_address || 'N/A';
            const price = p.price || 0;
            productOptions += `<option value="${p.id}" data-price="${price}">${productName} (${branch})</option>`;
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
    
    const formData = {
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

