<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        if (!empty($_SESSION['logged_in'])) {
            $this->redirectTo('/dashboard');
        }

        $this->redirectTo('/login');
    }

    public function dashboard(): void
    {
        $user = [
            'id' => $_SESSION['user_id'] ?? null,
            'nama' => $_SESSION['user_name'] ?? 'User',
        ];

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/dashboard/index.php';
    }
}