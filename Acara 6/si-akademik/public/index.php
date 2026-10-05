<?php

session_start();

require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

require_once __DIR__ . '/../routes/web.php';

use App\Core\Router;
use App\Core\Middleware\AuthMiddleware;

$middlewareMap = [
    'auth' => AuthMiddleware::class,
];

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$method = $_SERVER['REQUEST_METHOD'];

$uri = rawurldecode($uri);

$basePath = rtrim(
    str_replace(
        '\\',
        '/',
        dirname($_SERVER['SCRIPT_NAME'])
    ),
    '/'
);

if (
    $basePath !== '' &&
    str_starts_with($uri, $basePath)
) {
    $uri = substr(
        $uri,
        strlen($basePath)
    );
}

$uri = $uri === '' ? '/' : $uri;

$router = new Router(
    $routes,
    $middlewareMap
);

$router->dispatch(
    $method,
    $uri
);