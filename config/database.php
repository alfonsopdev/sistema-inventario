<?php

if (!isset($_ENV['DB_HOST'])) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

//conexion local
define("DRIVER", $_ENV['DB_DRIVER'] ?? 'mysql');
define("HOST", $_ENV['DB_HOST'] ?? 'localhost');
define("USER", $_ENV['DB_USER'] ?? '');
define("PASS", $_ENV['DB_PASS'] ?? '');
define("DATABASE", $_ENV['DB_DATABASE'] ?? '');
define("PORT", $_ENV['DB_PORT'] ?? '3306');
define("CHARSET", $_ENV['DB_CHARSET'] ?? 'utf8mb4');

?>