<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;           // ← NUEVO
use Database\Factories\RoleFactory;
use App\Modules\Auth\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar manualmente la factory para Role si es necesario
        $this->app->bind(RoleFactory::class, function () {
            return new RoleFactory();
        });
    }

    public function boot()
    {
        // REGISTRO OBLIGATORIO DE ALIAS DE MIDDLEWARE EN LARAVEL 12
        Route::aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        Route::aliasMiddleware('coordinator', \App\Http\Middleware\CoordinatorMiddleware::class);

        // FORZAR HTTPS EN PRODUCCIÓN (Render, Supabase, etc.)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}