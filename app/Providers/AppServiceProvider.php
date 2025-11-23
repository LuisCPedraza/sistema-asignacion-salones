<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        //
    }
}