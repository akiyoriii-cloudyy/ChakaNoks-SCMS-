<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-content">
            <div class="content-wrapper">
                <div class="logo-section">
                    <div class="logo-circle"></div>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-form">
            <div class="form-card">
                <div class="welcome-header">
                    <h2>Reset <span>Password</span></h2>
                    <p>
                        <?php if (!empty($token)): ?>
                            Enter your new password
                        <?php else: ?>
                            Enter your email, OTP, and new password
                        <?php endif; ?>
                    </p>
                </div>

                <!-- ✅ Flash Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <!-- ✅ Reset Form -->
                <form method="post" action="<?= site_url('auth/forgot/verify') ?>">
                    <?= csrf_field() ?>

                    <?php if (!empty($token)): ?>
                        <!-- Hidden token input if using link -->
                        <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <?php else: ?>
                        <!-- Normal OTP flow -->
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="otp">OTP Code</label>
                            <div class="input-wrapper">
                                <i class="fas fa-key"></i>
                                <input type="text" name="otp" id="otp" placeholder="Enter OTP code" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label for="password">New Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Enter new password" required>
                        </div>
                    </div>

                    <button type="submit" class="forgot-btn">Reset Password</button>
                </form>

                <a href="<?= site_url('auth/login') ?>">
                    <button type="button" class="back-btn">Back to Sign In</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
