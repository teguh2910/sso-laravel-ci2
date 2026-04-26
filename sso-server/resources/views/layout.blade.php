<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'SSO Server')</title>
<style>
  *{box-sizing:border-box}body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;background:#0f172a;color:#e2e8f0}
  .wrap{max-width:420px;margin:6rem auto;padding:2rem;background:#1e293b;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.4)}
  h1{margin:0 0 1.25rem;font-size:1.5rem}
  label{display:block;margin:.75rem 0 .25rem;font-size:.9rem;color:#94a3b8}
  input{width:100%;padding:.65rem .8rem;border-radius:8px;border:1px solid #334155;background:#0f172a;color:#e2e8f0}
  button{margin-top:1.25rem;width:100%;padding:.7rem;border:0;border-radius:8px;background:#6366f1;color:#fff;font-weight:600;cursor:pointer}
  button:hover{background:#4f46e5}
  .err{background:#7f1d1d;color:#fecaca;padding:.6rem .8rem;border-radius:8px;margin-bottom:1rem;font-size:.9rem}
  .muted{color:#94a3b8;font-size:.85rem;margin-top:1rem;text-align:center}
  a{color:#a5b4fc}
</style>
</head>
<body>
  <div class="wrap">
    @yield('content')
  </div>
</body>
</html>
