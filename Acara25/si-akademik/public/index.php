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

(new Router())->dispatch();