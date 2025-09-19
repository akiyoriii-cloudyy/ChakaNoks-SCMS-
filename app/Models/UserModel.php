<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Table name
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    // Allowed columns (make sure these columns exist in your `users` table)
    protected $allowedFields = [
        'branch_id',
        'email',
        'username',
        'password',
        'role',
        'reset_otp',        // OTP for password reset
        'otp_expires',      // OTP expiration timestamp
        'reset_token',      // Token for password reset via email
        'token_expires',    // Token expiration timestamp
        'created_at',
        'updated_at',
    ];

    // Enable timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------- HELPER METHODS --------------------

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Verify user credentials (email + password)
     */
    public function verifyUser(string $email, string $password): ?array
    {
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    /**
     * Create a new user (password auto-hashed)
     */
    public function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->insert($data);
        return $this->getInsertID();
    }

    /**
     * Update user password and clear reset tokens
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password'      => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'token_expires' => null,
            'reset_otp'     => null,
            'otp_expires'   => null,
        ]);
    }

    /**
     * Set reset token + expiry for email reset
     */
    public function setResetToken(int $userId, string $token, string $expires): bool
    {
        return $this->update($userId, [
            'reset_token'   => $token,
            'token_expires' => $expires,
        ]);
    }

    /**
     * Get user by reset token
     */
    public function getUserByResetToken(string $token)
    {
        return $this->where('reset_token', $token)
                    ->where('token_expires >=', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Set OTP + expiry for forgot password OTP flow
     */
    public function setResetOtp(int $userId, string $otp, string $expires): bool
    {
        return $this->update($userId, [
            'reset_otp'   => $otp,
            'otp_expires' => $expires,
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyResetOtp(int $userId, string $otp): bool
    {
        $user = $this->find($userId);
        if ($user && $user['reset_otp'] === $otp && strtotime($user['otp_expires']) >= time()) {
            return true;
        }
        return false;
    }
}
