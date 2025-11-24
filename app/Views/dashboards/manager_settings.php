<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — CHAKANOKS SCMS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Professional Dashboard CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-pro.css') ?>">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">CHAKANOKS</h1>
                        <p class="logo-subtitle">Supply Chain Management</p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?= base_url('manager/dashboard') ?>" class="nav-item">
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
                <a href="<?= base_url('manager/settings') ?>" class="nav-item active">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile-card" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.95) 0%, rgba(74, 124, 42, 0.95) 100%); border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255, 255, 255, 0.3); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                            <i class="fas fa-user-circle" style="font-size: 32px; color: white;"></i>
                        </div>
                        <div class="user-info" style="flex: 1; min-width: 0;">
                            <div class="user-name" style="font-size: 0.9rem; font-weight: 600; color: white; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($me['email'] ?? 'User') ?></div>
                            <div class="user-role" style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;"><?= esc(ucwords(str_replace('_', ' ', $me['role'] ?? 'User'))) ?></div>
                        </div>
                    </div>
                </div>
                <a href="<?= base_url('/auth/logout') ?>" class="logout-btn" style="background: linear-gradient(135deg, rgba(45, 80, 22, 0.9) 0%, rgba(74, 124, 42, 0.9) 100%); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; color: white; text-decoration: none; font-weight: 500; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" onmouseover="this.style.background='linear-gradient(135deg, rgba(45, 80, 22, 1) 0%, rgba(74, 124, 42, 1) 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.2)';" onmouseout="this.style.background='linear-gradient(135deg, rgba(45, 80, 22, 0.9) 0%, rgba(74, 124, 42, 0.9) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
                    <i class="fas fa-sign-out-alt" style="font-size: 1rem;"></i>
                    <span style="font-size: 0.9rem;">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <header class="dashboard-header">
                <div class="header-left">
                    <div class="page-title-section">
                        <h2 class="page-title">Settings</h2>
                        <p class="page-subtitle">Manage your account and branch preferences</p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= base_url('manager/dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </header>

            <div class="dashboard-content">
                <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
                    <!-- Account Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user" style="color: var(--primary-green); margin-right: 8px;"></i>
                                Account Information
                            </h3>
                        </div>
                        <div style="padding: 1rem 0;">
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Email</label>
                                <input type="email" value="<?= esc($me['email'] ?? '') ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Role</label>
                                <input type="text" value="<?= esc(ucwords(str_replace('_', ' ', $me['role'] ?? 'User'))) ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Branch ID</label>
                                <input type="text" value="<?= esc($me['branch_id'] ?? 'N/A') ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                            </div>
                        </div>
                    </div>

                    <!-- Branch Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-store" style="color: var(--primary-green); margin-right: 8px;"></i>
                                Branch Information
                            </h3>
                        </div>
                        <div style="padding: 1rem 0;">
                            <?php if ($branch): ?>
                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Branch Name</label>
                                    <input type="text" value="<?= esc($branch['name'] ?? 'N/A') ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Branch Code</label>
                                    <input type="text" value="<?= esc($branch['code'] ?? 'N/A') ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Branch Address</label>
                                    <textarea readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb; min-height: 80px; resize: none;"><?= esc($branch['address'] ?? 'N/A') ?></textarea>
                                </div>
                            <?php else: ?>
                                <div style="padding: 2rem; text-align: center; color: #6b7280;">
                                    <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 1rem; color: #f59e0b;"></i>
                                    <p>Branch information not available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle" style="color: var(--primary-green); margin-right: 8px;"></i>
                                System Information
                            </h3>
                        </div>
                        <div style="padding: 1rem 0;">
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">System Version</label>
                                <input type="text" value="ChakaNoks SCMS v1.0" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Last Login</label>
                                <input type="text" value="<?= date('Y-m-d H:i:s') ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                            </div>
                            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">
                                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                                    For account changes, please contact the central administrator.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>

