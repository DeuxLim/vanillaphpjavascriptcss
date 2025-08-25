<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', __DIR__);
define('VIEW_PATH', BASE_PATH . "/../src/views/");

$DATABASE_CONFIG = [
    "db_user" => "root",
    "db_password" => "secret",
    "db_host" => "localhost",
    "db_port" => 3306,
    "db_name" => "bestToDoApp",
    "db_charset" => "utf8mb4"
];