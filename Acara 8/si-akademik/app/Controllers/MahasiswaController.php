<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use PDOException;

class MahasiswaController extends Controller
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    /**
     * Daftar mahasiswa + search + pagination
     */
    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');

        $page = max(
            1,
            (int) ($_GET['page'] ?? 1)
        );

        $perPage = 5;

        $total = $this->model->count(
            $keyword !== '' ? $keyword : null
        );

        $totalPages = max(
            1,
            (int) ceil($total / $perPage)
        );

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $mahasiswa = $this->model->all(
            $keyword !== '' ? $keyword : null,
            $perPage,
            $offset
        );

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/mahasiswa/index.php';
    }

    /**
     * Form tambah
     */
    public function create(): void
    {
        $prodi = $this->model->getProdi();

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/mahasiswa/create.php';
    }

    /**
     * Simpan mahasiswa
     */
    public function store(): void
    {
        $data = $this->validateInput();

        if ($data === null) {
            $this->setFlash(
                'Data tidak valid. Pastikan 2 angka pertama NIM sesuai tahun angkatan.',
                'danger'
            );

            $this->redirectTo('/mahasiswa/create');
            return;
        }

        try {
            $this->model->create($data);

            $this->setFlash(
                'Data mahasiswa berhasil ditambahkan.',
                'success'
            );
        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal menambahkan data. NIM mungkin sudah digunakan atau Prodi tidak valid.',
                'danger'
            );
        }

        $this->redirectTo('/mahasiswa');
    }

    /**
     * Detail mahasiswa
     */
    public function show($id): void
    {
        $id = (int) $id;

        $mhs = $this->model->find($id);

        if ($mhs === null) {
            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            return;
        }

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/mahasiswa/show.php';
    }

    /**
     * Form edit
     */
    public function edit($id): void
    {
        $id = (int) $id;

        $mhs = $this->model->find($id);

        if ($mhs === null) {
            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            return;
        }

        $prodi = $this->model->getProdi();

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    /**
     * Proses update mahasiswa
     */
    public function update($id): void
    {
        $id = (int) $id;

        $mhs = $this->model->find($id);

        if ($mhs === null) {
            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            return;
        }

        $data = $this->validateInput();

        if ($data === null) {
            $this->setFlash(
                'Data tidak valid. Pastikan 2 angka pertama NIM sesuai tahun angkatan.',
                'danger'
            );

            $this->redirectTo(
                '/mahasiswa/' . $id . '/edit'
            );

            return;
        }

        try {
            $this->model->update(
                $id,
                $data
            );

            $this->setFlash(
                'Data mahasiswa berhasil diubah.',
                'success'
            );

            $this->redirectTo('/mahasiswa');

        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal mengubah data. NIM mungkin sudah digunakan atau Prodi tidak valid.',
                'danger'
            );

            $this->redirectTo(
                '/mahasiswa/' . $id . '/edit'
            );
        }
    }

    /**
     * Hapus mahasiswa
     */
    public function destroy($id): void
    {
        $id = (int) $id;

        $mhs = $this->model->find($id);

        if ($mhs === null) {
            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            return;
        }

        try {
            $this->model->delete($id);

            $this->setFlash(
                'Data mahasiswa berhasil dihapus.',
                'success'
            );
        } catch (PDOException $e) {
            $this->setFlash(
                'Data mahasiswa gagal dihapus.',
                'danger'
            );
        }

        $this->redirectTo('/mahasiswa');
    }

    /**
     * Validasi input
     */
    private function validateInput(): ?array
    {
        $nim = trim(
            $_POST['nim'] ?? ''
        );

        $nama = trim(
            $_POST['nama'] ?? ''
        );

        $email = trim(
            $_POST['email'] ?? ''
        );

        $prodiId = (int) (
            $_POST['prodi_id'] ?? 0
        );

        $angkatan = (int) (
            $_POST['angkatan'] ?? 0
        );

        $status = $_POST['status'] ?? 'aktif';

        if ($nim === '') {
            return null;
        }

        if ($nama === '') {
            return null;
        }

        if (!filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )) {
            return null;
        }

        if ($prodiId < 1) {
            return null;
        }

        if (
            $angkatan < 2000 ||
            $angkatan > 2100
        ) {
            return null;
        }

        /*
         * Dua angka pertama NIM harus
         * sama dengan dua angka terakhir
         * tahun angkatan.
         *
         * Contoh:
         * Angkatan 2026 -> NIM harus diawali 26
         */
        $prefixAngkatan = substr(
            (string) $angkatan,
            -2
        );

        if (
            substr(
                $nim,
                0,
                2
            ) !== $prefixAngkatan
        ) {
            return null;
        }

        if (!in_array(
            $status,
            [
                'aktif',
                'cuti',
                'lulus'
            ],
            true
        )) {
            return null;
        }

        return [
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
            'status' => $status,
        ];
    }
}