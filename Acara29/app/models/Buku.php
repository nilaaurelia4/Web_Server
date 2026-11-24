<?php

class Buku
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT id, isbn, judul, penulis, penerbit, tahun_terbit, stok
             FROM buku ORDER BY id DESC'
        )->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare(
            'SELECT id, isbn, judul, penulis, penerbit, tahun_terbit, stok
             FROM buku WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function isbnExists(string $isbn, ?int $excludeId = null): bool
    {
        $sql    = 'SELECT id FROM buku WHERE isbn = :isbn';
        $params = [':isbn' => $isbn];

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function create(array $d): string
    {
        $stmt = $this->db->prepare(
            'INSERT INTO buku (isbn, judul, penulis, penerbit, tahun_terbit, stok)
             VALUES (:isbn, :judul, :penulis, :penerbit, :tahun_terbit, :stok)'
        );
        $stmt->execute([
            ':isbn'         => $d['isbn'],
            ':judul'        => $d['judul'],
            ':penulis'      => $d['penulis'],
            ':penerbit'     => $d['penerbit'],
            ':tahun_terbit' => $d['tahun_terbit'],
            ':stok'         => $d['stok'] ?? 0,
        ]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE buku
             SET isbn = :isbn, judul = :judul, penulis = :penulis,
                 penerbit = :penerbit, tahun_terbit = :tahun_terbit, stok = :stok
             WHERE id = :id'
        );
        $stmt->execute([
            ':isbn'         => $d['isbn'],
            ':judul'        => $d['judul'],
            ':penulis'      => $d['penulis'],
            ':penerbit'     => $d['penerbit'],
            ':tahun_terbit' => $d['tahun_terbit'],
            ':stok'         => $d['stok'] ?? 0,
            ':id'           => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM buku WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}