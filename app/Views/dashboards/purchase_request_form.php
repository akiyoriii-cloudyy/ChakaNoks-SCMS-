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
?>
<div class="container-fluid" style="padding: 0;">
    <ul class="nav nav-tabs" style="background: white; border-bottom: 2px solid #e0e0e0; border-radius: 0;">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url($dashboardUrl) ?>" style="color: #666; transition: all 0.3s ease;">
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
            <a href="<?= base_url($dashboardUrl) ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Submit for Approval
            </button>
        </div>
    </form>
</div>

<!-- Category Items Data -->
<script src="<?= base_url('assets/js/categoryItems.js') ?>?v=<?= time() ?>"></script>

<script>
let itemCount = 0;
let categoryItems = typeof CHAKANOKS_CATEGORY_ITEMS !== 'undefined' ? CHAKANOKS_CATEGORY_ITEMS : {};
let inventoryProducts = []; // All inventory products with prices
let productPriceMap = {}; // Fast lookup map: { "productname_category": price }

// Initialize form on page load
window.addEventListener('load', function() {
    console.log('=== Purchase Request Form Initializing ===');
    loadInventoryProducts();
});

// Load inventory products with prices
function loadInventoryProducts() {
    const url = '<?= base_url('inventory/items') ?>';
    console.log('📦 Loading inventory products from:', url);
    
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
            inventoryProducts = data.items;
            console.log(`✅ Loaded ${inventoryProducts.length} inventory products`);
            console.log('Sample product:', JSON.stringify(inventoryProducts[0]));
            
            // Build comprehensive price lookup map for faster access
            // Store ALL products with ALL variations, prioritizing non-zero prices
            productPriceMap = {};
            inventoryProducts.forEach(function(product) {
                const name = (product.name || '').trim().toLowerCase().replace(/\s+/g, ' ');
                const category = (product.category || '').trim().toLowerCase();
                const price = parseFloat(product.price) || 0;
                
                if (name) {
                    // Store by exact name (prefer higher price if duplicate)
                    if (!productPriceMap[name] || (price > 0 && productPriceMap[name] === 0)) {
                        productPriceMap[name] = price;
                    } else if (price > productPriceMap[name]) {
                        productPriceMap[name] = price;
                    }
                    
                    // Store by name_category for category-specific lookup
                    if (category) {
                        const key = `${name}_${category}`;
                        if (!productPriceMap[key] || (price > 0 && productPriceMap[key] === 0)) {
                            productPriceMap[key] = price;
                        } else if (price > productPriceMap[key]) {
                            productPriceMap[key] = price;
                        }
                    }
                    
                    // Store variations: without parentheses
                    const nameWithoutParens = name.replace(/\([^)]*\)/g, '').trim();
                    if (nameWithoutParens && nameWithoutParens !== name && nameWithoutParens.length > 0) {
                        if (!productPriceMap[nameWithoutParens] || (price > 0 && productPriceMap[nameWithoutParens] === 0)) {
                            productPriceMap[nameWithoutParens] = price;
                        } else if (price > productPriceMap[nameWithoutParens]) {
                            productPriceMap[nameWithoutParens] = price;
                        }
                        if (category) {
                            const key = `${nameWithoutParens}_${category}`;
                            if (!productPriceMap[key] || (price > 0 && productPriceMap[key] === 0)) {
                                productPriceMap[key] = price;
                            } else if (price > productPriceMap[key]) {
                                productPriceMap[key] = price;
                            }
                        }
                    }
                    
                    // Store first word only (for partial matches like "Lemon" matching "Lemon Juice")
                    const firstWord = name.split(/\s+/)[0];
                    if (firstWord && firstWord.length > 2 && firstWord !== name) {
                        if (!productPriceMap[firstWord] || (price > 0 && productPriceMap[firstWord] === 0)) {
                            productPriceMap[firstWord] = price;
                        }
                        if (category) {
                            const key = `${firstWord}_${category}`;
                            if (!productPriceMap[key] || (price > 0 && productPriceMap[key] === 0)) {
                                productPriceMap[key] = price;
                            }
                        }
                    }
                }
            });
            console.log(`📊 Built comprehensive price map with ${Object.keys(productPriceMap).length} entries`);
            console.log(`📊 Sample entries:`, Object.keys(productPriceMap).slice(0, 10));
            console.log(`📊 Products with prices > 0:`, inventoryProducts.filter(p => parseFloat(p.price) > 0).length);
            
            // Debug: Log all products grouped by category
            const productsByCategory = {};
            inventoryProducts.forEach(function(p) {
                const cat = (p.category || 'Uncategorized').toLowerCase();
                if (!productsByCategory[cat]) {
                    productsByCategory[cat] = [];
                }
                productsByCategory[cat].push({
                    name: p.name,
                    price: parseFloat(p.price) || 0
                });
            });
            console.log(`📊 Products by category:`, productsByCategory);
            
            // Update all existing dropdowns with prices now that products are loaded
            setTimeout(function() {
                updateAllProductPrices();
            }, 100);
        } else {
            console.warn('⚠️ No products returned');
            inventoryProducts = [];
            productPriceMap = {};
        }
        addItemRow();
    })
    .catch(error => {
        console.error('❌ Error loading inventory products:', error);
        inventoryProducts = [];
        addItemRow();
    });
}

// Find product price by name and category with multiple matching strategies
function findProductPrice(productName, category) {
    if (!productName) {
        return 0;
    }
    
    // Ensure products are loaded
    if (inventoryProducts.length === 0) {
        console.log(`⚠️ No inventory products loaded yet`);
        return 0;
    }
    
    const productNameLower = productName.trim().toLowerCase();
    const categoryLower = category ? category.trim().toLowerCase() : '';
    
    // Normalize product name - remove extra spaces, handle special characters
    const normalizedName = productNameLower.replace(/\s+/g, ' ').trim();
    
    // Strategy 1: Fast lookup from price map (exact match with category)
    if (categoryLower) {
        const categoryKey = `${normalizedName}_${categoryLower}`;
        if (productPriceMap.hasOwnProperty(categoryKey)) {
            const price = productPriceMap[categoryKey];
            console.log(`✅ [MAP] Found price (with category): "${productName}" = ₱${price.toFixed(2)}`);
            return price;
        }
    }
    
    // Strategy 2: Fast lookup from price map (exact name match)
    if (productPriceMap.hasOwnProperty(normalizedName)) {
        const price = productPriceMap[normalizedName];
        console.log(`✅ [MAP] Found price (name only): "${productName}" = ₱${price.toFixed(2)}`);
        return price;
    }
    
    // Strategy 2.5: Try name without parentheses (e.g., "Eggs (Dozen)" -> "Eggs")
    const nameWithoutParens = normalizedName.replace(/\([^)]*\)/g, '').trim();
    if (nameWithoutParens && nameWithoutParens !== normalizedName) {
        if (categoryLower && productPriceMap.hasOwnProperty(`${nameWithoutParens}_${categoryLower}`)) {
            const price = productPriceMap[`${nameWithoutParens}_${categoryLower}`];
            console.log(`✅ [MAP] Found price (without parens, with category): "${productName}" = ₱${price.toFixed(2)}`);
            return price;
        }
        if (productPriceMap.hasOwnProperty(nameWithoutParens)) {
            const price = productPriceMap[nameWithoutParens];
            console.log(`✅ [MAP] Found price (without parens): "${productName}" = ₱${price.toFixed(2)}`);
            return price;
        }
    }
    
    // Strategy 3: Fallback to array search if map lookup fails
    if (inventoryProducts.length === 0) {
        console.log(`⚠️ No inventory products available`);
        return 0;
    }
    
    // Strategy 4: Exact match with category (case-insensitive, trimmed)
    if (categoryLower) {
        const exactMatchWithCategory = inventoryProducts.find(function(product) {
            const prodName = (product.name || '').trim().toLowerCase().replace(/\s+/g, ' ');
            const prodCategory = (product.category || '').trim().toLowerCase();
            return prodName === normalizedName && prodCategory === categoryLower;
        });
        
        if (exactMatchWithCategory) {
            const price = parseFloat(exactMatchWithCategory.price) || 0;
            // Return price even if 0 (product exists in inventory)
            console.log(`✅ [ARRAY] Found exact match with category: ${exactMatchWithCategory.name} = ₱${price.toFixed(2)}`);
            return price;
        }
        
        // Also try without parentheses
        if (nameWithoutParens && nameWithoutParens !== normalizedName) {
            const exactMatchWithoutParens = inventoryProducts.find(function(product) {
                const prodName = (product.name || '').trim().toLowerCase().replace(/\s+/g, ' ').replace(/\([^)]*\)/g, '').trim();
                const prodCategory = (product.category || '').trim().toLowerCase();
                return prodName === nameWithoutParens && prodCategory === categoryLower;
            });
            
            if (exactMatchWithoutParens) {
                const price = parseFloat(exactMatchWithoutParens.price) || 0;
                console.log(`✅ [ARRAY] Found match without parens (with category): ${exactMatchWithoutParens.name} = ₱${price.toFixed(2)}`);
                return price;
            }
        }
    }
    
    // Strategy 5: Exact name match (regardless of category) - normalized
    const exactNameMatch = inventoryProducts.find(function(product) {
        const prodName = (product.name || '').trim().toLowerCase().replace(/\s+/g, ' ');
        return prodName === normalizedName;
    });
    
    if (exactNameMatch) {
        const price = parseFloat(exactNameMatch.price) || 0;
        // Return price even if 0 (product exists in inventory)
        console.log(`✅ [ARRAY] Found exact name match: ${exactNameMatch.name} = ₱${price.toFixed(2)}`);
        return price;
    }
    
    // Also try exact match without parentheses
    if (nameWithoutParens && nameWithoutParens !== normalizedName) {
        const exactNameMatchNoParens = inventoryProducts.find(function(product) {
            const prodName = (product.name || '').trim().toLowerCase().replace(/\s+/g, ' ').replace(/\([^)]*\)/g, '').trim();
            return prodName === nameWithoutParens;
        });
        
        if (exactNameMatchNoParens) {
            const price = parseFloat(exactNameMatchNoParens.price) || 0;
            console.log(`✅ [ARRAY] Found exact name match (without parens): ${exactNameMatchNoParens.name} = ₱${price.toFixed(2)}`);
            return price;
        }
    }
    
    // Strategy 5.5: Try first word match (e.g., "Lemon" matching "Lemon Juice")
    const firstWord = normalizedName.split(/\s+/)[0];
    if (firstWord && firstWord.length > 2) {
        // Try with category first
        if (categoryLower && productPriceMap.hasOwnProperty(`${firstWord}_${categoryLower}`)) {
            const price = productPriceMap[`${firstWord}_${categoryLower}`];
            console.log(`✅ [MAP] Found first word match (with category): "${productName}" via "${firstWord}" = ₱${price.toFixed(2)}`);
            return price;
        }
        // Try without category
        if (productPriceMap.hasOwnProperty(firstWord)) {
            const price = productPriceMap[firstWord];
            console.log(`✅ [MAP] Found first word match: "${productName}" via "${firstWord}" = ₱${price.toFixed(2)}`);
            return price;
        }
        
        // Try array search for first word
        const firstWordMatch = inventoryProducts.find(function(product) {
            const prodName = (product.name || '').trim().toLowerCase();
            return prodName.startsWith(firstWord + ' ') || prodName === firstWord;
        });
        
        if (firstWordMatch) {
            const price = parseFloat(firstWordMatch.price) || 0;
            console.log(`✅ [ARRAY] Found first word match: ${firstWordMatch.name} = ₱${price.toFixed(2)}`);
            return price;
        }
    }
    
    // Strategy 6: Partial match - product name contains search term or vice versa
    // Try to find the best match (prefer exact starts with, then contains)
    let partialMatch = null;
    let bestMatchType = '';
    
    inventoryProducts.forEach(function(product) {
        const prodName = (product.name || '').trim().toLowerCase();
        const prodPrice = parseFloat(product.price) || 0;
        
        // Prefer products with prices > 0
        if (prodPrice === 0 && partialMatch && parseFloat(partialMatch.price) > 0) {
            return; // Skip products with 0 price if we already have one with price
        }
        
        // Exact match
        if (prodName === normalizedName) {
            if (!partialMatch || prodPrice > parseFloat(partialMatch.price || 0)) {
                partialMatch = product;
                bestMatchType = 'exact';
            }
        }
        // Starts with match (e.g., "Lemon" matching "Lemon Juice")
        else if (prodName.startsWith(normalizedName + ' ') || prodName.startsWith(normalizedName + '(')) {
            if (!partialMatch || bestMatchType !== 'exact') {
                if (bestMatchType !== 'starts' || prodPrice > parseFloat(partialMatch.price || 0)) {
                    partialMatch = product;
                    bestMatchType = 'starts';
                }
            }
        }
        // Contains match
        else if (prodName.includes(normalizedName) || normalizedName.includes(prodName)) {
            if (!partialMatch || (bestMatchType !== 'exact' && bestMatchType !== 'starts')) {
                if (bestMatchType !== 'contains' || prodPrice > parseFloat(partialMatch.price || 0)) {
                    partialMatch = product;
                    bestMatchType = 'contains';
                }
            }
        }
    });
    
    if (partialMatch) {
        const price = parseFloat(partialMatch.price) || 0;
        console.log(`✅ [ARRAY] Found ${bestMatchType} partial match: ${partialMatch.name} = ₱${price.toFixed(2)}`);
        return price;
    }
    
    // Strategy 7: Word-based fuzzy match (for cases like "Eggs (Dozen)" vs "Eggs")
    const words = normalizedName.split(/[\s\(\)\-]+/).filter(w => w.length > 2);
    if (words.length > 0) {
        // Try to find best match by counting matching words
        let bestMatch = null;
        let bestMatchScore = 0;
        
        inventoryProducts.forEach(function(product) {
            const prodName = (product.name || '').trim().toLowerCase();
            const prodWords = prodName.split(/[\s\(\)\-]+/).filter(w => w.length > 2);
            
            // Count matching words
            let matchScore = 0;
            words.forEach(word => {
                if (prodWords.includes(word) || prodName.includes(word)) {
                    matchScore++;
                }
            });
            
            // If more than half the words match, consider it a good match
            if (matchScore > bestMatchScore && matchScore >= Math.ceil(words.length / 2)) {
                bestMatch = product;
                bestMatchScore = matchScore;
            }
        });
        
        if (bestMatch) {
            const price = parseFloat(bestMatch.price) || 0;
            // Return price even if 0 (product exists in inventory)
            console.log(`✅ [ARRAY] Found fuzzy match: ${bestMatch.name} = ₱${price.toFixed(2)}`);
            return price;
        }
    }
    
    // Strategy 8: Try all products and find closest match by Levenshtein-like similarity
    let closestMatch = null;
    let closestDistance = Infinity;
    const searchWords = normalizedName.split(/[\s\(\)\-]+/).filter(w => w.length > 1);
    
    if (searchWords.length > 0) {
        inventoryProducts.forEach(function(product) {
            const prodName = (product.name || '').trim().toLowerCase();
            const prodWords = prodName.split(/[\s\(\)\-]+/).filter(w => w.length > 1);
            
            // Calculate similarity score
            let matchCount = 0;
            searchWords.forEach(searchWord => {
                prodWords.forEach(prodWord => {
                    if (prodWord === searchWord || prodWord.includes(searchWord) || searchWord.includes(prodWord)) {
                        matchCount++;
                    }
                });
            });
            
            // If most words match, consider it
            if (matchCount > 0 && matchCount >= Math.ceil(searchWords.length * 0.6)) {
                const distance = Math.abs(searchWords.length - prodWords.length);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestMatch = product;
                }
            }
        });
        
        if (closestMatch) {
            const price = parseFloat(closestMatch.price) || 0;
            console.log(`✅ [ARRAY] Found closest match: ${closestMatch.name} = ₱${price.toFixed(2)}`);
            return price;
        }
    }
    
    console.log(`❌ No price found for "${productName}" in category "${category}"`);
    console.log(`   Searched ${inventoryProducts.length} products`);
    console.log(`   Normalized name: "${normalizedName}"`);
    console.log(`   Available products sample:`, inventoryProducts.slice(0, 10).map(p => `${p.name} (${p.category || 'no cat'}) - ₱${(parseFloat(p.price) || 0).toFixed(2)}`));
    
    // Show products in the same category for debugging
    if (category) {
        const categoryLower = category.toLowerCase();
        const categoryProducts = inventoryProducts.filter(p => {
            const cat = (p.category || '').toLowerCase();
            return cat === categoryLower;
        });
        if (categoryProducts.length > 0) {
            console.log(`   Products in category "${category}":`, categoryProducts.map(p => `${p.name} - ₱${(parseFloat(p.price) || 0).toFixed(2)}`).slice(0, 20));
        } else {
            console.log(`   No products found in category "${category}"`);
        }
    }
    
    return 0;
}

// Update item options based on selected category for a specific row
function updateItemOptionsForRow(rowId) {
    const row = document.getElementById(`itemRow${rowId}`);
    if (!row) return;
    
    const categorySelect = row.querySelector('.category-select');
    const itemSelect = row.querySelector('.item-name-select');
    const customNameInput = row.querySelector('.custom-name-input');
    const useCustomCheckbox = row.querySelector('.use-custom-checkbox');
    const priceInput = row.querySelector('.price-input');
    const unitSelect = row.querySelector('.unit-select');
    
    if (!categorySelect || !itemSelect) return;
    
    const selectedCategory = categorySelect.value;
    
    // Clear item options
    itemSelect.innerHTML = '<option value="">Select Product</option>';
    
    // Update unit options based on category
    if (unitSelect && selectedCategory) {
        updateUnitOptionsForCategory(unitSelect, selectedCategory);
    }
    
    // Reset custom name input and price
    if (customNameInput) {
        customNameInput.style.display = 'none';
        customNameInput.value = '';
        customNameInput.required = false;
    }
    if (useCustomCheckbox) {
        useCustomCheckbox.checked = false;
    }
    if (priceInput) {
        priceInput.value = '0';
        calculateTotal();
    }
    
    if (selectedCategory && categoryItems[selectedCategory]) {
        // Populate items from categoryItems.js
        categoryItems[selectedCategory].forEach(function(item) {
            const option = document.createElement('option');
            option.value = item;
            option.textContent = item;
            
            // IMMEDIATELY look up and store price in data attribute
            // Try multiple strategies to find the price
            let itemPrice = 0;
            
            if (inventoryProducts.length > 0) {
                // Strategy 1: Direct lookup
                itemPrice = findProductPrice(item, selectedCategory);
                
                // Strategy 2: Try without category
                if (itemPrice === 0) {
                    itemPrice = findProductPrice(item, '');
                }
                
                // Strategy 3: Try name variations
                if (itemPrice === 0) {
                    const nameWithoutParens = item.replace(/\([^)]*\)/g, '').trim();
                    if (nameWithoutParens && nameWithoutParens !== item) {
                        itemPrice = findProductPrice(nameWithoutParens, selectedCategory);
                        if (itemPrice === 0) {
                            itemPrice = findProductPrice(nameWithoutParens, '');
                        }
                    }
                }
                
                // Strategy 4: Try first word
                if (itemPrice === 0) {
                    const firstWord = item.split(/\s+/)[0];
                    if (firstWord && firstWord.length > 2) {
                        itemPrice = findProductPrice(firstWord, selectedCategory);
                        if (itemPrice === 0) {
                            itemPrice = findProductPrice(firstWord, '');
                        }
                    }
                }
                
                // Store price in data attribute (even if 0, so we know we tried)
                if (itemPrice > 0) {
                    option.setAttribute('data-price', itemPrice.toFixed(2));
                    console.log(`💰 Pre-loaded price for "${item}": ₱${itemPrice.toFixed(2)}`);
                }
            }
            
            itemSelect.appendChild(option);
        });
        
        // Add "Other" option for custom items
        const otherOption = document.createElement('option');
        otherOption.value = '__other__';
        otherOption.textContent = '-- Other (Custom Item) --';
        itemSelect.appendChild(otherOption);
        
        // If there's already a selected product, try to update its price immediately
        const currentSelection = itemSelect.value;
        if (currentSelection && currentSelection !== '__other__' && priceInput && inventoryProducts.length > 0) {
            // Re-search for price when category changes using all strategies
            let productPrice = findProductPrice(currentSelection, selectedCategory);
            if (productPrice === 0) {
                productPrice = findProductPrice(currentSelection, '');
            }
            if (productPrice === 0) {
                const nameWithoutParens = currentSelection.replace(/\([^)]*\)/g, '').trim();
                if (nameWithoutParens && nameWithoutParens !== currentSelection) {
                    productPrice = findProductPrice(nameWithoutParens, selectedCategory);
                    if (productPrice === 0) {
                        productPrice = findProductPrice(nameWithoutParens, '');
                    }
                }
            }
            if (productPrice > 0) {
                priceInput.value = productPrice.toFixed(2);
                calculateTotal();
            }
        }
    }
}

// Get relevant unit options for a product/category
function getRelevantUnitOptions(productName, category) {
    const nameLower = productName.toLowerCase();
    let allowedUnits = [];
    let defaultUnit = 'pcs';
    
    // First, try to find exact match in inventory products
    for (let product of inventoryProducts) {
        if (product.name === productName && product.unit) {
            // Return the product's unit as primary, plus common alternatives
            const productUnit = product.unit;
            allowedUnits = getUnitAlternatives(productUnit, category);
            return { units: allowedUnits, default: productUnit };
        }
    }
    
    // Beverages - bottles, cans, liters
    if (category === 'Beverages' || nameLower.includes('cola') || nameLower.includes('pepsi') || 
        nameLower.includes('juice') || nameLower.includes('water') || nameLower.includes('soda')) {
        allowedUnits = ['bottles', 'cans', 'liters', 'pcs'];
        defaultUnit = nameLower.includes('can') ? 'cans' : 'bottles';
    }
    
    // Condiments/Sauces - bottles, liters, packs
    else if (category === 'Condiments' || nameLower.includes('sauce') || nameLower.includes('vinegar') || 
        nameLower.includes('ketchup') || nameLower.includes('soy sauce')) {
        allowedUnits = ['bottles', 'liters', 'packs', 'pcs'];
        defaultUnit = 'bottles';
    }
    
    // Cooking Oil - liters, bottles
    else if (category === 'Cooking Oil' || nameLower.includes('oil') || nameLower.includes('cooking')) {
        allowedUnits = ['liters', 'bottles', 'pcs'];
        defaultUnit = 'liters';
    }
    
    // Seasonings/Spices - packs, kg, pcs
    else if (category === 'Seasonings' || category === 'Spices' || nameLower.includes('salt') || 
        nameLower.includes('pepper') || nameLower.includes('garlic') || nameLower.includes('msg')) {
        allowedUnits = ['packs', 'kg', 'pcs', 'bags'];
        defaultUnit = 'packs';
    }
    
    // Rice - kg, sacks, bags
    else if (category === 'Rice' || nameLower.includes('rice')) {
        allowedUnits = ['kg', 'sacks', 'bags', 'pcs'];
        defaultUnit = nameLower.includes('sack') ? 'sacks' : 'kg';
    }
    
    // Vegetables/Fruits - kg, pcs, bags
    else if (category === 'Vegetables' || category === 'Fruits' || nameLower.includes('vegetable') || nameLower.includes('fruit')) {
        allowedUnits = ['kg', 'pcs', 'bags', 'boxes'];
        defaultUnit = 'kg';
    }
    
    // Bread/Bakery - pcs, loaves, dozen, packs
    else if (category === 'Bread' || category === 'Bakery' || nameLower.includes('bread') || nameLower.includes('pandesal')) {
        allowedUnits = ['pcs', 'loaves', 'dozen', 'packs'];
        defaultUnit = nameLower.includes('loaf') ? 'loaves' : 'pcs';
    }
    
    // Dairy - liters, pcs, packs, kg
    else if (category === 'Dairy' || nameLower.includes('milk') || nameLower.includes('cheese') || nameLower.includes('butter')) {
        allowedUnits = ['liters', 'pcs', 'packs', 'kg'];
        defaultUnit = nameLower.includes('liter') || nameLower.includes('liquid') ? 'liters' : 'pcs';
    }
    
    // Frozen Foods - kg, packs, boxes, pcs
    else if (category === 'Frozen Foods' || nameLower.includes('frozen') || nameLower.includes('chicken')) {
        allowedUnits = ['kg', 'packs', 'boxes', 'pcs'];
        defaultUnit = nameLower.includes('pack') || nameLower.includes('box') ? 'packs' : 'kg';
    }
    
    // Packaging - pcs, rolls, packs, boxes
    else if (category === 'Packaging' || nameLower.includes('bag') || nameLower.includes('container') || nameLower.includes('cup')) {
        allowedUnits = ['pcs', 'rolls', 'packs', 'boxes'];
        defaultUnit = nameLower.includes('roll') ? 'rolls' : 'pcs';
    }
    
    // Cleaning Supplies - liters, bottles, pcs, packs
    else if (category === 'Cleaning Supplies' || nameLower.includes('cleaning') || nameLower.includes('detergent')) {
        allowedUnits = ['liters', 'bottles', 'pcs', 'packs'];
        defaultUnit = nameLower.includes('liquid') || nameLower.includes('bleach') ? 'liters' : 'pcs';
    }
    
    // Default - all common units
    else {
        allowedUnits = ['pcs', 'kg', 'liters', 'packs', 'boxes', 'bottles', 'cans', 'bags', 'rolls', 'dozen', 'sacks', 'loaves'];
        defaultUnit = 'pcs';
    }
    
    return { units: allowedUnits, default: defaultUnit };
}

// Get alternative units for a given primary unit
function getUnitAlternatives(primaryUnit, category) {
    const alternatives = {
        'bottles': ['bottles', 'cans', 'liters', 'pcs'],
        'cans': ['cans', 'bottles', 'pcs'],
        'liters': ['liters', 'bottles', 'pcs'],
        'kg': ['kg', 'pcs', 'bags', 'sacks'],
        'packs': ['packs', 'boxes', 'pcs', 'dozen'],
        'boxes': ['boxes', 'packs', 'pcs'],
        'pcs': ['pcs', 'dozen', 'packs'],
        'bags': ['bags', 'pcs', 'kg'],
        'rolls': ['rolls', 'pcs'],
        'dozen': ['dozen', 'pcs', 'packs'],
        'sacks': ['sacks', 'kg', 'bags'],
        'loaves': ['loaves', 'pcs', 'dozen']
    };
    
    return alternatives[primaryUnit] || ['pcs', 'kg', 'liters', 'packs', 'boxes', 'bottles'];
}

// Update unit dropdown options based on category only
function updateUnitOptionsForCategory(unitSelect, category) {
    if (!unitSelect) return;
    
    console.log(`📦 Updating unit options for category: "${category}"`);
    
    let allowedUnits = [];
    let defaultUnit = 'pcs';
    
    // Get category-appropriate units
    if (category === 'Beverages') {
        allowedUnits = ['bottles', 'cans', 'liters', 'pcs'];
        defaultUnit = 'bottles';
    } else if (category === 'Condiments') {
        allowedUnits = ['bottles', 'liters', 'packs', 'pcs'];
        defaultUnit = 'bottles';
    } else if (category === 'Cooking Oil') {
        allowedUnits = ['liters', 'bottles', 'pcs'];
        defaultUnit = 'liters';
    } else if (category === 'Seasonings' || category === 'Spices') {
        allowedUnits = ['packs', 'kg', 'pcs', 'bags'];
        defaultUnit = 'packs';
    } else if (category === 'Rice') {
        allowedUnits = ['kg', 'sacks', 'bags', 'pcs'];
        defaultUnit = 'kg';
    } else if (category === 'Vegetables' || category === 'Fruits') {
        allowedUnits = ['kg', 'pcs', 'bags', 'boxes'];
        defaultUnit = 'kg';
    } else if (category === 'Bread' || category === 'Bakery') {
        allowedUnits = ['pcs', 'loaves', 'dozen', 'packs'];
        defaultUnit = 'pcs';
    } else if (category === 'Dairy') {
        allowedUnits = ['liters', 'pcs', 'packs', 'kg'];
        defaultUnit = 'pcs';
    } else if (category === 'Frozen Foods') {
        allowedUnits = ['kg', 'packs', 'boxes', 'pcs'];
        defaultUnit = 'kg';
    } else if (category === 'Packaging') {
        allowedUnits = ['pcs', 'rolls', 'packs', 'boxes'];
        defaultUnit = 'pcs';
    } else if (category === 'Cleaning Supplies') {
        allowedUnits = ['liters', 'bottles', 'pcs', 'packs'];
        defaultUnit = 'pcs';
    } else {
        // Default all units
        allowedUnits = ['pcs', 'kg', 'liters', 'packs', 'boxes', 'bottles', 'cans', 'bags', 'rolls', 'dozen', 'sacks', 'loaves'];
        defaultUnit = 'pcs';
    }
    
    const currentValue = unitSelect.value;
    
    // Clear and repopulate
    unitSelect.innerHTML = '';
    allowedUnits.forEach(function(unit) {
        const option = document.createElement('option');
        option.value = unit;
        option.textContent = unit;
        unitSelect.appendChild(option);
    });
    
    // Set value
    if (allowedUnits.includes(currentValue)) {
        unitSelect.value = currentValue;
    } else {
        unitSelect.value = defaultUnit;
    }
}

// Update unit dropdown options for a specific product
function updateUnitOptionsForProduct(unitSelect, productName, category) {
    if (!unitSelect) return;
    
    console.log(`📦 Updating unit options for: "${productName}" in category: "${category}"`);
    
    const { units, default: defaultUnit } = getRelevantUnitOptions(productName, category);
    const currentValue = unitSelect.value;
    
    // Clear existing options
    unitSelect.innerHTML = '';
    
    // Add relevant unit options
    units.forEach(function(unit) {
        const option = document.createElement('option');
        option.value = unit;
        option.textContent = unit;
        unitSelect.appendChild(option);
    });
    
    // Set the value - prefer keeping current if valid, otherwise use default
    if (units.includes(currentValue)) {
        unitSelect.value = currentValue;
        console.log(`✅ Kept current unit: ${currentValue}`);
    } else {
        unitSelect.value = defaultUnit;
        console.log(`✅ Set default unit: ${defaultUnit}`);
    }
}

// Update all product prices in all existing rows
function updateAllProductPrices() {
    console.log('🔄 Updating prices for all existing product selections...');
    let updatedCount = 0;
    document.querySelectorAll('.item-row').forEach(function(row) {
        const itemSelect = row.querySelector('.item-name-select');
        const categorySelect = row.querySelector('.category-select');
        const priceInput = row.querySelector('.price-input');
        
        if (itemSelect && categorySelect && priceInput) {
            const selectedProduct = itemSelect.value;
            const selectedCategory = categorySelect.value;
            
            if (selectedProduct && selectedProduct !== '__other__') {
                // Try multiple strategies
                let productPrice = findProductPrice(selectedProduct, selectedCategory);
                
                if (productPrice === 0) {
                    productPrice = findProductPrice(selectedProduct, '');
                }
                
                if (productPrice === 0) {
                    const nameWithoutParens = selectedProduct.replace(/\([^)]*\)/g, '').trim();
                    if (nameWithoutParens && nameWithoutParens !== selectedProduct) {
                        productPrice = findProductPrice(nameWithoutParens, selectedCategory);
                        if (productPrice === 0) {
                            productPrice = findProductPrice(nameWithoutParens, '');
                        }
                    }
                }
                
                if (productPrice > 0) {
                    priceInput.value = productPrice.toFixed(2);
                    console.log(`✅ Updated price for "${selectedProduct}": ₱${productPrice.toFixed(2)}`);
                    updatedCount++;
                    calculateTotal();
                } else {
                    console.log(`⚠️ Could not find price for "${selectedProduct}"`);
                }
            }
        }
    });
    console.log(`✅ Updated ${updatedCount} product prices`);
}


function addItemRow() {
    itemCount++;
    console.log('Adding item row', itemCount);
    
    // Build category options
    let categoryOptions = '<option value="">Select Category</option>';
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            categoryOptions += '<option value="<?= esc($category['name']) ?>"><?= esc($category['name']) ?></option>';
        <?php endforeach; ?>
    <?php endif; ?>
    
    const row = `
        <div class="item-row" id="itemRow${itemCount}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select category-select" name="items[${itemCount}][category]" required onchange="updateItemOptionsForRow(${itemCount})">
                        ${categoryOptions}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Product Name</label>
                    <select class="form-select item-name-select" name="items[${itemCount}][item_name]" required onchange="handleItemNameChange(${itemCount})">
                        <option value="">Select Category First</option>
                    </select>
                    <input type="text" class="form-control custom-name-input" name="items[${itemCount}][custom_name]" placeholder="Or enter custom product name..." style="margin-top: 8px; display: none;" oninput="handleCustomNameChange(${itemCount})">
                    <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px; font-size: 0.875rem; color: #6b7280; cursor: pointer;">
                        <input type="checkbox" class="use-custom-checkbox" onchange="toggleCustomName(${itemCount})"> Enter custom product name
                    </label>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control quantity-input" name="items[${itemCount}][quantity]" min="1" value="1" required oninput="calculateTotal()">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Unit</label>
                    <select class="form-select unit-select" name="items[${itemCount}][unit]" required style="font-weight: 600;">
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="liters">liters</option>
                        <option value="packs">packs</option>
                        <option value="boxes">boxes</option>
                        <option value="bottles">bottles</option>
                        <option value="cans">cans</option>
                        <option value="bags">bags</option>
                        <option value="rolls">rolls</option>
                        <option value="dozen">dozen</option>
                        <option value="sacks">sacks</option>
                        <option value="loaves">loaves</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" class="form-control price-input" name="items[${itemCount}][unit_price]" step="0.01" min="0" value="0" required oninput="calculateTotal()">
                </div>
                <div class="col-md-1">
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

function handleItemNameChange(rowId) {
    const row = document.getElementById(`itemRow${rowId}`);
    if (!row) return;
    
    const categorySelect = row.querySelector('.category-select');
    const itemSelect = row.querySelector('.item-name-select');
    const customInput = row.querySelector('.custom-name-input');
    const useCustomCheckbox = row.querySelector('.use-custom-checkbox');
    const priceInput = row.querySelector('.price-input');
    const unitSelect = row.querySelector('.unit-select');
    
    if (!itemSelect) return;
    
    const selectedValue = itemSelect.value;
    const selectedCategory = categorySelect ? categorySelect.value : '';
    
    console.log(`🔄 Product changed: "${selectedValue}" in category "${selectedCategory}"`);
    
    // Update unit dropdown options based on selected product/category
    if (unitSelect && selectedValue && selectedValue !== '__other__') {
        updateUnitOptionsForProduct(unitSelect, selectedValue, selectedCategory);
    }
    
    // If "Other" is selected, show custom input
    if (selectedValue === '__other__') {
        if (customInput) {
            customInput.style.display = 'block';
            customInput.required = true;
        }
        if (useCustomCheckbox) {
            useCustomCheckbox.checked = true;
        }
        // Clear price when "Other" is selected
        if (priceInput) {
            priceInput.value = '0';
            calculateTotal();
        }
    } else {
        if (customInput) {
            customInput.style.display = 'none';
            customInput.required = false;
            customInput.value = '';
        }
        if (useCustomCheckbox) {
            useCustomCheckbox.checked = false;
        }
        
        // Always search for price immediately - use ALL strategies
        if (selectedValue && selectedValue !== '' && priceInput) {
            console.log(`🔍 Searching for price: "${selectedValue}" in category "${selectedCategory}"`);
            console.log(`📊 Inventory products loaded: ${inventoryProducts.length}`);
            console.log(`📊 Price map entries: ${Object.keys(productPriceMap).length}`);
            
            // Strategy 1: Check data attribute first (pre-loaded when option was created)
            let productPrice = 0;
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            if (selectedOption && selectedOption.hasAttribute('data-price')) {
                productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                if (productPrice > 0) {
                    console.log(`✅ Using pre-loaded price from data attribute: ₱${productPrice.toFixed(2)}`);
                }
            }
            
            // Strategy 2: Search with category
            if (productPrice === 0) {
                productPrice = findProductPrice(selectedValue, selectedCategory);
            }
            
            // Strategy 3: Search without category
            if (productPrice === 0) {
                console.log(`⚠️ Retrying search without category...`);
                productPrice = findProductPrice(selectedValue, '');
            }
            
            // Strategy 4: Try name without parentheses
            if (productPrice === 0) {
                const nameWithoutParens = selectedValue.replace(/\([^)]*\)/g, '').trim();
                if (nameWithoutParens && nameWithoutParens !== selectedValue) {
                    console.log(`⚠️ Retrying with name variation: "${nameWithoutParens}"`);
                    productPrice = findProductPrice(nameWithoutParens, selectedCategory);
                    if (productPrice === 0) {
                        productPrice = findProductPrice(nameWithoutParens, '');
                    }
                }
            }
            
            // Strategy 5: Try first word only
            if (productPrice === 0) {
                const firstWord = selectedValue.split(/\s+/)[0];
                if (firstWord && firstWord.length > 2) {
                    console.log(`⚠️ Retrying with first word: "${firstWord}"`);
                    productPrice = findProductPrice(firstWord, selectedCategory);
                    if (productPrice === 0) {
                        productPrice = findProductPrice(firstWord, '');
                    }
                }
            }
            
            // Strategy 6: Aggressive case-insensitive search through ALL products
            if (productPrice === 0 && inventoryProducts.length > 0) {
                const searchLower = selectedValue.toLowerCase().trim();
                const foundProduct = inventoryProducts.find(function(p) {
                    const pName = (p.name || '').toLowerCase().trim();
                    return pName === searchLower || 
                           pName.includes(searchLower) || 
                           searchLower.includes(pName) ||
                           pName.startsWith(searchLower + ' ') ||
                           pName.startsWith(searchLower + '(');
                });
                
                if (foundProduct) {
                    productPrice = parseFloat(foundProduct.price) || 0;
                    console.log(`✅ Found via aggressive search: ${foundProduct.name} = ₱${productPrice.toFixed(2)}`);
                }
            }
            
            // Auto-fill price (even if 0, so user knows product was found)
            priceInput.value = productPrice.toFixed(2);
            if (productPrice > 0) {
                console.log(`✅ Auto-filled price for "${selectedValue}": ₱${productPrice.toFixed(2)}`);
                // Update data attribute for future use
                if (selectedOption) {
                    selectedOption.setAttribute('data-price', productPrice.toFixed(2));
                }
            } else {
                console.log(`⚠️ No price found for "${selectedValue}" - setting to ₱0.00`);
                console.log(`   Tried all strategies but no match found.`);
                if (selectedCategory && inventoryProducts.length > 0) {
                    const categoryProducts = inventoryProducts.filter(p => {
                        const cat = (p.category || '').toLowerCase();
                        return cat === selectedCategory.toLowerCase();
                    });
                    console.log(`   Available products in category "${selectedCategory}":`, 
                        categoryProducts.map(p => `${p.name} (₱${(parseFloat(p.price) || 0).toFixed(2)})`).slice(0, 15));
                }
            }
            calculateTotal();
        }
    }
}

function handleCustomNameChange(rowId) {
    const row = document.getElementById(`itemRow${rowId}`);
    if (!row) return;
    
    const categorySelect = row.querySelector('.category-select');
    const customInput = row.querySelector('.custom-name-input');
    const priceInput = row.querySelector('.price-input');
    const unitSelect = row.querySelector('.unit-select');
    
    const customName = customInput ? customInput.value.trim() : '';
    const selectedCategory = categorySelect ? categorySelect.value : '';
    
    // Update unit options based on custom product name
    if (unitSelect && customName && selectedCategory) {
        updateUnitOptionsForProduct(unitSelect, customName, selectedCategory);
    }
    
    // Debounce: only search after user stops typing for 500ms
    if (customInput && customInput.searchTimeout) {
        clearTimeout(customInput.searchTimeout);
    }
    
    // Try to find price for custom name after a short delay
    if (customName && customName.length >= 2 && priceInput) {
        if (customInput) {
            customInput.searchTimeout = setTimeout(function() {
                const productPrice = findProductPrice(customName, selectedCategory);
                if (productPrice > 0) {
                    priceInput.value = productPrice.toFixed(2);
                    console.log(`💰 Auto-filled price for custom name "${customName}": ₱${productPrice.toFixed(2)}`);
                    calculateTotal();
                } else {
                    console.log(`⚠️ No price found for custom name "${customName}"`);
                }
            }, 500); // Wait 500ms after user stops typing
        }
    } else if (priceInput && (!customName || customName.length === 0)) {
        // Clear price if custom name is empty
        priceInput.value = '0';
        calculateTotal();
    }
}

function toggleCustomName(rowId) {
    const row = document.getElementById(`itemRow${rowId}`);
    if (!row) return;
    
    const checkbox = row.querySelector('.use-custom-checkbox');
    const customInput = row.querySelector('.custom-name-input');
    const itemSelect = row.querySelector('.item-name-select');
    const priceInput = row.querySelector('.price-input');
    
    if (checkbox && customInput && itemSelect) {
        if (checkbox.checked) {
            customInput.style.display = 'block';
            customInput.required = true;
            itemSelect.required = false;
            itemSelect.value = '__other__';
            // Clear price when switching to custom
            if (priceInput) {
                priceInput.value = '0';
                calculateTotal();
            }
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            itemSelect.required = true;
            customInput.value = '';
            itemSelect.value = '';
            // Clear price when switching back to dropdown
            if (priceInput) {
                priceInput.value = '0';
                calculateTotal();
            }
        }
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
        const categorySelect = row.querySelector('.category-select');
        const itemSelect = row.querySelector('.item-name-select');
        const customInput = row.querySelector('.custom-name-input');
        const quantity = row.querySelector('.quantity-input').value;
        const unitSelect = row.querySelector('.unit-select');
        const unit = unitSelect ? unitSelect.value : 'pcs';
        const unitPrice = row.querySelector('.price-input').value;
        const notesInput = row.querySelector('input[name*="[notes]"]');
        const notes = notesInput ? notesInput.value : '';
        
        const category = categorySelect ? categorySelect.value : '';
        let itemName = '';
        
        // Get item name from select or custom input
        if (itemSelect && itemSelect.value && itemSelect.value !== '__other__') {
            itemName = itemSelect.value;
        } else if (customInput && customInput.value.trim()) {
            itemName = customInput.value.trim();
        }
        
        console.log(`Processing row: category=${category}, itemName=${itemName}, qty=${quantity}, unit=${unit}, price=${unitPrice}`);
        
        // Validate that we have category, item name, and quantity > 0
        if (category && itemName && quantity && parseFloat(quantity) > 0) {
            formData.items.push({
                category: category,
                item_name: itemName,
                quantity: parseInt(quantity),
                unit: unit,
                unit_price: parseFloat(unitPrice) || 0,
                notes: notes || ''
            });
            console.log(`✅ Added item: ${itemName}`);
        } else {
            console.warn(`⚠️ Skipped row: category=${category}, itemName=${itemName}, qty=${quantity}`);
        }
    });
    
    console.log(`📊 Total items collected: ${formData.items.length}`);
    
    if (formData.items.length === 0) {
        alert('❌ Please add at least one item with category, product name, and quantity > 0');
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
            window.location.href = '<?= base_url($dashboardUrl) ?>';
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

