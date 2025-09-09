<h2>Reset Password via OTP</h2>
<form method="post" action="<?= site_url('auth/forgot/verifyOtp') ?>">
    <?= csrf_field() ?>
    <label>Email Address</label>
    <input type="email" name="email" required>

    <label>OTP Code</label>
    <input type="text" name="otp" required>

    <label>New Password</label>
    <input type="password" name="password" required>

    <button type="submit">Update Password</button>
</form>
