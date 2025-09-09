<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Email\Email;

class EmailTest extends BaseController
{
    public function index()
    {
        return view('auth/emailtest'); // create a simple view with "Send Test Email" button
    }

    public function sendTest()
    {
        $email = \Config\Services::email();

        $fromEmail = env('email.fromEmail');
        $fromName  = env('email.fromName');
        $to        = env('email.SMTPUser'); // send to yourself

        $email->setFrom($fromEmail, $fromName);
        $email->setTo($to);
        $email->setSubject("✅ Test Email — Chakanoks SCMS");
        $email->setMessage("<p>This is a <b>test email</b> from <u>Chakanoks SCMS</u>.</p>");

        if ($email->send()) {
            return "✅ Email sent successfully to {$to}";
        } else {
            return "❌ Failed to send email.<br><pre>" . $email->printDebugger(['headers', 'subject', 'body']) . "</pre>";
        }
    }
}
