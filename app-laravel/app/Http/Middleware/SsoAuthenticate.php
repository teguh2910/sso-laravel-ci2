<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SsoAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('sso_user')) {
            $authorize = rtrim(config('sso.base_url'), '/') . '/sso/authorize';
            return redirect($authorize . '?redirect=' . urlencode(config('sso.callback')));
        }
        return $next($request);
    }
}
