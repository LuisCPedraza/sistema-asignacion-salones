<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cambiar todos los correos con @sena.edu por @universidad.edu
        $users = User::where('email', 'like', '%@sena.edu')->get();

        foreach ($users as $user) {
            $newEmail = str_replace('@sena.edu', '@universidad.edu', $user->email);
            $user->update(['email' => $newEmail]);
        }

        $count = $users->count();
        $this->command->info("✅ Se actualizaron {$count} correos de @sena.edu a @universidad.edu");
    }
}
