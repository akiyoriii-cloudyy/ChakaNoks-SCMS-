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
     * Update user password
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
    }
}
