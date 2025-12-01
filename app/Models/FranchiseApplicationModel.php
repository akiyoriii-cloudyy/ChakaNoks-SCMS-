<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseApplicationModel extends Model
{
    protected $table = 'franchise_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'applicant_name',
        'email',
        'phone',
        'proposed_location',
        'city',
        'investment_capital',
        'business_experience',
        'status',
        'notes',
        'reviewed_by',
        'reviewed_at'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getApplicationsByStatus($status = null)
    {
        $builder = $this->builder();
        if ($status) {
            $builder->where('status', $status);
        }
        return $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
    }

    public function getApplicationStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'pending' => $this->where('status', 'pending')->countAllResults(false),
            'under_review' => $this->where('status', 'under_review')->countAllResults(false),
            'approved' => $this->where('status', 'approved')->countAllResults(false),
            'rejected' => $this->where('status', 'rejected')->countAllResults(false),
        ];
        return $stats;
    }
}

