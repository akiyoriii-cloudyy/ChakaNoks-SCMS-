<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ================= AUTH ROUTES =================

// Default → login page
$routes->get('/', 'Auth::login');

// Login & Logout
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/checkLogin', 'Auth::checkLogin');
$routes->get('auth/logout', 'Auth::logout');

// Forgot Password
$routes->get('auth/forgotPasswordForm', 'Auth::forgotPasswordForm');
$routes->post('auth/sendResetLink', 'Auth::sendResetLink');

// Generic dashboard (fallback)
$routes->get('dashboard', 'Auth::dashboard');

// ================= ROLE-BASED DASHBOARDS =================
$routes->get('superadmin/dashboard', 'Superadmin::dashboard');
$routes->get('centraladmin/dashboard', 'CentralAdmin::dashboard');
$routes->get('manager/dashboard', 'Manager::dashboard');
$routes->get('franchisemanager/dashboard', 'FranchiseManager::dashboard');
$routes->get('logisticscoordinator/dashboard', 'LogisticsCoordinator::dashboard');
$routes->get('staff/dashboard', 'Staff::dashboard');
