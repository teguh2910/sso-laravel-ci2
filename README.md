# SSO with Laravel 10 (JWT-based)

Three projects:

| Folder        | Role                       | Port  | Stack            |
|---------------|----------------------------|-------|------------------|
| `sso-server/` | Identity provider (IdP)    | 8000  | Laravel 10       |
| `app-laravel/`| SSO client #1              | 8001  | Laravel 10       |
| `app-ci2/`    | SSO client #2              | 8002  | CodeIgniter 2.x  |

All three share **one HMAC-SHA256 JWT secret** (`SSO_JWT_SECRET`). The server signs a JWT after login and redirects back to the client with `?token=...`. Each client verifies the JWT locally — no back-channel call required.

## Flow

```
Browser                 Client (8001/8002)             SSO Server (8000)
  | --GET /dashboard------>|                                  |
  |                        | --redirect /sso/authorize------> |
  |                        |                                  | (login form if needed)
  |                        |                                  |
  | <----------------- redirect /sso/callback?token=JWT ------|
  | --GET /sso/callback--->| (verify JWT, set session)        |
  | <-----------200 OK-----| (dashboard)                      |
```

## Prereqs

- PHP 8.1+ (for Laravel 10) and PHP 5.6/7.x compatibility for CI2 (PHP 7.4 works for both)
- Composer
- SQLite (default for sso-server) or MySQL

Install on Ubuntu:
```bash
sudo apt update
sudo apt install -y php8.1-cli php8.1-sqlite3 php8.1-mbstring php8.1-xml php8.1-curl unzip
curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer
```

## Quick start

### 1) SSO Server

```bash
composer create-project laravel/laravel:^10.0 sso-server-app
cp -r sso-server/. sso-server-app/
cd sso-server-app
composer require firebase/php-jwt
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=DemoUserSeeder
php artisan serve --port=8000
```

Demo user: `demo@example.com` / `password`

### 2) Laravel Client

```bash
composer create-project laravel/laravel:^10.0 app-laravel-app
cp -r app-laravel/. app-laravel-app/
cd app-laravel-app
composer require firebase/php-jwt
cp .env.example .env
php artisan key:generate
php artisan serve --port=8001
```

Open http://localhost:8001/dashboard → redirects to SSO → back signed in.

### 3) CodeIgniter 2 Client

```bash
curl -L https://github.com/bcit-ci/CodeIgniter/archive/2.2.6.tar.gz | tar xz
mv CodeIgniter-2.2.6 app-ci2-app
cp -r app-ci2/application/. app-ci2-app/application/
php -S localhost:8002 -t app-ci2-app/
```

Open http://localhost:8002/index.php/dashboard.

## Same JWT secret in all three

In each `.env` (or `application/config/sso.php` for CI2), set the **same** value:

```
SSO_JWT_SECRET=please-change-me-shared-with-clients
```

## Production notes

- Use **RS256** (asymmetric) instead of HS256 so clients don't need the signing key.
- Put SSO server and clients on the same parent domain to share session/cookies if you want silent re-auth.
- Add CSRF state parameter to `/sso/authorize` to prevent login CSRF.
- Add `nonce` and replay protection (cache `jti` until `exp`).
- Implement back-channel logout (notify clients on SSO logout).
- Always serve over HTTPS in production.
