<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear administradores
        $administradores = [
            [
                'name' => 'Edison Giovanny',
                'apellidos' => 'Velasquez',
                'documento' => '10000001',
                'telefono' => '3104711341',
                'email' => 'parroco@santamonica.org',
                'password' => Hash::make('admin123'),
                'rol' => 'parroco',
                'cargo' => 'Párroco',
                'activo' => true,
            ],
            [
                'name' => 'Carlos Felipe',
                'apellidos' => 'Torres Paredes',
                'documento' => '10000002',
                'telefono' => '300000000',
                'email' => 'vicario@santamonica.org',
                'password' => Hash::make('admin123'),
                'rol' => 'vicario',
                'cargo' => 'Vicario Parroquial',
                'activo' => true,
            ],
            [
                'name' => 'Carmen',
                'apellidos' => 'Vargas',
                'documento' => '10000003',
                'telefono' => '300000001',
                'email' => 'secretaria@santamonica.org',
                'password' => Hash::make('admin123'),
                'rol' => 'secretaria',
                'cargo' => 'Secretaria Principal',
                'activo' => true,
            ],
        ];
        
        foreach ($administradores as $admin) {
            User::create($admin);
        }
        
        // Crear feligreses de ejemplo
        $feligreses = [
            [
                'name' => 'María',
                'apellidos' => 'González',
                'documento' => '10000004',
                'telefono' => '300000002',
                'email' => 'maria@example.com',
                'password' => Hash::make('feligres123'),
                'rol' => 'feligres',
                'activo' => true,
            ],
            [
                'name' => 'Juan',
                'apellidos' => 'Pérez',
                'documento' => '10000005',
                'telefono' => '300000003',
                'email' => 'juan@example.com',
                'password' => Hash::make('feligres123'),
                'rol' => 'feligres',
                'activo' => true,
            ],
        ];
        
        foreach ($feligreses as $feligres) {
            User::create($feligres);
        }
    }
}