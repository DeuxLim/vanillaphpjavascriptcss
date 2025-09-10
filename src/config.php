<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila');

define('BASE_PATH', __DIR__);
define('VIEW_PATH', BASE_PATH . "/../src/views/");

// load env file if it exists (simple parser)
$envFile = BASE_PATH . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // skip comments
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

$DATABASE_CONFIG = [
    "db_user"     => getenv("DB_USER") ?: "root",
    "db_password" => getenv("DB_PASSWORD") ?: "secret",
    "db_host"     => getenv("DB_HOST") ?: "database",
    "db_port"     => getenv("DB_PORT") ?: 3306,
    "db_name"     => getenv("DB_NAME") ?: "taskflow_db",
    "db_charset"  => getenv("DB_CHARSET") ?: "utf8mb4"
];