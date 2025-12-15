<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailTestPHPMailer extends BaseController
{
    public function test()
    {
        $emailConfig = config('Email');
        
        // Get password - try multiple sources
        $smtpPass1 = $emailConfig->SMTPPass;
        $smtpPass2 = trim(str_replace(' ', '', $emailConfig->SMTPPass));
        $smtpPass3 = getenv('email.SMTPPass') ?: $smtpPass1;
        
        $output = "<h2>PHPMailer Email Test & Debug</h2>";
        $output .= "<div style='background: #e7f3ff; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        $output .= "<h3>Configuration Check:</h3>";
        $output .= "<ul>";
        $output .= "<li>Config User: " . htmlspecialchars($emailConfig->SMTPUser) . "</li>";
        $output .= "<li>Config Pass (raw): " . htmlspecialchars(substr($smtpPass1, 0, 4)) . "..." . htmlspecialchars(substr($smtpPass1, -4)) . " (length: " . strlen($smtpPass1) . ")</li>";
        $output .= "<li>Config Pass (cleaned): " . htmlspecialchars(substr($smtpPass2, 0, 4)) . "..." . htmlspecialchars(substr($smtpPass2, -4)) . " (length: " . strlen($smtpPass2) . ")</li>";
        $output .= "<li>ENV Pass: " . ($smtpPass3 ? htmlspecialchars(substr($smtpPass3, 0, 4)) . "..." . htmlspecialchars(substr($smtpPass3, -4)) . " (length: " . strlen($smtpPass3) . ")" : "Not set") . "</li>";
        $output .= "<li>From Email: " . htmlspecialchars($emailConfig->fromEmail) . "</li>";
        $output .= "</ul>";
        $output .= "</div>";
        
        // Try with the cleaned password
        $smtpPass = $smtpPass2;
        $testEmail = $emailConfig->SMTPUser; // Send to yourself
        
        $mail = new PHPMailer(true);
        
        try {
            $output .= "<div style='background: #fff3cd; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            $output .= "<h3>Attempting to send test email...</h3>";
            
            // Server settings - EXACT match to working code
            // Use DIRECT values from .env to avoid config issues
            $directUser = getenv('email.SMTPUser') ?: 'mansuetomarky@gmail.com';
            $directPass = getenv('email.SMTPPass') ?: 'xlinukvsigvqnjgr';
            $directPass = trim(str_replace([' ', "\t", "\n", "\r"], '', $directPass));
            $directUser = trim($directUser);
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $directUser;
            $mail->Password   = $directPass;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            
            $output .= "<p>Using - User: <strong>" . htmlspecialchars($directUser) . "</strong>, Pass length: <strong>" . strlen($directPass) . "</strong></p>";

            $mail->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $mail->addAddress($testEmail);
            $mail->isHTML(true);
            $mail->Subject = 'PHPMailer Test - CHAKANOKS SCMS';
            $mail->Body    = '<h2>Test Email</h2><p>This is a test email from PHPMailer.</p>';

            $mail->send();
            
            $output .= "<div style='background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px; color: #155724;'>";
            $output .= "<p><strong>✅ SUCCESS!</strong> Email sent successfully!</p>";
            $output .= "<p>Check your inbox at: <strong>" . htmlspecialchars($testEmail) . "</strong></p>";
            $output .= "</div>";
            
        } catch (Exception $e) {
            $output .= "<div style='background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px; color: #721c24;'>";
            $output .= "<p><strong>❌ FAILED</strong></p>";
            $output .= "<p><strong>Error:</strong> " . htmlspecialchars($mail->ErrorInfo) . "</p>";
            $output .= "<p><strong>Exception:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            $output .= "<hr>";
            $output .= "<h4>Debugging Steps:</h4>";
            $output .= "<ol>";
            $output .= "<li>Verify the app password is correct: <code>xlinukvsigvqnjgr</code> (16 characters)</li>";
            $output .= "<li>Check if 2-Step Verification is enabled on Gmail</li>";
            $output .= "<li>Verify the app password was generated for 'Mail' app</li>";
            $output .= "<li>Try generating a NEW app password from <a href='https://myaccount.google.com/apppasswords' target='_blank'>Google Account</a></li>";
            $output .= "</ol>";
            $output .= "</div>";
        }
        
        $output .= "</div>";
        
        return $output;
    }
}
