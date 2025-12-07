<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Verificación del Middleware ===\n";

// Verificar middleware registrado
$kernel = $app->make(\App\Http\Kernel::class);
$middlewareAliases = $kernel->getMiddlewareAliases();

if (array_key_exists('admin', $middlewareAliases)) {
    echo "✅ Middleware 'admin' registrado: " . $middlewareAliases['admin'] . "\n";
} else {
    echo "❌ Middleware 'admin' NO registrado\n";
}

// Verificar que la clase existe
if (class_exists(\App\Http\Middleware\AdminMiddleware::class)) {
    echo "✅ Clase AdminMiddleware existe\n";
} else {
    echo "❌ Clase AdminMiddleware NO existe\n";
}

echo "=== Fin de verificación ===\n"; 