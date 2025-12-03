<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditTrailModel extends Model
{
    protected $table = 'audit_trail';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'table_name',
        'record_id',
        'action',
        'old_values',
        'new_values',
        'changed_fields',
        'changed_by',
        'ip_address',
        'user_agent',
        'created_at',
    ];
    
    protected $useTimestamps = false; // We handle created_at manually
    protected $returnType = 'array';
    
    /**
     * Override insert to auto-set created_at
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && !isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Log a change to the audit trail
     */
    public function logChange(string $tableName, int $recordId, string $action, ?array $oldValues = null, ?array $newValues = null, ?int $changedBy = null): bool
    {
        $changedFields = [];
        
        if ($oldValues && $newValues) {
            foreach ($newValues as $key => $value) {
                if (!isset($oldValues[$key]) || $oldValues[$key] !== $value) {
                    $changedFields[] = $key;
                }
            }
        }
        
        $data = [
            'table_name' => $tableName,
            'record_id' => $recordId,
            'action' => strtoupper($action),
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'changed_fields' => !empty($changedFields) ? implode(',', $changedFields) : null,
            'changed_by' => $changedBy,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        return $this->insert($data) !== false;
    }
    
    /**
     * Get audit trail for a specific record
     */
    public function getRecordHistory(string $tableName, int $recordId): array
    {
        return $this->select('audit_trail.*, users.email as changed_by_email')
            ->join('users', 'users.id = audit_trail.changed_by', 'left')
            ->where('audit_trail.table_name', $tableName)
            ->where('audit_trail.record_id', $recordId)
            ->orderBy('audit_trail.created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get recent changes
     */
    public function getRecentChanges(int $limit = 50): array
    {
        return $this->select('audit_trail.*, users.email as changed_by_email')
            ->join('users', 'users.id = audit_trail.changed_by', 'left')
            ->orderBy('audit_trail.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}

