<?php

namespace App\Core\Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            empty($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {
            $basePath = rtrim(
                str_replace(
                    '\\',
                    '/',
                    dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')
                ),
                '/'
            );

            header('Location: ' . $basePath . '/login');

            exit;
        }
    }
}