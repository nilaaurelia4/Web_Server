<?php

class BukuController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
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

    private function validate(array $d): ?string
    {
        foreach (['isbn', 'judul', 'penulis', 'penerbit', 'tahun_terbit'] as $f) {
            if (empty($d[$f])) {
                return "Field $f wajib diisi";
            }
        }

        $tahun = (int) $d['tahun_terbit'];
        if ($tahun < 1901 || $tahun > (int) date('Y')) {
            return 'tahun_terbit tidak valid';
        }

        if (isset($d['stok']) && (!is_numeric($d['stok']) || (int) $d['stok'] < 0)) {
            return 'stok harus berupa angka 0 atau lebih';
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
        $d = $this->input();

        if ($e = $this->validate($d)) {
            $this->respond(400, ['success' => false, 'message' => $e]);
        }

        if ($this->model->isbnExists($d['isbn'])) {
            $this->respond(409, ['success' => false, 'message' => 'ISBN sudah terdaftar']);
        }

        $id = $this->model->create($d);

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

        $d = $this->input();

        if ($e = $this->validate($d)) {
            $this->respond(400, ['success' => false, 'message' => $e]);
        }

        if ($this->model->isbnExists($d['isbn'], $id)) {
            $this->respond(409, ['success' => false, 'message' => 'ISBN sudah dipakai buku lain']);
        }

        $this->model->update($id, $d);
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