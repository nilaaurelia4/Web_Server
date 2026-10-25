<?php

// Mode aplikasi: 'development' (JSON pretty print) atau 'production' (JSON ringkas)
if (!defined('APP_ENV')) {
    define('APP_ENV', 'development');
}

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = 'mysql:host=localhost;dbname=si_akademik;charset=utf8mb4';

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$connection = new PDO($dsn, 'root', '', $options);
            } catch (PDOException $e) {
                Response::json(500, false, 'Koneksi database gagal');
            }
        }

        return self::$connection;
    }
}