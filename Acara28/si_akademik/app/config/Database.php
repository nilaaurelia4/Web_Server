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
            $host = 'localhost';
            $name = 'ISI_NAMA_DATABASE_CPANEL';
            $user = 'ISI_USERNAME_DATABASE_CPANEL';
            $password = 'ISI_PASSWORD_DATABASE_BARU';
            $port = 3306;

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $host,
                $port,
                $name
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$connection = new PDO($dsn, $user, $password, $options);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                Response::json(500, false, 'Koneksi database gagal');
            }
        }

        return self::$connection;
    }
}