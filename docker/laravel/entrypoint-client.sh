#!/usr/bin/env bash
set -e

cd /var/www/html

if [ ! -f composer.json ]; then
  echo ">> Creating fresh Laravel 10 project..."
  composer create-project --no-interaction --prefer-dist laravel/laravel:^10.0 /tmp/laravel
  shopt -s dotglob
  mv /tmp/laravel/* /var/www/html/
  shopt -u dotglob
fi

if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

if ! composer show firebase/php-jwt >/dev/null 2>&1; then
  composer require --no-interaction firebase/php-jwt
fi

if [ -d /scaffold ]; then
  cp -rT /scaffold /var/www/html/
fi

if [ ! -f .env ]; then
  cp .env.example .env || true
  php artisan key:generate
fi

# Inject SSO env (point to host's exposed sso-server port from BROWSER perspective)
grep -q '^SSO_BASE_URL='   .env || echo 'SSO_BASE_URL=http://localhost:8000' >> .env
grep -q '^SSO_JWT_SECRET=' .env || echo 'SSO_JWT_SECRET=please-change-me-shared-with-clients' >> .env
grep -q '^SSO_CALLBACK='   .env || echo 'SSO_CALLBACK=http://localhost:8001/sso/callback' >> .env
grep -q '^APP_URL=' .env && sed -i 's|^APP_URL=.*|APP_URL=http://localhost:8001|' .env || echo 'APP_URL=http://localhost:8001' >> .env

php artisan config:clear || true

echo ">> Starting Laravel client on :8001"
exec php artisan serve --host=0.0.0.0 --port=8001
