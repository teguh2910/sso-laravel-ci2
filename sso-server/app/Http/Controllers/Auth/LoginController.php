<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(Request $request)
    {
        return view('auth.login', [
            'redirect' => $request->query('redirect'),
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'redirect' => 'nullable|string',
        ]);

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $request->session()->regenerate();

        if (!empty($data['redirect'])) {
            return redirect()->route('sso.authorize', ['redirect' => $data['redirect']]);
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $clientUrls = config('sso.client_logout_urls', []);
        if (!empty($clientUrls)) {
            // Front-channel SLO: render iframes that hit each client's local-logout
            return response()->view('auth.logout', ['clients' => $clientUrls]);
        }
        return redirect()->route('login');
    }
}
