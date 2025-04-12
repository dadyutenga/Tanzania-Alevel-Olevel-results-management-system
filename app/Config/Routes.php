<?php

use CodeIgniter\Router\RouteCollection;
use Config\Auth; // Add this for Shield

/**
 * @var RouteCollection $routes
 */

// Remove index.php from URLs
$routes->setAutoRoute(false);
$routes->setDefaultNamespace('App\Controllers');

// Public routes (no authentication required)
$routes->get('/', 'Home::index');

// Authentication Routes - Make sure these are BEFORE the protected routes
$routes->group('', [], function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('login', '\CodeIgniter\Shield\Controllers\LoginController::loginView');
    $routes->post('login', '\CodeIgniter\Shield\Controllers\LoginController::loginAction');
    $routes->get('register', '\CodeIgniter\Shield\Controllers\RegisterController::registerView');
    $routes->post('register', '\CodeIgniter\Shield\Controllers\RegisterController::registerAction');
    $routes->get('logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction');
});

// Protected routes (require authentication)
$routes->group('', ['filter' => 'session'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    // ... other protected routes ...
});
