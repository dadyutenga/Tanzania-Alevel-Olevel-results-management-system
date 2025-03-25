<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Dashboard route (make this the default route)
$routes->get('/', 'DashboardController::index');
$routes->get('dashboard', 'DashboardController::index');

// Student routes
$routes->group('student', static function ($routes) {
    $routes->get('/', 'StudentController::index');
    $routes->get('fetchStudents', 'StudentController::fetchStudents');
    $routes->get('getStudent/(:num)', 'StudentController::getStudent/$1');
});