<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ActivateAllUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Activar todos los usuarios y cambiar contraseña a password123
        User::query()->update([
            'is_active' => true,
            'password' => Hash::make('password123'),
        ]);

        $this->command->info('✅ Todos los usuarios han sido activados con contraseña: password123');
    }
}
