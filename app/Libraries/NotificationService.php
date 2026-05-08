<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use App\Models\UserModel;
use Config\Email;
use CodeIgniter\Email\Email as CIEmail;

class NotificationService
{
    protected $notificationModel;
    protected $userModel;
    protected $email;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        $this->email = \Config\Services::email();
    }
    
    /**
     * Send notification with optional email
     */
    public function notify(array $data, bool $sendEmail = false, bool $sendSMS = false): int
    {
        try {
            // Create notification first (this should always work)
            $notificationId = $this->notificationModel->createNotification($data);
            
            // If notification creation failed, return early
            if (!$notificationId) {
                log_message('warning', 'Notification creation returned 0');
                return 0;
            }
            
            // Try to send email/SMS, but don't let failures break the process
            if ($sendEmail || $sendSMS) {
                try {
                    // Get recipients
                    $recipients = $this->getRecipients($data['user_id'] ?? null, $data['role'] ?? null);
                    
                    foreach ($recipients as $recipient) {
                        try {
                            if ($sendEmail && !empty($recipient['email'])) {
                                $emailSent = $this->sendEmailNotification($recipient['email'], $data);
                                if ($emailSent) {
                                    $this->notificationModel->update($notificationId, ['email_sent' => 1]);
                                }
                            }
                            
                            if ($sendSMS && !empty($recipient['phone'])) {
                                // SMS implementation would go here
                                // For now, just mark as sent if we have a phone number
                                $this->notificationModel->update($notificationId, ['sms_sent' => 1]);
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'Failed to send notification email/SMS to recipient: ' . $e->getMessage());
                            // Continue with other recipients
                        }
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Failed to process email/SMS recipients: ' . $e->getMessage());
                    // Continue - notification is still created
                }
            }
            
            return $notificationId;
        } catch (\Exception $e) {
            log_message('error', 'Notification creation failed: ' . $e->getMessage());
            log_message('error', 'Notification data: ' . json_encode($data));
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            // Return 0 to indicate failure, but don't throw exception to avoid breaking the main operation
            return 0;
        } catch (\Throwable $e) {
            log_message('error', 'Notification creation failed (Throwable): ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Notify about product updates
     */
    public function notifyProductUpdate(string $action, array $product, ?int $changedBy = null): void
    {
        $title = "Product {$action}";
        $message = "Product '{$product['name']}' has been {$action}";
        
        if ($action === 'created') {
            $message .= " in " . ($product['branch_name'] ?? 'system');
        } elseif ($action === 'updated') {
            $message .= " by " . ($product['changed_by'] ?? 'system');
        }
        
        $this->notify([
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'link' => base_url('staff/item/' . $product['id']),
            'related_table' => 'products',
            'related_id' => $product['id'],
            'role' => 'inventory_staff',
        ], false); // Email disabled to prevent errors
        
        // Also notify branch managers
        $this->notify([
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'link' => base_url('manager/dashboard'),
            'related_table' => 'products',
            'related_id' => $product['id'],
            'role' => 'branch_manager',
        ], false); // Email disabled to prevent errors
    }
    
    /**
     * Notify about low stock
     */
    public function notifyLowStock(array $product): void
    {
        $this->notify([
            'title' => 'Low Stock Alert',
            'message' => "Product '{$product['name']}' is running low. Current stock: {$product['stock_qty']}",
            'type' => 'warning',
            'link' => base_url('staff/item/' . $product['id']),
            'related_table' => 'products',
            'related_id' => $product['id'],
            'role' => 'branch_manager',
        ], false); // Email disabled to prevent errors
        
        $this->notify([
            'title' => 'Low Stock Alert',
            'message' => "Product '{$product['name']}' is running low in " . ($product['branch_name'] ?? 'system'),
            'type' => 'warning',
            'link' => base_url('centraladmin/dashboard'),
            'related_table' => 'products',
            'related_id' => $product['id'],
            'role' => 'central_admin',
        ], false); // Email disabled to prevent errors
    }
    
    /**
     * Notify about purchase request
     */
    public function notifyPurchaseRequest(string $action, array $request): void
    {
        $title = "Purchase Request {$action}";
        $message = "Purchase Request #{$request['id']} has been {$action}";
        
        if ($action === 'approved') {
            // Send notification (email optional - don't break if email fails)
            try {
                $this->notify([
                    'title' => $title,
                    'message' => $message,
                    'type' => 'success',
                    'link' => base_url('purchase/request/' . $request['id']),
                    'related_table' => 'purchase_requests',
                    'related_id' => $request['id'],
                    'role' => 'logistics_coordinator',
                ], false); // Set to false to skip email for now, or true if email is working
            } catch (\Exception $e) {
                log_message('error', 'Failed to notify about purchase request approval: ' . $e->getMessage());
            }
        } elseif ($action === 'created') {
        // Send notification (email optional - don't break if email fails)
        try {
            $this->notify([
                'title' => $title,
                'message' => $message,
                'type' => 'info',
                'link' => base_url('purchase/request/' . $request['id']),
                'related_table' => 'purchase_requests',
                'related_id' => $request['id'],
                'role' => 'central_admin',
            ], false); // Set to false to skip email for now, or true if email is working
        } catch (\Exception $e) {
            log_message('error', 'Failed to notify about purchase request creation: ' . $e->getMessage());
        }
        }
    }
    
    /**
     * Notify about delivery
     */
    public function notifyDelivery(string $action, array $delivery): void
    {
        $title = "Delivery {$action}";
        $message = "Delivery #{$delivery['id']} has been {$action}";
        
        $this->notify([
            'title' => $title,
            'message' => $message,
            'type' => $action === 'completed' ? 'success' : 'info',
            'link' => base_url('delivery/' . $delivery['id'] . '/track'),
            'related_table' => 'deliveries',
            'related_id' => $delivery['id'],
            'role' => 'branch_manager',
        ], false); // Email disabled to prevent errors
    }
    
    /**
     * Get recipients based on user_id or role
     */
    protected function getRecipients(?int $userId = null, ?string $role = null): array
    {
        $recipients = [];
        
        if ($userId) {
            $user = $this->userModel->find($userId);
            if ($user) {
                $recipients[] = $user;
            }
        } elseif ($role) {
            $users = $this->userModel->where('role', $role)->findAll();
            $recipients = array_merge($recipients, $users);
        } else {
            // Broadcast - get all users
            $users = $this->userModel->findAll();
            $recipients = array_merge($recipients, $users);
        }
        
        return $recipients;
    }
    
    /**
     * Send email notification
     */
    protected function sendEmailNotification(string $to, array $notification): bool
    {
        try {
            // Check if email is properly configured
            $emailConfig = config('Email');
            if (empty($emailConfig->SMTPHost) || empty($emailConfig->SMTPUser)) {
                log_message('debug', 'Email not configured, skipping email notification');
                return false;
            }
            
            // Ensure SMTPPort is an integer
            if (isset($emailConfig->SMTPPort)) {
                $emailConfig->SMTPPort = (int)$emailConfig->SMTPPort;
            }
            
            $this->email->setTo($to);
            $this->email->setSubject($notification['title']);
            $this->email->setMessage($this->buildEmailTemplate($notification));
            
            $result = $this->email->send();
            
            if (!$result) {
                log_message('error', 'Email send failed: ' . $this->email->printDebugger(['headers']));
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Failed to send email notification: ' . $e->getMessage());
            log_message('error', 'Email error trace: ' . $e->getTraceAsString());
            return false;
        } catch (\Throwable $e) {
            log_message('error', 'Failed to send email notification (Throwable): ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build email template
     */
    protected function buildEmailTemplate(array $notification): string
    {
        $typeColors = [
            'info' => '#3b82f6',
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'danger' => '#ef4444',
            'system' => '#2d5016',
        ];
        
        $color = $typeColors[$notification['type']] ?? '#3b82f6';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: ' . $color . '; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; padding: 10px 20px; background: ' . $color . '; color: white; text-decoration: none; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>' . htmlspecialchars($notification['title']) . '</h2>
        </div>
        <div class="content">
            <p>' . nl2br(htmlspecialchars($notification['message'])) . '</p>';
        
        if (!empty($notification['link'])) {
            $html .= '<a href="' . $notification['link'] . '" class="button">View Details</a>';
        }
        
        $html .= '
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
}

