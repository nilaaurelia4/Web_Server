<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Database;

$method = $_SERVER['REQUEST_METHOD'];

try {

    $db = Database::getInstance()->getConnection();

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET') {

        $id = $_GET['id'] ?? null;

        /*
         * GET berdasarkan ID
         */
        if ($id !== null) {

            if (!ctype_digit($id) || (int) $id <= 0) {

                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid',
                    'data' => null
                ]);

                exit;
            }

            $sql = "
                SELECT
                    id,
                    nim,
                    nama,
                    email
                FROM mahasiswa
                WHERE id = :id
            ";

            $stmt = $db->prepare($sql);

            $stmt->execute([
                ':id' => (int) $id
            ]);

            $data = $stmt->fetch();

            if (!$data) {

                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan',
                    'data' => null
                ]);

                exit;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => $data
            ]);

            exit;
        }

        /*
         * GET semua mahasiswa
         */

        $sql = "
            SELECT
                id,
                nim,
                nama,
                email
            FROM mahasiswa
            ORDER BY id ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST') {

        /*
         * Membaca JSON dari Postman.
         */
        $json = file_get_contents('php://input');

        /*
         * Mengubah JSON menjadi array PHP.
         */
        $data = json_decode($json, true);

        /*
         * Mengecek JSON.
         */
        if (json_last_error() !== JSON_ERROR_NONE) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'JSON tidak valid',
                'data' => null
            ]);

            exit;
        }

        /*
         * Mengambil data.
         */
        $nim = trim($data['nim'] ?? '');
        $nama = trim($data['nama'] ?? '');
        $email = trim($data['email'] ?? '');

        $prodiId = (int) ($data['prodi_id'] ?? 0);
        $angkatan = (int) ($data['angkatan'] ?? 0);

        /*
         * Validasi data.
         */
        if ($nim === '' || $nama === '' || $email === '') {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'NIM, nama, dan email wajib diisi',
                'data' => null
            ]);

            exit;
        }

        /*
         * Validasi email.
         */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Format email tidak valid',
                'data' => null
            ]);

            exit;
        }

        /*
         * Validasi prodi.
         */
        if ($prodiId <= 0) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'prodi_id wajib diisi',
                'data' => null
            ]);

            exit;
        }

        /*
         * Validasi angkatan.
         */
        if ($angkatan < 2000 || $angkatan > 2100) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Angkatan tidak valid',
                'data' => null
            ]);

            exit;
        }

        /*
         * Mengecek NIM.
         */
        $checkSql = "
            SELECT id
            FROM mahasiswa
            WHERE nim = :nim
        ";

        $checkStmt = $db->prepare($checkSql);

        $checkStmt->execute([
            ':nim' => $nim
        ]);

        if ($checkStmt->fetch()) {

            http_response_code(409);

            echo json_encode([
                'success' => false,
                'message' => 'NIM sudah terdaftar',
                'data' => null
            ]);

            exit;
        }

        /*
         * INSERT data.
         */
        $sql = "
            INSERT INTO mahasiswa
            (
                nim,
                nama,
                email,
                prodi_id,
                angkatan,
                status
            )
            VALUES
            (
                :nim,
                :nama,
                :email,
                :prodi_id,
                :angkatan,
                'aktif'
            )
        ";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':nim' => $nim,
            ':nama' => $nama,
            ':email' => $email,
            ':prodi_id' => $prodiId,
            ':angkatan' => $angkatan
        ]);

        $newId = $db->lastInsertId();

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => [
                'id' => (int) $newId,
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email
            ]
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | METHOD LAIN
    |--------------------------------------------------------------------------
    */

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method tidak didukung',
        'data' => null
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server',
        'data' => null
    ]);
}