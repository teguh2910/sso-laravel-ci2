# App CI2 (CodeIgniter 2 SSO Client)

Drop-in files to add SSO support to a CodeIgniter 2.x application.

## Install

1. Download CodeIgniter 2.2.x and extract into `app-ci2-app/` (this folder mirrors that layout):
   https://github.com/bcit-ci/CodeIgniter/archive/2.2.6.zip
2. Copy files from this `app-ci2/` folder INTO your CI2 app (overwriting matching files):
   ```bash
   cp -r app-ci2/application/* app-ci2-app/application/
   cp app-ci2/index.php app-ci2-app/  # only if you want the demo entry; usually skip
   ```
3. Install the JWT library (CI2 has no Composer by default — use the bundled lib):
   - This scaffold ships a minimal Firebase JWT class at `application/libraries/JWT.php` (HS256 only).
   - For production, prefer real `firebase/php-jwt` via Composer autoload bootstrapped from `index.php`.
4. Edit `application/config/sso.php` to set your shared secret and SSO base URL.
5. Serve with PHP 7.x compatible runtime:
   ```bash
   php -S localhost:8002 -t app-ci2-app/
   ```
6. Visit http://localhost:8002/index.php/dashboard

## Files added

- `application/config/sso.php` — config (base URL, secret, callback)
- `application/libraries/JWT.php` — minimal HS256 JWT decoder
- `application/libraries/Sso_client.php` — SSO helper
- `application/controllers/Sso.php` — handles `/sso/callback`, `/logout`
- `application/controllers/Dashboard.php` — protected demo page
- `application/hooks/sso_session_start.php` — ensures session_start
