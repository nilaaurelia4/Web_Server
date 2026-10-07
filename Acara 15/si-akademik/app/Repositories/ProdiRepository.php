<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ProdiRepository
{
    private PDO $db;

    public function __construct(
        Database $database
    ) {
        $this->db =
            $database->getConnection();
    }


    /**
     * Mengambil seluruh prodi.
     */
    public function all(): array
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
     * Mencari prodi berdasarkan ID.
     */
    public function find(
        int $id
    ): ?array {

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
     * Mengecek apakah prodi tersedia.
     */
    public function exists(
        int $id
    ): bool {

        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM prodi
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return
            (int) $stmt->fetchColumn() > 0;
    }
}