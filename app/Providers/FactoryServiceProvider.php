<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registrar manualmente las factories para modelos modulares
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            // Si es un modelo del módulo GestionAcademica
            if (str_starts_with($modelName, 'App\Modules\GestionAcademica\Models\\')) {
                $modelClass = class_basename($modelName);
                return 'Database\Factories\\' . $modelClass . 'Factory';
            }
            
            // Para otros modelos, usar la convención por defecto
            return 'Database\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }
}