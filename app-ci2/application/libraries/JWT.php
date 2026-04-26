<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Minimal HS256 JWT decoder/encoder for CodeIgniter 2.
 * For production, prefer firebase/php-jwt via Composer.
 */
class JWT
{
    public static function encode(array $payload, $key, $alg = 'HS256')
    {
        $header  = ['typ' => 'JWT', 'alg' => $alg];
        $segments = [
            self::b64url(json_encode($header)),
            self::b64url(json_encode($payload)),
        ];
        $signing = implode('.', $segments);
        $sig = hash_hmac('sha256', $signing, $key, true);
        $segments[] = self::b64url($sig);
        return implode('.', $segments);
    }

    public static function decode($jwt, $key, $alg = 'HS256')
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) throw new Exception('Malformed token');
        list($h64, $p64, $s64) = $parts;

        $header = json_decode(self::b64urlDecode($h64), true);
        if (!is_array($header) || empty($header['alg'])) throw new Exception('Invalid header');
        if ($header['alg'] !== $alg) throw new Exception('Algorithm mismatch');

        $payload = json_decode(self::b64urlDecode($p64), true);
        if (!is_array($payload)) throw new Exception('Invalid payload');

        $expected = hash_hmac('sha256', $h64 . '.' . $p64, $key, true);
        $actual   = self::b64urlDecode($s64);
        if (!hash_equals($expected, $actual)) throw new Exception('Bad signature');

        $now = time();
        if (isset($payload['nbf']) && $now < $payload['nbf']) throw new Exception('Token not yet valid');
        if (isset($payload['exp']) && $now >= $payload['exp']) throw new Exception('Token expired');

        return $payload;
    }

    private static function b64url($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function b64urlDecode($data)
    {
        $r = strlen($data) % 4;
        if ($r) $data .= str_repeat('=', 4 - $r);
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
