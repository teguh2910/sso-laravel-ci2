<?php

return [
    'base_url'   => env('SSO_BASE_URL', 'http://localhost:8000'),
    'jwt_secret' => env('SSO_JWT_SECRET', 'please-change-me'),
    'jwt_alg'    => 'HS256',
    'callback'   => env('SSO_CALLBACK', 'http://localhost:8001/sso/callback'),
];
