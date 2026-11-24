<?php

require_once __DIR__ . '/../app/core/autoload.php';

// Header CORS: berlaku untuk semua resource yang melewati Router
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight request dari browser dijawab langsung, tanpa masuk Router
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptDir = rtrim($scriptDir, '/');
$basePath = $scriptDir === '' ? '/' : $scriptDir;
$accept = $_SERVER['HTTP_ACCEPT'] ?? '';

if (
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET'
    && rtrim($requestPath, '/') === rtrim($basePath, '/')
    && str_contains($accept, 'text/html')
) {
    header('Location: views/login.html', true, 302);
    exit;
}

(new Router())->dispatch();