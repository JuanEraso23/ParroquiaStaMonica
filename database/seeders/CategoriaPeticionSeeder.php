<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaPeticion;

class CategoriaPeticionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Salud',
            'Difuntos',
            'Cumpleaños',
            'Trabajo',
            'Estudios',
            'Familia',
            'Agradecimiento',
            'Orientación espiritual',
            'Otro',
        ];

        foreach ($categorias as $categoria) {
            CategoriaPeticion::firstOrCreate([
                'nombre' => $categoria,
            ]);
        }
    }
}