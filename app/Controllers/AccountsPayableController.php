<?php

namespace App\Controllers;

use App\Models\AccountsPayableModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use App\Models\PaymentTransactionModel;
use Config\Database;

class AccountsPayableController extends BaseController
{
    protected $db;
    protected $accountsPayableModel;
    protected $purchaseOrderModel;
    protected $supplierModel;
    protected $paymentTransactionModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->accountsPayableModel = new AccountsPayableModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->paymentTransactionModel = new PaymentTransactionModel();
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
            $invoiceFilter = $this->request->getGet('invoice_filter');
            
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
            if ($invoiceFilter && $invoiceFilter !== 'all') {
                $filters['invoice_filter'] = $invoiceFilter;
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

                // Get latest payment transaction for this accounts payable
                $paymentDate = null;
                $paymentMethod = null;
                $paymentReference = null;
                
                try {
                    $paymentTransactions = $this->paymentTransactionModel->getByInvoice((int)$ap['id']);
                    if (!empty($paymentTransactions)) {
                        $latestPayment = $paymentTransactions[0]; // Already sorted by date DESC, then id DESC
                        $paymentDate = $latestPayment['payment_date'] ?? null;
                        $paymentMethod = $latestPayment['payment_method'] ?? null;
                        $paymentReference = $latestPayment['payment_reference'] ?? null;
                        
                        // Log retrieved payment method for debugging
                        log_message('debug', "AP ID {$ap['id']}: Retrieved payment method: " . ($paymentMethod ?? 'NULL') . " from transaction ID: " . ($latestPayment['id'] ?? 'N/A'));
                    } else {
                        log_message('debug', "AP ID {$ap['id']}: No payment transactions found");
                    }
                } catch (\Exception $e) {
                    // If payment_transactions table doesn't exist or error, just log and continue
                    log_message('error', 'Payment transactions not available for AP ' . $ap['id'] . ': ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
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
                    'paid_amount' => (float)($ap['amount_paid'] ?? $ap['paid_amount'] ?? 0), // Map amount_paid to paid_amount
                    'balance' => (float)$ap['balance'],
                    'payment_status' => $ap['payment_status'] ?? 'unpaid',
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $paymentReference,
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
     * Show accounts payable list page
     */
    public function showAccountsPayableList()
    {
        $session = session();
        
        // Allow central admin, branch managers, and staff to view accounts payable
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin', 'branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return redirect()->to('/auth/login');
        }
        
        $role = $session->get('role');
        $branchId = $session->get('branch_id');

        // Fetch accounts payable data server-side
        try {
            $builder = $this->db->table('accounts_payable')
                ->select('accounts_payable.*, suppliers.name as supplier_name, purchase_orders.order_number, purchase_orders.branch_id, purchase_orders.supplier_id, branches.name as branch_name')
                ->join('purchase_orders', 'purchase_orders.id = accounts_payable.purchase_order_id', 'left')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left');
            
            // Branch managers and staff only see their branch's accounts payable
            // Get branch_id from purchase_orders (normalized schema)
            if (!in_array($role, ['central_admin', 'superadmin']) && $branchId) {
                $builder->where('purchase_orders.branch_id', $branchId);
            }
            
            // Apply filters
            $paymentStatus = $this->request->getGet('payment_status');
            $invoiceFilter = $this->request->getGet('invoice_filter');
            
            if ($paymentStatus && $paymentStatus !== 'all') {
                $builder->where('accounts_payable.payment_status', $paymentStatus);
            }
            
            if ($invoiceFilter && $invoiceFilter !== 'all') {
                if ($invoiceFilter === 'with_invoice') {
                    $builder->where('accounts_payable.invoice_number IS NOT NULL')
                            ->where('accounts_payable.invoice_number !=', '');
                } elseif ($invoiceFilter === 'without_invoice') {
                    $builder->groupStart()
                            ->where('accounts_payable.invoice_number IS NULL')
                            ->orWhere('accounts_payable.invoice_number', '')
                            ->groupEnd();
                }
            }
            
            $accountsPayable = $builder->orderBy('accounts_payable.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Format the data
            $formattedAP = [];
            foreach ($accountsPayable as $ap) {
                $totalAmount = (float)($ap['amount'] ?? 0);
                $paidAmount = (float)($ap['paid_amount'] ?? 0);
                $balance = (float)($ap['balance'] ?? ($totalAmount - $paidAmount));
                
                $formattedAP[] = [
                    'id' => $ap['id'],
                    'invoice_number' => $ap['invoice_number'] ?? null,
                    'supplier' => [
                        'id' => $ap['supplier_id'],
                        'name' => $ap['supplier_name'] ?? 'N/A'
                    ],
                    'purchase_order' => [
                        'id' => $ap['purchase_order_id'],
                        'order_number' => $ap['order_number'] ?? 'N/A'
                    ],
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'balance' => $balance,
                    'payment_status' => $ap['payment_status'] ?? 'unpaid',
                    'payment_date' => $ap['payment_date'] ?? null,
                    'payment_method' => $ap['payment_method'] ?? null,
                    'payment_reference' => $ap['payment_reference'] ?? null,
                    'due_date' => $ap['due_date'],
                    'created_at' => $ap['created_at']
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching accounts payable: ' . $e->getMessage());
            $formattedAP = [];
        }

        // Get pending approvals for sidebar badge
        $purchaseRequestModel = new \App\Models\PurchaseRequestModel();
        $pendingRequests = $purchaseRequestModel->getPendingRequests();
        $dashboardData = [
            'purchaseRequests' => [
                'pending_approvals' => count($pendingRequests)
            ]
        ];

        return view('dashboards/accounts_payable_list', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'accounts_payable' => $formattedAP,
            'data' => $dashboardData
        ]);
    }

    /**
     * Get monthly accounts payable data for reporting
     */
    public function getMonthlyAccountsPayable()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin', 'branch_manager', 'manager'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authorized'
            ]);
        }
        
        try {
            $month = $this->request->getGet('month'); // Format: YYYY-MM
            
            if (!$month) {
                // Use current month if not specified
                $month = date('Y-m');
            }
            
            // Parse month - handle both YYYY-MM and YYYY-M formats
            $monthParts = explode('-', $month);
            if (count($monthParts) !== 2) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid month format. Expected YYYY-MM',
                    'accounts_payable' => []
                ]);
            }
            
            $year = (int)$monthParts[0];
            $monthNum = (int)$monthParts[1];
            
            // Validate month
            if ($monthNum < 1 || $monthNum > 12) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid month. Month must be between 1 and 12',
                    'accounts_payable' => []
                ]);
            }
            
            $startDate = date('Y-m-01', strtotime("$year-$monthNum-01"));
            $endDate = date('Y-m-t', strtotime("$year-$monthNum-01"));
            
            // Build query
            $builder = $this->db->table('accounts_payable')
                ->select('accounts_payable.*, suppliers.name as supplier_name, purchase_orders.order_number, purchase_orders.branch_id, purchase_orders.supplier_id, branches.name as branch_name')
                ->join('purchase_orders', 'purchase_orders.id = accounts_payable.purchase_order_id', 'left')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('DATE(accounts_payable.created_at) >=', $startDate)
                ->where('DATE(accounts_payable.created_at) <=', $endDate);
            
            // Branch managers only see their branch's accounts payable
            $role = $session->get('role');
            $branchId = $session->get('branch_id');
            if (!in_array($role, ['central_admin', 'superadmin']) && $branchId) {
                $builder->where('purchase_orders.branch_id', $branchId);
            }
            
            $accountsPayable = $builder->orderBy('accounts_payable.created_at', 'DESC')
                ->get()
                ->getResultArray();
            
            // Format the data
            $formattedAP = [];
            foreach ($accountsPayable as $ap) {
                $totalAmount = (float)($ap['amount'] ?? 0);
                $paidAmount = (float)($ap['paid_amount'] ?? 0);
                $balance = (float)($ap['balance'] ?? ($totalAmount - $paidAmount));
                
                $formattedAP[] = [
                    'id' => $ap['id'],
                    'invoice_number' => $ap['invoice_number'] ?? null,
                    'supplier' => [
                        'id' => $ap['supplier_id'] ?? null,
                        'name' => $ap['supplier_name'] ?? 'N/A'
                    ],
                    'branch' => [
                        'id' => $ap['branch_id'] ?? null,
                        'name' => $ap['branch_name'] ?? 'N/A',
                        'address' => $ap['branch_address'] ?? null
                    ],
                    'purchase_order' => [
                        'id' => $ap['purchase_order_id'] ?? null,
                        'order_number' => $ap['order_number'] ?? 'N/A'
                    ],
                    'total_amount' => $totalAmount,
                    'amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'balance' => $balance,
                    'payment_status' => $ap['payment_status'] ?? 'unpaid',
                    'payment_date' => $ap['payment_date'] ?? null,
                    'payment_method' => $ap['payment_method'] ?? null,
                    'payment_reference' => $ap['payment_reference'] ?? null,
                    'due_date' => $ap['due_date'] ?? null,
                    'created_at' => $ap['created_at'] ?? null
                ];
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'accounts_payable' => $formattedAP,
                'count' => count($formattedAP),
                'month' => $month
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching monthly accounts payable: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch monthly accounts payable: ' . $e->getMessage(),
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
        
        // Allow central admin, superadmin, and branch managers to view accounts payable details
        $allowedRoles = ['central_admin', 'superadmin', 'branch_manager', 'manager', 'inventory_staff', 'inventorystaff'];
        if (!$session->get('logged_in') || !in_array($session->get('role'), $allowedRoles)) {
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
            
            // Branch managers can only access accounts payable for their own branch
            $role = $session->get('role');
            $userBranchId = $session->get('branch_id');
            if (!in_array($role, ['central_admin', 'superadmin']) && $userBranchId) {
                if (empty($apData['branch_id']) || (int)$apData['branch_id'] !== (int)$userBranchId) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to access this accounts payable']);
                }
            }
            
            // Get payment transactions for this accounts payable
            try {
                $paymentTransactions = $this->paymentTransactionModel->getByInvoice((int)$id);
                
                // Get the latest payment transaction for display
                $latestPayment = !empty($paymentTransactions) ? $paymentTransactions[0] : null;
                
                // Add payment information to AP data
                if ($latestPayment) {
                    $apData['payment_method'] = $latestPayment['payment_method'] ?? null;
                    $apData['payment_reference'] = $latestPayment['payment_reference'] ?? null;
                    $apData['payment_date'] = $latestPayment['payment_date'] ?? null;
                    $apData['payment_transactions'] = $paymentTransactions;
                } else {
                    $apData['payment_method'] = null;
                    $apData['payment_reference'] = null;
                    $apData['payment_date'] = null;
                    $apData['payment_transactions'] = [];
                }
            } catch (\Exception $e) {
                // If payment_transactions table doesn't exist, set defaults
                log_message('info', 'Payment transactions not available: ' . $e->getMessage());
                $apData['payment_method'] = null;
                $apData['payment_reference'] = null;
                $apData['payment_date'] = null;
                $apData['payment_transactions'] = [];
            }
            
            // Get purchase order details
            $order = $this->purchaseOrderModel->getOrderWithDetails($apData['purchase_order_id']);
            
            // Ensure all required fields are present for the view
            $formattedAP = [
                'id' => $apData['id'],
                'invoice_number' => $apData['invoice_number'] ?? null,
                'invoice_date' => $apData['invoice_date'] ?? null,
                'due_date' => $apData['due_date'] ?? null,
                'amount' => (float)($apData['amount'] ?? 0),
                'paid_amount' => (float)($apData['amount_paid'] ?? $apData['paid_amount'] ?? 0),
                'amount_paid' => (float)($apData['amount_paid'] ?? $apData['paid_amount'] ?? 0),
                'balance' => (float)($apData['balance'] ?? ($apData['amount'] - ($apData['amount_paid'] ?? $apData['paid_amount'] ?? 0))),
                'payment_status' => $apData['payment_status'] ?? 'unpaid',
                'supplier_name' => $apData['supplier_name'] ?? 'N/A',
                'order_number' => $apData['order_number'] ?? 'N/A',
                'branch_name' => $apData['branch_name'] ?? 'N/A',
                'payment_method' => $apData['payment_method'] ?? null,
                'payment_reference' => $apData['payment_reference'] ?? null,
                'payment_date' => $apData['payment_date'] ?? null,
                'payment_transactions' => $apData['payment_transactions'] ?? [],
                'notes' => $apData['notes'] ?? null,
                'created_at' => $apData['created_at'] ?? null
            ];

            return $this->response->setJSON([
                'status' => 'success',
                'accounts_payable' => $formattedAP,
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

        // If invoice number is not provided, generate one automatically
        if (empty($invoiceNumber)) {
            $invoiceNumber = $this->accountsPayableModel->generateInvoiceNumber();
        }
        
        // If invoice date is not provided, use current date
        if (empty($invoiceDate)) {
            $invoiceDate = date('Y-m-d');
        }

        try {
            // Get accounts payable with details to include supplier_id from purchase_orders
            $apList = $this->accountsPayableModel->getAccountsPayableWithDetails(['id' => $id]);
            
            if (empty($apList)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable not found']);
            }
            
            $ap = $apList[0];

            // Get supplier to recalculate due date if needed
            $supplierId = $ap['supplier_id'] ?? null;
            if ($supplierId) {
                $supplier = $this->supplierModel->find($supplierId);
                $paymentTerms = $supplier['payment_terms'] ?? 'Net 30';
            } else {
                $paymentTerms = 'Net 30'; // Default if supplier not found
            }
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
                    'message' => 'Invoice information updated successfully',
                    'invoice_number' => $invoiceNumber // Return the invoice number (generated or provided)
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
        
        // Allow central admin, superadmin, and branch managers to record payments
        $allowedRoles = ['central_admin', 'superadmin', 'branch_manager', 'manager'];
        if (!$session->get('logged_in') || !in_array($session->get('role'), $allowedRoles)) {
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

        // Log the raw payment method received
        log_message('info', "Raw payment method received: '{$paymentMethod}'");
        
        // Normalize payment method to match ENUM values in database
        $normalizedMethod = $this->normalizePaymentMethod($paymentMethod);
        $paymentMethod = $normalizedMethod;
        
        // Log the normalized payment method
        log_message('info', "Normalized payment method: '{$paymentMethod}'");

        try {
            $ap = $this->accountsPayableModel->find((int)$id);
            
            if (!$ap) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable not found']);
            }

            // Branch managers can only record payments for their own branch
            // Get branch_id from purchase_order since accounts_payable doesn't store it directly
            $role = $session->get('role');
            $userBranchId = $session->get('branch_id');
            if (!in_array($role, ['central_admin', 'superadmin']) && $userBranchId) {
                // Get branch_id from the purchase order
                $purchaseOrder = $this->purchaseOrderModel->find($ap['purchase_order_id']);
                $apBranchId = $purchaseOrder['branch_id'] ?? null;
                
                if (empty($apBranchId) || (int)$apBranchId !== (int)$userBranchId) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to record payment for this accounts payable']);
                }
            }

            $currentPaid = (float)($ap['amount_paid'] ?? $ap['paid_amount'] ?? 0);
            $totalAmount = (float)$ap['amount'];
            $newPaidAmount = $currentPaid + (float)$paymentAmount;

            // Prevent overpayment
            if ($newPaidAmount > $totalAmount) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Payment amount exceeds total amount. Maximum payment: ₱' . number_format($totalAmount - $currentPaid, 2)]);
            }

            // Auto-generate payment reference if not provided
            if (empty($paymentReference)) {
                $paymentReference = $this->generatePaymentReference();
            }

            // Start transaction
            $this->db->transStart();

            // Update accounts payable amount_paid
            $updated = $this->accountsPayableModel->recordPayment(
                (int)$id,
                (float)$paymentAmount,
                $paymentMethod,
                $paymentReference
            );

            // Create payment transaction record
            $paymentTransactionId = null;
            if ($updated) {
                try {
                    $session = session();
                    $paymentTransactionData = [
                        'accounts_payable_id' => (int)$id,
                        'payment_date' => date('Y-m-d'),
                        'payment_amount' => (float)$paymentAmount,
                        'payment_method' => $paymentMethod, // Already normalized
                        'payment_reference' => $paymentReference,
                        'recorded_by' => $session->get('user_id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Log what we're saving
                    log_message('info', 'Saving payment transaction: ' . json_encode([
                        'ap_id' => $id,
                        'payment_method' => $paymentMethod,
                        'payment_amount' => $paymentAmount,
                        'payment_reference' => $paymentReference
                    ]));
                    
                    $paymentTransactionId = $this->paymentTransactionModel->insert($paymentTransactionData);
                    
                    if ($paymentTransactionId) {
                        log_message('info', "Payment transaction created successfully with ID: {$paymentTransactionId}");
                    } else {
                        log_message('error', 'Failed to insert payment transaction');
                    }
                } catch (\Exception $e) {
                    // If payment_transactions table doesn't exist, just log and continue
                    log_message('error', 'Payment transaction record not created: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() && $updated) {
                // Get updated AP data
                $updatedAP = $this->accountsPayableModel->find((int)$id);
                
                // Verify the payment transaction was saved correctly
                $savedTransaction = null;
                if ($paymentTransactionId) {
                    $savedTransaction = $this->paymentTransactionModel->find($paymentTransactionId);
                    if ($savedTransaction) {
                        log_message('info', "Verified saved payment transaction - Method: '{$savedTransaction['payment_method']}', Amount: {$savedTransaction['payment_amount']}");
                    }
                }
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Payment recorded successfully. Reference: ' . $paymentReference,
                    'payment_reference' => $paymentReference,
                    'payment_transaction_id' => $paymentTransactionId,
                    'payment_method' => $paymentMethod, // Include normalized payment method in response
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
     * Normalize payment method to match database ENUM values
     */
    private function normalizePaymentMethod(?string $method): string
    {
        if (empty($method)) {
            return 'cash'; // Default to cash if not specified
        }

        // Normalize to lowercase and trim, remove extra spaces
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $method)));

        // Map common variations to ENUM values (order matters - more specific first)
        $methodMap = [
            // Exact matches for ENUM values
            'cash' => 'cash',
            'check' => 'check',
            'cheque' => 'check',
            'bank_transfer' => 'bank_transfer',
            'credit_card' => 'credit_card',
            'online' => 'online',
            'other' => 'other',
            
            // Common variations - bank transfer
            'bank transfer' => 'bank_transfer',
            'banktransfer' => 'bank_transfer',
            'bank-transfer' => 'bank_transfer',
            'bank' => 'bank_transfer',
            'transfer' => 'bank_transfer',
            'wire transfer' => 'bank_transfer',
            'wire' => 'bank_transfer',
            'bdo' => 'bank_transfer',
            'bpi' => 'bank_transfer',
            'metrobank' => 'bank_transfer',
            
            // Common variations - credit card
            'credit card' => 'credit_card',
            'creditcard' => 'credit_card',
            'credit-card' => 'credit_card',
            'card' => 'credit_card',
            'visa' => 'credit_card',
            'mastercard' => 'credit_card',
            'amex' => 'credit_card',
            
            // Common variations - online
            'online payment' => 'online',
            'onlinepayment' => 'online',
            'online-payment' => 'online',
            'paypal' => 'online',
            'gcash' => 'online',
            'maya' => 'online',
            'paymaya' => 'online',
            'grabpay' => 'online',
            'paymongo' => 'online',
            'digital wallet' => 'online',
            'ewallet' => 'online',
            'e-wallet' => 'online',
        ];

        // First, check exact match
        if (isset($methodMap[$normalized])) {
            log_message('info', "Payment method normalized: '{$method}' -> '{$methodMap[$normalized]}'");
            return $methodMap[$normalized];
        }

        // If not found, try to match partial strings (contains)
        foreach ($methodMap as $key => $value) {
            if (strpos($normalized, $key) !== false || strpos($key, $normalized) !== false) {
                log_message('info', "Payment method normalized (partial match): '{$method}' -> '{$value}'");
                return $value;
            }
        }

        // Default to 'other' if no match found
        log_message('warning', "Payment method not recognized: '{$method}', defaulting to 'other'");
        return 'other';
    }

    /**
     * Generate receipt data for accounts payable
     */
    public function getReceipt($id = null, $paymentTransactionId = null)
    {
        $session = session();
        
        // Allow central admin, superadmin, and branch managers to access receipts
        $allowedRoles = ['central_admin', 'superadmin', 'branch_manager', 'manager'];
        if (!$session->get('logged_in') || !in_array($session->get('role'), $allowedRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable ID required']);
        }

        try {
            // Get accounts payable with all details
            $apList = $this->accountsPayableModel->getAccountsPayableWithDetails(['id' => $id]);
            
            if (empty($apList)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Accounts payable not found']);
            }
            
            $apData = $apList[0];
            
            // Branch managers can only access receipts for their own branch
            $role = $session->get('role');
            $userBranchId = $session->get('branch_id');
            if (!in_array($role, ['central_admin', 'superadmin']) && $userBranchId) {
                if (empty($apData['branch_id']) || (int)$apData['branch_id'] !== (int)$userBranchId) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to access this receipt']);
                }
            }
            
            // Get purchase order details
            $order = $this->purchaseOrderModel->getOrderWithDetails($apData['purchase_order_id']);
            
            // Get supplier details
            $supplier = $this->supplierModel->find($apData['supplier_id']);
            if (!$supplier) {
                $supplier = [];
            }
            
            // Get branch details
            $branch = null;
            if (!empty($apData['branch_id'])) {
                $branchModel = new \App\Models\BranchModel();
                $branch = $branchModel->find($apData['branch_id']);
            }
            if (!$branch) {
                $branch = [];
            }
            
            // Get all payment transactions
            $paymentTransactions = [];
            try {
                $paymentTransactions = $this->paymentTransactionModel->getByInvoice((int)$id);
            } catch (\Exception $e) {
                log_message('info', 'Payment transactions not available: ' . $e->getMessage());
            }
            
            // Get specific payment transaction if provided
            $currentPayment = null;
            if ($paymentTransactionId) {
                $currentPayment = $this->paymentTransactionModel->find($paymentTransactionId);
            } else if (!empty($paymentTransactions)) {
                // Get the latest payment transaction
                $currentPayment = $paymentTransactions[0];
            }
            
            // Get user who recorded the payment
            $recordedByUser = null;
            if ($currentPayment && isset($currentPayment['recorded_by'])) {
                $userModel = new \App\Models\UserModel();
                $recordedByUser = $userModel->find($currentPayment['recorded_by']);
            }
            
            // Calculate totals
            $totalAmount = (float)$apData['amount'];
            $totalPaid = (float)($apData['amount_paid'] ?? 0);
            $balance = $totalAmount - $totalPaid;
            $currentPaymentAmount = $currentPayment ? (float)$currentPayment['payment_amount'] : 0;
            
            // Prepare receipt data
            $receiptData = [
                'receipt_number' => $currentPayment ? ($currentPayment['payment_number'] ?? 'REC-' . date('Ymd') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT)) : 'REC-' . date('Ymd') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT),
                'receipt_date' => $currentPayment ? ($currentPayment['payment_date'] ?? date('Y-m-d')) : date('Y-m-d'),
                'invoice_number' => $apData['invoice_number'] ?? 'N/A',
                'invoice_date' => $apData['invoice_date'] ?? null,
                'purchase_order' => [
                    'order_number' => $apData['order_number'] ?? 'N/A',
                    'id' => $apData['purchase_order_id']
                ],
                'supplier' => [
                    'name' => $supplier['name'] ?? 'N/A',
                    'address' => $supplier['address'] ?? '',
                    'contact' => $supplier['contact'] ?? '',
                    'email' => $supplier['email'] ?? ''
                ],
                'branch' => [
                    'name' => $branch['name'] ?? 'Central Office',
                    'address' => $branch['address'] ?? ''
                ],
                'payment_details' => [
                    'current_payment_amount' => $currentPaymentAmount,
                    'payment_method' => $currentPayment ? ($currentPayment['payment_method'] ?? 'N/A') : 'N/A',
                    'payment_reference' => $currentPayment ? ($currentPayment['payment_reference'] ?? '') : '',
                    'payment_date' => $currentPayment ? ($currentPayment['payment_date'] ?? date('Y-m-d')) : date('Y-m-d'),
                    'payment_number' => $currentPayment ? ($currentPayment['payment_number'] ?? '') : ''
                ],
                'amounts' => [
                    'total_amount' => $totalAmount,
                    'total_paid' => $totalPaid,
                    'current_payment' => $currentPaymentAmount,
                    'previous_paid' => $totalPaid - $currentPaymentAmount,
                    'balance' => $balance
                ],
                'payment_status' => $apData['payment_status'] ?? 'unpaid',
                'due_date' => $apData['due_date'] ?? null,
                'notes' => $apData['notes'] ?? '',
                'recorded_by' => $recordedByUser ? [
                    'name' => $recordedByUser['email'] ?? 'System',
                    'email' => $recordedByUser['email'] ?? ''
                ] : null,
                'all_payments' => $paymentTransactions
            ];
            
            return $this->response->setJSON([
                'status' => 'success',
                'receipt' => $receiptData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error generating receipt: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to generate receipt: ' . $e->getMessage()]);
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
                
                // Generate invoice number
                $invoiceNumber = $this->accountsPayableModel->generateInvoiceNumber();
                
                // Create accounts payable entry
                // Note: supplier_id and branch_id are removed in normalized schema - they come from purchase_orders
                $apData = [
                    'purchase_order_id' => $order['id'],
                    'invoice_number' => $invoiceNumber, // Auto-generated invoice number
                    'invoice_date' => $invoiceDate,
                    'due_date' => $dueDate,
                    'amount' => (float)$order['total_amount'],
                    'amount_paid' => 0.00, // Use amount_paid instead of paid_amount
                    'payment_status' => 'unpaid',
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

    /**
     * Generate unique payment reference
     * Format: PAY-YYYYMMDD-XXXXX (e.g., PAY-20251215-00001)
     */
    private function generatePaymentReference(): string
    {
        $prefix = 'PAY';
        $date = date('Ymd');
        $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $paymentReference = $prefix . '-' . $date . '-' . $random;
        
        // Ensure unique payment reference by checking payment_transactions table if it exists
        $maxAttempts = 10;
        $attempts = 0;
        
        // Check if payment_transactions table exists and has records
        try {
            $existing = $this->db->table('payment_transactions')
                ->where('payment_reference', $paymentReference)
                ->countAllResults();
            
            while ($existing > 0 && $attempts < $maxAttempts) {
                $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                $paymentReference = $prefix . '-' . $date . '-' . $random;
                $existing = $this->db->table('payment_transactions')
                    ->where('payment_reference', $paymentReference)
                    ->countAllResults();
                $attempts++;
            }
        } catch (\Exception $e) {
            // Table might not exist, use timestamp as fallback
            if ($attempts >= $maxAttempts) {
                $timestamp = time();
                $paymentReference = $prefix . '-' . $date . '-' . substr($timestamp, -5);
            }
        }
        
        // If still not unique after max attempts, add timestamp
        if ($attempts >= $maxAttempts) {
            $timestamp = time();
            $paymentReference = $prefix . '-' . $date . '-' . substr($timestamp, -5);
        }
        
        return $paymentReference;
    }
}
