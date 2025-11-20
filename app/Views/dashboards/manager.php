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
    
    <style>
    /* Ensure proper spacing and functionality */
    .dashboard-container {
        display: flex !important;
        min-height: 100vh !important;
    }
    
    .sidebar {
        display: flex !important;
        flex-direction: column !important;
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%) !important;
    }
    
    .sidebar-header {
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%) !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    .sidebar-nav .nav-item.active {
        background: rgba(255, 255, 255, 0.15) !important;
        border-left: 4px solid #ffd700 !important;
    }
    
    .header-actions {
        display: flex !important;
        gap: 15px !important;
        align-items: center !important;
    }
    
    .page-header {
        margin-bottom: 30px !important;
        background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%);
        padding: 30px 40px;
        border-radius: 12px;
        color: white;
        box-shadow: 0 10px 30px rgba(45, 80, 22, 0.2);
    }
    
    .page-title {
        color: white !important;
        font-size: 2rem !important;
        font-weight: 700 !important;
        margin-bottom: 8px !important;
    }
    
    .page-subtitle {
        color: rgba(255, 255, 255, 0.85) !important;
        font-size: 1rem !important;
    }
    
    .overview-grid {
        margin-top: 0 !important;
        margin-bottom: 40px;
    }
    </style>
    
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
                <a href="<?= base_url('purchase/request/new') ?>" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Purchase Requests</span>
                </a>
                <a href="<?= base_url('purchase/request/list') ?>" class="nav-item">
                    <i class="fas fa-list"></i>
                    <span>My Requests</span>
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
                    <h1 class="page-title"><i class="fas fa-chart-line" style="margin-right: 12px;"></i>Branch Manager Dashboard</h1>
                    <p class="page-subtitle">Monitor performance, track inventory, and manage operations</p>
                </div>
                <div class="header-actions">
                    <a href="<?= base_url('staff/dashboard') ?>" class="inventory-btn" style="background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
                        <i class="fas fa-boxes"></i>
                        View Inventory
                    </a>
                    <a href="<?= base_url('auth/logout') ?>" class="logout-btn" style="background: rgba(255, 107, 53, 0.8); color: white; border: none; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 107, 53, 1)'" onmouseout="this.style.background='rgba(255, 107, 53, 0.8)'">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Row 1: Key Metrics (Central Admin Style) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Inventory Summary Widget -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">Total Items</div>
                            <div style="font-size: 2rem; font-weight: 700; color: #2563eb; margin-bottom: 8px;"><?= $data['inventory']['total_items'] ?></div>
                            <div style="font-size: 0.85rem; color: #666;">Stock Value: ₱<?= number_format($data['inventory']['total_value'], 2) ?></div>
                        </div>
                        <div style="font-size: 2.5rem; color: #2563eb; opacity: 0.2;">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">Low Stock Items</div>
                            <div style="font-size: 2rem; font-weight: 700; color: #f59e0b; margin-bottom: 8px;"><?= $data['inventory']['low_stock_items'] ?></div>
                            <div style="font-size: 0.85rem; color: #666;">Critical: <span style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><?= $data['inventory']['critical_items'] ?></span></div>
                        </div>
                        <div style="font-size: 2.5rem; color: #f59e0b; opacity: 0.2;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <!-- Purchase Requests -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">Pending Approvals</div>
                            <div style="font-size: 2rem; font-weight: 700; color: #ef4444; margin-bottom: 8px;"><?= $data['purchaseRequests']['pending_approvals'] ?></div>
                            <div style="font-size: 0.85rem; color: #666;">Value: ₱<?= number_format($data['purchaseRequests']['total_pending_value'], 2) ?></div>
                        </div>
                        <div style="font-size: 2.5rem; color: #ef4444; opacity: 0.2;">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>

                <!-- Deliveries -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">In Transit</div>
                            <div style="font-size: 2rem; font-weight: 700; color: #0ea5e9; margin-bottom: 8px;"><?= $data['deliveries']['in_transit_deliveries'] ?></div>
                            <div style="font-size: 0.85rem; color: #666;">Delayed: <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><?= $data['deliveries']['delayed_deliveries'] ?></span></div>
                        </div>
                        <div style="font-size: 2.5rem; color: #0ea5e9; opacity: 0.2;">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Supplier Reports & Delivery Tracking -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Supplier Reports Widget -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h5 style="margin: 0 0 15px 0; font-size: 1.1rem; font-weight: 600;"><i class="fas fa-building" style="color: #2563eb; margin-right: 8px;"></i>Supplier Reports</h5>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Active Suppliers</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #2563eb;"><?= $data['suppliers']['active_suppliers'] ?></div>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Total Suppliers</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #666;"><?= $data['suppliers']['total_suppliers'] ?></div>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Pending Orders</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #f59e0b;"><?= $data['suppliers']['pending_orders'] ?></div>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">On-Time Rate</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #10b981;"><?= $data['suppliers']['on_time_delivery_rate'] ?>%</div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Tracking Widget -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h5 style="margin: 0 0 15px 0; font-size: 1.1rem; font-weight: 600;"><i class="fas fa-truck" style="color: #0ea5e9; margin-right: 8px;"></i>Delivery Tracking</h5>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Pending</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #8b5cf6;"><?= $data['deliveries']['pending_deliveries'] ?></div>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Delivered Today</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #10b981;"><?= $data['deliveries']['delivered_today'] ?></div>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">In Transit</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #0ea5e9;"><?= $data['deliveries']['in_transit_deliveries'] ?></div>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Delayed</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #ef4444;"><?= $data['deliveries']['delayed_deliveries'] ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 5px;">Sales Performance</div>
                        <div style="font-size: 0.85rem; color: #666;">Hourly sales breakdown</div>
                    </div>
                    <canvas id="salesChart" height="200" style="max-height: 200px;"></canvas>
                </div>

                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 5px;">Inventory Status</div>
                        <div style="font-size: 0.85rem; color: #666;">Stock level overview</div>
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
                        <?php if (!empty($data['staff']['top_performers'])): ?>
                            <?php foreach ($data['staff']['top_performers'] as $performer): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #1a1a2e;"><?= esc($performer['name']) ?></div>
                                </td>
                                <td><?= esc($performer['role']) ?></td>
                                <td>
                                    <div class="rating">
                                        <div class="rating-stars">
                                            <?php 
                                            $rating = $performer['rating'] ?? 0;
                                            $fullStars = floor($rating);
                                            $hasHalf = ($rating - $fullStars) >= 0.5;
                                            for ($i = 0; $i < $fullStars; $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; ?>
                                            <?php if ($hasHalf): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php endif; ?>
                                            <?php for ($i = $fullStars + ($hasHalf ? 1 : 0); $i < 5; $i++): ?>
                                                <i class="fas fa-star" style="opacity: 0.3;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="rating-value"><?= number_format($performer['rating'], 1) ?></span>
                                    </div>
                                </td>
                                <td>₱<?= number_format($performer['sales']) ?></td>
                                <td>
                                    <?php 
                                    $performanceClass = $performer['rating'] >= 4.5 ? 'Excellent' : ($performer['rating'] >= 4.0 ? 'Very Good' : 'Good');
                                    $performanceColor = $performer['rating'] >= 4.5 ? '#10b981' : ($performer['rating'] >= 4.0 ? '#3b82f6' : '#f59e0b');
                                    ?>
                                    <div style="background: linear-gradient(90deg, <?= $performanceColor ?>, <?= $performanceColor ?>cc); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        <?= $performanceClass ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                                    No staff data available
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Alerts Section -->
            <div class="alerts-section">
                <div class="performance-header">
                    <div class="performance-title">Recent Alerts</div>
                    <div class="performance-subtitle">Important notifications</div>
                </div>
                
                <?php if (!empty($data['operations']['alerts'])): ?>
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
                <?php else: ?>
                    <div class="alert-item">
                        <div class="alert-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-message">All systems operating normally</div>
                            <div class="alert-time">Just now</div>
                        </div>
                    </div>
                <?php endif; ?>
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