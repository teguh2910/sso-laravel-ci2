# App Laravel (SSO Client)

Laravel 10 client that delegates auth to the SSO server.

## Install

```bash
composer create-project laravel/laravel:^10.0 app-laravel-app
cp -r app-laravel/* app-laravel-app/
cd app-laravel-app
composer require firebase/php-jwt
php artisan key:generate
php artisan serve --port=8001
```

## .env additions

```
SSO_BASE_URL=http://localhost:8000
SSO_JWT_SECRET=please-change-me-shared-with-clients
SSO_CALLBACK=http://localhost:8001/sso/callback
```

Visit http://localhost:8001/dashboard — you should be redirected to the SSO server, then back, signed in.
