<?php

// Always start the session at the beginning
session_start();

// --- Basic Autoloader ---
// A simple autoloader to avoid manual require_once calls everywhere.
spl_autoload_register(function ($class_name) {
    // Convert namespace separators to directory separators
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);

    // Define base directories for classes
    $base_dirs = [
        __DIR__ . '/../src/',
        __DIR__ . '/../src/controllers/',
    ];

    foreach ($base_dirs as $base_dir) {
        $file = $base_dir . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


// --- Configuration ---
require_once '../config/database.php';


// --- Routing ---
$router = new Router();

// Define routes
// The root and /login will show the login form
$router->get('/', [AuthController::class, 'showLoginForm']);
$router->get('/login', [AuthController::class, 'showLoginForm']);

// This route handles the form submission from the login page
$router->post('/login', [AuthController::class, 'login']);

// This route handles logging out
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard routes for different user roles
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/hod/dashboard', [HodController::class, 'dashboard']);
$router->get('/faculty/dashboard', [FacultyController::class, 'dashboard']);
$router->get('/student/dashboard', [StudentController::class, 'dashboard']);

// Dispatch the router to handle the request
$router->dispatch();
