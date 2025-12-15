<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetTokenModel extends Model
{
    protected $table            = 'password_reset_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'email', 'token', 'expires_at', 'is_used'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Create a new password reset token
     */
    public function createToken(int $userId, string $email, string $token, string $expiresAt): ?int
    {
        $data = [
            'user_id'    => $userId,
            'email'      => $email,
            'token'      => $token,
            'expires_at' => $expiresAt,
            'is_used'    => 0,
        ];

        if ($this->insert($data)) {
            return $this->insertID();
        }

        return null;
    }

    /**
     * Verify token and check if it's valid and not expired
     */
    public function verifyToken(string $token): ?array
    {
        $tokenData = $this->where('token', $token)
                          ->where('is_used', 0)
                          ->where('expires_at >', date('Y-m-d H:i:s'))
                          ->first();

        return $tokenData;
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(int $tokenId): bool
    {
        return $this->update($tokenId, ['is_used' => 1]);
    }

    /**
     * Delete all tokens for an email
     */
    public function deleteByEmail(string $email): bool
    {
        return $this->where('email', $email)->delete();
    }
}
