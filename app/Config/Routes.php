<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------- LEGAL PAGES ----------
$routes->get('legal/terms', 'Legal::termsOfService');
$routes->get('legal/privacy', 'Legal::privacyPolicy');

// ---------- AUTH ----------
$routes->get('/', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/checkLogin', 'Auth::checkLogin');
$routes->get('auth/logout', 'Auth::logout');

// ---------- FORGOT PASSWORD ----------
$routes->get('auth/forgotPasswordForm', 'Auth\Forgot::index'); // main form
$routes->get('auth/forgot', 'Auth\Forgot::index');             // alternate URL

// ---------- SEND RESET LINK + OTP ----------
$routes->post('auth/forgot/sendResetLink', 'Auth\Forgot::sendResetLink'); // send email with link + OTP
$routes->post('auth/forgot/submit', 'Auth\Forgot::sendResetLink');        // backward compatibility

// ---------- OTP FLOW ----------
$routes->get('auth/forgot/otp', 'Auth\Forgot::otpForm');          // show OTP input form
$routes->post('auth/forgot/verifyOtp', 'Auth\Forgot::verifyOtp'); // verify submitted OTP

// ---------- RESET PASSWORD ----------
$routes->get('auth/reset-password', 'Auth\ResetPassword::index');           // reset form (via token)
$routes->post('auth/reset-password/submit', 'Auth\ResetPassword::submit'); // reset password submit

// ---------- EMAIL TEST ----------
$routes->get('auth/emailtest', 'Auth\EmailTest::index');
$routes->post('auth/emailtest/send', 'Auth\EmailTest::sendTest');
$routes->get('auth/emailtest/sendTest', 'Auth\EmailTest::sendTest'); // original
$routes->get('auth/emailtest/sendtest', 'Auth\EmailTest::sendTest'); // ✅ lowercase alias
$routes->get('email-test', 'Auth\EmailTest::index');
$routes->get('email-test/send', 'Auth\EmailTest::sendTest');
$routes->get('emailtest/sendtest', 'Auth\EmailTest::sendTest');


// ---------- GENERIC DASHBOARD ----------
$routes->get('dashboard', 'Auth::dashboard');

// ---------- ROLE DASHBOARDS ----------
$routes->get('superadmin/dashboard', 'Superadmin::dashboard');
$routes->get('centraladmin/dashboard', 'CentralAdmin::dashboard');
$routes->get('centraladmin/dashboard/data', 'CentralAdmin::getDashboardDataAPI');
$routes->get('centraladmin/deliveries/list', 'CentralAdmin::getDeliveriesList');
$routes->get('centraladmin/suppliers', 'CentralAdmin::suppliersPage');
$routes->get('centraladmin/deliveries', 'CentralAdmin::deliveriesPage');
$routes->get('centraladmin/reports', 'CentralAdmin::reportsPage');
$routes->get('manager/dashboard', 'Manager::dashboard');
$routes->get('manager/deliveries', 'Manager::deliveries');
$routes->get('manager/stock-out', 'Manager::stockOut');
$routes->get('manager/settings', 'Manager::settings');
$routes->get('manager/api/search-products', 'Manager::searchProducts');
$routes->post('manager/api/stock-out', 'Manager::recordStockOut');
$routes->get('franchisemanager/dashboard', 'FranchiseManager::dashboard');

// ---------- FRANCHISE MANAGER APIs ----------
$routes->post('franchisemanager/application/create', 'FranchiseManager::createApplication');
$routes->post('franchisemanager/application/(:num)/status', 'FranchiseManager::updateApplicationStatus/$1');
$routes->get('franchisemanager/api/applications', 'FranchiseManager::getApplicationsList');
$routes->post('franchisemanager/allocation/create', 'FranchiseManager::createAllocation');
$routes->post('franchisemanager/allocation/(:num)/status', 'FranchiseManager::updateAllocationStatus/$1');
$routes->get('franchisemanager/api/allocations', 'FranchiseManager::getAllocationsList');
$routes->post('franchisemanager/royalty/create', 'FranchiseManager::createRoyalty');
$routes->post('franchisemanager/royalty/(:num)/payment', 'FranchiseManager::recordPayment/$1');
$routes->get('franchisemanager/api/royalties', 'FranchiseManager::getRoyaltiesList');
$routes->get('logisticscoordinator/dashboard', 'LogisticsCoordinator::dashboard');
$routes->get('logisticscoordinator/schedule', 'LogisticsCoordinator::scheduleDelivery');
$routes->get('logisticscoordinator/track-orders', 'LogisticsCoordinator::trackOrders');
$routes->get('logisticscoordinator/deliveries', 'LogisticsCoordinator::deliveries');
$routes->get('logisticscoordinator/schedules', 'LogisticsCoordinator::schedules');

// ---------- STAFF DASHBOARD & ACTIONS ----------
$routes->get('staff/dashboard', 'Staff::dashboard');
$routes->get('inventorystaff/dashboard', 'Staff::dashboard');
$routes->get('inventorystaff/item/(:num)', 'Staff::item/$1');

$routes->post('staff/addProduct', 'Staff::addProduct');
$routes->post('staff/updateStock/(:num)', 'Staff::updateStock/$1');
$routes->post('staff/receiveDelivery/(:num)', 'Staff::receiveDelivery/$1');
$routes->post('staff/reportDamage/(:num)', 'Staff::reportDamage/$1');
$routes->get('staff/checkExpiry/(:num)', 'Staff::checkExpiry/$1');
$routes->get('staff/api/get-branch-products', 'Staff::getBranchProducts');
$routes->post('staff/api/stock-in', 'Staff::recordStockIn');
$routes->post('staff/api/stock-out', 'Staff::recordStockOut');
$routes->get('staff/api/get-deliveries', 'Staff::getDeliveries');
$routes->get('delivery/(:num)/details', 'DeliveryController::trackDelivery/$1');

// ---------- INVENTORY GROUP ----------
$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Staff::index');  
    $routes->post('add', 'Staff::add');  
    $routes->post('updateStock', 'Staff::updateStock');  
    $routes->post('reportDamage', 'Staff::reportDamage');  
    $routes->get('branch/(:segment)', 'Staff::getBranchInventory/$1');  
    $routes->get('expiry/(:num)', 'Staff::checkExpiry/$1');  

    $routes->get('staff', 'Staff::index');
    $routes->get('staff/dashboard', 'Staff::dashboard');

    // Inventory AJAX APIs
    $routes->get('items', 'Staff::getItems');
    $routes->post('staff/add-product', 'Staff::addProduct');
    $routes->post('staff/addProduct', 'Staff::addProduct');
    $routes->post('staff/update-stock/(:num)', 'Staff::updateStock/$1');
    $routes->post('inventory/reportDamage', 'Staff::reportDamage');
});

// ---------- PURCHASING MODULE ----------
$routes->group('purchase', ['filter' => 'auth'], function($routes) {
    // Purchase Requests - UI
    $routes->get('request/new', 'PurchaseController::showForm');
    $routes->get('request/list', 'PurchaseController::showRequests');
    // Purchase Requests - API
    $routes->post('request/create', 'PurchaseController::createRequest');
    $routes->get('request/api/list', 'PurchaseController::getRequests');
    $routes->get('request/pending', 'PurchaseController::getPendingRequests');
    $routes->get('request/(:num)', 'PurchaseController::getRequest/$1');
    $routes->post('request/(:num)/accept-supplier', 'PurchaseController::acceptSupplier/$1');
    $routes->post('request/(:num)/approve', 'PurchaseController::approveRequest/$1');
    $routes->post('request/(:num)/reject', 'PurchaseController::rejectRequest/$1');
    $routes->post('request/(:num)/convert-to-po', 'PurchaseController::convertToPO/$1');
});

// ---------- SUPPLIER MODULE ----------
$routes->group('supplier', ['filter' => 'auth'], function($routes) {
    $routes->get('list', 'SupplierController::showSuppliersList');
    $routes->get('/', 'SupplierController::index');
    $routes->get('api/list', 'SupplierController::index');
    $routes->get('seed', 'SupplierController::seedSuppliers');
    $routes->get('(:num)', 'SupplierController::getSupplier/$1');
    $routes->post('create', 'SupplierController::create');
    $routes->post('(:num)/update', 'SupplierController::update/$1');
    $routes->post('(:num)/delete', 'SupplierController::delete/$1');
    $routes->get('performance', 'SupplierController::getSuppliersWithPerformance');
});

    // ---------- DELIVERY MODULE ----------
    $routes->group('delivery', ['filter' => 'auth'], function($routes) {
        $routes->get('branch/list', 'DeliveryController::showDeliveriesList');
        $routes->get('api/branch/list', 'DeliveryController::getDeliveriesByBranch');
        $routes->post('schedule', 'DeliveryController::scheduleDelivery');
        $routes->post('(:num)/update-status', 'DeliveryController::updateDeliveryStatus/$1');
        $routes->post('(:num)/receive', 'DeliveryController::receiveDelivery/$1');
        $routes->post('(:num)/confirm', 'DeliveryController::confirmDelivery/$1');
        $routes->get('(:num)/track', 'DeliveryController::trackDelivery/$1');
        $routes->get('supplier/(:num)/list', 'DeliveryController::getDeliveriesBySupplier/$1');
    });

// ---------- PURCHASE ORDER MODULE ----------
$routes->group('purchase/order', ['filter' => 'auth'], function($routes) {
    $routes->get('list', 'PurchaseController::showPurchaseOrdersList');
    $routes->get('api/list', 'PurchaseController::getPurchaseOrdersList');
    $routes->post('(:num)/update', 'PurchaseController::updatePurchaseOrder/$1');
    $routes->post('(:num)/approve', 'PurchaseController::approvePurchaseOrder/$1');
    $routes->get('(:num)/track', 'PurchaseController::trackOrder/$1');
    $routes->get('(:num)', 'PurchaseController::getPurchaseOrder/$1');
});

// ---------- ACCOUNTS PAYABLE MODULE ----------
$routes->group('accounts-payable', ['filter' => 'auth'], function($routes) {
    $routes->get('list', 'AccountsPayableController::showAccountsPayableList');
    $routes->get('api/list', 'AccountsPayableController::getAccountsPayableList');
    $routes->get('summary', 'AccountsPayableController::getSummary');
    $routes->get('backfill', 'AccountsPayableController::backfillAccountsPayable');
    $routes->get('(:num)', 'AccountsPayableController::getAccountsPayable/$1');
    $routes->post('(:num)/update-invoice', 'AccountsPayableController::updateInvoice/$1');
    $routes->post('(:num)/record-payment', 'AccountsPayableController::recordPayment/$1');
});
