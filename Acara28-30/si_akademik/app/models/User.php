<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Tugas Mandiri 1: validasi format email
    public function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Dipakai register: cegah username/email ganda sebelum INSERT
    public function usernameExists(string $username): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return (bool) $stmt->fetch();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        return (bool) $stmt->fetch();
    }

    // Kolom role tidak diisi: akun hasil register selalu berperan 'user'
    public function create(array $data): string
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
            );
            $stmt->execute([
                ':u' => $data['username'],
                ':e' => $data['email'],
                ':p' => $data['password'],
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }
}