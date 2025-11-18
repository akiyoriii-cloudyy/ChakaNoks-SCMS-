<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'categories';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'created_by',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
    ];

    /**
     * Get all active categories
     */
    public function getActiveCategories(): array
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}

