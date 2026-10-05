<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use InvalidArgumentException;
use PDOException;

class MahasiswaController extends Controller
{
    /*
     * Controller MEMILIKI Repository.
     *
     * Object Composition.
     */
    private MahasiswaRepository $repo;


    /*
     * =========================================================
     * CONSTRUCTOR DEPENDENCY INJECTION
     * =========================================================
     *
     * Repository diberikan dari luar.
     */
    public function __construct(
        MahasiswaRepository $repo
    ) {
        $this->repo = $repo;
    }


    /*
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index(): void
    {
        $keyword = trim(
            $_GET['q'] ?? ''
        );

        $page = max(
            1,
            (int) ($_GET['page'] ?? 1)
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

        $offset = (
            $page - 1
        ) * $perPage;

        $mahasiswa = $this->repo->all(
            $keyword !== ''
                ? $keyword
                : null,
            $perPage,
            $offset
        );

        $flash = $this->getFlash();

        include __DIR__ .
            '/../Views/mahasiswa/index.php';
    }


    /*
     * =========================================================
     * FORM TAMBAH
     * =========================================================
     */
    public function create(): void
    {
        $prodi = $this->repo->getProdi();

        $flash = $this->getFlash();

        include __DIR__ .
            '/../Views/mahasiswa/create.php';
    }


    /*
     * =========================================================
     * SIMPAN
     * =========================================================
     */
    public function store(): void
    {
        $mahasiswa = $this->validateInput();

        if ($mahasiswa === null) {
            $this->setFlash(
                'Data tidak valid. Pastikan nama tidak kosong, NIM berupa angka, dan 2 angka pertama NIM sesuai tahun angkatan.',
                'danger'
            );

            $this->redirectTo(
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

        $this->redirectTo(
            '/mahasiswa'
        );
    }


    /*
     * =========================================================
     * DETAIL
     * =========================================================
     */
    public function show($id): void
    {
        $id = (int) $id;

        $mhs = $this->repo->find($id);

        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }

        $flash = $this->getFlash();

        include __DIR__ .
            '/../Views/mahasiswa/show.php';
    }


    /*
     * =========================================================
     * FORM EDIT
     * =========================================================
     */
    public function edit($id): void
    {
        $id = (int) $id;

        $mhs = $this->repo->find($id);

        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }

        $prodi = $this->repo->getProdi();

        $flash = $this->getFlash();

        include __DIR__ .
            '/../Views/mahasiswa/edit.php';
    }


    /*
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update($id): void
    {
        $id = (int) $id;

        $mhs = $this->repo->find($id);

        if ($mhs === null) {

            http_response_code(404);

            echo '<h1>
                404 - Mahasiswa Tidak Ditemukan
            </h1>';

            return;
        }

        $mahasiswa = $this->validateInput();

        if ($mahasiswa === null) {

            $this->setFlash(
                'Data tidak valid. Pastikan nama tidak kosong, NIM berupa angka, dan 2 angka pertama NIM sesuai tahun angkatan.',
                'danger'
            );

            $this->redirectTo(
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

            $this->redirectTo(
                '/mahasiswa'
            );

        } catch (PDOException $e) {

            $this->setFlash(
                'Gagal mengubah data. NIM mungkin sudah digunakan atau Prodi tidak valid.',
                'danger'
            );

            $this->redirectTo(
                '/mahasiswa/' .
                $id .
                '/edit'
            );
        }
    }


    /*
     * =========================================================
     * DELETE
     * =========================================================
     */
    public function destroy($id): void
    {
        $id = (int) $id;

        $mhs = $this->repo->find($id);

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

        $this->redirectTo(
            '/mahasiswa'
        );
    }


    /*
     * =========================================================
     * VALIDASI INPUT
     * =========================================================
     */
    private function validateInput(): ?Mahasiswa
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

        $status = $_POST['status']
            ?? 'aktif';


        try {

            /*
             * Membuat object Mahasiswa.
             */
            $mahasiswa = new Mahasiswa();


            /*
             * Semua data masuk melalui SETTER.
             */
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
             * =================================================
             * VALIDASI NIM SESUAI ANGKATAN
             * =================================================
             *
             * Contoh:
             *
             * Angkatan 2026
             * NIM harus diawali 26
             */

            $prefixAngkatan = substr(
                (string) $mahasiswa->getAngkatan(),
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