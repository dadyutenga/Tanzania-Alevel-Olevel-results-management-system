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
    $routes->get('getClasses', 'StudentController::getClasses');
    $routes->get('getSections/(:num)', 'StudentController::getSections/$1');
    $routes->get('getClasses', 'StudentController::getClasses');
    $routes->get('getSections/(:num)', 'StudentController::getSections/$1');
    $routes->get('fetchStudents', 'StudentController::fetchStudents');
    $routes->get('getSessions', 'StudentController::getSessions');
});

// Exam routes
$routes->group('exam', static function ($routes) {
    $routes->get('/', 'AddExamController::index');
    $routes->get('getSessions', 'AddExamController::getSessions');
    $routes->post('store', 'AddExamController::store');
    
    // Exam Subject routes
    $routes->get('subjects', 'AddExamSubjectController::index');
    $routes->get('subjects/(:num)', 'AddExamSubjectController::index/$1');
    $routes->post('subjects/update/(:num)', 'AddExamSubjectController::update/$1');
    $routes->get('subjects/list/(:num)', 'AddExamSubjectController::getExamSubjects/$1');
});