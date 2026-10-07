<?php

namespace App\Services;

use Throwable;

class LoggerService
{
    /**
     * Menyimpan error ke storage/logs/app.log.
     */
    public function error(Throwable $e): void
    {
        $logDirectory =
            __DIR__ .
            '/../../storage/logs';

        /*
         * Jika folder belum ada,
         * buat secara otomatis.
         */
        if (!is_dir($logDirectory)) {
            mkdir(
                $logDirectory,
                0777,
                true
            );
        }

        $logFile =
            $logDirectory .
            '/app.log';

        $message =
            date('Y-m-d H:i:s') .
            ' - ' .
            get_class($e) .
            ' - ' .
            $e->getMessage() .
            ' - File: ' .
            $e->getFile() .
            ' - Line: ' .
            $e->getLine() .
            PHP_EOL;

        error_log(
            $message,
            3,
            $logFile
        );
    }
}