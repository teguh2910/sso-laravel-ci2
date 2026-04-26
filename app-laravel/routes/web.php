<?php

use App\Http\Controllers\SsoClientController;
use App\Http\Middleware\SsoAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/sso/callback', [SsoClientController::class, 'callback'])->name('sso.callback');
Route::get('/sso/local-logout', [SsoClientController::class, 'localLogout'])->name('sso.local-logout');
Route::match(['get','post'], '/logout', [SsoClientController::class, 'logout'])->name('logout');

Route::middleware(SsoAuthenticate::class)->group(function () {
    Route::get('/dashboard', [SsoClientController::class, 'dashboard'])->name('dashboard');
});
