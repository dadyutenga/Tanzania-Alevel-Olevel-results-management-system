<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Remove index.php from URLs
$routes->setAutoRoute(false);
$routes->setDefaultNamespace("App\Controllers");

// =============================================================================
// Public Routes (No Authentication Required)
// =============================================================================
$routes->group("", [], function ($routes) {
    // Homepage
    $routes->get("/", "Home::index");

    // Public Student Results
    $routes->group(
        "public/results",
        ["namespace" => "App\Controllers"],
        function ($routes) {
            $routes->get("/", "StudenResultController::showStudentResultsPage");
            $routes->get("getExams", "StudenResultController::getExams");
            $routes->post(
                "getFilteredStudentResults",
                "StudenResultController::getFilteredStudentResults",
            );
            $routes->post(
                "getStudentSubjectMarks",
                "StudenResultController::getStudentSubjectMarks",
            );
            $routes->post(
                "getReportCard",
                "StudenResultController::getReportCard",
            );
        },
    );
});

// =============================================================================
// Authentication Routes (Custom)
// =============================================================================
$routes->group("", [], function ($routes) {
    $routes->get("login", "AuthController::loginForm");
    $routes->post("login", "AuthController::login");
    $routes->get("logout", "AuthController::logout");

    $routes->get("register", "AuthController::registerForm");
    $routes->post("register", "AuthController::register");
});

// =============================================================================
// Protected Routes (Require Authentication)
// =============================================================================
$routes->group("", ["filter" => "auth"], function ($routes) {
    // -----------------------------------------------------------------------------
    // Dashboard
    // -----------------------------------------------------------------------------
    $routes->get("/", "DashboardController::index");
    $routes->get("dashboard", "DashboardController::index");

    // -----------------------------------------------------------------------------
    // Students (Old)
    // -----------------------------------------------------------------------------
    $routes->group("student", function ($routes) {
        $routes->get("/", "StudentController::index");
        $routes->get("fetchStudents", "StudentController::fetchStudents");
        $routes->get("getStudent/(:num)", 'StudentController::getStudent/$1');
        $routes->get("getClasses", "StudentController::getClasses");
        $routes->get("getSections/(:num)", 'StudentController::getSections/$1');
        $routes->get("getSessions", "StudentController::getSessions");
    });

    // -----------------------------------------------------------------------------
    // Student Management
    // -----------------------------------------------------------------------------
    $routes->group("students", ["namespace" => "App\Controllers"], function ($routes) {
        $routes->get("/", "StudentManagementController::index");
        $routes->get("create", "StudentManagementController::create");
        $routes->get("edit/(:segment)", "StudentManagementController::edit/$1");
        $routes->get("bulk-register", "StudentManagementController::bulkRegister");
        $routes->post("store", "StudentManagementController::store");
        $routes->post("store-bulk", "StudentManagementController::storeBulk");
        $routes->post("update/(:segment)", "StudentManagementController::update/$1");
        $routes->get("getStudents", "StudentManagementController::getStudents");
        $routes->post("delete/(:segment)", "StudentManagementController::delete/$1");
    });

    // -----------------------------------------------------------------------------
    // Class Management
    // -----------------------------------------------------------------------------
    $routes->group("classes", ["namespace" => "App\Controllers"], function ($routes) {
        $routes->get("/", "ClassManagementController::index");
        $routes->get("create", "ClassManagementController::create");
        $routes->get("edit/(:segment)", "ClassManagementController::edit/$1");
        $routes->post("store", "ClassManagementController::store");
        $routes->post("update/(:segment)", "ClassManagementController::update/$1");
        $routes->get("getClasses", "ClassManagementController::getClasses");
        $routes->post("delete/(:segment)", "ClassManagementController::delete/$1");
        $routes->delete("delete/(:segment)", "ClassManagementController::delete/$1");
        
        $routes->get("sections", "ClassManagementController::sections");
        $routes->get("sections/create", "ClassManagementController::createSection");
        $routes->get("sections/edit/(:segment)", "ClassManagementController::editSection/$1");
        $routes->post("sections/store", "ClassManagementController::storeSection");
        $routes->post("sections/update/(:segment)", "ClassManagementController::updateSection/$1");
        $routes->get("getSections", "ClassManagementController::getSections");
        $routes->post("sections/delete/(:segment)", "ClassManagementController::deleteSection/$1");
        
        $routes->get("allocations", "ClassManagementController::allocations");
        $routes->get("allocations/create", "ClassManagementController::createAllocation");
        $routes->get("allocations/edit/(:segment)", "ClassManagementController::editAllocation/$1");
        $routes->post("allocations/store", "ClassManagementController::storeAllocation");
        $routes->post("allocations/update/(:segment)", "ClassManagementController::updateAllocation/$1");
        $routes->get("getAllocations", "ClassManagementController::getAllocations");
        $routes->post("allocations/delete/(:segment)", "ClassManagementController::deleteAllocation/$1");
    });

    // -----------------------------------------------------------------------------
    // Exams (O-Level and General)
    // -----------------------------------------------------------------------------
    $routes->group("exam", function ($routes) {
        // Exam Creation
        $routes->get("/", "AddExamController::index");
        $routes->get("getSessions", "AddExamController::getSessions");
        $routes->post("store", "AddExamController::store");

        // Exam Subjects
        $routes->get("subjects", "AddExamSubjectController::index");
        $routes->get("subjects/(:num)", 'AddExamSubjectController::index/$1');
        $routes->post(
            "subjects/store-batch",
            "AddExamSubjectController::storeBatch",
        );
        $routes->post(
            "subjects/update/(:num)",
            'AddExamSubjectController::update/$1',
        );
        $routes->get(
            "subjects/list/(:num)",
            'AddExamSubjectController::getExamSubjects/$1',
        );
        $routes->post(
            "subjects/delete/(:num)",
            'AddExamSubjectController::delete/$1',
        );

        // Exam Allocations
        $routes->get("allocation", "AllocationController::index");
        $routes->get(
            "allocation/exams/(:num)",
            'AllocationController::getExamsBySession/$1',
        );
        $routes->get(
            "allocation/list/(:num)",
            'AllocationController::getAllocations/$1',
        );
        $routes->post("allocation/store", "AllocationController::store");
        $routes->post(
            "allocation/delete/(:num)/(:num)",
            'AllocationController::deallocate/$1/$2',
        );

        // Exam Marks (Individual)
        $routes->get("marks", "AddExamMarks::index");
        $routes->get("marks/exams/(:num)", 'AddExamMarks::getExams/$1');
        $routes->get("marks/classes/(:num)", 'AddExamMarks::getClasses/$1');
        $routes->get("marks/subjects", "AddExamMarks::getSubjects");
        $routes->get("marks/students", "AddExamMarks::getStudents");
        $routes->get(
            "marks/existing/(:num)/(:num)",
            'AddExamMarks::getExistingMarks/$1/$2',
        );
        $routes->post("marks/save", "AddExamMarks::saveMarks");

        // Exam Marks (Bulk)
        $routes->get("marks/bulk", "BulkExamMarksController::index");
        $routes->get(
            "marks/bulk/getExams/(:num)",
            'BulkExamMarksController::getExams/$1',
        );
        $routes->get(
            "marks/bulk/getClasses/(:num)",
            'BulkExamMarksController::getClasses/$1',
        );
        $routes->get(
            "marks/bulk/downloadTemplate",
            "BulkExamMarksController::downloadTemplate",
        );
        $routes->post(
            "marks/bulk/uploadMarks",
            "BulkExamMarksController::uploadMarks",
        );

        // View Exam Marks
        $routes->get("marks/view", "ViewExamMarksController::index");
        $routes->get(
            "marks/view/getExams",
            "ViewExamMarksController::getExams",
        );
        $routes->get(
            "marks/view/getExamClasses",
            "ViewExamMarksController::getExamClasses",
        );
        $routes->get(
            "marks/view/getStudentMarks",
            "ViewExamMarksController::getStudentMarks",
        );
        $routes->post(
            "marks/view/update/(:num)",
            'ViewExamMarksController::update/$1',
        );
        $routes->post(
            "marks/view/delete/(:num)",
            'ViewExamMarksController::delete/$1',
        );
        $routes->post(
            "marks/view/updateAll",
            "ViewExamMarksController::updateAll",
        );
        $routes->post(
            "marks/view/deleteAll",
            "ViewExamMarksController::deleteAll",
        );

        // View Exams
        $routes->get("view", "ViewExamController::index");
        $routes->get("view/getSessions", "ViewExamController::getSessions");
        $routes->get("view/getExams", "ViewExamController::getExams");
        $routes->post("view/update/(:num)", 'ViewExamController::update/$1');
        $routes->post("view/delete/(:num)", 'ViewExamController::delete/$1');
    });

    // -----------------------------------------------------------------------------
    // Results
    // -----------------------------------------------------------------------------
    $routes->group("results", ["namespace" => "App\Controllers"], function (
        $routes,
    ) {
        // Result Grading and Publishing
        $routes->get("publish", "ResultGradingController::showPublishPage");
        $routes->post(
            "process-grades",
            "ResultGradingController::processGradeCalculation",
        );
        $routes->get(
            "fetch-class-results",
            "ResultGradingController::fetchClassResults",
        );
        $routes->get(
            "fetch-class-exams",
            "ResultGradingController::fetchClassExams",
        );
        $routes->get("getExams", "ResultGradingController::getExams");
        $routes->get(
            "getSections/(:num)",
            'ResultGradingController::getSections/$1',
        );
        $routes->get(
            "getExamsBySession/(:num)",
            'ResultGradingController::getExamsBySession/$1',
        );

        // View Results
        $routes->get("view", "ViewResultsModel::showResultsPage");
        $routes->get("view/getExams", "ViewResultsModel::getExams");
        $routes->post(
            "view/getFilteredResults",
            "ViewResultsModel::getFilteredResults",
        );
        $routes->get(
            "view/fetchResults/(:num)/(:num)/(:num)",
            'ViewResultsModel::fetchResults/$1/$2/$3',
        );
        $routes->post(
            "view/getStudentSubjectMarks",
            "ViewResultsModel::getStudentSubjectMarks",
        );
        $routes->post("view/downloadPDF", "PDFController::generateResultPDF");
        $routes->post(
            "view/downloadStudentPDF",
            "PDFController::generateResultPDF",
        );
    });

    // -----------------------------------------------------------------------------
    // A-Level Combinations & Marks
    // -----------------------------------------------------------------------------
    $routes->group(
        "alevel",
        ["namespace" => "App\Controllers\Alevel"],
        function ($routes) {
            // Combinations
            $routes->get("combinations", "AddAlevelController::index");
            $routes->post("combinations/store", "AddAlevelController::store");
            $routes->get(
                "combinations/edit/(:num)",
                'AddAlevelController::edit/$1',
            );
            $routes->post(
                "combinations/update/(:num)",
                'AddAlevelController::update/$1',
            );
            $routes->post(
                "combinations/delete/(:num)",
                'AddAlevelController::delete/$1',
            );

            // Subjects
            $routes->get("subjects", "AlevelSubjectsController::index");
            $routes->get("subjects/view", "AlevelSubjectsController::view");
            $routes->post("subjects/store", "AlevelSubjectsController::store");
            $routes->get(
                "subjects/edit/(:num)",
                'AlevelSubjectsController::edit/$1',
            );
            $routes->post(
                "subjects/update/(:num)",
                'AlevelSubjectsController::update/$1',
            );
            $routes->post(
                "subjects/delete/(:num)",
                'AlevelSubjectsController::delete/$1',
            );

            // Combination Allocations
            $routes->get(
                "allocations",
                "AllocationCombinationClasssController::create",
            );
            $routes->get(
                "allocations/view",
                "AllocationCombinationClasssController::index",
            );
            $routes->post(
                "allocations/store",
                "AllocationCombinationClasssController::store",
            );
            $routes->get(
                "allocations/edit/(:num)",
                'AllocationCombinationClasssController::edit/$1',
            );
            $routes->post(
                "allocations/update/(:num)",
                'AllocationCombinationClasssController::update/$1',
            );
            $routes->get(
                "allocations/delete/(:num)",
                'AllocationCombinationClasssController::delete/$1',
            );
            $routes->get(
                "allocations/get-sections",
                "AllocationCombinationClasssController::getSections",
            );
            $routes->get(
                "allocations/get-classes-by-session/(:num)",
                'AllocationCombinationClasssController::getClassesBySession/$1',
            );

            // Marks Entry
            $routes->get("marks", "AddAlevelMarksController::index");
            $routes->get(
                "marks/getExams/(:num)",
                'AddAlevelMarksController::getExams/$1',
            );
            $routes->get(
                "marks/getClasses/(:num)",
                'AddAlevelMarksController::getClasses/$1',
            );
            $routes->get(
                "marks/getCombinations/(:num)/(:num)",
                'AddAlevelMarksController::getCombinations/$1/$2',
            );
            $routes->get(
                "marks/getStudents",
                "AddAlevelMarksController::getStudents",
            );
            $routes->get(
                "marks/getSubjects",
                "AddAlevelMarksController::getSubjects",
            );
            $routes->get(
                "marks/getExistingMarks/(:num)/(:num)",
                'AddAlevelMarksController::getExistingMarks/$1/$2',
            );
            $routes->post("marks/save", "AddAlevelMarksController::saveMarks");

            // Bulk Marks Upload
            $routes->get("marks/bulk", "BulkMarksUploadController::index");
            $routes->get(
                "marks/bulk/downloadTemplate",
                "BulkMarksUploadController::downloadTemplate",
            );
            $routes->post(
                "marks/bulk/upload",
                "BulkMarksUploadController::uploadMarks",
            );

            // View, Update, and Delete Marks
            $routes->get("marks/view", "ViewAlevelMarksController::index");
            $routes->post("marks/view", "ViewAlevelMarksController::index");
            $routes->post("marks/update", "ViewAlevelMarksController::update");
            $routes->post("marks/delete", "ViewAlevelMarksController::delete");
            $routes->get(
                "marks/getExams/(:num)",
                'ViewAlevelMarksController::getExams/$1',
            );
            $routes->get(
                "marks/getClasses/(:num)",
                'ViewAlevelMarksController::getClasses/$1',
            );

            $routes->get("results/publish", "PublishAlevelResults::index");
            $routes->get(
                "results/getExams/(:num)",
                'PublishAlevelResults::getExams/$1',
            );
            $routes->get(
                "results/getClasses/(:num)",
                'PublishAlevelResults::getClasses/$1',
            );
            $routes->get(
                "results/getCombinations/(:num)/(:num)",
                'PublishAlevelResults::getCombinations/$1/$2',
            );
            $routes->get(
                "results/calculate",
                "PublishAlevelResults::calculateResults",
            );

            // Routes for viewing A-Level results
            $routes->get(
                "results/view",
                "ViewAlevelResultsController::showResultsPage",
            );
            $routes->get(
                "results/view/getExams",
                "ViewAlevelResultsController::getExams",
            );
            $routes->post(
                "results/view/getFilteredResults",
                "ViewAlevelResultsController::getFilteredResults",
            );
            $routes->post(
                "results/view/getStudentSubjectMarks",
                "ViewAlevelResultsController::getStudentSubjectMarks",
            );
            $routes->post(
                "results/view/generateClassResultsPDF",
                "ViewAlevelResultsController::generateClassResultsPDF",
            );
            $routes->post(
                "results/view/downloadStudentResultPDF",
                "ViewAlevelResultsController::downloadStudentResultPDF",
            );
        },
    );

    // -----------------------------------------------------------------------------
    // A-Level Exam Allocations
    // -----------------------------------------------------------------------------
    $routes->group(
        "alevel",
        ["namespace" => "App\Controllers\Alevel"],
        function ($routes) {
            // Exam Allocations
            $routes->get("allocate-exams", "AlllocateAlevelExam::index");
            $routes->get(
                "allocate-exams/get-exams/(:num)",
                'AlllocateAlevelExam::getExamsBySession/$1',
            );
            $routes->get(
                "allocate-exams/get-classes/(:num)",
                'AlllocateAlevelExam::getClassesBySession/$1',
            );
            $routes->post("allocate-exams/store", "AlllocateAlevelExam::store");

            // View Exam Allocations
            $routes->get("view-exams", "ViewAlevelExams::index");
            $routes->get(
                "view-exams/get-allocations/(:num)",
                'ViewAlevelExams::getAllocations/$1',
            );
            $routes->delete(
                "view-exams/deallocate/(:num)/(:num)",
                'ViewAlevelExams::deallocate/$1/$2',
            );
        },
    );

    // -----------------------------------------------------------------------------
    // Settings
    // -----------------------------------------------------------------------------
    $routes->group("settings", ["namespace" => "App\Controllers"], function (
        $routes,
    ) {
        $routes->get("/", "SettingsController::index");
        $routes->get("create", "SettingsController::create");
        $routes->get("edit", "SettingsController::edit");
        $routes->get("view", "SettingsController::view");
        $routes->post("store", "SettingsController::store");
        $routes->post("update", "SettingsController::update");
        $routes->get("test", "SettingsController::test");
    });

    // -------------------------------------------------------------------------
    // Analytics
    // -------------------------------------------------------------------------
    $routes->get("analytics", "DataAnalyticsController::index");
    $routes->get("analytics/overview", "DataAnalyticsController::overview");
});

?>
