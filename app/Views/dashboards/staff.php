<?php /** @var array $items */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inventory Management — CHAKANOKS</title>

  <!-- Styles -->
  <link rel="stylesheet" href="<?= base_url('assets/css/inventorystaff.css') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<main class="content-pane">

  <!-- Header -->
  <header class="header">
    <!-- Navigation -->
    <nav class="tabs">
      <?php if (($me['role'] ?? '') !== 'inventory_staff'): ?>
        <a class="tab" href="<?= base_url('dashboard') ?>"></a>
        <a class="tab active" href="<?= base_url('inventory') ?>"></a>
        <a class="tab" href="<?= base_url('purchases') ?>"></a>
        <a class="tab" href="<?= base_url('settings') ?>"></a>
      <?php else: ?>
        <a class="tab active" href="#">Inventory</a>
      <?php endif; ?>
    </nav>

    <!-- User Strip -->
    <div class="user-strip" style="display:flex;justify-content:flex-end;align-items:center;gap:15px;padding:10px;">
      <div style="
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: bold;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
      ">
        <?= esc($me['email'] ?? '') ?> (<?= esc($me['role'] ?? '') ?>)
      </div>
      <a class="btn logout" href="<?= base_url('/auth/logout') ?>"
         style="background:#e74c3c;color:white;padding:10px 20px;border-radius:25px;font-weight:bold;text-decoration:none;">
        Logout
      </a>
    </div>
  </header>

  <!-- Inventory Section -->
  <section class="card">
    <div class="card-head">
      <h2>Inventory Management</h2>
      <div class="actions">
        <button id="btnAdd" class="btn add">
          <span class="plus">+</span> Add New Item
        </button>
        <input type="date" id="filterDate" class="input date">

        <!-- Filters -->
        <div class="filters">
          <select id="filterBranch" class="input select">
            <option value="all">All Branches</option>
            <?php
              $branches = array_values(array_unique(array_map(fn($r) => $r['branch'], $items)));
              foreach ($branches as $b): ?>
                <option value="<?= esc($b) ?>"><?= esc($b) ?></option>
            <?php endforeach; ?>
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

    <!-- Search -->
    <div class="tools">
      <div class="search">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M21 21l-4.3-4.3m1.6-4.7A7.5 7.5 0 1 1 3.5 7.5
                   7.5 7.5 0 0 1 18.3 12z"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <input id="searchBox" class="input" placeholder="Search items">
      </div>
    </div>

    <!-- Table -->
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
        <tbody>
          <?php foreach ($items as $row): ?>
            <tr data-id="<?= esc($row['id']) ?>"
                data-branch="<?= esc($row['branch']) ?>"
                data-status="<?= esc($row['status']) ?>"
                data-date="<?= esc($row['date']) ?>">

              <!-- Item -->
              <td>
                <div class="cell-title"><?= esc($row['name']) ?></div>
                <div class="cell-sub"><?= esc($row['category']) ?></div>
              </td>

              <!-- Branch -->
              <td><?= esc($row['branch']) ?></td>

              <!-- Stock -->
              <td>
                <div class="cell-title"><?= esc($row['stock']) ?></div>
                <div class="cell-sub">Min: <?= esc($row['min']) ?> / Max: <?= esc($row['max']) ?></div>
              </td>

              <!-- Status -->
              <td>
                <span class="badge <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                  <?= esc($row['status']) ?>
                </span>
              </td>

              <!-- Last Updated -->
              <td><?= esc($row['updated_ago']) ?></td>

              <!-- Actions -->
              <td class="right">
                <button class="link viewBtn"
                        data-id="<?= esc($row['id']) ?>"
                        data-name="<?= esc($row['name']) ?>"
                        data-category="<?= esc($row['category']) ?>"
                        data-branch="<?= esc($row['branch']) ?>"
                        data-stock="<?= esc($row['stock']) ?>"
                        data-min="<?= esc($row['min']) ?>"
                        data-max="<?= esc($row['max']) ?>"
                        data-status="<?= esc($row['status']) ?>"
                        data-updated="<?= esc($row['updated_ago']) ?>"
                        data-expiry="<?= esc($row['expiry'] ?? 'N/A') ?>">
                  View
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Summary Pills -->
    <div class="summary">
      <div class="pill" id="pillCritical">
        <div class="pill-num">0</div>
        <div class="pill-label">Critical Items</div>
      </div>
      <div class="pill" id="pillLow">
        <div class="pill-num">0</div>
        <div class="pill-label">Low Stock</div>
      </div>
      <div class="pill" id="pillGood">
        <div class="pill-num">0</div>
        <div class="pill-label">Good Stock</div>
      </div>
      <div class="pill total" id="pillTotal">
        <div class="pill-num">0</div>
        <div class="pill-label">Total</div>
      </div>
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

        <!-- Item Name -->
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
              <option>Other</option>
            </optgroup>
          </select>
        </div>

        <!-- Category -->
        <div>
          <b>Category</b>
          <input type="text" name="category" value="Food Supply" readonly>
        </div>

        <!-- Branch -->
        <div>
          <b>Branch</b>
          <select name="branch" required>
            <option value="">Select Branch</option>
            <option value="MATINA">MATINA</option>
            <option value="TORIL">TORIL</option>
            <option value="BUHANGIN">BUHANGIN</option>
            <option value="AGDAO">AGDAO</option>
            <option value="LANANG">LANANG</option>
          </select>
        </div>

        <!-- Stock -->
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

        <!-- Min / Max -->
        <div style="display:flex; gap:10px;">
          <div>
            <b>Min Stock</b>
            <input type="number" name="min" required min="1" placeholder="e.g. 100">
          </div>
          <div>
            <b>Max Stock</b>
            <input type="number" name="max" required min="1" placeholder="e.g. 300">
          </div>
        </div>

        <!-- Expiry Date -->
        <div>
          <b>Expiry Date</b>
          <input type="date" name="expiry">
        </div>
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
  <div class="modal-card">
    <div class="modal-head">
      <h3>Item Details</h3>
      <button class="icon-btn" id="viewClose">&times;</button>
    </div>

    <div class="view-body">
      <p><b>Item:</b> <span id="viewName"></span></p>
      <p><b>Category:</b> <span id="viewCategory"></span></p>
      <p><b>Branch:</b> <span id="viewBranch"></span></p>
      <p><b>Stock:</b> <span id="viewStock"></span></p>
      <p><b>Min:</b> <span id="viewMin"></span> | <b>Max:</b> <span id="viewMax"></span></p>
      <p><b>Status:</b> <span id="viewStatus"></span></p>
      <p><b>Expiry:</b> <span id="viewExpiry"></span></p>
      <p><b>Expiry Status:</b> <span id="viewExpiryStatus"></span></p> <!-- ✅ Added -->
      <p><b>Last Updated:</b> <span id="viewUpdated"></span></p>
    </div>

    <div class="modal-foot">
  <button class="btn ghost" id="viewPrev">⟨ Prev</button>
  <button class="btn ghost" id="viewNext">Next ⟩</button>
  <button class="btn ghost" id="viewClose2">Close</button>
</div>


    <!-- Responsibilities -->
    <div class="responsibilities">
      <h4>Responsibilities</h4>
      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn action-btn" id="btnUpdateStock">Update Stock</button>
        <button class="btn action-btn" id="btnReceiveDelivery">Receive Delivery</button>
        <button class="btn action-btn" id="btnReportDamage">Report Damaged/Expired</button>
        <button class="btn action-btn" id="btnTrackBranch">Track Branch Inventory</button>
        <button class="btn action-btn" id="btnCheckExpiry">Check Expiry</button>
      </div>
    </div>

<!-- Update Stock Modal -->
<div class="modal" id="updateStockModal" hidden>
  <div class="backdrop"></div>
  <div class="modal-card">
    <div class="modal-head">
      <h3>Update Stock</h3>
      <button class="icon-btn" id="updateClose">&times;</button>
    </div>
    <form id="updateStockForm">
      <input type="hidden" name="id" id="updateId">
      <div class="add-body">
        <div>
          <b>New Stock Quantity</b>
          <input type="number" name="stock" id="updateStock" required min="0">
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn ghost" id="updateClose2">Cancel</button>
        <button type="submit" class="btn add">Update</button>
      </div>
    </form>
  </div>
</div>

   <!-- Branch Tracking Modal -->
<div class="modal" id="trackBranchModal" hidden>
  <div class="backdrop"></div>
  <div class="modal-card">
    <div class="modal-head">
      <h3>Branch Inventory</h3>
      <button class="icon-btn" id="trackClose">&times;</button>
    </div>
    <div class="view-body" id="branchInventoryList">
      <!-- JS will populate all items from the same branch -->
      <p>Loading branch inventory...</p>
    </div>
    <div class="modal-foot">
      <button class="btn ghost" id="trackClose2">Close</button>
    </div>
  </div>
</div>

<!-- Expiry Check Modal -->
<div class="modal" id="expiryCheckModal" hidden>
  <div class="backdrop"></div>
  <div class="modal-card">
    <div class="modal-head">
      <h3>Expiry Check</h3>
      <button class="icon-btn" id="expiryClose">&times;</button>
    </div>
    <div class="view-body" id="expiryCheckBody">
      <!-- JS will check expiry date and populate -->
      <p>Loading expiry status...</p>
    </div>
    <div class="modal-foot">
      <button class="btn ghost" id="expiryClose2">Close</button>
    </div>
  </div>
</div>
         
<!-- Cache-busted JS -->
<script src="<?= base_url('assets/js/inventorystaff.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
