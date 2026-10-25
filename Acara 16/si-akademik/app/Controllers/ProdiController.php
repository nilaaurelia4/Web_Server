<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Prodi;
use PDOException;

class ProdiController extends Controller
{
    private Prodi $model;

    public function __construct()
    {
        $this->model = new Prodi();
    }

    /**
     * Daftar prodi + pagination.
     */
    public function index(): void
    {
        $page = max(
            1,
            (int) ($_GET['page'] ?? 1)
        );

        $perPage = 5;

        $total = $this->model->count();

        $totalPages = max(
            1,
            (int) ceil(
                $total / $perPage
            )
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $prodi = $this->model->all(
            $perPage,
            $offset
        );

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/prodi/index.php';
    }

    /**
     * Form tambah.
     */
    public function create(): void
    {
        $flash = $this->getFlash();

        include __DIR__ . '/../Views/prodi/create.php';
    }

    /**
     * Simpan prodi.
     */
    public function store(): void
    {
        $data = $this->validateInput();

        if ($data === null) {
            $this->setFlash(
                'Kode dan nama prodi wajib diisi.',
                'danger'
            );

            $this->redirectTo('/prodi/create');
            return;
        }

        try {
            $this->model->create($data);

            $this->setFlash(
                'Data prodi berhasil ditambahkan.',
                'success'
            );

            $this->redirectTo('/prodi');

        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal menambahkan prodi. Kode prodi mungkin sudah digunakan.',
                'danger'
            );

            $this->redirectTo('/prodi/create');
        }
    }

    /**
     * Form edit.
     */
    public function edit($id): void
    {
        $id = (int) $id;

        $prodi = $this->model->find($id);

        if ($prodi === null) {
            http_response_code(404);

            echo '<h1>404 - Prodi Tidak Ditemukan</h1>';

            return;
        }

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/prodi/edit.php';
    }

    /**
     * Update prodi.
     */
    public function update($id): void
    {
        $id = (int) $id;

        $prodi = $this->model->find($id);

        if ($prodi === null) {
            http_response_code(404);

            echo '<h1>404 - Prodi Tidak Ditemukan</h1>';

            return;
        }

        $data = $this->validateInput();

        if ($data === null) {
            $this->setFlash(
                'Kode dan nama prodi wajib diisi.',
                'danger'
            );

            $this->redirectTo(
                '/prodi/' . $id . '/edit'
            );

            return;
        }

        try {
            $this->model->update(
                $id,
                $data
            );

            $this->setFlash(
                'Data prodi berhasil diubah.',
                'success'
            );

            $this->redirectTo('/prodi');

        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal mengubah prodi. Kode prodi mungkin sudah digunakan.',
                'danger'
            );

            $this->redirectTo(
                '/prodi/' . $id . '/edit'
            );
        }
    }

    /**
     * Hapus prodi.
     */
    public function destroy($id): void
    {
        $id = (int) $id;

        $prodi = $this->model->find($id);

        if ($prodi === null) {
            http_response_code(404);

            echo '<h1>404 - Prodi Tidak Ditemukan</h1>';

            return;
        }

        try {
            $this->model->delete($id);

            $this->setFlash(
                'Data prodi berhasil dihapus.',
                'success'
            );

        } catch (PDOException $e) {
            $this->setFlash(
                'Prodi tidak dapat dihapus karena masih digunakan oleh data lain.',
                'danger'
            );
        }

        $this->redirectTo('/prodi');
    }

    /**
     * Validasi input.
     */
    private function validateInput(): ?array
    {
        $kode = trim(
            $_POST['kode'] ?? ''
        );

        $nama = trim(
            $_POST['nama'] ?? ''
        );

        if ($kode === '' || $nama === '') {
            return null;
        }

        return [
            'kode' => $kode,
            'nama' => $nama,
        ];
    }
}