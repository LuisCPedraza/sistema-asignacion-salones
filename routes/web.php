<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboards por rol
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/coordinador/dashboard', function () {
        return view('coordinador.dashboard');
    })->name('coordinador.dashboard');
    
    Route::get('/infraestructura/dashboard', function () {
        return view('infraestructura.dashboard');
    })->name('infraestructura.dashboard');
    
    Route::get('/profesor/dashboard', function () {
        return view('profesor.dashboard');
    })->name('profesor.dashboard');
});