<?php /** @var array $items */ ?>

<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
    <title>Inventory Management — CHAKANOKS</title>

    <!-- Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/inventorystaff.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('header') ?>
    <header class="header">
        <nav class="tabs">
            <?php if (($me['role'] ?? '') !== 'inventory_staff'): ?>
                <a class="tab active" href="<?= base_url('inventory') ?>">Inventory</a>
            <?php else: ?>
                <a class="tab active" href="#">Inventory</a>
            <?php endif; ?>
        </nav>

        <div class="user-strip" style="display:flex;justify-content:flex-end;align-items:center;gap:15px;padding:10px;">
            <?php if (in_array($me['role'] ?? '', ['branch_manager', 'manager'])): ?>
                <a class="btn" href="<?= base_url('manager/dashboard') ?>" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 10px 20px; border-radius: 25px; font-weight: bold; text-decoration: none; box-shadow: 0px 4px 10px rgba(0,0,0,0.2);">
                    ← Back to Manager Dashboard
                </a>
            <?php endif; ?>
            <div class="user-highlight">
                <?= esc($me['email'] ?? '') ?>
                <div class="role">
                    <?= esc($me['role'] ?? '') ?>
                </div>
            </div>
            <a class="btn logout" href="<?= base_url('/auth/logout') ?>" style="background:#e74c3c;color:white;padding:10px 20px;border-radius:25px;font-weight:bold;text-decoration:none;">
                Logout
            </a>
        </div>
    </header>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<main class="content-pane">

    <!-- Inventory Section -->
    <section class="card">
        <div class="card-head">
            <h2>Inventory Management</h2>
            <div class="actions">
                <button id="btnAdd" class="btn add"><span class="plus">+</span> Add New Item</button>
                <button id="btnPrintAll" class="btn add">🖨 Print All Reports</button>
                <input type="date" id="filterDate" class="input date">
                <div class="filters">
                    <select id="filterBranch" class="input select">
    <option value="all">All Branches</option>
    <?php if (!empty($branches) && is_array($branches)): ?>
        <?php foreach ($branches as $b): ?>
            <option value="<?= esc($b['branch_address']) ?>">
                <?= esc($b['branch_address']) ?>
            </option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>
                    <select id="filterStatus" class="input select">
                        <option value="all">All Status</option>
                        <option value="Critical">Critical</option>
                        <option value="Low Stock">Low Stock</option>
                        <option value="Good">Good</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tools">
            <div class="search">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 21l-4.3-4.3m1.6-4.7A7.5 7.5 0 1 1 3.5 7.5 7.5 7.5 0 0 1 18.3 12z"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input id="searchBox" class="input" placeholder="Search items">
            </div>
        </div>

        <div class="table-wrap">
            <table class="table" id="invTable">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Branch</th>
                    <th>Stock Level</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th class="right">Actions</th>
                </tr>
                </thead>
                <tbody id="invBody"></tbody>
            </table>
        </div>

        <div class="summary">
            <div class="pill" id="pillCritical"><div class="pill-num">0</div><div class="pill-label">Critical Items</div></div>
            <div class="pill" id="pillLow"><div class="pill-num">0</div><div class="pill-label">Low Stock</div></div>
            <div class="pill" id="pillGood"><div class="pill-num">0</div><div class="pill-label">Good Stock</div></div>
            <div class="pill total" id="pillTotal"><div class="pill-num">0</div><div class="pill-label">Total</div></div>
        </div>
    </section>
</main>

<!-- Add Item Modal -->
<div class="modal" id="addItemModal" hidden>
    <div class="backdrop"></div>
    <div class="modal-card">
        <div class="modal-head">
            <h3>Add New Item</h3>
            <button class="icon-btn" id="addClose">&times;</button>
        </div>
        <form id="addForm">
            <input type="hidden" name="id" value="">
            <div class="add-body">
                <div>
                    <b>Item Name</b>
                    <select name="name" required>
                        <option value="">Select Item</option>
                        <optgroup label="Chicken Parts">
                            <option>Whole Chicken</option>
                            <option>Chicken Breast</option>
                            <option>Chicken Thigh</option>
                            <option>Chicken Wings</option>
                            <option>Chicken Drumstick</option>
                            <option>Chicken Liver</option>
                            <option>Chicken Gizzard</option>
                            <option>Chicken Feet</option>
                            <option>Chicken Head</option>
                            <option>Chicken Neck</option>
                            <option>Chicken Back</option>
                            <option>Chicken Heart</option>
                            <option>Chicken Kidney</option>
                            <option>Chicken Intestine</option>
                            <option>Chicken Blood</option>
                            <option>Chicken Skin</option>
                            <option>Chicken Fat</option>
                            <option>Chicken Bones</option>
                            <option>Chicken Tail</option>
                            <option>Chicken Leg Quarter</option>
                            <option>Chicken Breast Fillet</option>
                            <option>Chicken Tenderloin</option>
                            <option>Chicken Wing Tip</option>
                            <option>Chicken Wing Flat</option>
                            <option>Chicken Wing Drumlette</option>
                            <option>Chicken Wing Drumette</option>
                        </optgroup>
                    </select>
                </div>
                <div><b>Category</b><input type="text" name="category" value="Chicken Parts" readonly></div>

                <!-- Fixed Branch Select -->
                <div>
                    <b>Branch</b>
                    <label for="branch_address"></label>
<select name="branch_address" class="form-control" required>
    <option value="">Select Branch</option>
    <?php foreach ($branches as $b): ?>
        <option value="<?= esc($b['branch_address']) ?>"
            <?= ($filters['branch_address'] ?? '') === $b['branch_address'] ? 'selected' : '' ?>>
            <?= esc($b['branch_address']) ?>
        </option>
    <?php endforeach; ?>
</select>
                </div>
                <div>
                    <b>Stock</b>
                    <div style="display:flex; gap:10px;">
                        <input type="number" name="stock" required min="1" placeholder="Enter quantity">
                        <select name="unit" required>
                            <option value="pcs">pcs</option>
                            <option value="kg">kg</option>
                            <option value="liters">liters</option>
                            <option value="packs">packs</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:10px;">
                    <div><b>Min Stock</b><input type="number" name="min_stock" required min="1" placeholder="e.g. 100"></div>
                    <div><b>Max Stock</b><input type="number" name="max_stock" required min="1" placeholder="e.g. 300"></div>
                </div>
                <div><b>Expiry Date</b><input type="date" name="expiry"></div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn ghost" id="addClose2">Cancel</button>
                <button type="submit" class="btn add">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- View Item Modal -->
<div class="modal" id="viewItemModal" hidden>
    <div class="backdrop"></div>
    <div class="modal-card" style="max-width:600px;">
        <div class="modal-head" style="display:flex;justify-content:space-between;align-items:center;">
            <h3 id="viewItemTitle" style="margin:0;">Item Name</h3>
            <button class="icon-btn" id="viewClose" style="font-size:1.5rem;line-height:1;">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px; display:flex; flex-direction:column; gap:15px; font-family:'Inter',sans-serif;">
            <div style="display:flex; justify-content:space-between;"><b>Category:</b> <span id="viewCategory"></span></div>
            <div style="display:flex; justify-content:space-between;"><b>Branch:</b> <span id="viewBranch"></span></div>
            <div style="display:flex; justify-content:space-between;"><b>Stock:</b> <span id="viewStock"></span></div>
            <div style="display:flex; justify-content:space-between;"><b>Min/Max:</b> <span id="viewMinMax"></span></div>
            <div style="display:flex; justify-content:space-between;"><b>Status:</b> <span id="viewStatus" style="padding:2px 8px; border-radius:12px; color:white; background:#2ecc71;"></span></div>
            <div style="display:flex; justify-content:space-between;"><b>Last Updated:</b> <span id="viewUpdated"></span></div>
            <div style="display:flex; justify-content:space-between;"><b>Expiry Date:</b> <span id="viewExpiry"></span></div>

            <div id="updateStockContainer" style="display:none; gap:10px; align-items:center; margin-top:10px;">
                <input type="number" id="updateStockInput" style="padding:8px; flex:1;" min="0">
                <button class="btn add" id="saveStockBtn">Save</button>
                <button class="btn ghost" id="cancelStockBtn">Cancel</button>
            </div>
        </div>

        <div class="modal-foot" style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; padding:15px 20px;">
            <button class="btn add" id="btnUpdateStock">Update Stock</button>
            <button class="btn" id="btnReceiveDelivery">Receive Delivery</button>
            <button class="btn ghost" id="btnReportDamaged">Report Damaged</button>
            <button class="btn" id="btnTrackInventory">Track Inventory</button>
            <button class="btn ghost" id="btnCheckExpiry">Check Expiry</button>
            <button class="btn" id="btnPrintReport">Print Report</button>
        </div>
    </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Initial data for JS -->
<script id="initial-items" type="application/json"><?= json_encode($items) ?></script>

<!-- INVENTORY STAFF JS -->
<script src="<?= base_url('assets/js/inventorystaff.js') ?>?v=<?= time() ?>"></script>

<script>
document.getElementById('btnPrintAll').addEventListener('click', function() {
    // Get all items from your JSON script
    const items = JSON.parse(document.getElementById('initial-items').textContent);

    const today = new Date();
    const formattedDate = today.toLocaleDateString() + ' ' + today.toLocaleTimeString();

    let reportHTML = `
    <html>
    <head>
        <title>Inventory Report — CHAKANOKS</title>
        <style>
            @page { size: A4; margin: 20mm; }
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
            h1, h2 { text-align: center; margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
            th { background-color: #f0f0f0; }
            .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #555555ff; }
        </style>
    </head>
    <body>
        <h1>CHAKANOKS</h1>
        <h2>Inventory Report</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Branch</th>
                    <th>Stock Quantity</th>
                    <th>Min Stock</th>
                    <th>Max Stock</th>
                    <th>Unit</th>
                    <th>Expiry Date</th>
                </tr>
            </thead>
            <tbody>
    `;

    items.forEach(item => {
        reportHTML += `
        <tr>
            <td>${item.name}</td>
            <td>${item.category}</td>
            <td>${item.branch_address}</td>
            <td>${item.stock_qty}</td>
            <td>${item.min_stock}</td>
            <td>${item.max_stock}</td>
            <td>${item.unit}</td>
            <td>${item.expiry}</td>
        </tr>
        `;
    });

    reportHTML += `
            </tbody>
        </table>
        <div class="footer">Generated on ${formattedDate}</div>
    </body>
    </html>
    `;

    // Open new window and print
    const printWindow = window.open('', '', 'width=900,height=700');
    printWindow.document.write(reportHTML);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});

// Print Report for Individual Item
document.getElementById('btnPrintReport').addEventListener('click', function() {
    // Get the current item data from the modal
    const itemName = document.getElementById('viewItemTitle').textContent;
    const itemCategory = document.getElementById('viewCategory').textContent;
    const itemBranch = document.getElementById('viewBranch').textContent;
    const itemStock = document.getElementById('viewStock').textContent;
    const itemMinMax = document.getElementById('viewMinMax').textContent;
    const itemStatus = document.getElementById('viewStatus').textContent;
    const itemUpdated = document.getElementById('viewUpdated').textContent;
    const itemExpiry = document.getElementById('viewExpiry').textContent;

    const today = new Date();
    const formattedDate = today.toLocaleDateString() + ' ' + today.toLocaleTimeString();

    let reportHTML = `
    <html>
    <head>
        <title>Item Report — CHAKANOKS</title>
        <style>
            @page { size: A4; margin: 20mm; }
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
            h1, h2 { text-align: center; margin: 5px 0; }
            .item-details { margin: 20px 0; }
            .detail-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px; border-bottom: 1px solid #eee; }
            .detail-label { font-weight: bold; }
            .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #555; }
            .status-badge { padding: 4px 12px; border-radius: 12px; color: white; font-weight: bold; }
            .status-critical { background-color: #e74c3c; }
            .status-low { background-color: #f39c12; }
            .status-good { background-color: #2ecc71; }
        </style>
    </head>
    <body>
        <h1>CHAKANOKS</h1>
        <h2>Item Report</h2>
        
        <div class="item-details">
            <div class="detail-row">
                <span class="detail-label">Item Name:</span>
                <span>${itemName}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Category:</span>
                <span>${itemCategory}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Branch:</span>
                <span>${itemBranch}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Current Stock:</span>
                <span>${itemStock}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Min/Max Stock:</span>
                <span>${itemMinMax}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="status-badge ${itemStatus.toLowerCase().replace(' ', '-')}">${itemStatus}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Updated:</span>
                <span>${itemUpdated}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Expiry Date:</span>
                <span>${itemExpiry}</span>
            </div>
        </div>
        
        <div class="footer">Generated on ${formattedDate}</div>
    </body>
    </html>
    `;

    // Open new window and print
    const printWindow = window.open('', '', 'width=900,height=700');
    printWindow.document.write(reportHTML);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});
</script>

<?= $this->endSection() ?>
