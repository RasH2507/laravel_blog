<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagArtikelController;
use App\Http\Controllers\ArtikelSectionController;

Route::get('/', [HomeController::class, 'index'])->name('/');

// Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'checkRole:admin'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User
    Route::resource('user', UserController::class);

    // Game 
    Route::resource('game', GameController::class);

    // Tag
    Route::resource('tag', TagController::class);
    Route::get('/tag-artikel', [TagArtikelController::class, 'index'])->name('tag-artikel.index');

    // Komentar
    Route::resource('komentar', KomentarController::class);

    // Artikel
    Route::resource('artikel', ArtikelController::class);
    Route::patch('/artikel/{id}/confirm', [ArtikelController::class, 'confirm'])->name('artikel.confirm');
    Route::patch('/artikel/{id}/reject', [ArtikelController::class, 'reject'])->name('artikel.reject');
    Route::get('/artikel-section', [ArtikelSectionController::class, 'index'])->name('artikel-section.index');
});


Route::middleware(['auth', 'checkRole:penulis'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile')
        ->middleware('auth');

    Route::get('/penulis/dashboard', [HomeController::class, 'writerDashboard'])
        ->name('penulis.dashboard');

    Route::get('/penulis/myarticles', [PenulisController::class, 'myArticles'])
        ->name('penulis.myarticles');

    Route::get('/penulis/artikel/create', [PenulisController::class, 'createArtikel'])
        ->name('penulis.artikel.create');
    Route::post('/penulis/artikel', [PenulisController::class, 'storeArtikel'])
        ->name('penulis.artikel.store');

    Route::get('/penulis/artikel/show/{id}', [PenulisController::class, 'showArtikel'])
        ->name('penulis.artikel.show');

    Route::get('/penulis/artikel/{id}/edit', [PenulisController::class, 'editArtikel'])
        ->name('penulis.artikel.edit');
    Route::put('/penulis/artikel/{id}', [PenulisController::class, 'updateArtikel'])
        ->name('penulis.artikel.update');

    Route::delete('/penulis/artikel/{artikel}', [PenulisController::class, 'destroyArtikel'])
        ->name('penulis.artikel.destroy');

    Route::post('/artikel/{id}/like', [LikeController::class, 'toggleLike'])->name('artikel.like');
    Route::get('/artikel/{id}/like-count', [LikeController::class, 'getLikeCount'])->name('artikel.likeCount');
});
Route::get('/artikel/{id}', [HomeController::class, 'artikelDetail'])->name('artikel.detail');
Route::get('/artikel', [HomeController::class, 'artikelPage'])->name('artikel.page');
Route::get('/game/{id}', [HomeController::class, 'gameArticles'])->name('game.articles');
