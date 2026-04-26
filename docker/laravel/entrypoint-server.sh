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

# Apply scaffold (custom files)
if [ -d /scaffold ]; then
  cp -rT /scaffold /var/www/html/
fi

if [ ! -f .env ]; then
  cp .env.example .env || true
  php artisan key:generate
fi

# SQLite DB
mkdir -p database
[ -f database/database.sqlite ] || touch database/database.sqlite

# Force sqlite for demo
if ! grep -q "^DB_CONNECTION=sqlite" .env; then
  sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env || echo 'DB_CONNECTION=sqlite' >> .env
  sed -i '/^DB_HOST=/d;/^DB_PORT=/d;/^DB_DATABASE=/d;/^DB_USERNAME=/d;/^DB_PASSWORD=/d' .env
fi

# Inject SSO env vars if missing
grep -q '^SSO_JWT_SECRET=' .env || echo 'SSO_JWT_SECRET=please-change-me-shared-with-clients' >> .env
grep -q '^SSO_JWT_TTL='    .env || echo 'SSO_JWT_TTL=3600' >> .env
grep -q '^SSO_ALLOWED_REDIRECTS=' .env || echo 'SSO_ALLOWED_REDIRECTS=http://localhost:8001/sso/callback,http://localhost:8002/sso/callback' >> .env
grep -q '^SSO_CLIENT_LOGOUT_URLS=' .env || echo 'SSO_CLIENT_LOGOUT_URLS=http://localhost:8001/sso/local-logout,http://localhost:8002/sso/local-logout' >> .env
grep -q '^APP_URL=' .env && sed -i 's|^APP_URL=.*|APP_URL=http://localhost:8000|' .env || echo 'APP_URL=http://localhost:8000' >> .env

php artisan config:clear || true
php artisan migrate --force
php artisan db:seed --class=DemoUserSeeder --force || true

echo ">> Starting SSO server on :8000"
exec php artisan serve --host=0.0.0.0 --port=8000
