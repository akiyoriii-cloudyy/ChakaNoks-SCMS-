<?php
// inventorystaff.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Staff Dashboard - Chakanok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f8f9fa; }
    .sidebar { height: 100vh; background: #146214; color: #fff; padding-top: 20px; }
    .sidebar a { color: #fff; display: block; padding: 10px 20px; margin: 5px 0; border-radius: 8px; text-decoration: none; }
    .sidebar a:hover { background: #198754; }
    .logout { color: red; font-weight: bold; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 sidebar">
      <h4 class="text-center">CHAKANOKS</h4><hr>
      <a href="#"><i class="fa-solid fa-box"></i> Stock Management</a>
      <a href="#"><i class="fa-solid fa-truck-ramp-box"></i> Requests</a>
      <a href="#"><i class="fa-solid fa-clipboard-list"></i> Delivery Logs</a>
      <hr>
      <a href="<?= base_url('/auth/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    <div class="col-md-10 p-4">
      <h3>Inventory Staff Dashboard</h3>
      <p>Manage stock and requests.</p>
      <div class="row">
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Available Stock</h5><p>12,300 items</p></div></div>
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Pending Requests</h5><p>5</p></div></div>
      </div>
    </div>
  </div>
</div>
</body>
  <a href="<?= base_url('/auth/logout') ?>">Logout</a>
</body>
</html>
</html>
