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
<<<<<<< HEAD
  <link rel="stylesheet" href="<?= base_url('assets/css/logisticscoordinator.css') ?>">
=======
  <style>
    body { background: #f8f9fa; }
    .sidebar { height: 100vh; background: #146214; color: #fff; padding-top: 20px; }
    .sidebar a { color: #fff; display: block; padding: 10px 20px; margin: 5px 0; border-radius: 8px; text-decoration: none; }
    .sidebar a:hover { background: #198754; }
    .logout { color: red; font-weight: bold; }
  </style>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
</head>
<body>
<div class="container-fluid">
  <div class="row">
<<<<<<< HEAD
    <!-- Sidebar -->
=======
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
    <div class="col-md-2 sidebar">
      <h4 class="text-center">CHAKANOKS</h4><hr>
      <a href="#"><i class="fa-solid fa-truck"></i> Deliveries</a>
      <a href="#"><i class="fa-solid fa-route"></i> Fleet Tracking</a>
      <a href="#"><i class="fa-solid fa-file-contract"></i> Schedules</a>
      <hr>
      <a href="<?= base_url('/auth/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
<<<<<<< HEAD
    
    <!-- Main Content -->
=======
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
    <div class="col-md-10 p-4">
      <h3>Logistics Coordinator Dashboard</h3>
      <p>Manage deliveries and fleet schedules.</p>
      <div class="row">
<<<<<<< HEAD
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
=======
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Ongoing Deliveries</h5><p>12</p></div></div>
        <div class="col-md-6"><div class="card p-3 shadow"><h5>Vehicles in Use</h5><p>7</p></div></div>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
      </div>
    </div>
  </div>
</div>
</body>
<<<<<<< HEAD
=======
  <a href="<?= base_url('/auth/logout') ?>">Logout</a>
</body>
</html>
>>>>>>> 2b5675c60087b8c9e273cc2f256eac7f56137e39
</html>
