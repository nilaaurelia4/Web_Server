<?php

namespace App\Models;

use App\Core\Model;

class MataKuliah extends Model
{
    protected string $table = 'matakuliah';

    /**
     * Ambil data mata kuliah
     * + JOIN prodi.
     */
    public function all(
        int $limit = 5,
        int $offset = 0
    ): array {
        $sql = "
            SELECT
                m.id,
                m.kode,
                m.nama,
                m.sks,
                m.prodi_id,
                m.updated_at,

                p.kode AS prodi_kode,
                p.nama AS prodi_nama

            FROM matakuliah m

            INNER JOIN prodi p
                ON p.id = m.prodi_id

            ORDER BY m.id ASC

            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

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
     * Total mata kuliah.
     */
    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM matakuliah"
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * Cari mata kuliah berdasarkan ID.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                m.id,
                m.kode,
                m.nama,
                m.sks,
                m.prodi_id,
                p.kode AS prodi_kode,
                p.nama AS prodi_nama
            FROM matakuliah m
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
     * Ambil semua prodi.
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
     * Tambah mata kuliah.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO matakuliah
            (
                kode,
                nama,
                sks,
                prodi_id
            )
            VALUES
            (
                :kode,
                :nama,
                :sks,
                :prodi_id
            )
        ");

        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    /**
     * Update mata kuliah.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $stmt = $this->db->prepare("
            UPDATE matakuliah
            SET
                kode = :kode,
                nama = :nama,
                sks = :sks,
                prodi_id = :prodi_id
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    /**
     * Hapus mata kuliah.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM matakuliah
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}