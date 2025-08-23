<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/auth/login', 'Auth::login');

$routes->post('/auth/checkLogin', 'Auth::checkLogin');
$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/logout', 'Auth::logout');
