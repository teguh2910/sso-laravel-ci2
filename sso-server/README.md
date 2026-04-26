# SSO Server (Laravel 10)

JWT-based Single Sign-On provider.

## Install

```bash
composer create-project laravel/laravel:^10.0 sso-server-app
# Then copy the files from this folder INTO sso-server-app/, overwriting matching files.
cp -r sso-server/* sso-server-app/
cd sso-server-app
composer require firebase/php-jwt
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DemoUserSeeder
php artisan serve --port=8000
```

## .env additions

```
SSO_JWT_SECRET=please-change-me-shared-with-clients
SSO_JWT_TTL=3600
SSO_ALLOWED_REDIRECTS="http://localhost:8001/sso/callback,http://localhost:8002/sso/callback"
```

## Flow

1. Client redirects user to `GET /sso/authorize?redirect=<client-callback>`.
2. If user not logged in here, they see the login form.
3. After login, the server signs a JWT and redirects to `<client-callback>?token=<jwt>`.
4. The client verifies the JWT with the shared `SSO_JWT_SECRET`.

## Endpoints

- `GET  /login`              — Login page
- `POST /login`              — Authenticate
- `GET  /sso/authorize`      — Issues JWT and redirects back to client
- `POST /sso/verify`         — (Optional) verify a JWT (for clients that prefer back-channel)
- `GET  /logout`             — Logout from SSO
