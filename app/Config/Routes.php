<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------- AUTH ----------
$routes->get('/', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/checkLogin', 'Auth::checkLogin');
$routes->get('auth/logout', 'Auth::logout');

$routes->get('auth/forgotPasswordForm', 'Auth::forgotPasswordForm');
$routes->post('auth/sendResetLink', 'Auth::sendResetLink');

// ---------- GENERIC DASH ----------
$routes->get('dashboard', 'Auth::dashboard');

// ---------- ROLE DASHBOARDS ----------
$routes->get('superadmin/dashboard', 'Superadmin::dashboard');
$routes->get('centraladmin/dashboard', 'CentralAdmin::dashboard');
$routes->get('manager/dashboard', 'Manager::dashboard');
$routes->get('franchisemanager/dashboard', 'FranchiseManager::dashboard');
$routes->get('logisticscoordinator/dashboard', 'LogisticsCoordinator::dashboard');

// Inventory staff (both URLs work)
$routes->get('staff/dashboard', 'Staff::dashboard');
$routes->get('inventorystaff/dashboard', 'Staff::dashboard');
$routes->get('inventorystaff/item/(:num)', 'Staff::item/$1');


$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Staff::index');  
    $routes->post('add', 'Staff::add');  
    $routes->post('updateStock', 'Staff::updateStock');  
    $routes->post('reportDamage', 'Staff::reportDamage');  
    $routes->get('branch/(:segment)', 'Staff::getBranchInventory/$1');  
    $routes->get('expiry/(:num)', 'Staff::checkExpiry/$1');  

    // Staff Dashboard
    $routes->get('staff', 'Staff::index');
    $routes->get('staff/dashboard', 'Staff::dashboard');

            // Inventory AJAX APIs
    $routes->get('inventory/items', 'Staff::getItems');
    $routes->post('inventory/add', 'Staff::add');
    $routes->post('inventory/updateStock', 'Staff::updateStock');
    $routes->post('inventory/reportDamage', 'Staff::reportDamage');
 });
