<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Database;

/*
|--------------------------------------------------------------------------
| FUNGSI BANTU
|--------------------------------------------------------------------------
*/

/**
 * Mengirim response JSON lalu menghentikan program.
 */
function kirim(int $status, bool $success, string $message, $data = null): void
{
    http_response_code($status);

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ]);

    exit;
}

/**
 * Mengambil ID dari URL.
 * Mendukung:  /api/mahasiswa.php/1   atau   /api/mahasiswa.php?id=1
 * Return null jika tidak ada ID.
 */
function ambilId(): ?int
{
    $pathInfo = trim($_SERVER['PATH_INFO'] ?? '', '/');

    $id = $pathInfo !== '' ? $pathInfo : ($_GET['id'] ?? null);

    if ($id === null) {
        return null;
    }

    if (!ctype_digit((string) $id) || (int) $id <= 0) {
        kirim(400, false, 'ID tidak valid');
    }

    return (int) $id;
}

/**
 * Membaca body JSON dari Postman.
 */
function bacaJson(): array
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        kirim(400, false, 'JSON tidak valid atau data tidak lengkap');
    }

    return $data;
}

/**
 * Validasi data mahasiswa (dipakai POST dan PUT).
 * Return array data yang sudah bersih.
 */
function validasi(PDO $db, array $data, ?int $excludeId = null): array
{
    $nim      = trim((string) ($data['nim'] ?? ''));
    $nama     = trim((string) ($data['nama'] ?? ''));
    $email    = trim((string) ($data['email'] ?? ''));
    $prodiId  = (int) ($data['prodi_id'] ?? 0);
    $angkatan = (int) ($data['angkatan'] ?? 0);
    $status   = trim((string) ($data['status'] ?? 'aktif'));

    if ($nim === '' || $nama === '' || $email === '') {
        kirim(400, false, 'NIM, nama, dan email wajib diisi');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        kirim(400, false, 'Format email tidak valid');
    }

    if ($prodiId <= 0) {
        kirim(400, false, 'prodi_id wajib diisi');
    }

    if ($angkatan < 2000 || $angkatan > 2100) {
        kirim(400, false, 'Angkatan tidak valid');
    }

    if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
        kirim(400, false, 'Status harus aktif, cuti, atau lulus');
    }

    // Prodi harus ada di database
    $stmt = $db->prepare('SELECT id FROM prodi WHERE id = :id');
    $stmt->execute([':id' => $prodiId]);

    if (!$stmt->fetch()) {
        kirim(400, false, 'Prodi tidak ditemukan');
    }

    // NIM tidak boleh kembar (kecuali milik data itu sendiri saat PUT)
    $sql    = 'SELECT id FROM mahasiswa WHERE nim = :nim';
    $params = [':nim' => $nim];

    if ($excludeId !== null) {
        $sql .= ' AND id != :id';
        $params[':id'] = $excludeId;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    if ($stmt->fetch()) {
        kirim(409, false, 'NIM sudah terdaftar');
    }

    return [
        'nim'      => $nim,
        'nama'     => $nama,
        'email'    => $email,
        'prodi_id' => $prodiId,
        'angkatan' => $angkatan,
        'status'   => $status
    ];
}

/**
 * Mengambil 1 mahasiswa berdasarkan ID. Return false jika tidak ada.
 */
function cariMahasiswa(PDO $db, int $id)
{
    $stmt = $db->prepare(
        'SELECT id, nim, nama, email, prodi_id, angkatan, status
         FROM mahasiswa
         WHERE id = :id'
    );

    $stmt->execute([':id' => $id]);

    return $stmt->fetch();
}

/*
|--------------------------------------------------------------------------
| PROSES UTAMA
|--------------------------------------------------------------------------
*/

$method = $_SERVER['REQUEST_METHOD'];

try {

    $db = Database::getInstance()->getConnection();

    $id = ambilId();

    switch ($method) {

        /*
        |--------------------------------------------------------------
        | GET  -> semua data, atau satu data jika ada ID
        |--------------------------------------------------------------
        */
        case 'GET':

            if ($id !== null) {

                $data = cariMahasiswa($db, $id);

                if (!$data) {
                    kirim(404, false, 'Data mahasiswa tidak ditemukan');
                }

                kirim(200, true, 'Data berhasil diambil', $data);
            }

            $stmt = $db->query(
                'SELECT id, nim, nama, email, prodi_id, angkatan, status
                 FROM mahasiswa
                 ORDER BY id ASC'
            );

            kirim(200, true, 'Data berhasil diambil', $stmt->fetchAll());
            break;


        /*
        |--------------------------------------------------------------
        | POST -> tambah data
        |--------------------------------------------------------------
        */
        case 'POST':

            if ($id !== null) {
                kirim(405, false, 'POST tidak boleh memakai ID');
            }

            $m = validasi($db, bacaJson());

            $stmt = $db->prepare(
                'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
                 VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
            );

            $stmt->execute([
                ':nim'      => $m['nim'],
                ':nama'     => $m['nama'],
                ':email'    => $m['email'],
                ':prodi_id' => $m['prodi_id'],
                ':angkatan' => $m['angkatan'],
                ':status'   => $m['status']
            ]);

            kirim(201, true, 'Data mahasiswa berhasil ditambahkan', [
                'id'    => (int) $db->lastInsertId(),
                'nim'   => $m['nim'],
                'nama'  => $m['nama'],
                'email' => $m['email']
            ]);
            break;


        /*
        |--------------------------------------------------------------
        | PUT -> ubah data berdasarkan ID
        |--------------------------------------------------------------
        */
        case 'PUT':

            if ($id === null) {
                kirim(400, false, 'ID wajib diisi');
            }

            if (!cariMahasiswa($db, $id)) {
                kirim(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $m = validasi($db, bacaJson(), $id);

            $stmt = $db->prepare(
                'UPDATE mahasiswa
                 SET nim = :nim,
                     nama = :nama,
                     email = :email,
                     prodi_id = :prodi_id,
                     angkatan = :angkatan,
                     status = :status
                 WHERE id = :id'
            );

            $stmt->execute([
                ':nim'      => $m['nim'],
                ':nama'     => $m['nama'],
                ':email'    => $m['email'],
                ':prodi_id' => $m['prodi_id'],
                ':angkatan' => $m['angkatan'],
                ':status'   => $m['status'],
                ':id'       => $id
            ]);

            kirim(200, true, 'Data mahasiswa berhasil diubah', [
                'id'    => $id,
                'nim'   => $m['nim'],
                'nama'  => $m['nama'],
                'email' => $m['email']
            ]);
            break;


        /*
        |--------------------------------------------------------------
        | DELETE -> hapus data berdasarkan ID
        |--------------------------------------------------------------
        */
        case 'DELETE':

            if ($id === null) {
                kirim(400, false, 'ID wajib diisi');
            }

            if (!cariMahasiswa($db, $id)) {
                kirim(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $stmt = $db->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);

            kirim(200, true, 'Data mahasiswa berhasil dihapus');
            break;


        /*
        |--------------------------------------------------------------
        | METHOD LAIN
        |--------------------------------------------------------------
        */
        default:
            kirim(405, false, 'Method tidak didukung');
    }

} catch (PDOException $e) {

    // Data yang masih dipakai tabel lain (foreign key) tidak bisa dihapus
    if ($e->getCode() === '23000') {
        kirim(409, false, 'Data tidak dapat diproses karena masih berelasi dengan data lain');
    }

    kirim(500, false, 'Terjadi kesalahan pada server');
}