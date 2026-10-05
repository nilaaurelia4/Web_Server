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
     * Menampilkan daftar mahasiswa.
     */
    public function index(): void
    {
        $mahasiswa = $this->model->all();

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/mahasiswa/index.php';
    }


    /**
     * Menampilkan form tambah mahasiswa.
     */
    public function create(): void
    {
        $prodi = $this->model->getProdi();

        $flash = $this->getFlash();

        include __DIR__ . '/../Views/mahasiswa/create.php';
    }


    /**
     * Menyimpan mahasiswa baru.
     */
    public function store(): void
    {
        $data = $this->validateInput();

        if ($data === null) {

    $this->setFlash(
            'Data tidak valid. Silakan periksa kembali.',
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
     * Menampilkan detail mahasiswa.
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
     * Menampilkan form edit.
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
     * Memproses perubahan mahasiswa.
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
            'Data tidak valid. Silakan periksa kembali.',
            'danger'
        );

        $this->redirectTo(
            '/mahasiswa/' . $id . '/edit'
        );

        return;
    }

        try {
            $this->model->update($id, $data);

            $this->setFlash(
                'Data mahasiswa berhasil diubah.',
                'success'
            );
        } catch (PDOException $e) {
            $this->setFlash(
                'Gagal mengubah data. NIM mungkin sudah digunakan atau Prodi tidak valid.',
                'danger'
            );

            $this->redirectTo(
                '/mahasiswa/' . $id . '/edit'
            );

            return;
        }

        $this->redirectTo('/mahasiswa');

        try {

            if ($data === null) {

            $this->setFlash(
                'Data tidak valid. Silakan periksa kembali.',
                'danger'
            );

            $this->redirectTo(
                '/mahasiswa/' . $id . '/edit'
            );

            return;
        }

        } catch (PDOException $e) {

            $this->setFlash(
                'Gagal mengubah data. NIM mungkin sudah digunakan atau Prodi tidak valid.',
                'danger'
            );
        }

        $this->redirectTo('/mahasiswa');
    }


    /**
     * Menghapus mahasiswa.
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
     * Validasi input form.
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


        /*
         * Validasi NIM
         */
        if ($nim === '') {
            return null;
        }


        /*
         * Validasi nama
         */
        if ($nama === '') {
            return null;
        }


        /*
         * Validasi email
         */
        if (!filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )) {
            return null;
        }


        /*
         * Validasi Prodi
         */
        if ($prodiId < 1) {
            return null;
        }


        /*
         * Validasi angkatan
         */
        if ($angkatan < 2000 || $angkatan > 2100) {
            return null;
        }

        $prefixAngkatan = substr((string) $angkatan, -2);

        if (substr($nim, 0, 2) !== $prefixAngkatan) {
            return null;
        }


        /*
         * Validasi status.
         *
         * Hanya boleh:
         * aktif
         * cuti
         * lulus
         */
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