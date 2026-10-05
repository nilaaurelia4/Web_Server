<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController
{
    private function basePath(): string
    {
        $basePath = rtrim(
            str_replace(
                '\\',
                '/',
                dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')
            ),
            '/'
        );

        return $basePath;
    }

    private function redirectToList(): void
    {
        header(
            'Location: ' .
            $this->basePath() .
            '/mahasiswa'
        );

        exit;
    }

    private function getMahasiswa(): array
    {
        if (
            !isset($_SESSION['mahasiswa']) ||
            !is_array($_SESSION['mahasiswa'])
        ) {
            $_SESSION['mahasiswa'] =
                \Mahasiswa::defaultData();
        }

        foreach ($_SESSION['mahasiswa'] as $mhs) {

            if (!($mhs instanceof \Mahasiswa)) {

                $_SESSION['mahasiswa'] =
                    \Mahasiswa::defaultData();

                break;
            }
        }

        return $_SESSION['mahasiswa'];
    }

    private function findIndexById($id): ?int
    {
        $index = (int) $id - 1;

        $mahasiswa = $this->getMahasiswa();

        return isset($mahasiswa[$index])
            ? $index
            : null;
    }

    public function index()
    {
        $mahasiswa = $this->getMahasiswa();

        include __DIR__ .
            '/../Views/mahasiswa/index.php';
    }

    public function create()
    {
        include __DIR__ .
            '/../Views/mahasiswa/create.php';
    }

    public function store()
    {
        $mahasiswa = $this->getMahasiswa();

        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $prodi = trim($_POST['prodi'] ?? '');

        if (
            $nim === '' ||
            $nama === '' ||
            $prodi === ''
        ) {
            http_response_code(400);

            echo '<h1>400 - Data tidak lengkap</h1>';

            exit;
        }

        $mahasiswa[] =
            new \Mahasiswa($nim, $nama, $prodi);

        $_SESSION['mahasiswa'] = $mahasiswa;

        $this->redirectToList();
    }

    public function show($id)
    {
        echo '<h1>Detail Mahasiswa</h1>';

        echo '<p>ID Mahasiswa: ' .
            htmlspecialchars(
                $id,
                ENT_QUOTES,
                'UTF-8'
            ) .
            '</p>';
    }

    public function edit($id)
    {
        $index = $this->findIndexById($id);

        if ($index === null) {

            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            exit;
        }

        $mahasiswa = $this->getMahasiswa();

        $mhs = $mahasiswa[$index];

        include __DIR__ .
            '/../Views/mahasiswa/edit.php';
    }

    public function update($id)
    {
        $index = $this->findIndexById($id);

        if ($index === null) {

            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            exit;
        }

        $mahasiswa = $this->getMahasiswa();

        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $prodi = trim($_POST['prodi'] ?? '');

        if (
            $nim === '' ||
            $nama === '' ||
            $prodi === ''
        ) {
            http_response_code(400);

            echo '<h1>400 - Data tidak lengkap</h1>';

            exit;
        }

        $mahasiswa[$index]->setNim($nim);
        $mahasiswa[$index]->setNama($nama);
        $mahasiswa[$index]->setProdi($prodi);

        $_SESSION['mahasiswa'] = $mahasiswa;

        $this->redirectToList();
    }

    public function destroy($id)
    {
        $index = $this->findIndexById($id);

        if ($index === null) {

            http_response_code(404);

            echo '<h1>404 - Mahasiswa Tidak Ditemukan</h1>';

            exit;
        }

        $mahasiswa = $this->getMahasiswa();

        array_splice($mahasiswa, $index, 1);

        $_SESSION['mahasiswa'] = $mahasiswa;

        $this->redirectToList();
    }
}