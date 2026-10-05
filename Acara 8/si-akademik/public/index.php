<?php

session_start();

/*
|--------------------------------------------------------------------------
| CORE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Core/Router.php';

/*
|--------------------------------------------------------------------------
| MIDDLEWARE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';

/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Models/Prodi.php';
require_once __DIR__ . '/../app/Models/MataKuliah.php';

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MataKuliahController.php';

/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../routes/web.php';

use App\Core\Router;
use App\Core\Middleware\AuthMiddleware;

/*
|--------------------------------------------------------------------------
| MIDDLEWARE MAP
|--------------------------------------------------------------------------
*/

$middlewareMap = [
    'auth' => AuthMiddleware::class,
];

/*
|--------------------------------------------------------------------------
| REQUEST
|--------------------------------------------------------------------------
*/

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$method = $_SERVER['REQUEST_METHOD'];

$uri = rawurldecode($uri);

/*
|--------------------------------------------------------------------------
| BASE PATH
|--------------------------------------------------------------------------
*/

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
    str_starts_with(
        $uri,
        $basePath
    )
) {
    $uri = substr(
        $uri,
        strlen($basePath)
    );
}

$uri = $uri === ''
    ? '/'
    : $uri;

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

$router = new Router(
    $routes,
    $middlewareMap
);

$router->dispatch(
    $method,
    $uri
);