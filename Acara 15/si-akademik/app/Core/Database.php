<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

        } catch (PDOException $e) {

            // Lokasi folder log
            $logDirectory =
                __DIR__ . '/../../storage/logs';

            // Buat folder logs jika belum ada
            if (!is_dir($logDirectory)) {
                mkdir(
                    $logDirectory,
                    0777,
                    true
                );
            }

            // Lokasi file log
            $logFile =
                $logDirectory . '/app.log';

            // Isi log
            $logMessage =
                date('Y-m-d H:i:s') .
                ' - Database Connection Error - ' .
                $e->getMessage() .
                ' - File: ' .
                $e->getFile() .
                ' - Line: ' .
                $e->getLine() .
                PHP_EOL;

            // Simpan ke app.log
            file_put_contents(
                $logFile,
                $logMessage,
                FILE_APPEND
            );

            // Pesan aman untuk pengguna
            die(
                'Koneksi database gagal. Silakan coba lagi.'
            );
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}