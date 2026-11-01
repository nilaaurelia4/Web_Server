
<?php

class Auth
{
    const SECRET = 'kunci_rahasia_workshop_2026';

    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function generateToken(array $payload): string
    {
        $header = self::base64url(
            json_encode(['alg' => 'HS256', 'typ' => 'JWT'])
        );

        // Tugas Mandiri 2: masa berlaku token 5 menit
        $payload['exp'] = time() + 300;

        $payloadEnc = self::base64url(json_encode($payload));

        $signature = self::base64url(
            hash_hmac(
                'sha256',
                "$header.$payloadEnc",
                self::SECRET,
                true
            )
        );

        return "$header.$payloadEnc.$signature";
    }

    public static function validateToken(string $token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        $headerData = json_decode(
            base64_decode(strtr($header, '-_', '+/')),
            true
        );

        if (
            !is_array($headerData) ||
            ($headerData['alg'] ?? '') !== 'HS256' ||
            ($headerData['typ'] ?? '') !== 'JWT'
        ) {
            return false;
        }

        $valid = self::base64url(
            hash_hmac(
                'sha256',
                "$header.$payload",
                self::SECRET,
                true
            )
        );

        if (!hash_equals($valid, $signature)) {
            return false;
        }

        $payloadBase64 = strtr($payload, '-_', '+/');
        $payloadBase64 .= str_repeat(
            '=',
            (4 - strlen($payloadBase64) % 4) % 4
        );

        $data = json_decode(base64_decode($payloadBase64), true);

        if (!is_array($data) || !isset($data['exp'])) {
            return false;
        }

        if (!is_numeric($data['exp']) || (int) $data['exp'] <= time()) {
            return false;
        }

        return $data;
    }

    public static function requireAuth(): array
    {
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $authHeader = '';

        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $authHeader = $value;
                break;
            }
        }

        if (
            !preg_match('/^Bearer\s+(\S+)$/i', trim($authHeader), $m)
        ) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($m[1]);

        if (!$payload) {
            Response::json(
                401,
                false,
                'Token tidak valid atau kedaluwarsa'
            );
        }

        return $payload;
    }
}
