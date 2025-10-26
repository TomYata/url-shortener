<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShortUrlController;

Route::get('/', function () {
    return view('welcome');
});

// Authentification
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// Formulaire de création d'URL courte
Route::get('/shorturl/create', [ShortUrlController::class, 'create'])->name('shorturl.create')->middleware('auth');
// Soumission du formulaire
Route::post('/shorturl/store', [ShortUrlController::class, 'store'])->name('shorturl.store')->middleware('auth');
// Edition d'une URL courte
Route::get('/shorturl/{id}/edit', [ShortUrlController::class, 'edit'])->name('shorturl.edit')->middleware('auth');
Route::put('/shorturl/{id}', [ShortUrlController::class, 'update'])->name('shorturl.update')->middleware('auth');
// Suppression d'une URL courte
Route::delete('/shorturl/{id}', [ShortUrlController::class, 'destroy'])->name('shorturl.destroy')->middleware('auth');

// Tableau de bord utilisateur
Route::get('/dashboard', [ShortUrlController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// Redirection via l'URL courte
Route::get('/{short}', [ShortUrlController::class, 'redirect'])->name('shorturl.redirect');
