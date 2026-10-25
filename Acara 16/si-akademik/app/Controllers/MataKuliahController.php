<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MataKuliah;
use PDOException;

class MataKuliahController extends Controller
{
    private MataKuliah $model;

    public function __construct()
    {
        $this->model = new MataKuliah();
    }

    /**
     * Daftar mata kuliah + pagination.
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

        $matakuliah = $this->model->all(
            $perPage,
            $offset
        );

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/matakuliah/index.php';
    }

    /**
     * Form tambah.
     */
    public function create(): void
    {
        $prodi = $this->model->getProdi();

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/matakuliah/create.php';
    }

    /**
     * Simpan mata kuliah.
     */
    public function store(): void
    {
        $data = $this->validateInput();

        if ($data === null) {
            $this->setFlash(
                'Data mata kuliah tidak valid.',
                'danger'
            );

            $this->redirectTo(
                '/matakuliah/create'
            );

            return;
        }

        try {
            $this->model->create($data);

            $this->setFlash(
                'Data mata kuliah berhasil ditambahkan.',
                'success'
            );

            $this->redirectTo(
                '/matakuliah'
            );

        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal menambahkan data. Kode mata kuliah mungkin sudah digunakan.',
                'danger'
            );

            $this->redirectTo(
                '/matakuliah/create'
            );
        }
    }

    /**
     * Form edit.
     */
    public function edit($id): void
    {
        $id = (int) $id;

        $matakuliah = $this->model->find($id);

        if ($matakuliah === null) {
            http_response_code(404);

            echo '<h1>404 - Mata Kuliah Tidak Ditemukan</h1>';

            return;
        }

        $prodi = $this->model->getProdi();

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/matakuliah/edit.php';
    }

    /**
     * Update mata kuliah.
     */
    public function update($id): void
    {
        $id = (int) $id;

        $matakuliah = $this->model->find($id);

        if ($matakuliah === null) {
            http_response_code(404);

            echo '<h1>404 - Mata Kuliah Tidak Ditemukan</h1>';

            return;
        }

        $data = $this->validateInput();

        if ($data === null) {
            $this->setFlash(
                'Data mata kuliah tidak valid.',
                'danger'
            );

            $this->redirectTo(
                '/matakuliah/' . $id . '/edit'
            );

            return;
        }

        try {
            $this->model->update(
                $id,
                $data
            );

            $this->setFlash(
                'Data mata kuliah berhasil diubah.',
                'success'
            );

            $this->redirectTo(
                '/matakuliah'
            );

        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal mengubah data. Kode mata kuliah mungkin sudah digunakan.',
                'danger'
            );

            $this->redirectTo(
                '/matakuliah/' . $id . '/edit'
            );
        }
    }

    /**
     * Hapus mata kuliah.
     */
    public function destroy($id): void
    {
        $id = (int) $id;

        $matakuliah = $this->model->find($id);

        if ($matakuliah === null) {
            http_response_code(404);

            echo '<h1>404 - Mata Kuliah Tidak Ditemukan</h1>';

            return;
        }

        try {
            $this->model->delete($id);

            $this->setFlash(
                'Data mata kuliah berhasil dihapus.',
                'success'
            );

        } catch (PDOException $e) {
            $this->setFlash(
                'Data mata kuliah gagal dihapus.',
                'danger'
            );
        }

        $this->redirectTo(
            '/matakuliah'
        );
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

        $sks = (int) (
            $_POST['sks'] ?? 0
        );

        $prodiId = (int) (
            $_POST['prodi_id'] ?? 0
        );

        if ($kode === '') {
            return null;
        }

        if ($nama === '') {
            return null;
        }

        if (
            $sks < 1 ||
            $sks > 6
        ) {
            return null;
        }

        if ($prodiId < 1) {
            return null;
        }

        return [
            'kode' => $kode,
            'nama' => $nama,
            'sks' => $sks,
            'prodi_id' => $prodiId,
        ];
    }
}