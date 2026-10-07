<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use App\Services\MahasiswaService;
use PDOException;

class MahasiswaController extends Controller
{
    /**
     * Repository dan Service yang digunakan Controller.
     */
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;
    private MahasiswaService $service;


    /**
     * Dependency Injection.
     */
    public function __construct(
        MahasiswaRepository $repo,
        ProdiRepository $prodiRepo,
        MahasiswaService $service
    ) {
        $this->repo = $repo;

        $this->prodiRepo =
            $prodiRepo;

        $this->service =
            $service;
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
            $this->prodiRepo->all();

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
        $result =
            $this->service->create(
                $_POST
            );


        if ($result['success']) {

            $this->setFlash(
                $result['message'],
                'success'
            );


            $this->redirect(
                '/mahasiswa'
            );

            return;
        }


        $this->setFlash(
            $result['message'],
            'danger'
        );


    $this->redirect(
        '/mahasiswa/create'
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
            $this->prodiRepo->all();


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


        $result =
            $this->service->update(
                $id,
                $_POST
            );


        if ($result['success']) {

            $this->setFlash(
                $result['message'],
                'success'
            );


            $this->redirect(
                '/mahasiswa'
            );

            return;
        }


        $this->setFlash(
            $result['message'],
            'danger'
        );


        $this->redirect(
            '/mahasiswa/' .
            $id .
            '/edit'
        );
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
}