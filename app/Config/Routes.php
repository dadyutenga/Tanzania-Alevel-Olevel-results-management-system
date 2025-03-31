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
    $routes->post('subjects/store-batch', 'AddExamSubjectController::storeBatch');
    $routes->post('subjects/update/(:num)', 'AddExamSubjectController::update/$1');
    $routes->get('subjects/list/(:num)', 'AddExamSubjectController::getExamSubjects/$1');
    $routes->post('subjects/delete/(:num)', 'AddExamSubjectController::delete/$1');
    
    // Allocation routes
    $routes->get('allocation', 'AllocationController::index');
    $routes->get('allocation/exams/(:num)', 'AllocationController::getExamsBySession/$1');
    $routes->get('allocation/list/(:num)', 'AllocationController::getAllocations/$1');
    $routes->post('allocation/store', 'AllocationController::store');
    $routes->post('allocation/delete/(:num)/(:num)', 'AllocationController::deallocate/$1/$2');
    
    // Add Exam Marks routes
    $routes->get('marks', 'AddExamMarks::index');
    $routes->get('marks/exams/(:num)', 'AddExamMarks::getExams/$1');
    $routes->get('marks/classes/(:num)', 'AddExamMarks::getClasses/$1');
    $routes->get('marks/subjects', 'AddExamMarks::getSubjects');
    $routes->get('marks/students', 'AddExamMarks::getStudents');
    $routes->get('marks/existing/(:num)/(:num)', 'AddExamMarks::getExistingMarks/$1/$2');
    $routes->post('marks/save', 'AddExamMarks::saveMarks');
});
