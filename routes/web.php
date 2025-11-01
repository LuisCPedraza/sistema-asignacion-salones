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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');  // Fix: name 'profile.edit' for navigation.blade.php
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Grupo para admin (protegido por auth)
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::resource('users', UserController::class);  // Movido aquí para CheckRole:admin (solo admin edita)
    Route::resource('grupos', \App\Http\Controllers\Admin\GrupoController::class);  // CRUD grupos (Épica 2)
    Route::resource('salones', \App\Http\Controllers\Admin\SalonController::class)->parameters(['salones' => 'salon']);  // CRUD salones (Épica 3, parámetro {salon})
    Route::resource('profesores', \App\Http\Controllers\Admin\ProfesorController::class)->parameters(['profesores' => 'profesor']);  // CRUD profesores (Épica 4, parámetro {profesor})
    Route::resource('configuraciones', \App\Http\Controllers\Admin\ConfiguracionController::class)->parameters(['configuraciones' => 'configuracion']);  // CRUD configuraciones (Épica 5, parámetro {configuracion})
});

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':profesor'])->prefix('profesor')->name('profesor.')->group(function () {
    Route::get('/perfil', function () { return view('profesor.perfil'); })->name('perfil');
});

Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':coordinador'])->prefix('coordinador')->name('coordinador.')->group(function () {
    Route::get('/asignaciones', function () { return view('coordinador.asignaciones'); })->name('asignaciones');
});

require __DIR__.'/auth.php';
