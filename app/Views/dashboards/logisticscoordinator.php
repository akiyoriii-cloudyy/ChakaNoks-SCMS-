<?= $this->extend('templates/base_template') ?>

<?= $this->section('head') ?>
<style>
    :root {
        --primary-green: #2d5016;
        --secondary-green: #3a6520;
        --accent-green: #4a7a2a;
        --light-green: #5a8a35;
        --text-light: #f8fff8;
        --text-muted: #d4e8c9;
        --table-header: #3a6520;
        --card-bg: #ffffff;
        --hover-green: #e8f5e8;
        --border-green: #d4e8c9;
    }
    
    body {
        background-color: #f8fff8;
        color: #2c3e50;
    }
    
    .delivery-card {
        border: 1px solid var(--border-green);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: var(--card-bg);
        box-shadow: 0 2px 4px rgba(45, 80, 22, 0.1);
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: bold;
    }
    
    .status-scheduled { 
        background: #0d6efd; 
        color: #fff; 
    }
    
    .status-in_transit { 
        background: #ffc107; 
        color: #000; 
    }
    
    .status-delivered { 
        background: #28a745; 
        color: #fff; 
    }
    
    /* Sidebar Styles */
    .sidebar {
        min-height: 98vh;
        background: var(--primary-green);
        color: var(--text-light);
        padding: 50;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        border-right: 3px solid var(--accent-green);
    }
    
    .sidebar-header {
        padding: 20px 15px;
        border-bottom: 1px solid var(--accent-green);
        background: var(--primary-green);
    }
    
    .sidebar-nav {
        padding: 15px 0;
    }
    
    .nav-link-custom {
        display: flex;
        align-items: center;
        padding: 20px 30px;
        color: var(--text-light);
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        transition: all 0.3s ease;
    }
    
    .nav-link-custom:hover {
        background: var(--accent-green);
        color: white;
    }
    
    .nav-link-custom.active {
        background: var(--accent-green);
        color: white;
        border-left: 4px solid var(--text-light);
    }
    
    .nav-link-custom i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .main-content {
        margin-left: 0;
        padding: 20px;
        background: #f8fff8;
        min-height: 100vh;
    }
    
    .user-profile-sidebar {
        padding: 15px;
        border-top: 1px solid var(--accent-green);
        background: var(--primary-green);
        margin-top: auto;
    }
    
    /* Card Styles */
    .card {
        border: 1px solid var(--border-green);
        box-shadow: 0 2px 4px rgba(45, 80, 22, 0.1);
        background: var(--card-bg);
        color: #2c3e50;
    }
    
    .card-header {
        background-color: var(--primary-green);
        border-bottom: 1px solid var(--border-green);
        color: var(--text-light);
    }
    
    .card-header h5 {
        color: var(--text-light);
        font-weight: 600;
    }
    
    /* Table Styles */
    .table {
        color: #2c3e50;
        border-color: var(--border-green);
    }
    
    .table thead th {
        background-color: var(--table-header);
        color: var(--text-light);
        border-bottom: 1px solid var(--border-green);
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: var(--hover-green);
        color: #2c3e50;
    }
    
    .table tbody td {
        border-color: var(--border-green);
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--secondary-green);
        border-color: var(--secondary-green);
    }
    
    .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }
    
    .btn-success {
        background-color: var(--accent-green);
        border-color: var(--accent-green);
    }
    
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
        background: transparent;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Content Card Styles */
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    
    .card-header {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d5016;
        margin: 0;
    }
    
    /* Table row hover effect */
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Ensure navigation is ALWAYS visible */
    .dashboard-sidebar,
    .sidebar-nav,
    .nav-item {
        visibility: visible !important;
        display: block !important;
    }
    
    /* Content sections */
    .content-section {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (min-width: 768px) {
        .sidebar {
            width: 250px;
            position: fixed;
        }
        
        .main-content {
            margin-left: 250px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
         <br>
         <br>
        <div class="sidebar d-flex flex-column">
            <div class="sidebar-header">
                <h4 class="mb-0">Logistics Coordinator</h4>
                <small>Delivery Management</small>
            </div>
            
            <div class="sidebar-nav flex-grow-1">
                <?php 
                $currentPage = $currentPage ?? 'dashboard';
                ?>
                <a href="<?= base_url('logisticscoordinator/dashboard') ?>" class="nav-link-custom <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= base_url('logisticscoordinator/schedule') ?>" class="nav-link-custom <?= $currentPage === 'schedule' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-plus"></i> Schedule Delivery
                </a>
                <a href="<?= base_url('logisticscoordinator/track-orders') ?>" class="nav-link-custom <?= $currentPage === 'track' ? 'active' : '' ?>">
                    <i class="fas fa-search"></i> Track Orders
                </a>
                <a href="<?= base_url('logisticscoordinator/deliveries') ?>" class="nav-link-custom <?= $currentPage === 'deliveries' ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i> Deliveries
                </a>
                
                <a href="<?= base_url('logisticscoordinator/schedules') ?>" class="nav-link-custom <?= $currentPage === 'schedules' ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i> Schedules
                </a>
            </div>
            
            <div class="user-profile-sidebar">
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-3">
                        <i class="fas fa-user-circle fa-2x text-white-50"></i>
                    </div>
                    <div class="user-info">
                        <div class="user-name fw-bold text-white"><?= esc(session()->get('email') ?? 'User') ?></div>
                        <div class="user-role small text-white-50 text-uppercase">
                            <?= esc(ucwords(str_replace('_', ' ', session()->get('role') ?? 'User')) ) ?>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger w-100 mt-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <?php $currentPage = $currentPage ?? 'dashboard'; ?>
            
            <?php if ($currentPage === 'dashboard'): ?>
                <!-- Dashboard View -->
                <!-- Pending POs for Scheduling -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Pending Purchase Orders (Need Scheduling)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pendingPOs)): ?>
                            <p class="text-muted">No pending purchase orders</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Supplier</th>
                                            <th>Branch</th>
                                            <th>Expected Date</th>
                                            <th>Total Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingPOs as $po): ?>
                                        <tr>
                                            <td><strong><?= esc($po['order_number'] ?? 'N/A') ?></strong></td>
                                            <td><?= esc($po['supplier']['name'] ?? ($po['supplier_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($po['branch']['name'] ?? ($po['branch_id'] ?? 'N/A')) ?></td>
                                            <td><?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : 'Not set' ?></td>
                                            <td>₱<?= number_format($po['total_amount'] ?? 0, 2) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="scheduleDelivery(<?= $po['id'] ?>)">
                                                    <i class="fas fa-calendar"></i> Schedule
                                                </button>
                                                <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $po['id'] ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Scheduled Deliveries -->
                <br>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-calendar-check"></i> Scheduled Deliveries</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($scheduledDeliveries)): ?>
                                    <p class="text-muted">No scheduled deliveries</p>
                                <?php else: ?>
                                    <?php foreach ($scheduledDeliveries as $delivery): ?>
                                    <div class="delivery-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <strong><?= esc($delivery['delivery_number']) ?></strong>
                                                <span class="status-badge status-scheduled ms-2">Scheduled</span>
                                            </div>
                                        </div>
                                        <div class="small text-muted">
                                            <div>Date: <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?></div>
                                            <div>Branch: <?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></div>
                                            <?php if ($delivery['driver_name']): ?>
                                                <div>Driver: <?= esc($delivery['driver_name']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                                <i class="fas fa-truck"></i> Mark In Transit
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-truck"></i> In Transit Deliveries</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($inTransitDeliveries)): ?>
                                    <p class="text-muted">No deliveries in transit</p>
                                <?php else: ?>
                                    <?php foreach ($inTransitDeliveries as $delivery): ?>
                                    <div class="delivery-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <strong><?= esc($delivery['delivery_number']) ?></strong>
                                                <span class="status-badge status-in_transit ms-2">In Transit</span>
                                            </div>
                                        </div>
                                        <div class="small text-muted">
                                            <div>Scheduled: <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?></div>
                                            <div>Driver: <?= esc($delivery['driver_name'] ?? 'N/A') ?></div>
                                            <div>Vehicle: <?= esc($delivery['vehicle_info'] ?? 'N/A') ?></div>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delivered')">
                                                <i class="fas fa-check"></i> Mark Delivered
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php elseif ($currentPage === 'schedule'): ?>
                <!-- Schedule Delivery View -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-calendar-plus"></i> Schedule Delivery</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pendingPOs)): ?>
                            <p class="text-muted">No pending purchase orders to schedule</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Supplier</th>
                                            <th>Branch</th>
                                            <th>Expected Date</th>
                                            <th>Total Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pendingPOs as $po): ?>
                                        <tr>
                                            <td><strong><?= esc($po['order_number'] ?? 'N/A') ?></strong></td>
                                            <td><?= esc($po['supplier']['name'] ?? ($po['supplier_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($po['branch']['name'] ?? ($po['branch_id'] ?? 'N/A')) ?></td>
                                            <td><?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : 'Not set' ?></td>
                                            <td>₱<?= number_format($po['total_amount'] ?? 0, 2) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="scheduleDelivery(<?= $po['id'] ?>)">
                                                    <i class="fas fa-calendar"></i> Schedule
                                                </button>
                                                <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $po['id'] ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            
            <?php elseif ($currentPage === 'track'): ?>
                <!-- Track Orders View -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-search"></i> Track Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($allOrders ?? [])): ?>
                            <p class="text-muted">No orders found</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Supplier</th>
                                            <th>Branch</th>
                                            <th>Status</th>
                                            <th>Expected Date</th>
                                            <th>Total Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allOrders as $order): ?>
                                        <tr>
                                            <td><strong><?= esc($order['order_number'] ?? 'N/A') ?></strong></td>
                                            <td><?= esc($order['supplier']['name'] ?? ($order['supplier_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($order['branch']['name'] ?? ($order['branch_id'] ?? 'N/A')) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= esc($order['status'] ?? 'pending') ?>">
                                                    <?= esc(ucwords(str_replace('_', ' ', $order['status'] ?? 'pending'))) ?>
                                                </span>
                                            </td>
                                            <td><?= $order['expected_delivery_date'] ? date('M d, Y', strtotime($order['expected_delivery_date'])) : 'Not set' ?></td>
                                            <td>₱<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $order['id'] ?>)">
                                                    <i class="fas fa-eye"></i> Track
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            
            <?php elseif ($currentPage === 'deliveries'): ?>
                <!-- All Deliveries View -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-truck"></i> All Deliveries</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($allDeliveries ?? [])): ?>
                            <p class="text-muted">No deliveries found</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Delivery Number</th>
                                            <th>PO Number</th>
                                            <th>Supplier</th>
                                            <th>Branch</th>
                                            <th>Status</th>
                                            <th>Scheduled Date</th>
                                            <th>Driver</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allDeliveries as $delivery): ?>
                                        <tr>
                                            <td><strong><?= esc($delivery['delivery_number'] ?? 'N/A') ?></strong></td>
                                            <td><?= esc($delivery['purchase_order']['order_number'] ?? ($delivery['purchase_order_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($delivery['supplier']['name'] ?? ($delivery['supplier_id'] ?? 'N/A')) ?></td>
                                            <td><?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= esc($delivery['status'] ?? 'pending') ?>">
                                                    <?= esc(ucwords(str_replace('_', ' ', $delivery['status'] ?? 'pending'))) ?>
                                                </span>
                                            </td>
                                            <td><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'Not set' ?></td>
                                            <td><?= esc($delivery['driver_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php if ($delivery['status'] === 'scheduled'): ?>
                                                    <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                                        <i class="fas fa-truck"></i> Mark In Transit
                                                    </button>
                                                <?php elseif ($delivery['status'] === 'in_transit'): ?>
                                                    <button class="btn btn-sm btn-success" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'delivered')">
                                                        <i class="fas fa-check"></i> Mark Delivered
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $delivery['purchase_order_id'] ?? 0 ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            
            <?php elseif ($currentPage === 'schedules'): ?>
                <!-- Schedules View -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-file-contract"></i> Scheduled Deliveries</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($scheduledDeliveries)): ?>
                            <p class="text-muted">No scheduled deliveries</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($scheduledDeliveries as $delivery): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="delivery-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <strong><?= esc($delivery['delivery_number']) ?></strong>
                                                <span class="status-badge status-scheduled ms-2">Scheduled</span>
                                            </div>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <div><strong>PO Number:</strong> <?= esc($delivery['purchase_order']['order_number'] ?? ($delivery['purchase_order_id'] ?? 'N/A')) ?></div>
                                            <div><strong>Date:</strong> <?= date('M d, Y', strtotime($delivery['scheduled_date'])) ?></div>
                                            <div><strong>Branch:</strong> <?= esc($delivery['branch']['name'] ?? ($delivery['branch_id'] ?? 'N/A')) ?></div>
                                            <?php if ($delivery['driver_name']): ?>
                                                <div><strong>Driver:</strong> <?= esc($delivery['driver_name']) ?></div>
                                            <?php endif; ?>
                                            <?php if ($delivery['vehicle_info']): ?>
                                                <div><strong>Vehicle:</strong> <?= esc($delivery['vehicle_info']) ?></div>
                                            <?php endif; ?>
                                            <?php if ($delivery['notes']): ?>
                                                <div><strong>Notes:</strong> <?= esc($delivery['notes']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-warning" onclick="updateDeliveryStatus(<?= $delivery['id'] ?>, 'in_transit')">
                                                <i class="fas fa-truck"></i> Mark In Transit
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="trackOrder(<?= $delivery['purchase_order_id'] ?? 0 ?>)">
                                                <i class="fas fa-eye"></i> View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Schedule Delivery Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Delivery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <input type="hidden" id="poId" name="purchase_order_id">
                    <div class="mb-3">
                        <label class="form-label">Scheduled Date</label>
                        <input type="date" class="form-control" id="scheduledDate" name="scheduled_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Driver Name</label>
                        <input type="text" class="form-control" id="driverName" name="driver_name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle Info</label>
                        <input type="text" class="form-control" id="vehicleInfo" name="vehicle_info" placeholder="e.g., Truck ABC-123">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="deliveryNotes" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitSchedule()">Schedule Delivery</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="trackingContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function scheduleDelivery(poId) {
    $('#poId').val(poId);
    // Set default date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('#scheduledDate').val(tomorrow.toISOString().split('T')[0]);
    $('#scheduleModal').modal('show');
}

function submitSchedule() {
    const formData = {
        purchase_order_id: $('#poId').val(),
        scheduled_date: $('#scheduledDate').val(),
        driver_name: $('#driverName').val(),
        vehicle_info: $('#vehicleInfo').val(),
        notes: $('#deliveryNotes').val()
    };

    $.ajax({
        url: '<?= base_url('delivery/schedule') ?>',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            if (response.status === 'success') {
                alert('Delivery scheduled successfully!');
                $('#scheduleModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        },
        error: function() {
            alert('Error scheduling delivery');
        }
    });
}

function trackOrder(poId) {
    $.get('<?= base_url('purchase/order/') ?>' + poId + '/track', function(response) {
        if (response.status === 'success') {
            const order = response.order;
            let html = `
                <div class="mb-3">
                    <strong>Order Number:</strong> ${order.order_number}<br>
                    <strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status}</span><br>
                    <strong>Supplier:</strong> ${order.supplier ? order.supplier.name : 'N/A'}<br>
                    <strong>Expected Delivery:</strong> ${order.expected_delivery_date ? new Date(order.expected_delivery_date).toLocaleDateString() : 'Not set'}<br>
                </div>
            `;
            
            if (order.tracking) {
                html += `
                    <div class="alert alert-${order.tracking.is_overdue ? 'danger' : 'info'}">
                        <strong>Tracking Info:</strong><br>
                        Current Status: ${order.tracking.current_status}<br>
                        ${order.tracking.is_overdue ? '<span class="text-danger">⚠️ Overdue!</span>' : ''}
                        ${order.tracking.days_until_delivery !== null ? `Days until delivery: ${Math.ceil(order.tracking.days_until_delivery)}` : ''}
                    </div>
                `;
            }
            
            if (order.delivery) {
                html += `
                    <div class="mt-3">
                        <h6>Delivery Information:</h6>
                        <p>Delivery #: ${order.delivery.delivery_number}<br>
                        Status: ${order.delivery.status}<br>
                        Scheduled: ${new Date(order.delivery.scheduled_date).toLocaleDateString()}</p>
                    </div>
                `;
            }
            
            $('#trackingContent').html(html);
            $('#trackingModal').modal('show');
        }
    });
}

function updateDeliveryStatus(deliveryId, status) {
    if (confirm(`Update delivery status to ${status}?`)) {
        $.post('<?= base_url('delivery/') ?>' + deliveryId + '/update-status', {
            status: status,
            actual_delivery_date: status === 'delivered' ? new Date().toISOString().split('T')[0] : null
        }, function(response) {
            if (response.status === 'success') {
                alert('Delivery status updated!');
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        });
    }
}

</script>
<?= $this->endSection() ?>