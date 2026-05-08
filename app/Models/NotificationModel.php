<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'user_id',
        'role',
        'type',
        'title',
        'message',
        'link',
        'related_table',
        'related_id',
        'is_read',
        'email_sent',
        'sms_sent',
        'read_at',
    ];
    
    protected $useTimestamps = false; // We'll handle timestamps manually to avoid SQL issues
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $returnType = 'array';
    
    /**
     * Create a notification
     */
    public function createNotification(array $data): int
    {
        // Normalize role to standard format
        $role = $data['role'] ?? null;
        if ($role) {
            $role = $this->normalizeRole($role);
        }
        
        // Ensure all required fields are present
        $notificationData = [
            'user_id' => $data['user_id'] ?? null,
            'role' => $role,
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'] ?? '',
            'message' => $data['message'] ?? '',
            'link' => $data['link'] ?? null,
            'related_table' => $data['related_table'] ?? null,
            'related_id' => $data['related_id'] ?? null,
            'is_read' => 0,
            'email_sent' => 0,
            'sms_sent' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        // Only include fields that are in allowedFields
        $cleanData = [];
        foreach ($notificationData as $key => $value) {
            if (in_array($key, $this->allowedFields)) {
                $cleanData[$key] = $value;
            }
        }
        
        try {
            $result = $this->insert($cleanData);
            return $result ? $this->getInsertID() : 0;
        } catch (\Exception $e) {
            log_message('error', 'Notification creation failed: ' . $e->getMessage());
            log_message('error', 'Data: ' . json_encode($cleanData));
            return 0;
        }
    }
    
    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications(?int $userId = null, ?string $role = null, int $limit = 50): array
    {
        $builder = $this->where('is_read', 0);
        
        // Normalize role for matching
        $normalizedRole = $this->normalizeRole($role);
        $roleVariants = $this->getRoleVariants($role);
        
        if ($userId) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        } else {
            $builder->where('user_id', null);
        }
        
        if ($role) {
            $builder->groupStart()
                ->whereIn('role', $roleVariants)
                ->orWhere('role', null)
                ->groupEnd();
        } else {
            // If no role specified, get notifications with null role (broadcast)
            $builder->where('role', null);
        }
        
        return $builder->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Get all notifications for a user
     */
    public function getUserNotifications(?int $userId = null, ?string $role = null, int $limit = 100): array
    {
        $builder = $this->select('notifications.*');
        
        // Normalize role for matching
        $roleVariants = $this->getRoleVariants($role);
        
        if ($userId) {
            $builder->groupStart()
                ->where('notifications.user_id', $userId)
                ->orWhere('notifications.user_id', null)
                ->groupEnd();
        } else {
            $builder->where('notifications.user_id', null);
        }
        
        if ($role) {
            $builder->groupStart()
                ->whereIn('notifications.role', $roleVariants)
                ->orWhere('notifications.role', null)
                ->groupEnd();
        } else {
            // If no role specified, get notifications with null role (broadcast)
            $builder->where('notifications.role', null);
        }
        
        return $builder->orderBy('notifications.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, ?int $userId = null): bool
    {
        $data = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
        ];
        
        $builder = $this->where('id', $notificationId);
        
        if ($userId) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }
        
        return $this->update($notificationId, $data);
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(?int $userId = null, ?string $role = null): bool
    {
        $builder = $this->where('is_read', 0);
        
        if ($userId) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        }
        
        if ($role) {
            $builder->groupStart()
                ->where('role', $role)
                ->orWhere('role', null)
                ->groupEnd();
        }
        
        return $this->set([
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
        ])->update();
    }
    
    /**
     * Get unread count for a user
     */
    public function getUnreadCount(?int $userId = null, ?string $role = null): int
    {
        $builder = $this->where('is_read', 0);
        
        // Normalize role for matching
        $roleVariants = $this->getRoleVariants($role);
        
        if ($userId) {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', null)
                ->groupEnd();
        } else {
            $builder->where('user_id', null);
        }
        
        if ($role) {
            $builder->groupStart()
                ->whereIn('role', $roleVariants)
                ->orWhere('role', null)
                ->groupEnd();
        } else {
            // If no role specified, get notifications with null role (broadcast)
            $builder->where('role', null);
        }
        
        return $this->countAllResults(false);
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
            'inventorystaff' => 'inventory_staff',
            'inventory_staff' => 'inventory_staff',
            'logisticscoordinator' => 'logistics_coordinator',
            'logistics_coordinator' => 'logistics_coordinator',
            'franchisemanager' => 'franchise_manager',
            'franchise_manager' => 'franchise_manager',
            'systemadmin' => 'system_admin',
            'system_admin' => 'system_admin',
        ];
        
        return $roleMap[$role] ?? $role;
    }
    
    /**
     * Get all possible role variants for matching
     */
    protected function getRoleVariants(?string $role): array
    {
        if (!$role) return [null];
        
        $normalized = $this->normalizeRole($role);
        $variants = [$normalized, null]; // Include null for broadcast notifications
        
        // Add common variations
        $variations = [
            'central_admin' => ['central_admin', 'centraladmin'],
            'branch_manager' => ['branch_manager', 'branchmanager', 'manager'],
            'inventory_staff' => ['inventory_staff', 'inventorystaff', 'staff'],
            'logistics_coordinator' => ['logistics_coordinator', 'logisticscoordinator'],
            'franchise_manager' => ['franchise_manager', 'franchisemanager'],
            'system_admin' => ['system_admin', 'systemadmin'],
        ];
        
        if (isset($variations[$normalized])) {
            $variants = array_merge($variants, $variations[$normalized]);
        }
        
        return array_unique($variants);
    }
    
    /**
     * Broadcast notification to all users with a specific role
     */
    public function broadcastToRole(string $role, array $data): array
    {
        $notificationIds = [];
        $data['role'] = $role;
        $data['user_id'] = null;
        
        $notificationId = $this->createNotification($data);
        if ($notificationId) {
            $notificationIds[] = $notificationId;
        }
        
        return $notificationIds;
    }
    
    /**
     * Broadcast notification to all users
     */
    public function broadcastToAll(array $data): array
    {
        $notificationIds = [];
        $data['role'] = null;
        $data['user_id'] = null;
        
        $notificationId = $this->createNotification($data);
        if ($notificationId) {
            $notificationIds[] = $notificationId;
        }
        
        return $notificationIds;
    }
}

