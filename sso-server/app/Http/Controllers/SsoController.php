<?php

namespace App\Http\Controllers;

use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoController extends Controller
{
    public function authorizeClient(Request $request)
    {
        $redirect = $request->query('redirect');

        if (!$redirect || !$this->isAllowed($redirect)) {
            abort(400, 'Invalid or unregistered redirect URI.');
        }

        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $redirect]);
        }

        $user  = Auth::user();
        $token = JwtService::issue([
            'id'    => $user->id,
            'email' => $user->email,
            'name'  => $user->name,
        ]);

        $sep = str_contains($redirect, '?') ? '&' : '?';
        return redirect()->away($redirect . $sep . 'token=' . urlencode($token));
    }

    public function verify(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        try {
            $claims = JwtService::verify($request->input('token'));
            return response()->json(['valid' => true, 'claims' => $claims]);
        } catch (\Throwable $e) {
            return response()->json(['valid' => false, 'error' => $e->getMessage()], 401);
        }
    }

    private function isAllowed(string $url): bool
    {
        $allowed = config('sso.allowed_redirects');
        if (empty($allowed)) return false;
        foreach ($allowed as $a) {
            if ($a !== '' && str_starts_with($url, $a)) return true;
        }
        return false;
    }
}
