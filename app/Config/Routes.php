<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

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
$routes->get('franchisemanager/dashboard', 'FranchiseManager::dashboard');
$routes->get('logisticscoordinator/dashboard', 'LogisticsCoordinator::dashboard');

// ---------- STAFF DASHBOARD & ACTIONS ----------
$routes->get('staff/dashboard', 'Staff::dashboard');
$routes->get('inventorystaff/dashboard', 'Staff::dashboard');
$routes->get('inventorystaff/item/(:num)', 'Staff::item/$1');

$routes->post('staff/addProduct', 'Staff::addProduct');
$routes->post('staff/updateStock/(:num)', 'Staff::updateStock/$1');

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
    $routes->post('request/(:num)/approve', 'PurchaseController::approveRequest/$1');
    $routes->post('request/(:num)/reject', 'PurchaseController::rejectRequest/$1');
    $routes->post('request/(:num)/convert-to-po', 'PurchaseController::convertToPO/$1');
});

// ---------- SUPPLIER MODULE ----------
$routes->group('supplier', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'SupplierController::index');
    $routes->get('list', 'SupplierController::index');
    $routes->get('(:num)', 'SupplierController::getSupplier/$1');
    $routes->post('create', 'SupplierController::create');
    $routes->post('(:num)/update', 'SupplierController::update/$1');
    $routes->post('(:num)/delete', 'SupplierController::delete/$1');
    $routes->get('performance', 'SupplierController::getSuppliersWithPerformance');
});

// ---------- DELIVERY MODULE ----------
$routes->group('delivery', ['filter' => 'auth'], function($routes) {
    $routes->post('schedule', 'DeliveryController::scheduleDelivery');
    $routes->post('(:num)/update-status', 'DeliveryController::updateDeliveryStatus/$1');
    $routes->post('(:num)/receive', 'DeliveryController::receiveDelivery/$1');
    $routes->get('(:num)/track', 'DeliveryController::trackDelivery/$1');
    $routes->get('branch/list', 'DeliveryController::getDeliveriesByBranch');
    $routes->get('supplier/(:num)/list', 'DeliveryController::getDeliveriesBySupplier/$1');
});

// ---------- PURCHASE ORDER MODULE ----------
$routes->group('purchase/order', ['filter' => 'auth'], function($routes) {
    $routes->get('(:num)/track', 'PurchaseController::trackOrder/$1');
});
