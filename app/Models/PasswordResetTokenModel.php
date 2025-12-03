<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetTokenModel extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'user_id',
        'token',
        'otp',
        'otp_expires',
        'reset_expires',
        'is_used',
        'used_at',
        'ip_address',
        'user_agent',
        'created_at',
    ];
    
    protected $useTimestamps = false; // We handle created_at manually
    protected $returnType = 'array';
    
    /**
     * Create a new password reset token
     */
    public function createToken(int $userId, string $token, string $otp, string $otpExpires, string $resetExpires): bool
    {
        // Invalidate any existing tokens for this user
        $this->where('user_id', $userId)
            ->where('is_used', false)
            ->set('is_used', true)
            ->set('used_at', date('Y-m-d H:i:s'))
            ->update();

        // Create new token
        return $this->insert([
            'user_id' => $userId,
            'token' => $token,
            'otp' => $otp,
            'otp_expires' => $otpExpires,
            'reset_expires' => $resetExpires,
            'is_used' => false,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
        ]) !== false;
    }

    /**
     * Verify token is valid
     */
    public function verifyToken(string $token): ?array
    {
        $tokenData = $this->where('token', $token)
            ->where('is_used', false)
            ->first();

        if (!$tokenData) {
            return null;
        }

        // Check if expired
        if (strtotime($tokenData['reset_expires']) < time()) {
            return null;
        }

        return $tokenData;
    }

    /**
     * Verify OTP for a user
     */
    public function verifyOTP(int $userId, string $otp): ?array
    {
        $tokenData = $this->where('user_id', $userId)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->first();

        if (!$tokenData) {
            return null;
        }

        // Check if OTP expired
        if (strtotime($tokenData['otp_expires']) < time()) {
            return null;
        }

        return $tokenData;
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(int $tokenId): bool
    {
        return $this->update($tokenId, [
            'is_used' => true,
            'used_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Clean up expired tokens (for maintenance)
     */
    public function cleanExpiredTokens(): int
    {
        $count = $this->where('reset_expires <', date('Y-m-d H:i:s'))
            ->orWhere('is_used', true)
            ->countAllResults();

        $this->where('reset_expires <', date('Y-m-d H:i:s'))
            ->orWhere('is_used', true)
            ->delete();

        return $count;
    }
}

