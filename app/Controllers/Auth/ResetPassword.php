<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PasswordResetTokenModel;
use CodeIgniter\I18n\Time;

class ResetPassword extends BaseController
{
    protected $userModel;
    protected $tokenModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new PasswordResetTokenModel();
        $this->session   = session();
    }

    /**
     * Show reset password form (if token valid).
     */
    public function index()
    {
        $token = $this->request->getGet('token');

        if (empty($token)) {
            return redirect()->to('auth/forgot')->with('error', 'Invalid reset token.');
        }

        // Verify token using new password_reset_tokens table
        $tokenData = $this->tokenModel->verifyToken($token);

        if (!$tokenData) {
            return redirect()->to('auth/forgot')->with('error', 'Invalid or expired reset link.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    /**
     * Handle reset password submission.
     */
    public function submit()
    {
        $token       = $this->request->getPost('token');
        $password    = trim($this->request->getPost('password'));
        $confirmPass = trim($this->request->getPost('confirm_password'));

        // Validate form fields
        if (!$token || !$password || !$confirmPass) {
            return redirect()->back()->with('error', 'Please fill in all fields.');
        }

        if ($password !== $confirmPass) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
        }

        // Verify token using new password_reset_tokens table
        $tokenData = $this->tokenModel->verifyToken($token);

        if (!$tokenData) {
            return redirect()->to('auth/forgot')->with('error', 'Invalid or expired reset link.');
        }

        // Update password
        $this->userModel->update($tokenData['user_id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        // Mark token as used
        $this->tokenModel->markAsUsed($tokenData['id']);

        return redirect()->to('auth/login')->with('success', '✅ Password updated. You can now log in.');
    }
}
