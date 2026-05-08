<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use Config\Database;

class Notifications extends BaseController
{
    protected $notificationModel;
    protected $db;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->db = Database::connect();
    }
    
    /**
     * Get notifications for current user
     */
    public function getNotifications()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }
        
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $unreadOnly = $this->request->getGet('unread_only') === 'true';
        
        // Normalize role for matching
        $role = $this->normalizeRole($role);
        
        if ($unreadOnly) {
            $notifications = $this->notificationModel->getUnreadNotifications($userId, $role);
        } else {
            $notifications = $this->notificationModel->getUserNotifications($userId, $role);
        }
        
        $unreadCount = $this->notificationModel->getUnreadCount($userId, $role);
        
        // Debug logging
        log_message('debug', 'Notifications query - User ID: ' . $userId . ', Role: ' . $role . ', Found: ' . count($notifications));
        
        return $this->response->setJSON([
            'status' => 'success',
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }
        
        $userId = $session->get('user_id');
        $result = $this->notificationModel->markAsRead($notificationId, $userId);
        
        if ($result) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Notification marked as read',
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to mark notification as read',
        ]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }
        
        $userId = $session->get('user_id');
        $role = $session->get('role');
        $result = $this->notificationModel->markAllAsRead($userId, $role);
        
        if ($result) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'All notifications marked as read',
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to mark notifications as read',
        ]);
    }
    
    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }
        
        $userId = $session->get('user_id');
        $role = $session->get('role');
        
        // Normalize role for matching
        $role = $this->normalizeRole($role);
        
        $count = $this->notificationModel->getUnreadCount($userId, $role);
        
        return $this->response->setJSON([
            'status' => 'success',
            'unread_count' => $count,
        ]);
    }
    
    /**
     * Normalize role name (convert variations to standard format)
     */
    protected function normalizeRole(?string $role): ?string
    {
        if (!$role) return null;
        
        $role = strtolower(trim($role));
        
        // Map role variations to standard format
        $roleMap = [
            'centraladmin' => 'central_admin',
            'central_admin' => 'central_admin',
            'branchmanager' => 'branch_manager',
            'branch_manager' => 'branch_manager',
            'manager' => 'branch_manager',
            'inventorystaff' => 'inventory_staff',
            'inventory_staff' => 'inventory_staff',
            'staff' => 'inventory_staff',
            'logisticscoordinator' => 'logistics_coordinator',
            'logistics_coordinator' => 'logistics_coordinator',
            'franchisemanager' => 'franchise_manager',
            'franchise_manager' => 'franchise_manager',
            'systemadmin' => 'system_admin',
            'system_admin' => 'system_admin',
        ];
        
        return $roleMap[$role] ?? $role;
    }
}

