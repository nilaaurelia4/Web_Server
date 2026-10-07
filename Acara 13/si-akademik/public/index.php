<?php

session_start();


/*
|--------------------------------------------------------------------------
| CORE
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../app/Core/BaseController.php';

require_once __DIR__ .
    '/../app/Core/Controller.php';

require_once __DIR__ .
    '/../app/Core/Database.php';

require_once __DIR__ .
    '/../app/Core/BaseModel.php';

require_once __DIR__ .
    '/../app/Core/Model.php';

require_once __DIR__ .
    '/../app/Core/Router.php';


/*
|--------------------------------------------------------------------------
| MIDDLEWARE
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../app/Core/Middleware/AuthMiddleware.php';


/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../app/Models/Mahasiswa.php';

require_once __DIR__ .
    '/../app/Models/Prodi.php';

require_once __DIR__ .
    '/../app/Models/MataKuliah.php';


/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ .
    '/../app/Repositories/ProdiRepository.php';


/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../app/Services/MahasiswaService.php';

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../app/Controllers/HomeController.php';

require_once __DIR__ .
    '/../app/Controllers/AuthController.php';

require_once __DIR__ .
    '/../app/Controllers/MahasiswaController.php';

require_once __DIR__ .
    '/../app/Controllers/ProdiController.php';

require_once __DIR__ .
    '/../app/Controllers/MataKuliahController.php';


/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

require_once __DIR__ .
    '/../routes/web.php';


/*
|--------------------------------------------------------------------------
| USE
|--------------------------------------------------------------------------
*/

use App\Core\Database;
use App\Core\Router;
use App\Core\Middleware\AuthMiddleware;
use App\Repositories\MahasiswaRepository;
use App\Controllers\MahasiswaController;
use App\Repositories\ProdiRepository;
use App\Services\MahasiswaService;


/*
|--------------------------------------------------------------------------
| DEPENDENCY INJECTION
|--------------------------------------------------------------------------
|
| Database
|     ↓
| MahasiswaRepository
|     ↓
| MahasiswaController
|
*/


$database =
    Database::getInstance();


$mahasiswaRepository =
    new MahasiswaRepository(
        $database
    );

$prodiRepository =
    new ProdiRepository(
        $database
    );


$mahasiswaService =
    new MahasiswaService(
        $mahasiswaRepository,
        $prodiRepository
    );


$controllerFactories = [

    'MahasiswaController' =>
        function () use (
            $mahasiswaRepository,
            $prodiRepository,
            $mahasiswaService
        ) {

            return new MahasiswaController(
                $mahasiswaRepository,
                $prodiRepository,
                $mahasiswaService
            );
        },
];


/*
|--------------------------------------------------------------------------
| MIDDLEWARE
|--------------------------------------------------------------------------
*/

$middlewareMap = [

    'auth' =>
        AuthMiddleware::class,

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


$method =
    $_SERVER['REQUEST_METHOD'];


$uri =
    rawurldecode($uri);


/*
|--------------------------------------------------------------------------
| BASE PATH
|--------------------------------------------------------------------------
*/

$basePath = rtrim(

    str_replace(
        '\\',
        '/',
        dirname(
            $_SERVER['SCRIPT_NAME']
        )
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

    $uri =
        substr(
            $uri,
            strlen($basePath)
        );
}


$uri =
    $uri === ''
        ? '/'
        : $uri;


/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

$router = new Router(

    $routes,

    $middlewareMap,

    $controllerFactories
);


$router->dispatch(
    $method,
    $uri
);