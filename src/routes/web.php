<?php

use App\Controllers\AuthController;

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

});

// Open routes
$router->get('/', function () {
    echo "Welcome to your dashboard!";
});