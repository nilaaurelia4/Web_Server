<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Mahasiswa extends Model
{
    protected string $table = 'mahasiswa';

    /**
     * Mengambil semua data mahasiswa.
     */
    public function all(): array
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

            ORDER BY m.id DESC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    /**
     * Mengambil satu mahasiswa berdasarkan ID.
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
     * Mengambil semua data program studi.
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
     * Menambahkan mahasiswa.
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
     * Mengubah mahasiswa.
     */
    public function update(int $id, array $data): bool
    {
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
     * Menghapus mahasiswa.
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