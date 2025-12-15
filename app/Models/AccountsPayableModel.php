<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountsPayableModel extends Model
{
    protected $table      = 'accounts_payable';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'purchase_order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'amount_paid', // Normalized schema uses amount_paid instead of paid_amount
        'payment_status',
        'notes',
        'created_by',
        'created_at',
        'updated_at'
        // Note: supplier_id and branch_id removed in normalized schema - get from purchase_orders via join
        // Note: balance is a computed column (amount - amount_paid)
        // Note: payment_date, payment_method, payment_reference moved to payment_transactions table
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    protected $validationRules = [
        'purchase_order_id' => 'required|integer',
        'amount' => 'required|decimal',
    ];

    /**
     * Generate unique invoice number
     * Format: INV-YYYYMMDD-XXXXX (e.g., INV-20251125-00001)
     */
    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $invoiceNumber = $prefix . '-' . $date . '-' . $random;
        
        // Ensure unique invoice number
        $maxAttempts = 10;
        $attempts = 0;
        while ($this->where('invoice_number', $invoiceNumber)->first() && $attempts < $maxAttempts) {
            $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $invoiceNumber = $prefix . '-' . $date . '-' . $random;
            $attempts++;
        }
        
        // If still not unique after max attempts, add timestamp
        if ($this->where('invoice_number', $invoiceNumber)->first()) {
            $timestamp = time();
            $invoiceNumber = $prefix . '-' . $date . '-' . substr($timestamp, -5);
        }
        
        return $invoiceNumber;
    }

    /**
     * Override insert to auto-generate invoice_number
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && empty($data['invoice_number'])) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
        }
        
        // Set defaults
        if (is_array($data)) {
            $data['payment_status'] = $data['payment_status'] ?? 'unpaid';
            $data['amount_paid'] = $data['amount_paid'] ?? 0.00; // Use amount_paid (normalized schema)
            $data['invoice_date'] = $data['invoice_date'] ?? date('Y-m-d');
            
            // Calculate due date if not provided - get supplier from purchase_order
            if (empty($data['due_date']) && !empty($data['purchase_order_id'])) {
                $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
                $po = $purchaseOrderModel->find($data['purchase_order_id']);
                if ($po && !empty($po['supplier_id'])) {
                    $supplierModel = new \App\Models\SupplierModel();
                    $supplier = $supplierModel->find($po['supplier_id']);
                    if ($supplier && !empty($supplier['payment_terms'])) {
                        $data['due_date'] = $this->calculateDueDate($supplier['payment_terms'], $data['invoice_date']);
                    }
                }
            }
            
            // Default due_date if still not set (30 days from invoice)
            if (empty($data['due_date'])) {
                $data['due_date'] = date('Y-m-d', strtotime('+30 days', strtotime($data['invoice_date'])));
            }
        }
        
        return parent::insert($data, $returnID);
    }

    /**
     * Get accounts payable with related data
     */
    public function getAccountsPayableWithDetails(array $filters = []): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('accounts_payable')
            ->select('accounts_payable.*, 
                     purchase_orders.order_number,
                     purchase_orders.supplier_id,
                     purchase_orders.branch_id,
                     suppliers.name as supplier_name,
                     suppliers.payment_terms,
                     branches.name as branch_name')
            ->join('purchase_orders', 'purchase_orders.id = accounts_payable.purchase_order_id', 'left')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->join('branches', 'branches.id = purchase_orders.branch_id', 'left');

        if (!empty($filters['payment_status'])) {
            $builder->where('accounts_payable.payment_status', $filters['payment_status']);
        }

        if (!empty($filters['supplier_id'])) {
            $builder->where('purchase_orders.supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['branch_id'])) {
            $builder->where('purchase_orders.branch_id', $filters['branch_id']);
        }

        if (!empty($filters['overdue'])) {
            $builder->where('accounts_payable.due_date <', date('Y-m-d'))
                    ->where('accounts_payable.payment_status !=', 'paid');
        }

        if (!empty($filters['invoice_filter'])) {
            if ($filters['invoice_filter'] === 'with_invoice') {
                $builder->where('accounts_payable.invoice_number IS NOT NULL')
                        ->where('accounts_payable.invoice_number !=', '');
            } elseif ($filters['invoice_filter'] === 'without_invoice') {
                $builder->groupStart()
                        ->where('accounts_payable.invoice_number IS NULL')
                        ->orWhere('accounts_payable.invoice_number', '')
                        ->groupEnd();
            }
        }

        if (!empty($filters['id'])) {
            $builder->where('accounts_payable.id', (int)$filters['id']);
        }

        return $builder->orderBy('accounts_payable.due_date', 'ASC')
                      ->orderBy('accounts_payable.created_at', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Calculate due date based on payment terms
     */
    public function calculateDueDate(string $paymentTerms, string $invoiceDate = null): ?string
    {
        $baseDate = $invoiceDate ? strtotime($invoiceDate) : time();
        
        // Parse payment terms (e.g., "Net 30", "COD", "Net 15")
        if (preg_match('/net\s*(\d+)/i', $paymentTerms, $matches)) {
            $days = (int)$matches[1];
            return date('Y-m-d', strtotime("+{$days} days", $baseDate));
        }
        
        // Default to Net 30 if no match
        return date('Y-m-d', strtotime('+30 days', $baseDate));
    }

    /**
     * Update payment status based on paid amount
     */
    public function updatePaymentStatus(int $id): bool
    {
        $ap = $this->find($id);
        if (!$ap) {
            return false;
        }

        $amount = (float)$ap['amount'];
        $paidAmount = (float)($ap['amount_paid'] ?? 0); // Use amount_paid (normalized schema)
        $balance = $amount - $paidAmount;
        
        // Determine payment status
        $paymentStatus = 'unpaid';
        if ($balance <= 0) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'partial';
        }
        
        // Check if overdue
        if ($paymentStatus !== 'paid' && $ap['due_date']) {
            $today = date('Y-m-d');
            if ($ap['due_date'] < $today) {
                $paymentStatus = 'overdue';
            }
        }

        $updateData = [
            'payment_status' => $paymentStatus,
            'updated_at' => date('Y-m-d H:i:s')
            // Note: balance is computed column, no need to update
            // Note: payment_date moved to payment_transactions table
        ];

        return $this->update($id, $updateData);
    }

    /**
     * Record a payment
     */
    public function recordPayment(int $id, float $amount, string $paymentMethod = null, string $paymentReference = null): bool
    {
        $ap = $this->find($id);
        if (!$ap) {
            return false;
        }

        $newPaidAmount = (float)($ap['amount_paid'] ?? 0) + $amount;
        
        $updateData = [
            'amount_paid' => $newPaidAmount, // Use amount_paid (normalized schema)
            'updated_at' => date('Y-m-d H:i:s')
            // Note: payment_method and payment_reference moved to payment_transactions table
        ];

        $updated = $this->update($id, $updateData);
        
        if ($updated) {
            // Update payment status
            $this->updatePaymentStatus($id);
        }

        return $updated;
    }
}

