<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    private function respond(int $code, array $body): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }

    private function input(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    // Mengembalikan pesan error, atau null jika data valid
    private function validate(array $d): ?string
    {
        foreach (['nim', 'nama', 'email', 'prodi_id', 'angkatan'] as $field) {
            if (empty($d[$field])) {
                return "Field $field wajib diisi";
            }
        }

        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Format email tidak valid';
        }

        if (isset($d['status']) && !in_array($d['status'], ['aktif', 'cuti', 'lulus'], true)) {
            return 'Status harus aktif, cuti, atau lulus';
        }

        if (!$this->model->prodiExists((int) $d['prodi_id'])) {
            return 'prodi_id tidak ditemukan';
        }

        return null;
    }

    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $data = $this->model->find((int) $id);

            if (!$data) {
                $this->respond(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
            }

            $this->respond(200, ['success' => true, 'data' => $data]);
        }

        $this->respond(200, ['success' => true, 'data' => $this->model->getAll()]);
    }

    public function store(): void
    {
        $data  = $this->input();
        $error = $this->validate($data);

        if ($error) {
            $this->respond(400, ['success' => false, 'message' => $error]);
        }

        if ($this->model->nimExists($data['nim'])) {
            $this->respond(409, ['success' => false, 'message' => 'NIM sudah terdaftar']);
        }

        $id = $this->model->create($data);

        $this->respond(201, [
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data'    => ['id' => (int) $id],
        ]);
    }

    public function update($id): void
    {
        if (!$id || !ctype_digit((string) $id)) {
            $this->respond(400, ['success' => false, 'message' => 'ID wajib diisi']);
        }
        $id = (int) $id;

        if (!$this->model->find($id)) {
            $this->respond(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $data  = $this->input();
        $error = $this->validate($data);

        if ($error) {
            $this->respond(400, ['success' => false, 'message' => $error]);
        }

        if ($this->model->nimExists($data['nim'], $id)) {
            $this->respond(409, ['success' => false, 'message' => 'NIM sudah dipakai mahasiswa lain']);
        }

        $this->model->update($id, $data);
        $this->respond(200, ['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id): void
    {
        if (!$id || !ctype_digit((string) $id)) {
            $this->respond(400, ['success' => false, 'message' => 'ID wajib diisi']);
        }
        $id = (int) $id;

        if (!$this->model->find($id)) {
            $this->respond(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->model->delete($id);
        $this->respond(200, ['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}