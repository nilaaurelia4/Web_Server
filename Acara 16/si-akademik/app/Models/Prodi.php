<?php

namespace App\Models;

use App\Core\Model;

class Prodi extends Model
{
    protected string $table = 'prodi';

    /**
     * Ambil semua prodi dengan pagination.
     */
    public function all(
        int $limit = 5,
        int $offset = 0
    ): array {
        $sql = "
            SELECT
                id,
                kode,
                nama,
                created_at,
                updated_at
            FROM prodi
            ORDER BY id ASC
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
     * Total prodi.
     */
    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM prodi"
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * Cari prodi berdasarkan ID.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                kode,
                nama
            FROM prodi
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    /**
     * Tambah prodi.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO prodi
            (
                kode,
                nama
            )
            VALUES
            (
                :kode,
                :nama
            )
        ");

        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
    }

    /**
     * Update prodi.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $stmt = $this->db->prepare("
            UPDATE prodi
            SET
                kode = :kode,
                nama = :nama
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
    }

    /**
     * Hapus prodi.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM prodi
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}