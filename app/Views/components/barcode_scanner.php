<!-- Barcode Scanner Component -->
<div id="barcodeScannerModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-barcode"></i> Barcode Scanner
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Scan or Enter Barcode</label>
                    <div class="input-group">
                        <input type="text" 
                               id="barcodeInput" 
                               class="form-control" 
                               placeholder="Scan barcode or type manually"
                               autofocus
                               onkeypress="if(event.key === 'Enter') scanBarcode()">
                        <button class="btn btn-primary" onclick="scanBarcode()">
                            <i class="fas fa-search"></i> Scan
                        </button>
                    </div>
                </div>
                
                <div id="barcodeResult" style="display: none; margin-top: 20px;">
                    <div id="barcodeProductInfo"></div>
                </div>
                
                <div id="barcodeLoading" style="display: none; text-align: center; padding: 20px;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Scanning...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function openBarcodeScanner() {
    const modal = new bootstrap.Modal(document.getElementById('barcodeScannerModal'));
    modal.show();
    
    // Focus on input when modal opens
    setTimeout(() => {
        document.getElementById('barcodeInput').focus();
    }, 300);
}

function scanBarcode() {
    const barcode = document.getElementById('barcodeInput').value.trim();
    
    if (!barcode) {
        alert('Please enter or scan a barcode');
        return;
    }
    
    const loadingDiv = document.getElementById('barcodeLoading');
    const resultDiv = document.getElementById('barcodeResult');
    const productInfo = document.getElementById('barcodeProductInfo');
    
    loadingDiv.style.display = 'block';
    resultDiv.style.display = 'none';
    
    fetch('<?= base_url('barcode/scan') ?>?barcode=' + encodeURIComponent(barcode))
        .then(response => response.json())
        .then(data => {
            loadingDiv.style.display = 'none';
            resultDiv.style.display = 'block';
            
            if (data.status === 'success' && data.product) {
                const product = data.product;
                
                // Close barcode scanner modal
                const scannerModal = bootstrap.Modal.getInstance(document.getElementById('barcodeScannerModal'));
                if (scannerModal) {
                    scannerModal.hide();
                }
                
                // Open view modal automatically
                // Wait a bit for inventorystaff.js to load if needed
                setTimeout(function() {
                    if (typeof window.openViewModal === 'function') {
                        // Open view modal with product ID
                        window.openViewModal(product.id);
                    } else if (typeof openViewModal === 'function') {
                        openViewModal(product.id);
                    } else {
                    // Fallback: show product info in scanner modal
                    let html = '<div class="alert alert-success">';
                    html += '<h5>Product Found</h5>';
                    html += '<p><strong>Name:</strong> ' + escapeHtml(product.name) + '</p>';
                    html += '<p><strong>Stock:</strong> ' + product.stock_qty + ' ' + (product.unit || 'pcs') + '</p>';
                    html += '<p><strong>Branch:</strong> ' + (product.branch_name || 'N/A') + '</p>';
                    html += '<p><strong>Price:</strong> ₱' + parseFloat(product.price || 0).toFixed(2) + '</p>';
                    
                    if (product.min_stock > 0 && product.stock_qty <= product.min_stock) {
                        html += '<div class="alert alert-warning mt-2">';
                        html += '<i class="fas fa-exclamation-triangle"></i> Low Stock Alert!';
                        html += '</div>';
                    }
                    
                    html += '<div class="mt-3">';
                    html += '<button class="btn btn-primary" onclick="openViewModalFromBarcode(' + product.id + ')">';
                    html += '<i class="fas fa-eye"></i> View Product Details';
                    html += '</button>';
                    html += '</div>';
                    html += '</div>';
                    
                        productInfo.innerHTML = html;
                    }
                }, 100);
            } else if (data.status === 'not_found') {
                productInfo.innerHTML = '<div class="alert alert-info">';
                productInfo.innerHTML += '<h5>Product Not Found</h5>';
                productInfo.innerHTML += '<p>Barcode: <strong>' + escapeHtml(barcode) + '</strong></p>';
                productInfo.innerHTML += '<p>Would you like to create a new product with this barcode?</p>';
                productInfo.innerHTML += '<button class="btn btn-primary mt-2" onclick="createProductFromBarcode(\'' + escapeHtml(barcode) + '\')">';
                productInfo.innerHTML += '<i class="fas fa-plus"></i> Create Product';
                productInfo.innerHTML += '</button>';
                productInfo.innerHTML += '</div>';
            } else {
                productInfo.innerHTML = '<div class="alert alert-danger">';
                productInfo.innerHTML += '<p>Error: ' + (data.message || 'Failed to scan barcode') + '</p>';
                productInfo.innerHTML += '</div>';
            }
        })
        .catch(error => {
            loadingDiv.style.display = 'none';
            resultDiv.style.display = 'block';
            productInfo.innerHTML = '<div class="alert alert-danger">';
            productInfo.innerHTML += '<p>Error scanning barcode: ' + error + '</p>';
            productInfo.innerHTML += '</div>';
        });
}

function createProductFromBarcode(barcode) {
    const name = prompt('Enter product name:');
    if (!name) return;
    
    const stock = prompt('Enter initial stock quantity:', '0');
    if (stock === null) return;
    
    fetch('<?= base_url('barcode/create-or-update') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            barcode: barcode,
            name: name,
            stock: stock || 0,
            unit: 'pcs'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Product ' + data.action + ' successfully!');
            scanBarcode(); // Refresh scan result
        } else {
            alert('Error: ' + (data.message || 'Failed to create product'));
        }
    })
    .catch(error => {
        alert('Error: ' + error);
    });
}

// Function to open view modal from barcode scanner
function openViewModalFromBarcode(productId) {
    // Close barcode scanner modal
    const scannerModal = bootstrap.Modal.getInstance(document.getElementById('barcodeScannerModal'));
    if (scannerModal) {
        scannerModal.hide();
    }
    
    // Open view modal if function exists
    if (typeof openViewModal === 'function') {
        openViewModal(productId);
    } else {
        // Fallback: reload page to show item
        window.location.href = '<?= base_url('staff/dashboard') ?>';
    }
}

// Auto-focus and scan on barcode input
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    if (barcodeInput) {
        // Listen for barcode scanner input (usually ends with Enter)
        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                scanBarcode();
            }
        });
    }
});
</script>

