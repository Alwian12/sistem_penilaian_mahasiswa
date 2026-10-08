<?php

$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$scriptDir = str_replace('\\', '/', dirname($scriptName));
$basePath = $scriptDir === '/' ? '' : $scriptDir;

if (getenv('BASE_URL')) {
    define('BASE_URL', getenv('BASE_URL'));
} else {
    define('BASE_URL', 'http://' . $httpHost . $basePath);
}

define('APP_NAME', 'Sistem Informasi Rekap Nilai');
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('ROOT_PATH', dirname(__DIR__));
