<?php

use App\Http\Controllers\CompanyLoginController;
use App\Http\Controllers\CompanyRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.old');

Route::middleware('throttle:5,1')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::get('/auth/magic/{audience}', [MagicLinkController::class, 'authenticate'])->name('auth.magic');

Route::post('/logout', LogoutController::class)->name('logout');

Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
Route::get('/vote/{category:slug}', [VoteController::class, 'show'])->name('vote.show');

// Company Registration (deve vir antes da rota genérica /empresa/{company})
Route::middleware('throttle:5,1')->group(function () {
    Route::get('/empresa/cadastro', [CompanyRegisterController::class, 'show'])->name('company.register');
    Route::post('/empresa/cadastro', [CompanyRegisterController::class, 'store'])->name('company.register.store');
});

// Company Login
Route::middleware('throttle:5,1')->group(function () {
    Route::get('/empresa/login', [CompanyLoginController::class, 'show'])->name('company.login');
    Route::post('/empresa/login/enviar-codigo', [CompanyLoginController::class, 'sendCode'])->name('company.login.send-code');
    Route::post('/empresa/login/verificar-codigo', [CompanyLoginController::class, 'verifyCode'])->name('company.login.verify-code');
});

Route::get('/empresa/{company:slug}', [VoteController::class, 'company'])->name('vote.company');

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/vote/{category:slug}/{company}', [VoteController::class, 'store'])->name('vote.store');
});
