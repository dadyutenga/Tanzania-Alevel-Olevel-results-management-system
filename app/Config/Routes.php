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
    $routes->get('/', 'AddExamController::index');                    // Show add exam form
    $routes->get('getSessions', 'AddExamController::getSessions');    // Get active sessions for dropdown
    $routes->post('store', 'AddExamController::store');              // Store new exam
    
    // Add Exam Subject routes - using 'subjects' (plural) to avoid conflicts
    $routes->group('subjects', static function ($routes) {
        $routes->get('add/(:num)', 'AddExamSubjectController::index/$1');     // Show subject form for specific exam
        $routes->get('list/(:num)', 'AddExamSubjectController::getExamSubjects/$1'); // Get subjects for exam
        $routes->post('add', 'AddExamSubjectController::store');        // Store single subject
        $routes->delete('(:num)', 'AddExamSubjectController::delete/$1'); // Delete subject
    });
});