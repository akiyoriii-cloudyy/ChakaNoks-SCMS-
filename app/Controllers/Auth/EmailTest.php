<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Email\Email;

class EmailTest extends BaseController
{
    public function sendTest()
    {
        // 📌 Load values from .env
        $fromEmail = env('email.fromEmail');
        $fromName  = env('email.fromName');
        $to        = env('email.SMTPUser'); // send test to yourself

        // Try TLS:587 first, then fallback to SSL:465 if socket cannot connect
        $attempts = [
            [
                'label'       => 'TLS:587',
                'protocol'    => 'smtp',
                'SMTPHost'    => env('email.SMTPHost', 'smtp.gmail.com'),
                'SMTPUser'    => env('email.SMTPUser'),
                'SMTPPass'    => env('email.SMTPPass'),
                'SMTPPort'    => 587,
                'SMTPCrypto'  => 'tls',
            ],
            [
                'label'       => 'SSL:465',
                'protocol'    => 'smtp',
                'SMTPHost'    => env('email.SMTPHost', 'smtp.gmail.com'),
                'SMTPUser'    => env('email.SMTPUser'),
                'SMTPPass'    => env('email.SMTPPass'),
                'SMTPPort'    => 465,
                'SMTPCrypto'  => 'ssl',
            ],
        ];

        $logs = [];
        foreach ($attempts as $cfg) {
            $config = array_merge($cfg, [
                'mailType'    => env('email.mailType', 'html'),
                'charset'     => env('email.charset', 'utf-8'),
                'newline'     => env('email.newline', "\r\n"),
                'crlf'        => env('email.crlf', "\r\n"),
                'SMTPTimeout' => (int) env('email.SMTPTimeout', 30),
            ]);

            $email = new Email();
            $email->initialize($config);
            $email->setFrom($fromEmail, $fromName);
            $email->setTo($to);
            $email->setSubject("✅ Test Email — Chakanoks SCMS");
            $email->setMessage("<p>This is a <b>test email</b> from <u>Chakanoks SCMS</u> via <code>{$cfg['label']}</code>.</p>");

            if ($email->send()) {
                return "<p style='color:green'>✅ SUCCESS — Email sent to {$to} using {$cfg['label']}</p>";
            }

            $logs[] = "[{$cfg['label']}]\n" . $email->printDebugger(['headers', 'subject']);
        }

        return "<p style='color:red'>❌ FAILED — Could not send email via TLS:587 nor SSL:465</p>"
             . "<pre>" . htmlspecialchars(implode("\n\n---\n\n", $logs)) . "</pre>";
    }
}
