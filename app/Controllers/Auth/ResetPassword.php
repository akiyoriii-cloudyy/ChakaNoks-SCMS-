<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PasswordResetTokenModel;

class ResetPassword extends BaseController
{
    protected $userModel;
    protected $tokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new PasswordResetTokenModel();
    }

    /**
     * Show reset password form
     */
    public function index()
    {
        $token = $this->request->getGet('token');
        
        if (empty($token)) {
            return redirect()->to('auth/forgot-password')->with('error', 'Invalid reset link.');
        }

        // Verify token
        $tokenData = $this->tokenModel->verifyToken($token);

        if (!$tokenData) {
            return redirect()->to('auth/forgot-password')->with('error', 'Invalid or expired reset link.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    /**
     * Process password reset
     */
    public function submit()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($token) || empty($password) || empty($confirmPassword)) {
            return redirect()->back()->with('error', 'Please fill in all fields.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
        }

        // Verify token
        $tokenData = $this->tokenModel->verifyToken($token);

        if (!$tokenData) {
            return redirect()->to('auth/forgot-password')->with('error', 'Invalid or expired reset link.');
        }

        // Update user password
        $success = $this->userModel->updatePassword($tokenData['user_id'], $password);

        if ($success) {
            // Mark token as used
            $this->tokenModel->markAsUsed($tokenData['id']);
            
            log_message('info', "Password reset successfully for user ID: {$tokenData['user_id']}");
            return redirect()->to('auth/login')->with('success', 'Password reset successfully. Please login with your new password.');
        } else {
            return redirect()->back()->with('error', 'Failed to reset password. Please try again.');
        }
    }
}
