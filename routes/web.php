<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grupo para admin (protegido por auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// Rutas protegidas por rol (HU2) con full class path
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
});

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':profesor'])->prefix('profesor')->name('profesor.')->group(function () {
    Route::get('/perfil', function () { return view('profesor.perfil'); })->name('perfil');
});

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':coordinador'])->prefix('coordinador')->name('coordinador.')->group(function () {
    Route::get('/asignaciones', function () { return view('coordinador.asignaciones'); })->name('asignaciones');
});

require __DIR__.'/auth.php';
