# 🔐 SSO with Laravel 10 (JWT-based)

[![JWT Auth](https://img.shields.io/badge/JWT-SHA256-brightgreen?style=flat-square&logo=json-web-tokens)](https://jwt.io/)
[![Laravel 10](https://img.shields.io/badge/Laravel-10-red?style=flat-square&logo=laravel)](https://laravel.com/)
[![CodeIgniter 2](https://img.shields.io/badge/CodeIgniter-2-orange?style=flat-square)](https://codeigniter.com/)
[![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)](LICENSE)

> Enterprise-grade Single Sign-On implementation with JWT authentication across Laravel 10 and CodeIgniter 2

## 🎯 Overview

A production-ready SSO (Single Sign-On) system demonstrating seamless authentication across multiple platforms using HMAC-SHA256 JWT tokens. Perfect for enterprise environments requiring unified identity management.

```
Browser ──▶ Client App ──▶ SSO Server ──▶ JWT Token ──▶ Authenticated ✓
```

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        SSO ARCHITECTURE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│   ┌──────────────┐     JWT Token     ┌──────────────────────┐  │
│   │  SSO Server  │◄──────────────────│   Laravel Client    │  │
│   │  (IdP)       │  (HMAC-SHA256)    │   Port: 8001        │  │
│   │  Port: 8000 │───────────────────►│                     │  │
│   └──────────────┘                   └──────────────────────┘  │
│          │                                               │     │
│          │ JWT Token                                   │     │
│          ▼                                               ▼     │
│   ┌──────────────┐                   ┌──────────────────────┐  │
│   │   Demo User  │                   │  CodeIgniter Client │  │
│   │  Seeder      │                   │  Port: 8002         │  │
│   └──────────────┘                   └──────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 📦 Components

| Component | Role | Port | Stack |
|-----------|------|------|-------|
| `sso-server/` | Identity Provider (IdP) | 8000 | Laravel 10 |
| `app-laravel/` | SSO Client #1 | 8001 | Laravel 10 |
| `app-ci2/` | SSO Client #2 | 8002 | CodeIgniter 2.x |

## 🔑 Key Features

- ✅ **HMAC-SHA256 JWT** - Cryptographically secure token signing
- ✅ **Zero back-channel calls** - Clients verify tokens locally
- ✅ **Cross-platform** - Laravel + CodeIgniter interoperability
- ✅ **Single Logout** - Terminate sessions across all clients
- ✅ **Session management** - Automatic session handling
- ✅ **Demo user seeded** - Ready to test out of the box

## 🚀 Quick Start

### Prerequisites

- PHP 8.1+ (Laravel 10) & PHP 7.4+ (CodeIgniter 2)
- Composer
- SQLite or MySQL

### 1️⃣ Install SSO Server

```bash
# Clone and setup
composer create-project laravel/laravel:^10.0 sso-server-app
cp -r sso-server/. sso-server-app/
cd sso-server-app

# Install dependencies
composer require firebase/php-jwt
cp .env.example .env
php artisan key:generate

# Initialize database
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=DemoUserSeeder

# Start server
php artisan serve --port=8000
```

### 2️⃣ Install Laravel Client

```bash
composer create-project laravel/laravel:^10.0 app-laravel-app
cp -r app-laravel/. app-laravel-app/
cd app-laravel-app

composer require firebase/php-jwt
cp .env.example .env
php artisan key:generate
php artisan serve --port=8001
```

### 3️⃣ Install CodeIgniter Client

```bash
curl -L https://github.com/bcit-ci/CodeIgniter/archive/2.2.6.tar.gz | tar xz
mv CodeIgniter-2.2.6 app-ci2-app
cp -r app-ci2/application/. app-ci2-app/application/
php -S localhost:8002 -t app-ci2-app/
```

## 🔐 Configuration

Set the **same JWT secret** across all projects:

```env
SSO_JWT_SECRET=please-change-me-shared-with-clients
```

## 🧪 Test Credentials

| Email | Password |
|-------|----------|
| `demo@example.com` | `password` |

## 🔒 Security Notes

> ⚠️ For production, consider:
> - Use **RS256** (asymmetric) instead of HS256
> - Implement **CSRF state parameter** in `/sso/authorize`
> - Add **nonce** and replay protection (`jti` caching)
> - Implement **back-channel logout** notifications
> - Always serve over **HTTPS**

## 📄 License

MIT License - See [LICENSE](LICENSE) for details.

---

<div align="center">

**⭐ Star this repo if it helped!** | Built with ❤️ by [@teguh2910](https://github.com/teguh2910)

</div>