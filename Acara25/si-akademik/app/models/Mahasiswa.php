<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT m.id, m.nim, m.nama, m.email, m.prodi_id, p.nama AS prodi,
                    m.angkatan, m.status
             FROM mahasiswa m
             JOIN prodi p ON p.id = m.prodi_id
             ORDER BY m.id DESC'
        )->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare(
            'SELECT m.id, m.nim, m.nama, m.email, m.prodi_id, p.nama AS prodi,
                    m.angkatan, m.status
             FROM mahasiswa m
             JOIN prodi p ON p.id = m.prodi_id
             WHERE m.id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Tugas 2: cek NIM duplikat (excludeId dipakai saat update)
    public function nimExists(string $nim, ?int $excludeId = null): bool
    {
        $sql    = 'SELECT id FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $nim];

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function prodiExists(int $prodiId): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM prodi WHERE id = :id');
        $stmt->execute([':id' => $prodiId]);
        return (bool) $stmt->fetch();
    }

    
public function create(array $data): string
{
    try {
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa
                (nim, nama, email, prodi_id, angkatan, status)
             VALUES
                (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );

        $stmt->execute([
            ':nim'      => $data['nim'],
            ':nama'     => $data['nama'],
            ':email'    => $data['email'],
            ':prodi_id' => $data['prodi_id'],
            ':angkatan' => $data['angkatan'],
            ':status'   => $data['status'] ?? 'aktif',
        ]);

        return $this->db->lastInsertId();
    } catch (PDOException $e) {
        Response::json(
            500,
            false,
            'Terjadi kesalahan pada server'
        );
    }
}


    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':nim'      => $data['nim'],
            ':nama'     => $data['nama'],
            ':email'    => $data['email'],
            ':prodi_id' => $data['prodi_id'],
            ':angkatan' => $data['angkatan'],
            ':status'   => $data['status'] ?? 'aktif',
            ':id'       => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}