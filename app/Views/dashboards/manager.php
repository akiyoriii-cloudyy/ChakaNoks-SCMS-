<?php /** @var array $data */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Manager Dashboard — CHAKANOKS</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/manager.css') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="logo">CHAKANOKS</a>
                <div class="logo-subtitle">Supply Chain Management</div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('staff/dashboard') ?>" class="nav-item">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <div class="user-info">
                <div class="user-email"><?= esc($me['email'] ?? '') ?></div>
                <div class="user-role"><?= esc($me['role'] ?? '') ?></div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Branch Manager Dashboard</h1>
                    <p class="page-subtitle">Monitor performance, track inventory, and manage operations</p>
                </div>
                <div class="header-actions">
                    <a href="<?= base_url('staff/dashboard') ?>" class="inventory-btn">
                        <i class="fas fa-boxes"></i>
                        View Inventory
                    </a>
                    <a href="<?= base_url('auth/logout') ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Overview Cards -->
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="card-header">
                        <div>
                            <div class="card-value">₱<?= number_format($data['sales']['today_sales']) ?></div>
                            <div class="card-label">Today's Sales</div>
                        </div>
                        <div class="card-icon sales">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> +<?= $data['sales']['growth_rate'] ?>% from yesterday
                    </div>
                </div>

                <!-- Removed Active Staff card as per request -->

                <div class="overview-card">
                    <div class="card-header">
                        <div>
                            <div class="card-value">₱<?= number_format($data['inventory']['total_value']) ?></div>
                            <div class="card-label">Inventory Value</div>
                        </div>
                        <div class="card-icon inventory">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                    <div class="card-change positive">
                        <i class="fas fa-arrow-up"></i> <?= $data['inventory']['total_items'] ?> items in stock
                    </div>
                </div>

                                 <div class="overview-card">
                     <div class="card-header">
                         <div>
                             <div class="card-value"><?= $data['operations']['customer_satisfaction'] ?>/5</div>
                             <div class="card-label">Customer Satisfaction</div>
                         </div>
                         <div class="card-icon satisfaction">
                             <i class="fas fa-star"></i>
                         </div>
                     </div>
                     <div class="card-change positive">
                         <i class="fas fa-arrow-up"></i> Excellent rating
                     </div>
                 </div>

                 <div class="overview-card inventory-preview">
                     <div class="card-header">
                         <div>
                             <div class="card-value"><?= $data['inventory']['critical_items'] + $data['inventory']['low_stock_items'] ?></div>
                             <div class="card-label">Items Need Attention</div>
                         </div>
                         <div class="card-icon inventory-alert">
                             <i class="fas fa-exclamation-triangle"></i>
                         </div>
    </div>
                     <div class="card-change negative">
                         <i class="fas fa-arrow-down"></i> 
                         <a href="<?= base_url('staff/dashboard') ?>" style="color: inherit; text-decoration: none;">
                             View Inventory Dashboard →
                         </a>
      </div>
    </div>
  </div>

            <!-- Charts Section -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Sales Performance</div>
                            <div class="chart-subtitle">Hourly sales breakdown</div>
                        </div>
                    </div>
                    <canvas id="salesChart" height="200" style="max-height: 200px;"></canvas>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Inventory Status</div>
                            <div class="chart-subtitle">Stock level overview</div>
                        </div>
                    </div>
                    <canvas id="inventoryChart" height="200" style="max-height: 200px;"></canvas>
                </div>
            </div>

            <!-- Performance Section -->
            <div class="performance-section">
                <div class="performance-header">
                    <div class="performance-title">Top Performing Staff</div>
                    <div class="performance-subtitle">Best performers this month</div>
                </div>
                
                <table class="performance-table">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Role</th>
                            <th>Rating</th>
                            <th>Sales</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #1a1a2e;">Markypadilla</div>
                            </td>
                            <td>Manager</td>
                            <td>
                                <div class="rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="rating-value">4.8</span>
                                </div>
                            </td>
                            <td>₱350,000</td>
                    <td>
                                <div style="background: linear-gradient(90deg, #10b981, #34d399); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                            Excellent
                        </div>
                    </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #1a1a2e;">AbbyGarciaa</div>
                            </td>
                            <td>Inventory staff</td>
                            <td>
                                <div class="rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-value">4.6</span>
                                </div>
                            </td>
                            <td>₱85,000</td>
                            <td>
                                <div style="background: linear-gradient(90deg, #10b981, #34d399); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                            Excellent
                        </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #1a1a2e;">Louieee</div>
                            </td>
                            <td>Server</td>
                            <td>
                                <div class="rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-value">4.5</span>
                                </div>
                            </td>
                            <td>₱89,000</td>
                            <td>
                                <div style="background: linear-gradient(90deg, #10b981, #34d399); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    Excellent
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #1a1a2e;">Wakwakk</div>
                            </td>
                            <td>Logistics Coordinator</td>
                            <td>
                                <div class="rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-value">4.4</span>
                                </div>
                            </td>
                            <td>₱78,500</td>
                            <td>
                                <div style="background: linear-gradient(90deg, #10b981, #34d399); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    Excellent
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #1a1a2e;">Akiyorii</div>
                            </td>
                            <td>Assistant Owner</td>
                            <td>
                                <div class="rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-value">4.3</span>
                                </div>
                            </td>
                            <td>₱230,000</td>
                            <td>
                                <div style="background: linear-gradient(90deg, #10b981, #34d399); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                                    Excellent
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Alerts Section -->
            <div class="alerts-section">
                <div class="performance-header">
                    <div class="performance-title">Recent Alerts</div>
                    <div class="performance-subtitle">Important notifications</div>
                </div>
                
                <?php foreach ($data['operations']['alerts'] as $alert): ?>
                <div class="alert-item">
                    <div class="alert-icon <?= $alert['type'] ?>">
                        <i class="fas fa-<?= $alert['type'] === 'warning' ? 'exclamation-triangle' : ($alert['type'] === 'info' ? 'info-circle' : 'check-circle') ?>"></i>
                    </div>
                    <div class="alert-content">
                        <div class="alert-message"><?= esc($alert['message']) ?></div>
                        <div class="alert-time">Just now</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
</div>

    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($data['sales']['hourly_sales'], 'hour')) ?>,
                datasets: [{
                    label: 'Sales (₱)',
                    data: <?= json_encode(array_column($data['sales']['hourly_sales'], 'sales')) ?>,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Inventory Chart
        const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(inventoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'Low Stock', 'Good'],
                datasets: [{
                    data: [
                        <?= $data['inventory']['critical_items'] ?>,
                        <?= $data['inventory']['low_stock_items'] ?>,
                        <?= $data['inventory']['good_stock_items'] ?>
                    ],
                    backgroundColor: [
                        '#ef4444',
                        '#f59e0b',
                        '#10b981'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
