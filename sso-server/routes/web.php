<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SsoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login',  [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/sso/authorize', [SsoController::class, 'authorizeClient'])->name('sso.authorize');
Route::post('/sso/verify',   [SsoController::class, 'verify'])->name('sso.verify');
