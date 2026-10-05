<?php

namespace App\Core;

abstract class Controller
{
    protected function basePath(): string
    {
        return rtrim(
            str_replace(
                '\\',
                '/',
                dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')
            ),
            '/'
        );
    }

    protected function redirectTo(string $path): void
    {
        header('Location: ' . $this->basePath() . $path);
        exit;
    }

    protected function setFlash(
        string $message,
        string $type = 'success'
    ): void {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    protected function getFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];

        unset($_SESSION['flash']);

        return $flash;
    }
}