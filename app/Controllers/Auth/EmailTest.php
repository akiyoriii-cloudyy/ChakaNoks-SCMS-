<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Email\Email;

class EmailTest extends BaseController
{
    public function sendTest()
    {
        // Use Email config with updated App Password
        $emailConfig = config('Email');
        
        // Ensure SMTPPort is integer (critical for fsockopen)
        $smtpPort = (int) $emailConfig->SMTPPort;
        $smtpTimeout = (int) ($emailConfig->SMTPTimeout ?? 30);
        
        $fromEmail = $emailConfig->fromEmail;
        $fromName  = $emailConfig->fromName;
        $to        = $emailConfig->SMTPUser; // send test to yourself

        // Try configured settings first
        $attempts = [
            [
                'label'       => 'Configured (' . $emailConfig->SMTPCrypto . ':' . $smtpPort . ')',
                'protocol'    => $emailConfig->protocol,
                'SMTPHost'    => $emailConfig->SMTPHost,
                'SMTPUser'    => $emailConfig->SMTPUser,
                'SMTPPass'    => $emailConfig->SMTPPass,
                'SMTPPort'    => $smtpPort,
                'SMTPCrypto'  => $emailConfig->SMTPCrypto,
                'SMTPTimeout' => $smtpTimeout,
            ],
        ];

        $logs = [];
        foreach ($attempts as $cfg) {
            $config = $cfg; // Already includes SMTPPort and SMTPTimeout as integers
            $config['mailType'] = $emailConfig->mailType;
            $config['charset'] = $emailConfig->charset;
            $config['newline'] = $emailConfig->newline;
            $config['crlf'] = "\r\n";
            $config['wordWrap'] = $emailConfig->wordWrap;

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
