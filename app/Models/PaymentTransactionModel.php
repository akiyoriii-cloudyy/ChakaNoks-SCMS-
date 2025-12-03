<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentTransactionModel extends Model
{
    protected $table = 'payment_transactions';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'payment_number',
        'accounts_payable_id',
        'payment_date',
        'payment_amount',
        'payment_method',
        'payment_reference',
        'bank_name',
        'check_number',
        'notes',
        'paid_by',
        'recorded_by',
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
    
    protected $validationRules = [
        'payment_number' => 'required|is_unique[payment_transactions.payment_number,id,{id}]',
        'accounts_payable_id' => 'required|integer',
        'payment_date' => 'required|valid_date',
        'payment_amount' => 'required|decimal',
        'payment_method' => 'required',
    ];

    /**
     * Generate unique payment number
     */
    public function generatePaymentNumber(): string
    {
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $paymentNumber = "PAY-{$date}-{$random}";

        // Ensure uniqueness
        $count = 1;
        $originalNumber = $paymentNumber;
        while ($this->where('payment_number', $paymentNumber)->first()) {
            $paymentNumber = $originalNumber . '-' . $count++;
        }

        return $paymentNumber;
    }

    /**
     * Override insert to auto-generate payment number
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && empty($data['payment_number'])) {
            $data['payment_number'] = $this->generatePaymentNumber();
        }

        return parent::insert($data, $returnID);
    }

    /**
     * Get payment with related data
     */
    public function getWithDetails(int $id): ?array
    {
        return $this->select('payment_transactions.*, 
                             accounts_payable.invoice_number,
                             u1.email as paid_by_email,
                             u2.email as recorded_by_email')
            ->join('accounts_payable', 'accounts_payable.id = payment_transactions.accounts_payable_id')
            ->join('users u1', 'u1.id = payment_transactions.paid_by', 'left')
            ->join('users u2', 'u2.id = payment_transactions.recorded_by', 'left')
            ->where('payment_transactions.id', $id)
            ->first();
    }

    /**
     * Get payments for an invoice
     */
    public function getByInvoice(int $apId): array
    {
        return $this->select('payment_transactions.*, 
                             u1.email as paid_by_email,
                             u2.email as recorded_by_email')
            ->join('users u1', 'u1.id = payment_transactions.paid_by', 'left')
            ->join('users u2', 'u2.id = payment_transactions.recorded_by', 'left')
            ->where('payment_transactions.accounts_payable_id', $apId)
            ->orderBy('payment_transactions.payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Get payments by date range
     */
    public function getByDateRange(string $dateFrom, string $dateTo): array
    {
        return $this->select('payment_transactions.*, 
                             accounts_payable.invoice_number,
                             suppliers.name as supplier_name')
            ->join('accounts_payable', 'accounts_payable.id = payment_transactions.accounts_payable_id')
            ->join('purchase_orders', 'purchase_orders.id = accounts_payable.purchase_order_id')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->where('payment_transactions.payment_date >=', $dateFrom)
            ->where('payment_transactions.payment_date <=', $dateTo)
            ->orderBy('payment_transactions.payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Get payment summary by method
     */
    public function getSummaryByMethod(string $dateFrom = null, string $dateTo = null): array
    {
        $builder = $this->db->table($this->table);
        
        if ($dateFrom) {
            $builder->where('payment_date >=', $dateFrom);
        }
        
        if ($dateTo) {
            $builder->where('payment_date <=', $dateTo);
        }

        return $builder->select('payment_method, COUNT(*) as count, SUM(payment_amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->getResultArray();
    }
}

