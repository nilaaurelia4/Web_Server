<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Mahasiswa;
use PDO;

class MahasiswaRepository
{
    private PDO $db;


    /**
     * Dependency Injection.
     *
     * Database diberikan dari luar.
     */
    public function __construct(
        Database $database
    ) {
        $this->db =
            $database->getConnection();
    }


    /**
     * Menampilkan data mahasiswa.
     */
    public function all(
        ?string $keyword = null,
        int $limit = 5,
        int $offset = 0
    ): array {

        $sql = "
            SELECT
                m.id,
                m.nim,
                m.nama,
                m.email,
                m.prodi_id,
                m.angkatan,
                m.status,
                m.updated_at,

                p.kode AS prodi_kode,
                p.nama AS prodi_nama

            FROM mahasiswa m

            INNER JOIN prodi p
                ON p.id = m.prodi_id
        ";


        if (
            $keyword !== null &&
            $keyword !== ''
        ) {
            $sql .= "
                WHERE
                    m.nim LIKE :nim
                    OR m.nama LIKE :nama
            ";
        }


        $sql .= "
            ORDER BY m.id DESC
            LIMIT :limit
            OFFSET :offset
        ";


        $stmt = $this->db->prepare($sql);


        if (
            $keyword !== null &&
            $keyword !== ''
        ) {
            $keyword = '%' . $keyword . '%';

            $stmt->bindValue(
                ':nim',
                $keyword
            );

            $stmt->bindValue(
                ':nama',
                $keyword
            );
        }


        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll();
    }


    /**
     * Menghitung jumlah mahasiswa.
     */
    public function count(
        ?string $keyword = null
    ): int {

        $sql = "
            SELECT COUNT(*)
            FROM mahasiswa m

            INNER JOIN prodi p
                ON p.id = m.prodi_id
        ";


        if (
            $keyword !== null &&
            $keyword !== ''
        ) {
            $sql .= "
                WHERE
                    m.nim LIKE :nim
                    OR m.nama LIKE :nama
            ";
        }


        $stmt = $this->db->prepare($sql);


        if (
            $keyword !== null &&
            $keyword !== ''
        ) {

            $keyword = '%' . $keyword . '%';

            $stmt->execute([
                'nim' => $keyword,
                'nama' => $keyword,
            ]);

        } else {

            $stmt->execute();
        }


        return (int)
            $stmt->fetchColumn();
    }


    /**
     * Mengambil satu mahasiswa.
     */
    public function find(
        int $id
    ): ?array {

        $stmt = $this->db->prepare("
            SELECT
                m.id,
                m.nim,
                m.nama,
                m.email,
                m.prodi_id,
                m.angkatan,
                m.status,
                m.updated_at,

                p.kode AS prodi_kode,
                p.nama AS prodi_nama

            FROM mahasiswa m

            INNER JOIN prodi p
                ON p.id = m.prodi_id

            WHERE m.id = :id

            LIMIT 1
        ");


        $stmt->execute([
            'id' => $id
        ]);


        $data = $stmt->fetch();


        return $data ?: null;
    }


    /**
     * Mengambil semua data prodi.
     */
    public function getProdi(): array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                kode,
                nama
            FROM prodi
            ORDER BY nama ASC
        ");


        return $stmt->fetchAll();
    }

    /**
     * Mengecek apakah NIM sudah digunakan.
     */
    public function existsByNim(
        string $nim,
        ?int $excludeId = null
    ): bool {

        $sql = "
            SELECT COUNT(*)
            FROM mahasiswa
            WHERE nim = :nim
        ";

        if ($excludeId !== null) {
            $sql .= "
                AND id != :id
            ";
        }

        $stmt = $this->db->prepare($sql);

        $params = [
            'nim' => $nim
        ];

        if ($excludeId !== null) {
            $params['id'] = $excludeId;
        }

        $stmt->execute($params);

        return
            (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Menambahkan mahasiswa.
     */
    public function create(
        Mahasiswa $mahasiswa
    ): bool {

        $stmt = $this->db->prepare("
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
                :status
            )
        ");


        return $stmt->execute([
            'nim' =>
                $mahasiswa->getNim(),

            'nama' =>
                $mahasiswa->getNama(),

            'email' =>
                $mahasiswa->getEmail(),

            'prodi_id' =>
                $mahasiswa->getProdiId(),

            'angkatan' =>
                $mahasiswa->getAngkatan(),

            'status' =>
                $mahasiswa->getStatus(),
        ]);
    }


    /**
     * Mengubah mahasiswa.
     */
    public function update(
        int $id,
        Mahasiswa $mahasiswa
    ): bool {

        $stmt = $this->db->prepare("
            UPDATE mahasiswa
            SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan,
                status = :status

            WHERE id = :id
        ");


        return $stmt->execute([
            'id' =>
                $id,

            'nim' =>
                $mahasiswa->getNim(),

            'nama' =>
                $mahasiswa->getNama(),

            'email' =>
                $mahasiswa->getEmail(),

            'prodi_id' =>
                $mahasiswa->getProdiId(),

            'angkatan' =>
                $mahasiswa->getAngkatan(),

            'status' =>
                $mahasiswa->getStatus(),
        ]);
    }


    /**
     * Menghapus mahasiswa.
     */
    public function delete(
        int $id
    ): bool {

        $stmt = $this->db->prepare("
            DELETE FROM mahasiswa
            WHERE id = :id
        ");


        return $stmt->execute([
            'id' => $id
        ]);
    }
}