<?php

class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = is_array($data) ? $data : [];

        // 400: field wajib belum lengkap
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            Response::json(400, false, 'Username, email, dan password wajib diisi');
        }

        // 422: format data tidak valid
        $errors = [];

        $panjang = strlen((string) $data['username']);
        if ($panjang < 3 || $panjang > 50) {
            $errors['username'] = 'Username harus 3-50 karakter';
        }

        if (!$this->model->isValidEmail((string) $data['email'])) {
            $errors['email'] = 'Format email tidak valid';
        }

        if (strlen((string) $data['password']) < 6) {
            $errors['password'] = 'Password minimal 6 karakter';
        }

        // 422: username / email sudah dipakai (kolom UNIQUE pada tabel users)
        if (!isset($errors['username']) && $this->model->usernameExists((string) $data['username'])) {
            $errors['username'] = 'Username sudah dipakai';
        }

        if (!isset($errors['email']) && $this->model->emailExists((string) $data['email'])) {
            $errors['email'] = 'Email sudah terdaftar';
        }

        if ($errors) {
            Response::json(422, false, 'Validasi data gagal', null, $errors);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $id = $this->model->create($data);

        Response::json(201, true, 'Registrasi berhasil', ['id' => (int) $id]);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = is_array($data) ? $data : [];

        $user = $this->model->findByUsername(
            $data['username'] ?? ''
        );

        if (
            !$user ||
            !password_verify(
                $data['password'] ?? '',
                $user['password']
            )
        ) {
            Response::json(401, false, 'Username atau password salah');
        }

        $token = Auth::generateToken([
            'id' => $user['id'],
            'role' => $user['role']
        ]);

        Response::json(
            200,
            true,
            'Login berhasil',
            [
                'token' => $token,
                'role' => $user['role']
            ]
        );
    }

}