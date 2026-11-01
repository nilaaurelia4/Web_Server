<?php

class Response
{
    public static function json(
        int $code,
        bool $success,
        string $message,
        mixed $data = null,
        array $errors = []
    ): void {
        // Memicu autoload Database.php agar konstanta APP_ENV tersedia
        class_exists('Database');

        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');

        $body = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];

        if (!empty($errors)) {
            $body['errors'] = $errors;
        }

        $body['timestamp'] = date('c');

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if (APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($body, $flags);
        exit;
    }
}