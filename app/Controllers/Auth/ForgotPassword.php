<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PasswordResetTokenModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPassword extends BaseController
{
    protected $userModel;
    protected $tokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new PasswordResetTokenModel();
    }

    /**
     * Show forgot password form
     */
    public function index()
    {
        return view('auth/forgot_password');
    }

    /**
     * Send password reset link to user's email
     */
    public function sendResetLink()
    {
        $email = trim($this->request->getPost('email'));

        if (empty($email)) {
            return redirect()->back()->with('error', 'Please enter your email address.');
        }

        // Find user by email
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return redirect()->back()->with('error', 'No user found with this email.');
        }

        // Generate secure token
        $token = bin2hex(random_bytes(32));
        
        // Set expiration (30 minutes from now)
        $expires = time() + 1800; // 30 minutes expiry

        // Remove existing reset requests for this email
        $this->tokenModel->deleteByEmail($email);
        
        // Create new token record
        $tokenId = $this->tokenModel->createToken(
            $user['id'],
            $email,
            $token,
            date('Y-m-d H:i:s', $expires)
        );

        if (!$tokenId) {
            return redirect()->back()->with('error', 'Failed to create reset token. Please try again.');
        }

        // Send email with reset link using PHPMailer - Match working code EXACTLY
        // Use HARDCODED values like your working code to ensure no config issues
        $smtpUser = 'mansuetomarky@gmail.com';
        $smtpPass = 'xlinukvsigvqnjgr'; // App password - 16 characters, no spaces
        
        $resetLink = base_url('auth/reset-password?token=' . $token);

        $mail = new PHPMailer(true);
        
        try {
            // Server settings - EXACT match to your working code
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mansuetomarky@gmail.com';
            $mail->Password   = 'xlinukvsigvqnjgr'; // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom($smtpUser, 'CHAKANOKS SCMS');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <p>We received a password reset request for your account.</p>
                <p>Click the link below to reset your password:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>This link will expire in 30 minutes.</p>
            ";

            $mail->send();
            
            log_message('info', "Password reset link sent successfully to: {$email} using PHPMailer");
            return redirect()->back()->with('success', 'A password reset link has been sent to your email.');
            
        } catch (Exception $e) {
            log_message('error', 'Failed to send password reset email to: ' . $email);
            log_message('error', 'PHPMailer Error: ' . $mail->ErrorInfo);
            log_message('error', 'Exception: ' . $e->getMessage());
            
            // Provide detailed error message
            $errorMsg = 'Mailer Error: Could not authenticate. ';
            $errorMsg .= 'This usually means the Gmail app password is incorrect or expired. ';
            $errorMsg .= 'Please generate a NEW app password from https://myaccount.google.com/apppasswords and update it in app/Config/Email.php';
            
            return redirect()->back()->with('error', $errorMsg);
        }
    }
}
