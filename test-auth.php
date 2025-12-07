<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;

echo "=== Prueba de Autenticación ===\n";

// Probar autenticación con coordinador
$credentials = [
    'email' => 'coordinador@universidad.edu',
    'password' => 'password123'
];

if (Auth::attempt($credentials)) {
    $user = Auth::user();
    echo "✅ AUTENTICACIÓN EXITOSA\n";
    echo "Usuario: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Rol: {$user->role->name}\n";
    echo "Redirección: " . route('academic.dashboard') . "\n";
} else {
    echo "❌ AUTENTICACIÓN FALLIDA\n";
    echo "Posibles causas:\n";
    echo "- Usuario no existe\n";
    echo "- Contraseña incorrecta\n";
    echo "- Usuario inactivo\n";
    
    // Verificar si el usuario existe
    $user = \App\Models\User::where('email', $credentials['email'])->first();
    if ($user) {
        echo "✅ Usuario EXISTE en la base de datos\n";
        echo "Contraseña hash: " . $user->password . "\n";
        echo "Está activo: " . ($user->is_active ? 'SÍ' : 'NO') . "\n";
        
        // Verificar contraseña manualmente
        if (\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            echo "✅ Contraseña es CORRECTA\n";
        } else {
            echo "❌ Contraseña es INCORRECTA\n";
        }
    } else {
        echo "❌ Usuario NO EXISTE en la base de datos\n";
    }
}

echo "=== Fin de prueba ===\n";