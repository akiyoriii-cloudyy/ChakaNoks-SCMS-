<?php

namespace App\Controllers;

use App\Models\AccountsPayableModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use Config\Database;

class AccountsPayableController extends BaseController
{
    protected $db;
    protected $accountsPayableModel;
    protected $purchaseOrderModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->accountsPayableModel = new AccountsPayableModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Get all accounts payable with filters
     */
    public function getAccountsPayableList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $filters = [];
            
            // Get filter parameters
            $paymentStatus = $this->request->getGet('payment_status');
            $supplierId = $this->request->getGet('supplier_id');
            $branchId = $this->request->getGet('branch_id');
            $overdue = $this->request->getGet('overdue');
            
            if ($paymentStatus && $paymentStatus !== 'all') {
                $filters['payment_status'] = $paymentStatus;
            }
            if ($supplierId) {
                $filters['supplier_id'] = (int)$supplierId;
            }
            if ($branchId) {
                $filters['branch_id'] = (int)$branchId;
            }
            if ($overdue === 'true' || $overdue === '1') {
                $filters['overdue'] = true;
            }

            $accountsPayable = $this->accountsPayableModel->getAccountsPayableWithDetails($filters);

            // Format the response
            $formattedAP = [];
            foreach ($accountsPayable as $ap) {
                // Calculate days until due or days overdue
                $dueDate = $ap['due_date'];
                $daysInfo = null;
                if ($dueDate) {
                    $today = date('Y-m-d');
                    $daysDiff = (strtotime($dueDate) - strtotime($today)) / 86400;
                    if ($daysDiff < 0) {
                        $daysInfo = abs($daysDiff) . ' days overdue';
                    } else {
                        $daysInfo = round($daysDiff) . ' days remaining';
                    }
                }

                $formattedAP[] = [
                    'id' => $ap['id'],
                    'purchase_order_id' => $ap['purchase_order_id'],
                    'order_number' => $ap['order_number'] ?? 'N/A',
                    'supplier' => [
                        'id' => $ap['supplier_id'],
                        'name' => $ap['supplier_name'] ?? 'N/A'
                    ],
                    'branch' => [
                        'id' => $ap['branch_id'],
                        'name' => $ap['branch_name'] ?? 'N/A'
                    ],
                    'invoice_number' => $ap['invoice_number'],
                    'invoice_date' => $ap['invoice_date'],
                    'due_date' => $ap['due_date'],
                    'days_info' => $daysInfo,
                    'amount' => (float)$ap['amount'],
                    'paid_amount' => (float)$ap['paid_amount'],
                    'balance' => (float)$ap['balance'],
                    'payment_status' => $ap['payment_status'] ?? 'unpaid',
                    'payment_date' => $ap['payment_date'],
                    'payment_method' => $ap['payment_method'],
                    'payment_reference' => $ap['payment_reference'],
                    'notes' => $ap['notes'],
                    'created_at' => $ap['created_at']
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'accounts_payable' => $formattedAP,
                'count' => count($formattedAP)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching accounts payable: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Failed to fetch accounts payable: ' . $e->getMessage(),
                'accounts_payable' => []
            ]);
        }
    }

    /**
     * Get accounts payable by ID with details
     */
    public function getAccountsPayable($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable ID required']);
        }

        try {
            // Get accounts payable by ID with details
            $apList = $this->accountsPayableModel->getAccountsPayableWithDetails(['id' => $id]);
            
            if (empty($apList)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable not found']);
            }
            
            $apData = $apList[0];
            
            // Get purchase order details
            $order = $this->purchaseOrderModel->getOrderWithDetails($apData['purchase_order_id']);

            return $this->response->setJSON([
                'status' => 'success',
                'accounts_payable' => $apData,
                'purchase_order' => $order
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching accounts payable: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to fetch accounts payable']);
        }
    }

    /**
     * Update invoice information
     */
    public function updateInvoice($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable ID required']);
        }

        $invoiceNumber = $this->request->getPost('invoice_number');
        $invoiceDate = $this->request->getPost('invoice_date');

        if (!$invoiceNumber || !$invoiceDate) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invoice number and date are required']);
        }

        try {
            $ap = $this->accountsPayableModel->find((int)$id);
            
            if (!$ap) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable not found']);
            }

            // Get supplier to recalculate due date if needed
            $supplier = $this->supplierModel->find($ap['supplier_id']);
            $paymentTerms = $supplier['payment_terms'] ?? 'Net 30';
            $dueDate = $this->accountsPayableModel->calculateDueDate($paymentTerms, $invoiceDate);

            $updateData = [
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $updated = $this->accountsPayableModel->update((int)$id, $updateData);

            if ($updated) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Invoice information updated successfully'
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update invoice information']);
        } catch (\Exception $e) {
            log_message('error', 'Error updating invoice: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Record a payment
     */
    public function recordPayment($id = null)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable ID required']);
        }

        $paymentAmount = $this->request->getPost('payment_amount');
        $paymentMethod = $this->request->getPost('payment_method');
        $paymentReference = $this->request->getPost('payment_reference');

        if (!$paymentAmount || $paymentAmount <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Valid payment amount is required']);
        }

        try {
            $ap = $this->accountsPayableModel->find((int)$id);
            
            if (!$ap) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable not found']);
            }

            $currentPaid = (float)$ap['paid_amount'];
            $totalAmount = (float)$ap['amount'];
            $newPaidAmount = $currentPaid + (float)$paymentAmount;

            // Prevent overpayment
            if ($newPaidAmount > $totalAmount) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Payment amount exceeds total amount. Maximum payment: ₱' . number_format($totalAmount - $currentPaid, 2)]);
            }

            $updated = $this->accountsPayableModel->recordPayment(
                (int)$id,
                (float)$paymentAmount,
                $paymentMethod,
                $paymentReference
            );

            if ($updated) {
                // Get updated AP data
                $updatedAP = $this->accountsPayableModel->find((int)$id);
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Payment recorded successfully',
                    'accounts_payable' => $updatedAP
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to record payment']);
        } catch (\Exception $e) {
            log_message('error', 'Error recording payment: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Get accounts payable summary/statistics
     */
    public function getSummary()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $allAP = $this->accountsPayableModel->getAccountsPayableWithDetails([]);
            
            $summary = [
                'total_amount' => 0,
                'total_paid' => 0,
                'total_balance' => 0,
                'unpaid_count' => 0,
                'partial_count' => 0,
                'paid_count' => 0,
                'overdue_count' => 0,
                'overdue_amount' => 0
            ];

            $today = date('Y-m-d');
            
            foreach ($allAP as $ap) {
                $amount = (float)$ap['amount'];
                $paid = (float)$ap['paid_amount'];
                $balance = (float)$ap['balance'];
                $status = $ap['payment_status'] ?? 'unpaid';
                
                $summary['total_amount'] += $amount;
                $summary['total_paid'] += $paid;
                $summary['total_balance'] += $balance;
                
                if ($status === 'unpaid') $summary['unpaid_count']++;
                elseif ($status === 'partial') $summary['partial_count']++;
                elseif ($status === 'paid') $summary['paid_count']++;
                elseif ($status === 'overdue') {
                    $summary['overdue_count']++;
                    $summary['overdue_amount'] += $balance;
                }
                
                // Check if overdue (due date passed and not paid)
                if ($ap['due_date'] && $ap['due_date'] < $today && $status !== 'paid') {
                    if ($status !== 'overdue') {
                        $summary['overdue_count']++;
                        $summary['overdue_amount'] += $balance;
                    }
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching accounts payable summary: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to fetch summary']);
        }
    }

    /**
     * Backfill accounts payable for existing approved purchase orders
     * This is a utility method to create AP entries for POs that were approved before AP was implemented
     */
    public function backfillAccountsPayable()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            // Get all approved purchase orders that don't have AP entries
            $approvedPOs = $this->db->table('purchase_orders')
                ->select('purchase_orders.*')
                ->where('purchase_orders.status', 'approved')
                ->whereNotIn('purchase_orders.id', function($builder) {
                    return $builder->select('purchase_order_id')->from('accounts_payable');
                })
                ->get()
                ->getResultArray();

            $created = 0;
            $errors = [];

            foreach ($approvedPOs as $order) {
                // Check if AP already exists
                $existing = $this->accountsPayableModel
                    ->where('purchase_order_id', $order['id'])
                    ->first();
                
                if ($existing) {
                    continue;
                }

                // Get supplier to get payment terms
                $supplier = $this->supplierModel->find($order['supplier_id']);
                $paymentTerms = $supplier['payment_terms'] ?? 'Net 30';
                
                // Calculate due date based on payment terms
                $invoiceDate = $order['approved_at'] ? date('Y-m-d', strtotime($order['approved_at'])) : date('Y-m-d');
                $dueDate = $this->accountsPayableModel->calculateDueDate($paymentTerms, $invoiceDate);
                
                // Create accounts payable entry
                $apData = [
                    'purchase_order_id' => $order['id'],
                    'supplier_id' => (int)$order['supplier_id'],
                    'branch_id' => (int)$order['branch_id'],
                    'invoice_number' => null,
                    'invoice_date' => $invoiceDate,
                    'due_date' => $dueDate,
                    'amount' => (float)$order['total_amount'],
                    'paid_amount' => 0.00,
                    'balance' => (float)$order['total_amount'],
                    'payment_status' => 'unpaid',
                    'payment_date' => null,
                    'payment_method' => null,
                    'payment_reference' => null,
                    'notes' => 'Auto-created from approved purchase order: ' . $order['order_number'],
                    'created_by' => $order['approved_by'],
                    'created_at' => $order['approved_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $apId = $this->accountsPayableModel->insert($apData);
                
                if ($apId) {
                    $created++;
                } else {
                    $errors[] = 'Failed to create AP for PO ' . $order['order_number'];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Created {$created} accounts payable entries",
                'created' => $created,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error backfilling accounts payable: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to backfill: ' . $e->getMessage()]);
        }
    }
}
