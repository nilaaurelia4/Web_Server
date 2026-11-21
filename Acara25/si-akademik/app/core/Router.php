
<?php

class Router
{
    // Nama resource di URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
        'login'     => 'AuthController',
    ];

    public function dispatch(): void
    {
        $uri = trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );

        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';

        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();

        // Resource khusus: register dan login
        if (in_array($resource, ['register', 'login'], true)) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Response::json(405, false, 'Method tidak diizinkan');
            }

            if ($resource === 'register') {
                $controller->register();
            } else {
                $controller->login();
            }

            return;
        }

        // Routing CRUD
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $controller->index();
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                $controller->update($_GET['id'] ?? null);
                break;

            case 'DELETE':
                $controller->destroy($_GET['id'] ?? null);
                break;

            default:
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}
