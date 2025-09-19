<?php
// centraladmin.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Central Admin Dashboard - Chakanok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/centraladmin.css') ?>">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 sidebar">
      <h4 class="text-center">CHAKANOKS</h4><hr>
      <a href="#"><i class="fa-solid fa-warehouse"></i> Inventory Overview</a>
      <a href="#"><i class="fa-solid fa-boxes-stacked"></i> Distribution</a>
      <a href="#"><i class="fa-solid fa-chart-pie"></i> Reports</a>
      <hr>
      <a href="<?= base_url('/auth/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    <div class="col-md-10 p-4">
      <h3>Central Admin Dashboard</h3>
      <p>Monitor central operations and stock levels.</p>
      <div class="row">
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Stock Status</h5><p>Stable</p></div></div>
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Pending Deliveries</h5><p>15</p></div></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
