<?php

namespace App\Core;

class Router
{
    private array $routes;
    private array $middlewareMap;

    public function __construct(
        array $routes,
        array $middlewareMap = []
    ) {
        $this->routes = $routes;
        $this->middlewareMap = $middlewareMap;
    }

    public function dispatch(
        string $method,
        string $uri
    ): void {
        $methodRoutes = $this->routes[$method] ?? [];

        foreach ($methodRoutes as $route => $handler) {

            $params = $this->matchRoute(
                $route,
                $uri
            );

            if ($params === null) {
                continue;
            }

            $controllerName = $handler[0];
            $action = $handler[1];
            $middlewares = $handler[2] ?? [];

            foreach ($middlewares as $middlewareName) {

                $middlewareClass =
                    $this->middlewareMap[$middlewareName]
                    ?? null;

                if ($middlewareClass !== null) {
                    (new $middlewareClass())->handle();
                }
            }

            $controllerClass =
                "App\\Controllers\\{$controllerName}";

            $controller =
                new $controllerClass();

            $controller->$action(
                ...array_values($params)
            );

            return;
        }

        http_response_code(404);

        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';

        echo '<p>URL yang diminta: ' .
            htmlspecialchars(
                $uri,
                ENT_QUOTES,
                'UTF-8'
            ) .
            '</p>';
    }

    private function matchRoute(
        string $route,
        string $uri
    ): ?array {
        $pattern = preg_quote($route, '#');

        $pattern = str_replace(
            '\{id\}',
            '(?P<id>[0-9]+)',
            $pattern
        );

        if (
            !preg_match(
                '#^' . $pattern . '$#',
                $uri,
                $matches
            )
        ) {
            return null;
        }

        $params = [];

        if (isset($matches['id'])) {
            $params['id'] = $matches['id'];
        }

        return $params;
    }
}