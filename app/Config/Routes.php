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

    // Bulk Exam Marks routes
    $routes->get('marks/bulk', 'BulkExamMarksController::index');
    
    // View Exam Marks routes
    $routes->group('exam/marks/view', static function ($routes) {
        $routes->get('/', 'ViewExamMarksController::index');
        $routes->get('getExams', 'ViewExamMarksController::getExams');
        $routes->get('getExamClasses', 'ViewExamMarksController::getExamClasses');
        $routes->get('getStudentMarks', 'ViewExamMarksController::getStudentMarks');
        $routes->post('update/(:num)', 'ViewExamMarksController::update/$1');
        $routes->post('delete/(:num)', 'ViewExamMarksController::delete/$1');
        $routes->post('updateAll', 'ViewExamMarksController::updateAll'); // New route
        $routes->post('deleteAll', 'ViewExamMarksController::deleteAll'); // New route
    });
    $routes->get('marks/bulk/getExams/(:num)', 'BulkExamMarksController::getExams/$1');
    $routes->get('marks/bulk/getClasses/(:num)', 'BulkExamMarksController::getClasses/$1');
    $routes->get('marks/bulk/downloadTemplate', 'BulkExamMarksController::downloadTemplate');
    $routes->post('marks/bulk/uploadMarks', 'BulkExamMarksController::uploadMarks');

    // View Exam routes
    $routes->get('view', 'ViewExamController::index');
    $routes->get('view/getSessions', 'ViewExamController::getSessions');
    $routes->get('view/getExams', 'ViewExamController::getExams');
    $routes->post('view/update/(:num)', 'ViewExamController::update/$1');
    $routes->post('view/delete/(:num)', 'ViewExamController::delete/$1');
});

$routes->group('results', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('publish', 'ResultGradingController::showPublishPage');
    $routes->post('process-grades', 'ResultGradingController::processGradeCalculation');
    $routes->get('fetch-class-results', 'ResultGradingController::fetchClassResults');
    $routes->get('fetch-class-exams', 'ResultGradingController::fetchClassExams');
    $routes->get('getExams', 'ResultGradingController::getExams');
    $routes->get('getSections/(:num)', 'ResultGradingController::getSections/$1');
    $routes->get('getExamsBySession/(:num)', 'ResultGradingController::getExamsBySession/$1');
});
