<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Chakanoks SCMS</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="right-form">
            <div class="form-card">
                <div class="welcome-header">
                    <h2>Reset <span>Password</span></h2>
                    <p>Enter your new password and OTP sent to your email</p>
                </div>

                <!-- Flash Messages -->
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
                <?php if (session()->getFlashdata('info')): ?>
                    <div class="alert alert-info">
                        <?= session()->getFlashdata('info') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('auth/reset-password/submit') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <div class="input-group">
                        <label for="otp">OTP Code</label>
                        <div class="input-wrapper">
                            <i class="fas fa-key"></i>
                            <input type="text" name="otp" id="otp" placeholder="Enter OTP sent to your email" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password">New Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Enter new password" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
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
