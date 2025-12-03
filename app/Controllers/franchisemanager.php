<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\FranchiseApplicationModel;
use App\Models\SupplyAllocationModel;
use App\Models\RoyaltyPaymentModel;
use Config\Database;
use Exception;

class FranchiseManager extends BaseController
{
    protected $db;
    protected $productModel;
    protected $branchModel;
    protected $applicationModel;
    protected $allocationModel;
    protected $royaltyModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->applicationModel = new FranchiseApplicationModel();
        $this->allocationModel = new SupplyAllocationModel();
        $this->royaltyModel = new RoyaltyPaymentModel();
    }

    public function dashboard()
    {
        $session = session();

        // Auth check
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return redirect()->to('/auth/login');
        }

        // Get current section from query parameter
        $currentSection = $this->request->getGet('section') ?? 'overview';
        
        // Get pagination parameters
        $perPage = 10; // Maximum 10 records per page
        $applicationsPage = max(1, (int)$this->request->getGet('applications_page') ?? 1);
        $allocationsPage = max(1, (int)$this->request->getGet('allocations_page') ?? 1);
        $royaltiesPage = max(1, (int)$this->request->getGet('royalties_page') ?? 1);

        // Get all necessary data with pagination
        $branches = $this->branchModel->getAllBranches();
        $products = $this->productModel->getInventory();
        
        // Paginated data
        $applications = $this->applicationModel
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'default', $applicationsPage);
        $applicationsPager = $this->applicationModel->pager;
        
        $allocations = $this->allocationModel
            ->select('supply_allocations.*, branches.name as branch_name, branches.code as branch_code, products.name as product_name')
            ->join('branches', 'branches.id = supply_allocations.branch_id', 'left')
            ->join('products', 'products.id = supply_allocations.product_id', 'left')
            ->orderBy('supply_allocations.created_at', 'DESC')
            ->paginate($perPage, 'default', $allocationsPage);
        $allocationsPager = $this->allocationModel->pager;
        
        $royalties = $this->royaltyModel
            ->select('royalty_payments.*, branches.name as branch_name, branches.code as branch_code')
            ->join('branches', 'branches.id = royalty_payments.branch_id', 'left')
            ->orderBy('royalty_payments.period_year', 'DESC')
            ->orderBy('royalty_payments.period_month', 'DESC')
            ->paginate($perPage, 'default', $royaltiesPage);
        $royaltiesPager = $this->royaltyModel->pager;

        // Get statistics (non-paginated)
        $applicationStats = $this->applicationModel->getApplicationStats();
        $allocationStats = $this->allocationModel->getAllocationStats();
        $royaltyStats = $this->royaltyModel->getPaymentStats();

        return view('dashboards/franchisemanager', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'currentSection' => $currentSection,
            'branches' => $branches,
            'products' => $products,
            'applications' => $applications,
            'allocations' => $allocations,
            'royalties' => $royalties,
            'applicationsPager' => $applicationsPager,
            'allocationsPager' => $allocationsPager,
            'royaltiesPager' => $royaltiesPager,
            'applicationStats' => $applicationStats,
            'allocationStats' => $allocationStats,
            'royaltyStats' => $royaltyStats,
        ]);
    }

    // ==================== FRANCHISE APPLICATION METHODS ====================
    
    public function createApplication()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $data = [
                'applicant_name' => $this->request->getPost('applicant_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'proposed_location' => $this->request->getPost('proposed_location'),
                'city' => $this->request->getPost('city'),
                'investment_capital' => $this->request->getPost('investment_capital') ?? 0,
                'business_experience' => $this->request->getPost('business_experience') ?? '',
                'status' => 'pending',
                'reviewed_by' => $session->get('user_id'),
            ];

            if ($this->applicationModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Application created successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create application']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function viewApplication($id)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $application = $this->applicationModel->find($id);
            
            if (!$application) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Application not found']);
            }

            // Format dates for display
            if (!empty($application['created_at'])) {
                $application['created_at'] = date('M d, Y g:i A', strtotime($application['created_at']));
            }
            if (!empty($application['updated_at'])) {
                $application['updated_at'] = date('M d, Y g:i A', strtotime($application['updated_at']));
            }

            return $this->response->setJSON(['status' => 'success', 'data' => $application]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateApplication($id)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            // Note: investment_capital is excluded from updates as it's the franchise owner's proposed budget
            $data = [
                'applicant_name' => $this->request->getPost('applicant_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'proposed_location' => $this->request->getPost('proposed_location'),
                'city' => $this->request->getPost('city'),
                'business_experience' => $this->request->getPost('business_experience') ?? '',
            ];

            if ($this->applicationModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Application updated successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update application']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateApplicationStatus($id)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $status = $this->request->getPost('status');
            
            $data = [
                'status' => $status,
                'reviewed_by' => $session->get('user_id'),
                'reviewed_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->applicationModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Application updated successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update application']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getApplicationsList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $applications = $this->applicationModel->orderBy('created_at', 'DESC')->findAll();
        return $this->response->setJSON(['status' => 'success', 'data' => $applications]);
    }

    // ==================== SUPPLY ALLOCATION METHODS ====================
    
    public function createAllocation()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $allocatedQty = (int)$this->request->getPost('allocated_qty');
            $unitPrice = (float)$this->request->getPost('unit_price');
            
            $data = [
                'branch_id' => $this->request->getPost('branch_id'),
                'product_id' => $this->request->getPost('product_id'),
                'allocated_qty' => $allocatedQty,
                'unit_price' => $unitPrice,
                'total_amount' => $allocatedQty * $unitPrice,
                'status' => 'pending',
                'allocation_date' => $this->request->getPost('allocation_date') ?? date('Y-m-d'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'notes' => $this->request->getPost('notes') ?? '',
                'created_by' => $session->get('user_id'),
            ];

            if ($this->allocationModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Allocation created successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create allocation']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateAllocationStatus($id)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $status = $this->request->getPost('status');
            
            $data = ['status' => $status];

            if ($this->allocationModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Allocation updated successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update allocation']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getAllocationsList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $allocations = $this->allocationModel->getAllocationsWithDetails();
        return $this->response->setJSON(['status' => 'success', 'data' => $allocations]);
    }

    // ==================== ROYALTY PAYMENT METHODS ====================
    
    public function createRoyalty()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $grossSales = (float)$this->request->getPost('gross_sales');
            $royaltyRate = (float)($this->request->getPost('royalty_rate') ?? 5);
            $marketingFee = (float)($this->request->getPost('marketing_fee') ?? 0);
            
            $royaltyAmount = ($grossSales * $royaltyRate) / 100;
            $totalDue = $royaltyAmount + $marketingFee;
            
            $data = [
                'branch_id' => $this->request->getPost('branch_id'),
                'period_month' => $this->request->getPost('period_month'),
                'period_year' => $this->request->getPost('period_year'),
                'gross_sales' => $grossSales,
                'royalty_rate' => $royaltyRate,
                'royalty_amount' => $royaltyAmount,
                'marketing_fee' => $marketingFee,
                'total_due' => $totalDue,
                'amount_paid' => 0,
                'balance' => $totalDue,
                'status' => 'pending',
                'due_date' => $this->request->getPost('due_date'),
            ];

            if ($this->royaltyModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Royalty record created successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to create royalty record']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function recordPayment($id)
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        try {
            $royalty = $this->royaltyModel->find($id);
            if (!$royalty) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Royalty record not found']);
            }

            $paymentAmount = (float)$this->request->getPost('amount_paid');
            $newAmountPaid = $royalty['amount_paid'] + $paymentAmount;
            $newBalance = $royalty['total_due'] - $newAmountPaid;
            
            $data = [
                'amount_paid' => $newAmountPaid,
                'balance' => $newBalance,
                'status' => $newBalance <= 0 ? 'paid' : ($newBalance < $royalty['total_due'] ? 'partial' : 'pending'),
                'paid_date' => date('Y-m-d H:i:s'),
                'payment_reference' => $this->request->getPost('payment_reference') ?? '',
            ];

            if ($this->royaltyModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Payment recorded successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to record payment']);
            }
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getRoyaltiesList()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['franchise_manager', 'franchisemanager'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $royalties = $this->royaltyModel->getPaymentsWithBranch();
        return $this->response->setJSON(['status' => 'success', 'data' => $royalties]);
    }
}

