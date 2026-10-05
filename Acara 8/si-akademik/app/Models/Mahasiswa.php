<?php

namespace App\Models;

use App\Core\Model;

class Mahasiswa extends Model
{
    protected string $table = 'mahasiswa';

    /**
     * Mengambil data mahasiswa
     * dengan search dan pagination.
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
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Menghitung total data mahasiswa.
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

        return (int) $stmt->fetchColumn();
    }

    /**
     * Cari mahasiswa berdasarkan ID.
     */
    public function find(int $id): ?array
    {
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

            WHERE m.id = :id

            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    /**
     * Data prodi untuk dropdown.
     */
    public function getProdi(): array
    {
        $sql = "
            SELECT
                id,
                kode,
                nama
            FROM prodi
            ORDER BY nama ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Tambah mahasiswa.
     */
    public function create(array $data): bool
    {
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
                :status
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status' => $data['status'],
        ]);
    }

    /**
     * Update mahasiswa.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $sql = "
            UPDATE mahasiswa
            SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan,
                status = :status
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status' => $data['status'],
        ]);
    }

    /**
     * Hapus mahasiswa.
     */
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM mahasiswa
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }
}