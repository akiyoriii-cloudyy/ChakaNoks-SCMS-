<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class ResetPassword extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

        $user = $this->userModel->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('auth/forgot')->with('error', 'Invalid reset link.');
        }

        if (is_object($user)) {
            $user = (array) $user;
        }

        if (Time::now()->isAfter($user['token_expires'])) {
            return redirect()->to('auth/forgot')->with('error', 'Reset link expired.');
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

        $user = $this->userModel->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('auth/forgot')->with('error', 'Invalid reset link.');
        }

        if (is_object($user)) {
            $user = (array) $user;
        }

        if (Time::now()->isAfter($user['token_expires'])) {
            return redirect()->to('auth/forgot')->with('error', 'Reset link expired.');
        }

        // ✅ Update password & clear reset/OTP fields
        $this->userModel->update($user['id'], [
            'password'      => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'token_expires' => null,
            'otp_code'      => null,
            'otp_expires'   => null,
        ]);

        return redirect()->to('auth/login')->with('success', '✅ Password updated. You can now log in.');
    }
}
