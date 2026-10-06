<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use InvalidArgumentException;
use PDOException;

class MahasiswaController extends Controller
{
    /**
     * Repository yang digunakan Controller.
     */
    private MahasiswaRepository $repo;


    /**
     * Dependency Injection.
     */
    public function __construct(
        MahasiswaRepository $repo
    ) {
        $this->repo = $repo;
    }


    /**
     * Menampilkan daftar mahasiswa.
     */
    public function index(): void
    {
        $keyword = trim(
            $_GET['q'] ?? ''
        );


        $page = max(
            1,
            (int) (
                $_GET['page'] ?? 1
            )
        );


        $perPage = 5;


        $total = $this->repo->count(
            $keyword !== ''
                ? $keyword
                : null
        );


        $totalPages = max(
            1,
            (int) ceil(
                $total / $perPage
            )
        );


        if ($page > $totalPages) {
            $page = $totalPages;
        }


        $offset =
            ($page - 1) * $perPage;


        $mahasiswa =
            $this->repo->all(
                $keyword !== ''
                    ? $keyword
                    : null,
                $perPage,
                $offset
            );


        $flash =
            $this->getFlash();


        $this->view(
            'mahasiswa/index',
            [
                'mahasiswa' =>
                    $mahasiswa,

                'keyword' =>
                    $keyword,

                'page' =>
                    $page,

                'perPage' =>
                    $perPage,

                'total' =>
                    $total,

                'totalPages' =>
                    $totalPages,

                'flash' =>
                    $flash,
            ]
        );
    }


    /**
     * Form tambah mahasiswa.
     */
    public function create(): void
    {
        $prodi =
            $this->repo->getProdi();


        $flash =
            $this->getFlash();


        $this->view(
            'mahasiswa/create',
            [
                'prodi' =>
                    $prodi,

                'flash' =>
                    $flash,
            ]
        );
    }


    /**
     * Menyimpan mahasiswa baru.
     */
    public function store(): void
    {
        $mahasiswa =
            $this->validateInput();


        if ($mahasiswa === null) {

            $this->setFlash(
                'Data tidak valid. Pastikan nama tidak kosong, NIM berupa angka, dan 2 angka pertama NIM sesuai tahun angkatan.',
                'danger'
            );


            $this->redirect(
                '/mahasiswa/create'
            );

            return;
        }


        try {

            $this->repo->create(
                $mahasiswa
            );


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


        $this->redirect(
            '/mahasiswa'
        );
    }


    /**
     * Menampilkan detail mahasiswa.
     */
    public function show(
        $id
    ): void {

        $id = (int) $id;


        $mhs =
            $this->repo->find($id);


        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }


        $flash =
            $this->getFlash();


        $this->view(
            'mahasiswa/show',
            [
                'mhs' =>
                    $mhs,

                'flash' =>
                    $flash,
            ]
        );
    }


    /**
     * Form edit mahasiswa.
     */
    public function edit(
        $id
    ): void {

        $id = (int) $id;


        $mhs =
            $this->repo->find($id);


        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }


        $prodi =
            $this->repo->getProdi();


        $flash =
            $this->getFlash();


        $this->view(
            'mahasiswa/edit',
            [
                'mhs' =>
                    $mhs,

                'prodi' =>
                    $prodi,

                'flash' =>
                    $flash,
            ]
        );
    }


    /**
     * Mengubah mahasiswa.
     */
    public function update(
        $id
    ): void {

        $id = (int) $id;


        $mhs =
            $this->repo->find($id);


        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }


        $mahasiswa =
            $this->validateInput();


        if ($mahasiswa === null) {

            $this->setFlash(
                'Data tidak valid. Pastikan nama tidak kosong, NIM berupa angka, dan 2 angka pertama NIM sesuai tahun angkatan.',
                'danger'
            );


            $this->redirect(
                '/mahasiswa/' .
                $id .
                '/edit'
            );

            return;
        }


        try {

            $this->repo->update(
                $id,
                $mahasiswa
            );


            $this->setFlash(
                'Data mahasiswa berhasil diubah.',
                'success'
            );


            $this->redirect(
                '/mahasiswa'
            );

        } catch (PDOException $e) {

            $this->setFlash(
                'Gagal mengubah data. NIM mungkin sudah digunakan atau Prodi tidak valid.',
                'danger'
            );


            $this->redirect(
                '/mahasiswa/' .
                $id .
                '/edit'
            );
        }
    }


    /**
     * Menghapus mahasiswa.
     */
    public function destroy(
        $id
    ): void {

        $id = (int) $id;


        $mhs =
            $this->repo->find($id);


        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }


        try {

            $this->repo->delete($id);


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


        $this->redirect(
            '/mahasiswa'
        );
    }


    /**
     * Validasi input mahasiswa.
     */
    private function validateInput():
        ?Mahasiswa
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


        $status =
            $_POST['status'] ?? 'aktif';


        try {

            $mahasiswa =
                new Mahasiswa();


            $mahasiswa->setNim(
                $nim
            );


            $mahasiswa->setNama(
                $nama
            );


            $mahasiswa->setEmail(
                $email
            );


            $mahasiswa->setProdiId(
                $prodiId
            );


            $mahasiswa->setAngkatan(
                $angkatan
            );


            $mahasiswa->setStatus(
                $status
            );


            /*
             * Validasi dua digit awal NIM
             * berdasarkan dua digit terakhir
             * tahun angkatan.
             *
             * Contoh:
             * Angkatan 2026
             * NIM harus diawali 26.
             */
            $prefixAngkatan =
                substr(
                    (string)
                    $mahasiswa->getAngkatan(),
                    -2
                );


            if (
                substr(
                    $mahasiswa->getNim(),
                    0,
                    2
                ) !== $prefixAngkatan
            ) {
                return null;
            }


            return $mahasiswa;

        } catch (
            InvalidArgumentException $e
        ) {

            return null;
        }
    }
}