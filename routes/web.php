<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CellierController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\BouteilleManuelleController;

Route::get('/', [AccueilController::class, 'index'])->name('welcome');

// Routes accessibles seulement aux invités (non connectés)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Formulaire d'ajout manuel
    Route::get('/celliers/{cellier}/bouteilles/ajout', [BouteilleManuelleController::class, 'create'])
        ->name('bouteilles.manuelles.create');

    // Traitement du formulaire
    Route::post('/celliers/{cellier}/bouteilles/ajout', [BouteilleManuelleController::class, 'store'])
        ->name('bouteilles.manuelles.store');
});

// Déconnexion : seulement si connecté
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Routes protégées : seulement accessibles si la session est ouverte
Route::middleware('auth')->group(function () {
    // Page principale après login/inscription
    Route::get('/celliers', [CellierController::class, 'index'])->name('cellar.index');
    Route::get('/celliers/create', [CellierController::class, 'create'])->name('cellar.create');
    Route::post('/celliers/create', [CellierController::class, 'store'])->name('cellar.store');
    Route::get('/celliers/{cellier}/edit', [CellierController::class, 'edit'])->name('cellar.edit');
    Route::put('/celliers/{cellier}/edit', [CellierController::class, 'update'])->name('cellar.update');
    Route::delete('/celliers/{cellier}', [CellierController::class, 'destroy'])->name('cellar.destroy');
});
