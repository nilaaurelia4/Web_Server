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

        // 422: format email tidak valid (Tugas Mandiri 1)
        if (!$this->model->isValidEmail((string) $data['email'])) {
            Response::json(422, false, 'Validasi data gagal', null, [
                'email' => 'Format email tidak valid',
            ]);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $id = $this->model->create($data);

        Response::json(201, true, 'Registrasi berhasil', ['id' => (int) $id]);
    }
}