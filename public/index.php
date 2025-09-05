<?php

// Initialize dependencies
require __DIR__ . "/../src/config.php";
require __DIR__ . "/../src/autoload.php";

// Start session
App\Session::start();

// Initialize Router
$router = new App\Router();
require __DIR__ . "/../src/routes/web.php";

// Route
$router->dispatch();