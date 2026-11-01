<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    private function input(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    // 400: field wajib belum lengkap
    private function missingFields(array $d): array
    {
        $errors = [];
        foreach (['nim', 'nama', 'email', 'prodi_id', 'angkatan'] as $field) {
            if (empty($d[$field])) {
                $errors[$field] = "Field $field wajib diisi";
            }
        }
        return $errors;
    }

    // 422: data lengkap tetapi gagal validasi
    private function invalidFields(array $d): array
    {
        $errors = [];

        if (!preg_match('/^\d{6}$/', (string) $d['nim'])) {
            $errors['nim'] = 'Format NIM tidak valid, harus 6 digit angka';
        }

        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }

        if (isset($d['status']) && !in_array($d['status'], ['aktif', 'cuti', 'lulus'], true)) {
            $errors['status'] = 'Status harus aktif, cuti, atau lulus';
        }

        $tahun = (int) $d['angkatan'];
        if ($tahun < 1901 || $tahun > 2155) {
            $errors['angkatan'] = 'Angkatan harus berupa tahun antara 1901 dan 2155';
        }

        if (!$this->model->prodiExists((int) $d['prodi_id'])) {
            $errors['prodi_id'] = 'prodi_id tidak ditemukan';
        }

        return $errors;
    }

    // Menghentikan request dengan 400/422 jika data tidak valid
    private function validateOrFail(array $d): void
    {
        $errors = $this->missingFields($d);
        if ($errors) {
            Response::json(400, false, 'Field wajib belum lengkap', null, $errors);
        }

        $errors = $this->invalidFields($d);
        if ($errors) {
            Response::json(422, false, 'Validasi data gagal', null, $errors);
        }
    }

    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $data = $this->model->find((int) $id);

            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void
    {
        Auth::requireAuth();

        $data = $this->input();
        $this->validateOrFail($data);

        if ($this->model->nimExists((string) $data['nim'])) {
            Response::json(422, false, 'NIM sudah terdaftar', null, ['nim' => 'NIM sudah terdaftar']);
        }

        $id = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => (int) $id]);
    }

    public function update($id): void
    {
        Auth::requireAuth();

        if (!$id || !ctype_digit((string) $id)) {
            Response::json(400, false, 'ID wajib diisi');
        }
        $id = (int) $id;

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->input();
        $this->validateOrFail($data);

        if ($this->model->nimExists((string) $data['nim'], $id)) {
            Response::json(422, false, 'NIM sudah dipakai mahasiswa lain', null, ['nim' => 'NIM sudah dipakai mahasiswa lain']);
        }

        $this->model->update($id, $data);
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void
    {
        Auth::requireAuth();
        if (!$id || !ctype_digit((string) $id)) {
            Response::json(400, false, 'ID wajib diisi');
        }
        $id = (int) $id;

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }
}