<?php

// Initialize dependencies
require __DIR__ . "/../src/config.php";
require __DIR__ . "/../src/autoload.php";

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize Router
$router = new App\Router();
require __DIR__ . "/../src/routes/web.php";

// Route
$router->dispatch();