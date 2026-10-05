<?php

session_start();

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../routes/web.php';

/*
|--------------------------------------------------------------------------
| Ambil URI dan method request
|--------------------------------------------------------------------------
*/

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| Decode URL
|--------------------------------------------------------------------------
| Mengubah %20 menjadi spasi.
|--------------------------------------------------------------------------
*/

$uri = rawurldecode($uri);

/*
|--------------------------------------------------------------------------
| Tentukan base path project
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

$basePath = rawurldecode($basePath);

/*
|--------------------------------------------------------------------------
| Hilangkan base path
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Fungsi mencocokkan route
|--------------------------------------------------------------------------
*/

function matchRoute(
    string $route,
    string $uri
): ?array {

    $pattern = preg_quote(
        $route,
        '#'
    );

    $pattern = str_replace(
        '\{id\}',
        '(?P<id>[0-9]+)',
        $pattern
    );

    if (
        preg_match(
            '#^' . $pattern . '$#',
            $uri,
            $matches
        )
    ) {

        $params = [];

        if (isset($matches['id'])) {
            $params['id'] = $matches['id'];
        }

        return $params;
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| Cari route
|--------------------------------------------------------------------------
*/

$methodRoutes = $routes[$method] ?? null;

if ($methodRoutes !== null) {

    foreach (
        $methodRoutes
        as $route => [$controllerName, $action]
    ) {

        $params = matchRoute(
            $route,
            $uri
        );

        if ($params !== null) {

            $controllerClass =
                "App\\Controllers\\{$controllerName}";

            $controller =
                new $controllerClass();

            $controller->$action(
                ...array_values($params)
            );

            exit;
        }
    }
}


/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/

http_response_code(404);

echo '<h1>404 - Halaman Tidak Ditemukan</h1>';

echo '<p>URL yang diminta: ' .
    htmlspecialchars(
        $uri,
        ENT_QUOTES,
        'UTF-8'
    ) .
    '</p>';