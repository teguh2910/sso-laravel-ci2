<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class SsoClientController extends Controller
{
    public function callback(Request $request)
    {
        $token = $request->query('token');
        if (!$token) abort(400, 'Missing token.');

        try {
            $claims = (array) JWT::decode($token, new Key(config('sso.jwt_secret'), config('sso.jwt_alg')));
        } catch (\Throwable $e) {
            abort(401, 'Invalid SSO token: ' . $e->getMessage());
        }

        $request->session()->regenerate();
        $request->session()->put('sso_user', [
            'id'    => $claims['sub']   ?? null,
            'email' => $claims['email'] ?? null,
            'name'  => $claims['name']  ?? null,
            'exp'   => $claims['exp']   ?? null,
        ]);
        $request->session()->put('sso_token', $token);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['sso_user', 'sso_token']);
        $request->session()->invalidate();
        return redirect(rtrim(config('sso.base_url'), '/') . '/logout');
    }

    public function localLogout(Request $request)
    {
        // Called by SSO server (via iframe) to clear this app's session only.
        $request->session()->forget(['sso_user', 'sso_token']);
        $request->session()->invalidate();
        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    public function dashboard(Request $request)
    {
        return view('dashboard', ['user' => $request->session()->get('sso_user')]);
    }
}
