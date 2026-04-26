<?php

return [
    'jwt_secret' => env('SSO_JWT_SECRET', 'please-change-me'),
    'jwt_ttl'    => (int) env('SSO_JWT_TTL', 3600),
    'jwt_alg'    => 'HS256',
    'issuer'     => env('APP_URL', 'http://localhost:8000'),
    'allowed_redirects' => array_filter(array_map('trim', explode(',', (string) env('SSO_ALLOWED_REDIRECTS', '')))),
    'client_logout_urls' => array_filter(array_map('trim', explode(',', (string) env('SSO_CLIENT_LOGOUT_URLS', '')))),
];
