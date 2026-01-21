<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si ya existe para no duplicar
        if (!User::where('email', 'admin@tuuniversidad.edu.co')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@tuuniversidad.edu.co', // Tu correo real
                'password' => Hash::make('Tu_Contraseña_Segura_Aqui_123$'),
                'email_verified_at' => now(), // Para marcarlo como verificado automáticamente
            ]);
        }
    }
}
