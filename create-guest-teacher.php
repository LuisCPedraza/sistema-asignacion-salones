<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;

// Buscar el usuario
$user = User::where('email', 'invitado@universidad.edu')->first();

if (!$user) {
    echo "❌ Usuario no encontrado\n";
    exit(1);
}

echo "✓ Usuario encontrado: {$user->name} ({$user->email})\n";

// Verificar si ya existe en teachers
$existingTeacher = Teacher::where('user_id', $user->id)->first();
if ($existingTeacher) {
    echo "⚠️ Ya existe un registro en teachers con ID: {$existingTeacher->id}\n";
    echo "   is_guest actual: " . ($existingTeacher->is_guest ? 'true' : 'false') . "\n";
    
    // Actualizar a guest
    $existingTeacher->update([
        'is_guest' => true,
        'access_expires_at' => now()->addDays(30)
    ]);
    echo "✓ Actualizado a profesor invitado (expira en 30 días)\n";
    exit(0);
}

// Crear nuevo registro
$teacher = Teacher::create([
    'user_id' => $user->id,
    'first_name' => 'Profesor',
    'last_name' => 'Invitado',
    'email' => 'invitado@universidad.edu',
    'specialty' => 'Invitado Temporal',
    'estado' => 'activo',
    'is_guest' => true,
    'access_expires_at' => now()->addDays(30)
]);

echo "✓ Profesor invitado creado en tabla teachers con ID: {$teacher->id}\n";
echo "✓ Fecha de expiración: {$teacher->access_expires_at}\n";
