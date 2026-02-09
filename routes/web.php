<?php

use App\Http\Controllers\CompanyLoginController;
use App\Http\Controllers\CompanyRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\WinnersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.old');
Route::get('/vencedores', [WinnersController::class, 'index'])->name('winners');

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

// DESABILITADO - Cadastro de Empresa
// Route::middleware('throttle:5,1')->group(function () {
//     Route::get('/empresa/cadastro', [CompanyRegisterController::class, 'show'])->name('company.register');
//     Route::post('/empresa/cadastro', [CompanyRegisterController::class, 'store'])->name('company.register.store');
// });

// DESABILITADO - Página Individual de Empresa
// Route::get('/empresa/{company:slug}', [VoteController::class, 'company'])->name('vote.company');

// DESABILITADO - Login de Empresa
// Route::middleware('throttle:5,1')->group(function () {
//     Route::get('/empresa/login', [CompanyLoginController::class, 'show'])->name('company.login');
//     Route::post('/empresa/login/enviar-codigo', [CompanyLoginController::class, 'sendCode'])->name('company.login.send-code');
//     Route::post('/empresa/login/verificar-codigo', [CompanyLoginController::class, 'verifyCode'])->name('company.login.verify-code');
// });

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/vote/{category:slug}/{company}', [VoteController::class, 'store'])->name('vote.store');
});

// Sitemap
Route::get('/sitemap.xml', function () {
    $categories = \App\Models\Category::where('is_active', true)->get();

    $content = '<?xml version="1.0" encoding="UTF-8"?>';
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Página inicial
    $content .= '<url>';
    $content .= '<loc>https://melhores.valedoxingu.com.br</loc>';
    $content .= '<changefreq>daily</changefreq>';
    $content .= '<priority>1.0</priority>';
    $content .= '</url>';

    // Página de votação
    $content .= '<url>';
    $content .= '<loc>https://melhores.valedoxingu.com.br/vote</loc>';
    $content .= '<changefreq>daily</changefreq>';
    $content .= '<priority>0.9</priority>';
    $content .= '</url>';

    // Página de vencedores
    $content .= '<url>';
    $content .= '<loc>https://melhores.valedoxingu.com.br/vencedores</loc>';
    $content .= '<changefreq>weekly</changefreq>';
    $content .= '<priority>0.8</priority>';
    $content .= '</url>';

    // Categorias
    foreach ($categories as $category) {
        $content .= '<url>';
        $content .= '<loc>https://melhores.valedoxingu.com.br/vote/' . $category->slug . '</loc>';
        $content .= '<changefreq>daily</changefreq>';
        $content .= '<priority>0.7</priority>';
        $content .= '</url>';
    }

    $content .= '</urlset>';

    return response($content, 200)->header('Content-Type', 'application/xml');
});
