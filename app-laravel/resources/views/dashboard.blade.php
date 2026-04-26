<!doctype html>
<html><head><meta charset="utf-8"><title>Dashboard</title>
<style>body{font-family:system-ui;background:#0f172a;color:#e2e8f0;margin:0}.w{max-width:640px;margin:6rem auto;padding:2rem;background:#1e293b;border-radius:12px}pre{background:#0f172a;padding:1rem;border-radius:8px;overflow:auto}button{padding:.6rem 1rem;border:0;border-radius:8px;background:#ef4444;color:#fff;cursor:pointer;font-weight:600}</style>
</head><body><div class="w">
<h1>App Laravel — Dashboard</h1>
<p>Signed in via SSO as <strong>{{ $user['email'] ?? 'unknown' }}</strong></p>
<pre>{{ json_encode($user, JSON_PRETTY_PRINT) }}</pre>
<form method="POST" action="/logout">@csrf<button type="submit">Logout (SSO)</button></form>
</div></body></html>
