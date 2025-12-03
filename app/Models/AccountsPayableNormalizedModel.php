<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountsPayableNormalizedModel extends Model
{
    protected $table = 'accounts_payable';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'invoice_number',
        'purchase_order_id',
        'invoice_date',
        'due_date',
        'amount',
        'amount_paid',
        'payment_status',
        'tax_amount',
        'discount_amount',
        'notes',
        'created_by',
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
    
    protected $validationRules = [
        'invoice_number' => 'required|is_unique[accounts_payable.invoice_number,id,{id}]',
        'purchase_order_id' => 'required|integer',
        'invoice_date' => 'required|valid_date',
        'due_date' => 'required|valid_date',
        'amount' => 'required|decimal',
    ];

    /**
     * After insert callback - calculate payment status
     */
    protected $afterInsert = ['calculatePaymentStatus'];
    
    /**
     * After update callback - recalculate payment status
     */
    protected $afterUpdate = ['calculatePaymentStatus'];

    /**
     * Calculate and update payment_status based on amounts and due date
     */
    protected function calculatePaymentStatus(array $data)
    {
        $id = $data['id'] ?? $data['data']['id'] ?? null;
        
        if (!$id) {
            return $data;
        }

        $record = $this->find($id);
        if (!$record) {
            return $data;
        }

        $status = $this->determinePaymentStatus(
            $record['amount'],
            $record['amount_paid'],
            $record['due_date']
        );

        // Update status if changed
        if ($record['payment_status'] !== $status) {
            $this->db->table($this->table)
                ->where('id', $id)
                ->update(['payment_status' => $status]);
        }

        return $data;
    }

    /**
     * Determine payment status based on amount paid and due date
     */
    private function determinePaymentStatus(float $amount, float $amountPaid, string $dueDate): string
    {
        if ($amountPaid >= $amount) {
            return 'paid';
        }
        
        if ($amountPaid > 0) {
            return 'partial';
        }
        
        if (strtotime($dueDate) < strtotime(date('Y-m-d'))) {
            return 'overdue';
        }
        
        return 'unpaid';
    }

    /**
     * Record a payment
     */
    public function recordPayment(int $apId, array $paymentData): bool
    {
        $ap = $this->find($apId);
        if (!$ap) {
            return false;
        }

        $this->db->transStart();

        // Create payment transaction
        $paymentModel = new \App\Models\PaymentTransactionModel();
        $paymentData['accounts_payable_id'] = $apId;
        $paymentData['payment_date'] = $paymentData['payment_date'] ?? date('Y-m-d');
        
        if (!$paymentModel->insert($paymentData)) {
            $this->db->transRollback();
            return false;
        }

        // Update amount_paid
        $newAmountPaid = $ap['amount_paid'] + $paymentData['payment_amount'];
        $this->update($apId, ['amount_paid' => $newAmountPaid]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Get accounts payable with related data
     */
    public function getWithDetails(int $id): ?array
    {
        $ap = $this->select('accounts_payable.*, purchase_orders.order_number, 
                            suppliers.name AS supplier_name, branches.name AS branch_name')
            ->join('purchase_orders', 'purchase_orders.id = accounts_payable.purchase_order_id')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('accounts_payable.id', $id)
            ->first();

        if (!$ap) {
            return null;
        }

        // Get payment history
        $paymentModel = model('PaymentTransactionModel'); // Create this model
        $ap['payments'] = $this->db->table('payment_transactions')
            ->where('accounts_payable_id', $id)
            ->orderBy('payment_date', 'DESC')
            ->get()
            ->getResultArray();

        // Calculate balance (in case not using computed column)
        $ap['balance_calculated'] = $ap['amount'] - $ap['amount_paid'];

        return $ap;
    }

    /**
     * Get overdue accounts payable
     */
    public function getOverdue(): array
    {
        return $this->select('accounts_payable.*, purchase_orders.order_number, 
                             suppliers.name AS supplier_name, branches.name AS branch_name')
            ->join('purchase_orders', 'purchase_orders.id = accounts_payable.purchase_order_id')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->where('accounts_payable.payment_status', 'overdue')
            ->orWhere('(accounts_payable.payment_status != "paid" AND accounts_payable.due_date < CURDATE())', null, false)
            ->orderBy('accounts_payable.due_date', 'ASC')
            ->findAll();
    }

    /**
     * Update all payment statuses (for maintenance)
     */
    public function updateAllPaymentStatuses(): int
    {
        return $this->db->query("
            UPDATE accounts_payable
            SET payment_status = CASE 
                WHEN amount_paid >= amount THEN 'paid'
                WHEN amount_paid > 0 THEN 'partial'
                WHEN due_date < CURDATE() THEN 'overdue'
                ELSE 'unpaid'
            END
        ")->resultID ? $this->db->affectedRows() : 0;
    }

    /**
     * Get summary statistics
     */
    public function getSummary(): array
    {
        $result = $this->db->query("
            SELECT 
                COUNT(*) as total_invoices,
                SUM(CASE WHEN payment_status = 'unpaid' THEN 1 ELSE 0 END) as unpaid_count,
                SUM(CASE WHEN payment_status = 'partial' THEN 1 ELSE 0 END) as partial_count,
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN payment_status = 'overdue' THEN 1 ELSE 0 END) as overdue_count,
                SUM(amount) as total_amount,
                SUM(amount_paid) as total_paid,
                SUM(amount - amount_paid) as total_balance
            FROM accounts_payable
        ")->getRowArray();

        return $result;
    }
}

