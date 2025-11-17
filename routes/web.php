<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CellierController;
use App\Http\Controllers\AccueilController;

<<<<<<< HEAD
Route::get('/', [AccueilController::class, 'index']);
=======
// Route::get('/', function () {
//     return view('catalogue.index');
// });
>>>>>>> a908d07f3743df905fd93dc2e59ac54e7eb4df4f


// Formulaires
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

// Traitement des formulaires
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/// Routes protégées par auth 
Route::middleware('auth')->group(function () {
    // Route::get('/celliers', [CellierController::class, 'index'])->name('celliers.index');

});
