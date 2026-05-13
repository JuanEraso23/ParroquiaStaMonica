<?php

namespace Database\Seeders;

use App\Models\Peticion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeticionesSeeder extends Seeder
{
    public function run(): void
    {
        $feligreses = User::where('rol', 'feligres')->get();
        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])->get();
        
        if ($feligreses->isEmpty()) {
            $this->command->info('No hay feligreses. Ejecuta primero UsuariosSeeder.');
            return;
        }

        $peticiones = [
            [
                'titulo' => 'Salud de familiar enfermo',
                'descripcion' => 'Por la salud de un familiar enfermo',
                'estado' => 'aprobada',
                'fecha' => Carbon::now()->subDays(10),
            ],
            [
                'titulo' => 'Trabajo y sustento',
                'descripcion' => 'Por el trabajo y sustento diario',
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->subDays(5),
            ],
            [
                'titulo' => 'Paz en el hogar',
                'descripcion' => 'Por la paz y armonía en el hogar',
                'estado' => 'completada',
                'fecha' => Carbon::now()->subDays(15),
            ],
            [
                'titulo' => 'Por los enfermos',
                'descripcion' => 'Por todos los enfermos de la comunidad',
                'estado' => 'aprobada',
                'fecha' => Carbon::now()->subDays(7),
            ],
            [
                'titulo' => 'Por los jóvenes',
                'descripcion' => 'Por la guía y protección de los jóvenes',
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($peticiones as $p) {
            Peticion::create([
                'feligres_id' => $feligreses->random()->id,
                'sacerdote_id' => $sacerdotes->random()->id,
                'titulo' => $p['titulo'],
                'descripcion' => $p['descripcion'],
                'fecha' => $p['fecha'],
                'estado' => $p['estado'],
                'respuesta' => $p['estado'] == 'aprobada' ? 'Que Dios los bendiga. Su petición ha sido escuchada.' : null,
            ]);
        }

        $this->command->info('Se crearon ' . count($peticiones) . ' peticiones de ejemplo.');
    }
}