#!/usr/bin/env bash
set -e

cd /var/www/html

if [ ! -f index.php ] || [ ! -d system ]; then
  echo ">> Downloading CodeIgniter 2.2.6..."
  curl -sSL https://github.com/bcit-ci/CodeIgniter/archive/refs/tags/2.2.6.tar.gz -o /tmp/ci.tgz
  tar -xzf /tmp/ci.tgz -C /tmp
  shopt -s dotglob
  cp -r /tmp/CodeIgniter-2.2.6/* /var/www/html/
  shopt -u dotglob
  rm -rf /tmp/CodeIgniter-2.2.6 /tmp/ci.tgz
fi

# Apply scaffold
if [ -d /scaffold ]; then
  cp -rT /scaffold /var/www/html/
fi

# Configure base URL & URI protocol for PHP built-in server
if [ -f application/config/config.php ]; then
  sed -i "s|\$config\['base_url'\].*=.*|\$config['base_url'] = 'http://localhost:8002/';|" application/config/config.php
  sed -i "s|\$config\['uri_protocol'\].*=.*|\$config['uri_protocol'] = 'REQUEST_URI';|" application/config/config.php
  sed -i "s|\$config\['index_page'\].*=.*|\$config['index_page'] = '';|" application/config/config.php
fi

# Autoload url helper so site_url(), redirect() are always available
if [ -f application/config/autoload.php ]; then
  sed -i "s|\$autoload\['helper'\].*=.*|\$autoload['helper'] = array('url');|" application/config/autoload.php
fi

# Tiny router so /dashboard, /sso/callback etc. all hit index.php with full REQUEST_URI
cat > /tmp/router.php <<'PHP'
<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // serve static asset
}
require __DIR__ . '/index.php';
PHP
cp /tmp/router.php /var/www/html/router.php

echo ">> Starting CI2 client on :8002"
exec php -S 0.0.0.0:8002 -t /var/www/html /var/www/html/router.php
