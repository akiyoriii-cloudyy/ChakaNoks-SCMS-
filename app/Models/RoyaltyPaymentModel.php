<?php

namespace App\Models;

use CodeIgniter\Model;

class RoyaltyPaymentModel extends Model
{
    protected $table = 'royalty_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'branch_id',
        'period_month',
        'period_year',
        'gross_sales',
        'royalty_rate',
        'royalty_amount',
        'marketing_fee',
        'total_due',
        'amount_paid',
        'balance',
        'status',
        'due_date',
        'paid_date',
        'payment_reference',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function getPaymentsWithBranch()
    {
        return $this->db->table('royalty_payments rp')
            ->select('rp.*, b.name as branch_name, b.code as branch_code')
            ->join('branches b', 'b.id = rp.branch_id', 'left')
            ->orderBy('rp.period_year', 'DESC')
            ->orderBy('rp.period_month', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getPaymentStats()
    {
        $stats = [
            'total_due' => $this->selectSum('total_due')->get()->getRow()->total_due ?? 0,
            'total_paid' => $this->selectSum('amount_paid')->get()->getRow()->amount_paid ?? 0,
            'total_balance' => $this->selectSum('balance')->get()->getRow()->balance ?? 0,
            'pending_count' => $this->where('status', 'pending')->countAllResults(false),
            'overdue_count' => $this->where('status', 'overdue')->countAllResults(false),
            'paid_count' => $this->where('status', 'paid')->countAllResults(false),
        ];
        return $stats;
    }

    public function getOverduePayments()
    {
        return $this->db->table('royalty_payments rp')
            ->select('rp.*, b.name as branch_name, b.code as branch_code')
            ->join('branches b', 'b.id = rp.branch_id', 'left')
            ->where('rp.status', 'overdue')
            ->orderBy('rp.due_date', 'ASC')
            ->get()
            ->getResultArray();
    }
}

