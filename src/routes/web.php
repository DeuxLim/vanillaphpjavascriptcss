<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;


$router = new App\Router();

// Connectivity test
$router->get('/welcome', function(){
    echo "Welcome User!";
});

// Guest routes
$router->middleware(['guest'])->group(function ($router) {
    $router->get('/login', [AuthController::class, 'showLoginForm']);
    $router->post('/login', [AuthController::class, 'login']);

    $router->get('/register', [AuthController::class, 'showRegistrationForm']);
    $router->post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
$router->middleware(['auth'])->group(function ($router) {
    $router->post('/logout', [AuthController::class, "logout"]);
    $router->get('/dashboard', [DashboardController::class, "index"]);
});

// Open routes
$router->get('/', function () {
    echo "Welcome to the best To Do App!";
});


// Test route
$router->get('/test', function () {
    $array1 = [1, 2, 3, 4, 5];
    $array2 = [5, 6, 7, 8, 9];

    $output = array_intersect_key($array1, $array2);

    d($output);
});