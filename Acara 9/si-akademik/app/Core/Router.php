<?php

namespace App\Core;

class Router
{
    private array $routes;

    private array $middlewareMap;

    private array $controllerFactories;


    public function __construct(
        array $routes,
        array $middlewareMap = [],
        array $controllerFactories = []
    ) {
        $this->routes = $routes;

        $this->middlewareMap =
            $middlewareMap;

        $this->controllerFactories =
            $controllerFactories;
    }


    public function dispatch(
        string $method,
        string $uri
    ): void {

        $methodRoutes =
            $this->routes[$method] ?? [];


        foreach (
            $methodRoutes
            as $route => $handler
        ) {

            $params = $this->matchRoute(
                $route,
                $uri
            );


            if ($params === null) {
                continue;
            }


            $controllerName =
                $handler[0];

            $action =
                $handler[1];

            $middlewares =
                $handler[2] ?? [];


            /*
             * =================================================
             * MIDDLEWARE
             * =================================================
             */
            foreach (
                $middlewares
                as $middlewareName
            ) {

                $middlewareClass =
                    $this->middlewareMap[
                        $middlewareName
                    ] ?? null;


                if ($middlewareClass !== null) {

                    (new $middlewareClass())
                        ->handle();
                }
            }


            /*
             * =================================================
             * DEPENDENCY INJECTION
             * =================================================
             *
             * Kalau Controller memiliki factory,
             * gunakan factory tersebut.
             */
            if (
                isset(
                    $this->controllerFactories[
                        $controllerName
                    ]
                )
            ) {

                $controller =
                    (
                        $this->controllerFactories[
                            $controllerName
                        ]
                    )();

            } else {

                $controllerClass =
                    "App\\Controllers\\"
                    . $controllerName;

                $controller =
                    new $controllerClass();
            }


            /*
             * Jalankan method Controller.
             */
            $controller->$action(
                ...array_values($params)
            );

            return;
        }


        /*
         * =====================================================
         * 404
         * =====================================================
         */

        http_response_code(404);

        echo '<h1>
            404 - Halaman Tidak Ditemukan
        </h1>';

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

        $pattern =
            preg_quote(
                $route,
                '#'
            );


        /*
         * {id} menjadi angka.
         */
        $pattern = str_replace(
            '\{id\}',
            '(?P<id>[0-9]+)',
            $pattern
        );


        if (
            !preg_match(
                '#^' .
                $pattern .
                '$#',
                $uri,
                $matches
            )
        ) {
            return null;
        }


        $params = [];


        if (
            isset($matches['id'])
        ) {
            $params['id'] =
                $matches['id'];
        }


        return $params;
    }
}