<?php
namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\SupplierModel;
use App\Models\DeliveryModel;
use App\Models\BranchModel;
use App\Models\StockTransactionModel;
use App\Models\AuditTrailModel;
use App\Models\UserModel;
use Config\Database;
use Exception;

class CentralAdmin extends BaseController
{
    protected $db;
    protected $productModel;
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $supplierModel;
    protected $deliveryModel;
    protected $branchModel;
    protected $stockTransactionModel;
    protected $auditTrailModel;
    protected $userModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->productModel = new ProductModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->supplierModel = new SupplierModel();
        $this->deliveryModel = new DeliveryModel();
        $this->branchModel = new BranchModel();
        $this->stockTransactionModel = new StockTransactionModel();
        $this->auditTrailModel = new AuditTrailModel();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        // Get active tab from URL parameter
        $activeTab = $this->request->getGet('tab') ?: 'dashboard';

        // Get dashboard data
        $dashboardData = $this->getDashboardData();

        return view('dashboards/centraladmin', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'user_id' => $session->get('user_id'),
            ],
            'data' => $dashboardData,
            'activeTab' => $activeTab,
        ]);
    }

    public function suppliersPage()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        return redirect()->to(base_url('centraladmin/dashboard?tab=suppliers'));
    }

    public function deliveriesPage()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return redirect()->to('/auth/login');
        }

        return redirect()->to(base_url('centraladmin/dashboard?tab=deliveries'));
    }

    public function reportsPage()
    {
        $session = session();

        // Allow central admin, branch managers, and staff to view reports
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin', 'branch_manager', 'manager', 'inventory_staff', 'inventorystaff'])) {
            return redirect()->to('/auth/login');
        }

        // Get dashboard data for reports
        $dashboardData = $this->getDashboardData();

        return view('dashboards/reports', [
            'me' => [
                'email' => $session->get('email'),
                'role' => $session->get('role'),
            ],
            'data' => $dashboardData,
        ]);
    }

    /**
     * Get all dashboard data
     */
    private function getDashboardData(): array
    {
        return [
            'inventory' => $this->getInventorySummary(),
            'suppliers' => $this->getSupplierReports(),
            'branches' => $this->getBranchInventoryOverview(),
            'purchaseRequests' => $this->getPurchaseRequestSummary(),
            'deliveries' => $this->getDeliveryTracking(),
            'pendingRequests' => $this->purchaseRequestModel->getPendingRequests(),
            'users' => $this->getUsersData(),
        ];
    }

    /**
     * Real-time inventory summary widget
     */
    private function getInventorySummary(): array
    {
        $allProducts = $this->productModel->getInventory();
        
        $summary = [
            'total_items' => count($allProducts),
            'total_stock_value' => 0,
            'low_stock_count' => 0,
            'critical_items_count' => 0,
            'expired_count' => 0,
            'expiring_soon_count' => 0,
        ];

        foreach ($allProducts as $product) {
            $status = $product['status'] ?? 'Good';
            $stockQty = (int)($product['stock_qty'] ?? 0);
            $unitPrice = (float)($product['price'] ?? 0);
            
            $summary['total_stock_value'] += $stockQty * ($unitPrice > 0 ? $unitPrice : 150); // Default price if not set

            if (in_array($status, ['Low Stock', 'Expiring Soon'])) {
                $summary['low_stock_count']++;
            }
            
            if (in_array($status, ['Critical', 'Out of Stock'])) {
                $summary['critical_items_count']++;
            }
            
            if ($status === 'Expired') {
                $summary['expired_count']++;
            }
            
            if ($status === 'Expiring Soon') {
                $summary['expiring_soon_count']++;
            }
        }

        return $summary;
    }

    /**
     * Supplier reports widget
     */
    private function getSupplierReports(): array
    {
        $activeSuppliers = $this->supplierModel->getActiveSuppliers();
        $allSuppliers = $this->supplierModel->findAll();
        
        // Get pending orders
        $pendingOrders = $this->purchaseOrderModel->getOrdersByStatus('pending');
        $inTransitOrders = $this->purchaseOrderModel->getOrdersByStatus('in_transit');
        
        // Get delivery performance
        $deliveries = $this->db->table('deliveries')
            ->where('status', 'delivered')
            ->get()
            ->getResultArray();
        
        $onTimeDeliveries = 0;
        $delayedDeliveries = 0;
        
        foreach ($deliveries as $delivery) {
            if ($delivery['actual_delivery_date'] && $delivery['scheduled_date']) {
                $actual = strtotime($delivery['actual_delivery_date']);
                $scheduled = strtotime($delivery['scheduled_date']);
                if ($actual <= $scheduled) {
                    $onTimeDeliveries++;
                } else {
                    $delayedDeliveries++;
                }
            }
        }
        
        $totalDeliveries = count($deliveries);
        $onTimeRate = $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100, 2) : 0;

        return [
            'active_suppliers' => count($activeSuppliers),
            'total_suppliers' => count($allSuppliers),
            'pending_orders' => count($pendingOrders),
            'in_transit_orders' => count($inTransitOrders),
            'on_time_delivery_rate' => $onTimeRate,
            'total_deliveries' => $totalDeliveries,
            'on_time_deliveries' => $onTimeDeliveries,
            'delayed_deliveries' => $delayedDeliveries,
        ];
    }

    /**
     * Branch inventory overview table/grid
     */
    private function getBranchInventoryOverview(): array
    {
        // Get all branches ordered by name, excluding Central Office
        $branches = $this->db->table('branches')
            ->where('code !=', 'CENTRAL')
            ->where('name !=', 'Central Office')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
        
        $overview = [];

        foreach ($branches as $branch) {
            // All branches here are already filtered (Central excluded)

            // Get products for this branch
            $branchProducts = $this->db->table('products')
                ->where('branch_id', $branch['id'])
                ->get()
                ->getResultArray();

            $lowStockCount = 0;
            $criticalCount = 0;
            $totalProducts = count($branchProducts);

            foreach ($branchProducts as $product) {
                // Calculate status using the same logic as ProductModel
                $stock = (int)($product['stock_qty'] ?? 0);
                $minStock = (int)($product['min_stock'] ?? 0);
                $expiry = $product['expiry'] ?? null;
                
                $status = 'Good';
                if ($expiry) {
                    $expiryDate = new \DateTime($expiry);
                    $today = new \DateTime();
                    $today->setTime(0, 0, 0);
                    if ($expiryDate < $today) {
                        $status = 'Expired';
                    } elseif (($expiryDate->diff($today)->days) <= 7) {
                        $status = 'Expiring Soon';
                    }
                }
                
                if ($stock <= 0) {
                    $status = 'Out of Stock';
                } elseif ($stock <= $minStock) {
                    $status = 'Critical';
                } elseif ($status === 'Good' && $stock < ($minStock * 1.3)) {
                    $status = 'Low Stock';
                }
                
                if (in_array($status, ['Low Stock', 'Expiring Soon'])) {
                    $lowStockCount++;
                }
                if (in_array($status, ['Critical', 'Out of Stock', 'Expired'])) {
                    $criticalCount++;
                }
            }

            $overview[] = [
                'branch_id' => $branch['id'],
                'branch_name' => $branch['name'],
                'branch_code' => $branch['code'],
                'branch_address' => $branch['address'] ?? '',
                'total_products' => $totalProducts,
                'low_stock' => $lowStockCount,
                'low_stock_items' => $lowStockCount,
                'critical_alerts' => $criticalCount,
            ];
        }

        // Sort by total_products descending, then by name ascending
        usort($overview, function($a, $b) {
            if ($b['total_products'] != $a['total_products']) {
                return $b['total_products'] - $a['total_products'];
            }
            return strcmp($a['branch_name'], $b['branch_name']);
        });
        
        // Limit to 5 branches only (ensures Toril is included if it has products)
        return array_slice($overview, 0, 5);
    }

    /**
     * Purchase request summary widget
     */
    private function getPurchaseRequestSummary(): array
    {
        $pendingRequests = $this->purchaseRequestModel->getPendingRequests();
        
        $today = date('Y-m-d');
        $approvedToday = $this->db->table('purchase_requests')
            ->where('status', 'approved')
            ->where('DATE(approved_at)', $today)
            ->get()
            ->getResultArray();
        
        $rejectedRequests = $this->db->table('purchase_requests')
            ->where('status', 'rejected')
            ->get()
            ->getResultArray();
        
        $totalValue = 0;
        foreach ($pendingRequests as $request) {
            $totalValue += (float)($request['total_amount'] ?? 0);
        }

        return [
            'pending_approvals' => count($pendingRequests),
            'approved_today' => count($approvedToday),
            'rejected_requests' => count($rejectedRequests),
            'total_pending_value' => $totalValue,
        ];
    }

    /**
     * Delivery tracking widget
     */
    private function getDeliveryTracking(): array
    {
        $scheduled = $this->db->table('deliveries')
            ->where('status', 'scheduled')
            ->countAllResults(false);
        
        $inTransit = $this->db->table('deliveries')
            ->where('status', 'in_transit')
            ->countAllResults(false);
        
        $today = date('Y-m-d');
        $completedToday = $this->db->table('deliveries')
            ->where('status', 'delivered')
            ->where('DATE(actual_delivery_date)', $today)
            ->countAllResults(false);
        
        // Get delayed deliveries (scheduled date passed but not delivered)
        $delayed = $this->db->table('deliveries')
            ->where('scheduled_date <', $today)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->countAllResults(false);

        return [
            'scheduled_deliveries' => $scheduled,
            'in_transit_deliveries' => $inTransit,
            'completed_today' => $completedToday,
            'delayed_deliveries' => $delayed,
        ];
    }

    /**
     * Detailed deliveries list for central admin Deliveries tab
     */
    public function getDeliveriesList()
    {
        $session = session();

        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $status = $this->request->getGet('status');

        $builder = $this->db->table('deliveries d')
            ->select('d.*, s.name AS supplier_name, b.name AS branch_name, po.order_number')
            ->join('suppliers s', 's.id = d.supplier_id', 'left')
            ->join('branches b', 'b.id = d.branch_id', 'left')
            ->join('purchase_orders po', 'po.id = d.purchase_order_id', 'left');

        if (!empty($status) && $status !== 'all') {
            $builder->where('d.status', $status);
        }

        try {
            $deliveries = $builder
                ->orderBy('d.scheduled_date', 'DESC')
                ->limit(100)
                ->get()
                ->getResultArray();

            log_message('debug', 'Deliveries list: ' . count($deliveries) . ' deliveries found');

            return $this->response->setJSON([
                'status' => 'success',
                'deliveries' => $deliveries,
                'count' => count($deliveries)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getDeliveriesList: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to load deliveries: ' . $e->getMessage(),
                'deliveries' => []
            ]);
        }
    }

    /**
     * API endpoint for real-time dashboard updates
     */
    public function getDashboardDataAPI()
    {
        $session = session();
        
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $data = $this->getDashboardData();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get all branches (API) - for dropdowns
     */
    public function getBranches()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        try {
            $branches = $this->branchModel->getAllBranches();
            return $this->response->setJSON([
                'status' => 'success',
                'branches' => $branches,
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ==================== USER MANAGEMENT ====================

    /**
     * Get users data for management (including deleted users)
     */
    private function getUsersData(): array
    {
        $users = [];
        $activeUsers = [];
        $deletedUsers = [];
        $usersByRole = [];
        
        try {
            // Get all users including deleted ones - use raw query to bypass soft deletes
            $query = $this->db->query("
                SELECT u.*, b.name as branch_name, u.deleted_at
                FROM users u
                LEFT JOIN branches b ON b.id = u.branch_id
                ORDER BY 
                    CASE WHEN u.deleted_at IS NULL THEN 0 ELSE 1 END ASC,
                    u.created_at DESC
            ");
            
            if ($query) {
                $users = $query->getResultArray();
            }
            
            // Log if no users found
            if (empty($users)) {
                log_message('debug', 'No users found in getUsersData - query returned empty result');
            } else {
                log_message('debug', 'Found ' . count($users) . ' users in getUsersData');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in getUsersData: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            $users = [];
        }

        // Separate active and deleted users
        foreach ($users as $user) {
            // Check if deleted_at is not null and not empty
            if (isset($user['deleted_at']) && $user['deleted_at'] !== null && $user['deleted_at'] !== '') {
                $deletedUsers[] = $user;
            } else {
                $activeUsers[] = $user;
            }
        }

        // Count users by role (only active users)
        foreach ($activeUsers as $user) {
            $role = $user['role'] ?? 'unknown';
            if (!isset($usersByRole[$role])) {
                $usersByRole[$role] = 0;
            }
            $usersByRole[$role]++;
        }

        log_message('debug', 'Users data summary - Active: ' . count($activeUsers) . ', Deleted: ' . count($deletedUsers));

        return [
            'all_users' => $users,
            'active_users' => $activeUsers,
            'deleted_users' => $deletedUsers,
            'users_by_role' => $usersByRole,
            'total_count' => count($activeUsers),
            'deleted_count' => count($deletedUsers),
        ];
    }

    /**
     * Get users list (API) with pagination
     */
    public function getUsers()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = 10;
        $type = $this->request->getGet('type') ?? 'active'; // 'active' or 'deleted'
        
        $usersData = $this->getUsersData();
        $allUsers = $type === 'deleted' ? $usersData['deleted_users'] : $usersData['active_users'];
        
        $totalItems = count($allUsers);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedUsers = array_slice($allUsers, $offset, $perPage);
        
        return $this->response->setJSON([
            'status' => 'success',
            'users' => $paginatedUsers,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
            ]
        ]);
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
        ];

        // Validate
        if (empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email, password, and role are required']);
        }

        // Check if email already exists
        if ($this->userModel->getUserByEmail($data['email'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email already exists']);
        }

        try {
            $userId = $this->userModel->createUser($data);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'INSERT',
                null,
                $data,
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User created successfully',
                'user_id' => $userId,
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update user
     */
    public function updateUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->userModel->withDeleted()->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        $oldValues = $user;
        $updateData = [];

        // Get user's branch information
        $branchName = null;
        if (!empty($user['branch_id'])) {
            $branch = $this->db->table('branches')->where('id', $user['branch_id'])->get()->getRowArray();
            $branchName = $branch['name'] ?? null;
        }

        // Check if user is protected (system_admin or central_admin in Central Office)
        $isProtected = false;
        if ($user['role'] === 'system_admin') {
            $isProtected = true;
        } elseif ($user['role'] === 'central_admin' && $branchName === 'Central Office') {
            $isProtected = true;
        }

        if ($this->request->getPost('email')) {
            $updateData['email'] = $this->request->getPost('email');
        }
        if ($this->request->getPost('role')) {
            $newRole = $this->request->getPost('role');
            // Prevent role changes for protected users
            if ($isProtected && $newRole !== $user['role']) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the role of protected users (System Administrator IT or Central Admin in Central Office).']);
            }
            $updateData['role'] = $newRole;
        }
        if ($this->request->getPost('branch_id') !== null) {
            $newBranchId = $this->request->getPost('branch_id') ?: null;
            // Prevent branch changes for protected users
            if ($isProtected) {
                // Get current branch ID (handle null case)
                $currentBranchId = $user['branch_id'] ?? null;
                if ($newBranchId != $currentBranchId) {
                    if ($user['role'] === 'system_admin') {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the branch of System Administrator IT users. This account is protected.']);
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot change the branch of Central Admin in Central Office. This account is protected.']);
                    }
                }
            }
            $updateData['branch_id'] = $newBranchId;
        }
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        if (empty($updateData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No data to update']);
        }

        try {
            // Hash password if provided
            if (isset($updateData['password'])) {
                $updateData['password'] = password_hash($updateData['password'], PASSWORD_DEFAULT);
            }

            $this->userModel->update($userId, $updateData);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'UPDATE',
                $oldValues,
                array_merge($user, $updateData),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User updated successfully',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function deleteUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->userModel->withDeleted()->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Check if already deleted
        if (!empty($user['deleted_at'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User is already deleted']);
        }

        // Prevent deleting yourself
        if ($userId == $session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot delete your own account']);
        }

        // Get user's branch information
        $branchName = null;
        if (!empty($user['branch_id'])) {
            $branch = $this->db->table('branches')->where('id', $user['branch_id'])->get()->getRowArray();
            $branchName = $branch['name'] ?? null;
        }

        // Protect System Administrator IT users (any branch)
        if ($user['role'] === 'system_admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot delete System Administrator IT users. This account is protected.']);
        }

        // Protect Central Admin in Central Office
        if ($user['role'] === 'central_admin' && $branchName === 'Central Office') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot delete Central Admin in Central Office. This account is protected.']);
        }

        try {
            // Soft delete by setting deleted_at
            $this->userModel->update($userId, ['deleted_at' => date('Y-m-d H:i:s')]);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'SOFT_DELETE',
                $user,
                array_merge($user, ['deleted_at' => date('Y-m-d H:i:s')]),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User deleted successfully. You can restore it later.',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Restore a deleted user
     */
    public function restoreUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->userModel->withDeleted()->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Check if user is actually deleted
        if (empty($user['deleted_at'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User is not deleted']);
        }

        try {
            // Restore user by clearing deleted_at
            $this->userModel->restoreUser($userId);
            
            // Log to audit trail
            $this->auditTrailModel->logChange(
                'users',
                $userId,
                'RESTORE',
                $user,
                array_merge($user, ['deleted_at' => null]),
                $session->get('user_id')
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User restored successfully',
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Test endpoint to check users data
     */
    public function testUsersData()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $usersData = $this->getUsersData();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $usersData,
            'counts' => [
                'all' => count($usersData['all_users']),
                'active' => count($usersData['active_users']),
                'deleted' => count($usersData['deleted_users']),
            ]
        ]);
    }

    /**
     * Get user by ID (API) - includes deleted users
     */
    public function getUser($userId)
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $user = $this->db->table('users u')
            ->select('u.*, b.name as branch_name, b.id as branch_id, u.deleted_at')
            ->join('branches b', 'b.id = u.branch_id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRowArray();

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // Remove password from response
        unset($user['password']);

        return $this->response->setJSON([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * Get comprehensive monthly reports (API)
     */
    public function getMonthlyReports()
    {
        $session = session();
        if (!$session->get('logged_in') || !in_array($session->get('role'), ['central_admin', 'superadmin'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized']);
        }

        $month = $this->request->getGet('month'); // Format: YYYY-MM
        if (!$month) {
            $month = date('Y-m');
        }

        try {
            [$year, $monthNum] = explode('-', $month);
            $startDate = date('Y-m-01', strtotime("$year-$monthNum-01"));
            $endDate = date('Y-m-t', strtotime("$year-$monthNum-01"));

            $reports = [
                'branch_manager' => $this->getBranchManagerReports($startDate, $endDate),
                'inventory_staff' => $this->getInventoryStaffReports($startDate, $endDate),
                'franchise_manager' => $this->getFranchiseManagerReports($startDate, $endDate),
                'logistics_coordinator' => $this->getLogisticsCoordinatorReports($startDate, $endDate),
            ];

            // Get user information for prepared_by
            $userId = $session->get('user_id');
            $userEmail = $session->get('email');
            $userName = $userEmail; // Default to email, can be enhanced to get full name if available
            
            // Try to get user's full name if available
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            if ($user && isset($user['name'])) {
                $userName = $user['name'];
            } elseif ($user && isset($user['email'])) {
                $userName = $user['email'];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'month' => $month,
                'reports' => $reports,
                'prepared_by' => [
                    'name' => $userName,
                    'email' => $userEmail,
                    'role' => $session->get('role'),
                ],
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get Branch Manager monthly reports (Sales, Inventory & Damage Products)
     */
    private function getBranchManagerReports($startDate, $endDate)
    {
        $reports = [
            'sales' => [],
            'inventory' => [],
            'inventory_details' => [], // Detailed product data organized by branch → category → product
            'damage_products' => [],
        ];

        // Get sales data by branch (check if sales table exists)
        try {
            $tables = $this->db->listTables();
            if (in_array('sales', $tables)) {
                $salesQuery = $this->db->table('sales s')
                    ->select('b.id as branch_id, b.name as branch_name, 
                             SUM(s.total_amount) as total_sales, 
                             COUNT(s.id) as transaction_count,
                             AVG(s.total_amount) as avg_transaction')
                    ->join('branches b', 'b.id = s.branch_id', 'left')
                    ->where('DATE(s.sale_date) >=', $startDate)
                    ->where('DATE(s.sale_date) <=', $endDate)
                    ->groupBy('b.id, b.name')
                    ->get();

                $reports['sales'] = $salesQuery->getResultArray();
            } else {
                // If sales table doesn't exist, try to get sales from stock_transactions
                $startDateOnly = date('Y-m-d', strtotime($startDate));
                $endDateOnly = date('Y-m-d', strtotime($endDate));
                
                $salesQuery = "
                    SELECT 
                        b.id as branch_id, 
                        b.name as branch_name,
                        SUM(ABS(st.quantity * COALESCE(st.unit_cost, p.price, 0))) as total_sales,
                        COUNT(DISTINCT st.reference_id) as transaction_count,
                        AVG(ABS(st.quantity * COALESCE(st.unit_cost, p.price, 0))) as avg_transaction
                    FROM stock_transactions st
                    LEFT JOIN products p ON p.id = st.product_id
                    LEFT JOIN branches b ON b.id = st.branch_id
                    WHERE st.transaction_type = 'stock_out'
                    AND st.reference_type = 'sale'
                    AND (
                        (st.transaction_date IS NOT NULL AND DATE(st.transaction_date) >= " . $this->db->escape($startDateOnly) . " AND DATE(st.transaction_date) <= " . $this->db->escape($endDateOnly) . ")
                        OR 
                        (st.transaction_date IS NULL AND DATE(st.created_at) >= " . $this->db->escape($startDateOnly) . " AND DATE(st.created_at) <= " . $this->db->escape($endDateOnly) . ")
                    )
                    GROUP BY b.id, b.name
                ";
                
                $salesFromTransactions = $this->db->query($salesQuery);
                $reports['sales'] = $salesFromTransactions->getResultArray();
            }
        } catch (Exception $e) {
            log_message('error', 'Error fetching sales data: ' . $e->getMessage());
            $reports['sales'] = [];
        }

        // Get inventory summary by branch - filter by products active in the month
        // First get active product IDs for the month
        $activeProductIdsForSummary = $this->db->query("
            SELECT DISTINCT p.id
            FROM products p
            WHERE p.deleted_at IS NULL
            AND (
                (DATE(p.updated_at) >= ? AND DATE(p.updated_at) <= ?)
                OR
                p.id IN (
                    SELECT DISTINCT st.product_id
                    FROM stock_transactions st
                    WHERE (
                        (st.transaction_date IS NOT NULL AND DATE(st.transaction_date) >= ? AND DATE(st.transaction_date) <= ?)
                        OR
                        (st.transaction_date IS NULL AND DATE(st.created_at) >= ? AND DATE(st.created_at) <= ?)
                    )
                )
            )
        ", [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate])->getResultArray();
        
        $activeIdsForSummary = array_column($activeProductIdsForSummary, 'id');
        
        if (!empty($activeIdsForSummary)) {
            $inventoryQuery = $this->db->table('products p')
                ->select('b.id as branch_id, b.name as branch_name,
                         COUNT(DISTINCT p.id) as total_items,
                         SUM(p.stock_qty) as total_stock,
                         SUM(p.stock_qty * p.price) as total_value,
                         COUNT(CASE WHEN p.stock_qty <= p.min_stock THEN 1 END) as low_stock_items,
                         COUNT(CASE WHEN p.expiry IS NOT NULL AND p.expiry < CURDATE() THEN 1 END) as expired_items')
                ->join('branches b', 'b.id = p.branch_id', 'left')
                ->where('p.deleted_at IS NULL')
                ->whereIn('p.id', $activeIdsForSummary)
                ->groupBy('b.id, b.name')
                ->orderBy('b.name', 'ASC')
                ->get();

            $reports['inventory'] = $inventoryQuery->getResultArray();
        }

        // Get detailed product data organized by branch → category → product
        $productDetailsQuery = $this->db->table('products p')
            ->select('b.id as branch_id, 
                     b.name as branch_name,
                     COALESCE(c.id, 0) as category_id,
                     COALESCE(c.name, "Uncategorized") as category_name,
                     p.id as product_id,
                     p.name as product_name,
                     p.stock_qty,
                     p.price,
                     p.min_stock,
                     p.expiry')
            ->join('branches b', 'b.id = p.branch_id', 'left')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.deleted_at IS NULL')
            ->whereIn('p.id', !empty($activeIdsForSummary) ? $activeIdsForSummary : [0])
            ->orderBy('b.name', 'ASC')
            ->orderBy('c.name', 'ASC')
            ->orderBy('p.name', 'ASC')
            ->get();

        $products = $productDetailsQuery->getResultArray();
        
        // Organize products by branch → category → product and calculate derived values
        $organizedProducts = [];
        $today = date('Y-m-d');
        foreach ($products as $product) {
            $branchName = $product['branch_name'];
            $categoryName = $product['category_name'];
            
            if (!isset($organizedProducts[$branchName])) {
                $organizedProducts[$branchName] = [];
            }
            
            if (!isset($organizedProducts[$branchName][$categoryName])) {
                $organizedProducts[$branchName][$categoryName] = [];
            }
            
            $totalValue = (float)$product['stock_qty'] * (float)$product['price'];
            $isLowStock = ($product['stock_qty'] <= $product['min_stock']) ? 1 : 0;
            $isExpired = ($product['expiry'] !== null && $product['expiry'] < $today) ? 1 : 0;

            $organizedProducts[$branchName][$categoryName][] = [
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'total_stock' => (float)$product['stock_qty'],
                'total_value' => $totalValue,
                'is_low_stock' => $isLowStock,
                'is_expired' => $isExpired,
            ];
        }
        $reports['inventory_details'] = $organizedProducts;

        // Get damaged products from stock transactions
        $startDateOnly = date('Y-m-d', strtotime($startDate));
        $endDateOnly = date('Y-m-d', strtotime($endDate));
        
        $damageQuery = "
            SELECT 
                b.id as branch_id, 
                b.name as branch_name,
                p.id as product_id, 
                p.name as product_name, 
                COALESCE(c.name, 'N/A') as category,
                SUM(ABS(st.quantity)) as total_damaged,
                COUNT(st.id) as damage_count
            FROM stock_transactions st
            LEFT JOIN products p ON p.id = st.product_id
            LEFT JOIN branches b ON b.id = st.branch_id
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE st.reference_type = 'damage_report'
            AND (
                (st.transaction_date IS NOT NULL AND DATE(st.transaction_date) >= " . $this->db->escape($startDateOnly) . " AND DATE(st.transaction_date) <= " . $this->db->escape($endDateOnly) . ")
                OR 
                (st.transaction_date IS NULL AND DATE(st.created_at) >= " . $this->db->escape($startDateOnly) . " AND DATE(st.created_at) <= " . $this->db->escape($endDateOnly) . ")
            )
            GROUP BY b.id, b.name, p.id, p.name, c.name
        ";
        
        $damageResult = $this->db->query($damageQuery);
        $reports['damage_products'] = $damageResult->getResultArray();

        return $reports;
    }

    /**
     * Get Inventory Staff monthly reports (Inventory & Damage Products)
     */
    private function getInventoryStaffReports($startDate, $endDate)
    {
        $reports = [
            'inventory' => [],
            'inventory_details' => [], // Detailed product data organized by branch → category → product
            'damage_products' => [],
        ];

        // Get inventory summary by branch - filter by products active in the month
        // First get active product IDs for the month
        $activeProductIdsForSummary = $this->db->query("
            SELECT DISTINCT p.id
            FROM products p
            WHERE p.deleted_at IS NULL
            AND (
                (DATE(p.updated_at) >= ? AND DATE(p.updated_at) <= ?)
                OR
                p.id IN (
                    SELECT DISTINCT st.product_id
                    FROM stock_transactions st
                    WHERE (
                        (st.transaction_date IS NOT NULL AND DATE(st.transaction_date) >= ? AND DATE(st.transaction_date) <= ?)
                        OR
                        (st.transaction_date IS NULL AND DATE(st.created_at) >= ? AND DATE(st.created_at) <= ?)
                    )
                )
            )
        ", [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate])->getResultArray();
        
        $activeIdsForSummary = array_column($activeProductIdsForSummary, 'id');
        
        if (empty($activeIdsForSummary)) {
            $reports['inventory'] = [];
        } else {
            $inventoryQuery = $this->db->table('products p')
                ->select('b.id as branch_id, b.name as branch_name,
                         COUNT(DISTINCT p.id) as total_items,
                         SUM(p.stock_qty) as total_stock,
                         SUM(p.stock_qty * p.price) as total_value,
                         COUNT(CASE WHEN p.stock_qty <= p.min_stock THEN 1 END) as low_stock_items,
                         COUNT(CASE WHEN p.expiry IS NOT NULL AND p.expiry < CURDATE() THEN 1 END) as expired_items')
                ->join('branches b', 'b.id = p.branch_id', 'left')
                ->where('p.deleted_at IS NULL')
                ->whereIn('p.id', $activeIdsForSummary)
                ->groupBy('b.id, b.name')
                ->get();

            $reports['inventory'] = $inventoryQuery->getResultArray();
        }

        // Get detailed product data organized by branch → category → product
        // Filter products that were updated in the month OR have stock transactions in the month
        // Use a subquery to get product IDs that have activity in the month
        $activeProductIdsQuery = $this->db->query("
            SELECT DISTINCT p.id
            FROM products p
            WHERE p.deleted_at IS NULL
            AND (
                (DATE(p.updated_at) >= ? AND DATE(p.updated_at) <= ?)
                OR
                p.id IN (
                    SELECT DISTINCT st.product_id
                    FROM stock_transactions st
                    WHERE (
                        (st.transaction_date IS NOT NULL AND DATE(st.transaction_date) >= ? AND DATE(st.transaction_date) <= ?)
                        OR
                        (st.transaction_date IS NULL AND DATE(st.created_at) >= ? AND DATE(st.created_at) <= ?)
                    )
                )
            )
        ", [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);
        
        $activeProductIds = [];
        if ($activeProductIdsQuery) {
            $results = $activeProductIdsQuery->getResultArray();
            foreach ($results as $row) {
                $activeProductIds[] = $row['id'];
            }
        }
        
        // If no active products found, return empty array
        if (empty($activeProductIds)) {
            $reports['inventory_details'] = [];
        } else {
            $productDetailsQuery = $this->db->table('products p')
                ->select('b.id as branch_id, 
                         b.name as branch_name,
                         COALESCE(c.id, 0) as category_id,
                         COALESCE(c.name, "Uncategorized") as category_name,
                         p.id as product_id,
                         p.name as product_name,
                         p.stock_qty,
                         p.price,
                         p.min_stock,
                         p.expiry,
                         p.updated_at')
                ->join('branches b', 'b.id = p.branch_id', 'left')
                ->join('categories c', 'c.id = p.category_id', 'left')
                ->where('p.deleted_at IS NULL')
                ->whereIn('p.id', $activeProductIds)
                ->orderBy('b.name', 'ASC')
                ->orderBy('c.name', 'ASC')
                ->orderBy('p.name', 'ASC')
                ->get();

            $products = $productDetailsQuery->getResultArray();
            
            // Organize products by branch → category → product and calculate derived values
            $organizedProducts = [];
            foreach ($products as $product) {
            $branchName = $product['branch_name'] ?? 'Unknown Branch';
            $categoryName = $product['category_name'] ?? 'Uncategorized';
            
            // Calculate values
            $totalStock = (float)($product['stock_qty'] ?? 0);
            $price = (float)($product['price'] ?? 0);
            $totalValue = $totalStock * $price;
            
            // Calculate is_low_stock
            $isLowStock = 0;
            if (isset($product['min_stock']) && $product['min_stock'] !== null) {
                $minStock = (float)$product['min_stock'];
                $isLowStock = ($totalStock <= $minStock) ? 1 : 0;
            }
            
            // Calculate is_expired
            $isExpired = 0;
            if (isset($product['expiry']) && $product['expiry'] !== null) {
                $expiryDate = strtotime($product['expiry']);
                $today = strtotime('today');
                $isExpired = ($expiryDate < $today) ? 1 : 0;
            }
            
            if (!isset($organizedProducts[$branchName])) {
                $organizedProducts[$branchName] = [];
            }
            
            if (!isset($organizedProducts[$branchName][$categoryName])) {
                $organizedProducts[$branchName][$categoryName] = [];
            }
            
            $organizedProducts[$branchName][$categoryName][] = [
                'product_id' => $product['product_id'] ?? 0,
                'product_name' => $product['product_name'] ?? 'Unknown Product',
                'total_stock' => $totalStock,
                'total_value' => $totalValue,
                'is_low_stock' => $isLowStock,
                'is_expired' => $isExpired,
                'last_updated' => $product['updated_at'] ?? null,
            ];
            }
            
            $reports['inventory_details'] = $organizedProducts;
        }

        // Get damaged products (same as branch manager)
        $damageQuery = $this->db->table('stock_transactions st')
            ->select('b.id as branch_id, b.name as branch_name,
                     p.id as product_id, p.name as product_name, 
                     COALESCE(c.name, "N/A") as category,
                     SUM(ABS(st.quantity)) as total_damaged,
                     COUNT(st.id) as damage_count')
            ->join('products p', 'p.id = st.product_id', 'left')
            ->join('branches b', 'b.id = st.branch_id', 'left')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('st.reference_type', 'damage_report')
            ->where('DATE(st.transaction_date) >=', $startDate)
            ->where('DATE(st.transaction_date) <=', $endDate)
            ->groupBy('b.id, b.name, p.id, p.name, c.name')
            ->get();

        $reports['damage_products'] = $damageQuery->getResultArray();

        return $reports;
    }

    /**
     * Get Franchise Manager monthly reports
     */
    private function getFranchiseManagerReports($startDate, $endDate)
    {
        $reports = [
            'applications' => [],
            'allocations' => [],
            'royalties' => [],
        ];

        // Get franchise applications (no branch_id, use proposed_location/city instead)
        $applicationsQuery = $this->db->table('franchise_applications fa')
            ->select('fa.*, CONCAT(fa.proposed_location, ", ", fa.city) as branch_name')
            ->where('DATE(fa.created_at) >=', $startDate)
            ->where('DATE(fa.created_at) <=', $endDate)
            ->get();

        $reports['applications'] = $applicationsQuery->getResultArray();

        // Get allocations (table is called supply_allocations, not product_allocations)
        $allocationsQuery = $this->db->table('supply_allocations pa')
            ->select('pa.*, b.name as branch_name')
            ->join('branches b', 'b.id = pa.branch_id', 'left')
            ->where('DATE(pa.created_at) >=', $startDate)
            ->where('DATE(pa.created_at) <=', $endDate)
            ->get();

        $reports['allocations'] = $allocationsQuery->getResultArray();

        // Get royalty payments
        $royaltiesQuery = $this->db->table('royalty_payments rp')
            ->select('rp.*, b.name as branch_name')
            ->join('branches b', 'b.id = rp.branch_id', 'left')
            ->where('rp.period_year', date('Y', strtotime($startDate)))
            ->where('rp.period_month', date('m', strtotime($startDate)))
            ->get();

        $reports['royalties'] = $royaltiesQuery->getResultArray();

        return $reports;
    }

    /**
     * Get Logistics Coordinator monthly reports
     */
    private function getLogisticsCoordinatorReports($startDate, $endDate)
    {
        $reports = [
            'deliveries' => [],
            'delivery_summary' => [],
        ];

        // Get deliveries (use scheduled_date for filtering)
        $deliveriesQuery = $this->db->table('deliveries d')
            ->select('d.*, b.name as branch_name, po.order_number, s.name as supplier_name')
            ->join('branches b', 'b.id = d.branch_id', 'left')
            ->join('purchase_orders po', 'po.id = d.purchase_order_id', 'left')
            ->join('suppliers s', 's.id = po.supplier_id', 'left')
            ->where('DATE(d.scheduled_date) >=', $startDate)
            ->where('DATE(d.scheduled_date) <=', $endDate)
            ->get();

        $reports['deliveries'] = $deliveriesQuery->getResultArray();

        // Get delivery summary by branch (use scheduled_date for filtering)
        $summaryQuery = $this->db->table('deliveries d')
            ->select('b.id as branch_id, b.name as branch_name,
                     COUNT(d.id) as total_deliveries,
                     COUNT(CASE WHEN d.status = "delivered" OR d.status = "received" THEN 1 END) as completed_deliveries,
                     COUNT(CASE WHEN d.status = "scheduled" THEN 1 END) as pending_deliveries,
                     COUNT(CASE WHEN d.status = "in_transit" THEN 1 END) as in_transit_deliveries')
            ->join('branches b', 'b.id = d.branch_id', 'left')
            ->where('DATE(d.scheduled_date) >=', $startDate)
            ->where('DATE(d.scheduled_date) <=', $endDate)
            ->groupBy('b.id, b.name')
            ->get();

        $reports['delivery_summary'] = $summaryQuery->getResultArray();

        return $reports;
    }
}
