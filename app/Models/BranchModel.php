<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
    protected $table      = 'branches';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'code',
        'name',
        'address'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'code' => 'required|min_length[2]|max_length[50]',
        'name' => 'required|min_length[3]|max_length[100]',
    ];

    /**
     * Get all branches
     */
    public function getAllBranches(): array
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get branch by code
     */
    public function getBranchByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}

