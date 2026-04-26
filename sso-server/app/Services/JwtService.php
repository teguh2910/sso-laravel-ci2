<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public static function issue(array $user): string
    {
        $now = time();
        $payload = [
            'iss' => config('sso.issuer'),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + config('sso.jwt_ttl'),
            'sub' => $user['id'],
            'email' => $user['email'] ?? null,
            'name'  => $user['name'] ?? null,
        ];

        return JWT::encode($payload, config('sso.jwt_secret'), config('sso.jwt_alg'));
    }

    public static function verify(string $token): array
    {
        $decoded = JWT::decode($token, new Key(config('sso.jwt_secret'), config('sso.jwt_alg')));
        return (array) $decoded;
    }
}
