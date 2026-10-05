<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    private const USERNAME = 'admin';
    private const PASSWORD = '12345';

    public function loginForm(): void
    {
        if (!empty($_SESSION['logged_in'])) {
            $this->redirectTo('/dashboard');
        }

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (
            $username === self::USERNAME &&
            $password === self::PASSWORD
        ) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['logged_in'] = true;

            $this->setFlash(
                'Selamat datang, Admin',
                'success'
            );

            $this->redirectTo('/dashboard');
        }

        $this->setFlash(
            'Username atau password salah.',
            'danger'
        );

        $this->redirectTo('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);

        $this->setFlash(
            'Anda telah logout.',
            'success'
        );

        $this->redirectTo('/login');
    }
}