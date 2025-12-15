<?php
// superadmin.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superadmin Dashboard - Chakanok</title>
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
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <h4 class="text-center">CHAKANOKS</h4><hr>
      <a href="#"><i class="fa-solid fa-users"></i> Manage Users</a>
      <a href="#"><i class="fa-solid fa-building"></i> Manage Branches</a>
      <a href="#"><i class="fa-solid fa-gear"></i> System Settings</a>
      <a href="#"><i class="fa-solid fa-chart-line"></i> Analytics</a>
      <hr>
      <a href="<?= base_url('/auth/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    <!-- Content -->
    <div class="col-md-10 p-4">
      <!-- Welcome Banner -->
      <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15); display: inline-flex; align-items: center; gap: 12px; max-width: fit-content;">
          <div style="width: 36px; height: 36px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <i class="fas fa-check" style="font-size: 18px; color: #28a745;"></i>
          </div>
          <div>
              <div style="color: white; font-size: 1rem; font-weight: 700; line-height: 1.2;">Welcome to Super Admin</div>
              <div style="color: rgba(255, 255, 255, 0.95); font-size: 0.875rem; font-weight: 500; line-height: 1.2;">Dashboard</div>
          </div>
      </div>

      <h3>Superadmin Dashboard</h3>
      <p>Welcome, Superadmin! You have full control over the system.</p>
      <div class="row">
        <div class="col-md-4"><div class="card p-3 shadow"><h5>Total Users</h5><p>1,250</p></div></div>
        <div class="col-md-4"><div class="card p-3 shadow"><h5>Branches</h5><p>6</p></div></div>
        <div class="col-md-4"><div class="card p-3 shadow"><h5>Revenue</h5><p>₱5,000,000</p></div></div>
      </div>
    </div>
  </div>
</div>
</body>
  <a href="<?= base_url('/auth/logout') ?>">Logout</a>
</body>
</html>
</html>
