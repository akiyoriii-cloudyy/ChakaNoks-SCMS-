<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class Forgot extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session   = session();
    }

    /**
     * Show forgot password form.
     */
    public function index()
    {
        $candidates = [
            'auth/forgot',
            'auth/forgot_password',
            'auth/forgotpasswordform',
            'auth/forgotPasswordForm',
        ];

        foreach ($candidates as $viewName) {
            $path = APPPATH . 'Views/' . str_replace('/', DIRECTORY_SEPARATOR, $viewName) . '.php';
            if (is_file($path)) {
                return view($viewName);
            }
        }

        $expected = implode(", ", array_map(fn($v) => "app/Views/{$v}.php", $candidates));
        throw new \CodeIgniter\Exceptions\FrameworkException(
            "Forgot view not found. Please create one of: {$expected}"
        );
    }

    /**
     * Handle forgot password request.
     */
    public function sendResetLink()
    {
        $emailInput = trim($this->request->getPost('email'));

        if (empty($emailInput)) {
            return redirect()->back()->with('error', 'Please enter your email address.');
        }

        $user = $this->userModel->where('email', $emailInput)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'No account found with that email.');
        }

        if (is_object($user)) {
            $user = (array) $user;
        }

        // Generate token + OTP + expiries
        $token        = bin2hex(random_bytes(32));
        $tokenExpires = Time::now()->addMinutes(30);
        $otpCode      = (string) random_int(100000, 999999);
        $otpExpires   = Time::now()->addMinutes(10);

        $this->userModel->update($user['id'], [
            'reset_token'   => $token,
            'token_expires' => $tokenExpires->toDateTimeString(),
            'reset_otp'     => $otpCode,
            'otp_expires'   => $otpExpires->toDateTimeString(),
        ]);

        $resetLink = base_url("auth/reset-password?token=" . $token);
        $userName  = $user['username'] ?? $user['name'] ?? 'User';

        // Build email message (includes link + OTP)
        $messageHTML = "
            <p>Hi <b>" . esc($userName) . "</b>,</p>
            <p>You can reset your password in two ways:</p>
            <ol>
                <li>Click this link (valid for 30 minutes):<br>
                    <a href='{$resetLink}' target='_blank'>{$resetLink}</a>
                </li>
                <li>Or use this One-Time Password (OTP) within 10 minutes: <b>{$otpCode}</b><br>
                    Go to the reset page and enter your email, the OTP above, and your new password.
                </li>
            </ol>
            <p>If you didn’t request this, you can safely ignore this email.</p>
            <br>
            <p>— Chakanoks SCMS Team</p>
        ";

        // ✅ Fresh email config (from .env)
        $emailConfig = [
            'protocol'    => 'smtp',
            'SMTPHost'    => env('email.SMTPHost', 'smtp.gmail.com'),
            'SMTPUser'    => env('email.SMTPUser'),
            'SMTPPass'    => env('email.SMTPPass'),
            'SMTPPort'    => (int) env('email.SMTPPort', 587),
            'SMTPCrypto'  => env('email.SMTPCrypto', 'tls'),
            'mailType'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'wordWrap'    => true,
            'SMTPTimeout' => (int) env('email.SMTPTimeout', 30),
        ];

        // ✅ Always new service (no shared instance)
        $email = \Config\Services::email($emailConfig, false);

        $fromEmail = env('email.fromEmail', $emailConfig['SMTPUser']);
        $fromName  = env('email.fromName', 'CHAKANOKS SCMS');

        try {
            $email->setFrom($fromEmail, $fromName);
            $email->setTo($emailInput);
            $email->setSubject('Password Reset Request — Chakanoks SCMS');
            $email->setMessage($messageHTML);

            if ($email->send()) {
                return redirect()->back()->with('success', '✅ Password reset link sent to your email.');
            } else {
                $debug = $email->printDebugger(['headers', 'subject', 'body']);
                return redirect()->back()->with(
                    'error',
                    "❌ Failed to send email.<br><pre>" . esc($debug) . "</pre>"
                );
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Email error: ' . esc($e->getMessage()));
        }
    }

    public function submit()
    {
        return redirect()->to('auth/forgot')->with('info', 'This action is handled by sendResetLink.');
    }

    /**
     * Show OTP-based reset form.
     */
    public function otpForm()
    {
        // Reuse the generic reset view without token to show email+otp+password form
        return view('auth/reset');
    }

    /**
     * Verify OTP and update password.
     */
    public function verifyOtp()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $otp      = trim((string) $this->request->getPost('otp'));
        $password = trim((string) $this->request->getPost('password'));

        if ($email === '' || $otp === '' || $password === '') {
            return redirect()->back()->with('error', 'Please provide email, OTP, and a new password.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
        }

        $user = $this->userModel->where('email', $email)->first();
        if (! $user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        if (is_object($user)) {
            $user = (array) $user;
        }

        // Validate OTP
        if (empty($user['reset_otp']) || empty($user['otp_expires'])) {
            return redirect()->back()->with('error', 'No OTP request found. Please request a new reset.');
        }

        if ($user['reset_otp'] !== $otp) {
            return redirect()->back()->with('error', 'Invalid OTP code.');
        }

        if (Time::now()->isAfter($user['otp_expires'])) {
            return redirect()->back()->with('error', 'OTP has expired. Please request a new one.');
        }

        // Update password and clear reset fields
        $this->userModel->update($user['id'], [
            'password'      => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'token_expires' => null,
            'reset_otp'     => null,
            'otp_expires'   => null,
        ]);

        return redirect()->to('auth/login')->with('success', '✅ Password updated. You can now log in.');
    }
}
