<?php

namespace App\Core;

abstract class BaseController
{
    /**
     * Menentukan base path aplikasi.
     */
    protected function basePath(): string
    {
        return rtrim(
            str_replace(
                '\\',
                '/',
                dirname(
                    $_SERVER['SCRIPT_NAME'] ?? '/index.php'
                )
            ),
            '/'
        );
    }

    /**
     * Menampilkan view.
     */
    protected function view(
        string $view,
        array $data = []
    ): void {
        extract($data);

        require __DIR__ .
            '/../Views/' .
            $view .
            '.php';
    }

    /**
     * Redirect menggunakan nama method baru.
     */
    protected function redirect(
        string $url
    ): void {
        header(
            'Location: ' .
            $this->basePath() .
            $url
        );

        exit;
    }

    /**
     * Compatibility dengan Controller lama.
     *
     * Controller lama masih menggunakan:
     * $this->redirectTo('/login');
     *
     * Jadi method ini meneruskan ke redirect().
     */
    protected function redirectTo(
        string $url
    ): void {
        $this->redirect($url);
    }

    /**
     * Flash message.
     */
    protected function setFlash(
        string $message,
        string $type = 'success'
    ): void {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    /**
     * Mengambil flash message.
     */
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