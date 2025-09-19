<?php
// logisticscoordinator.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logistics Coordinator Dashboard - Chakanok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/logisticscoordinator.css') ?>">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <h4 class="text-center">CHAKANOKS</h4><hr>
      <a href="#"><i class="fa-solid fa-truck"></i> Deliveries</a>
      <a href="#"><i class="fa-solid fa-route"></i> Fleet Tracking</a>
      <a href="#"><i class="fa-solid fa-file-contract"></i> Schedules</a>
      <hr>
      <a href="<?= base_url('/auth/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    
    <!-- Main Content -->
    <div class="col-md-10 p-4">
      <h3>Logistics Coordinator Dashboard</h3>
      <p>Manage deliveries and fleet schedules.</p>
      <div class="row">
        <div class="col-md-6">
          <div class="card p-3 shadow">
            <h5>Ongoing Deliveries</h5>
            <p>12</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-3 shadow">
            <h5>Vehicles in Use</h5>
            <p>7</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
