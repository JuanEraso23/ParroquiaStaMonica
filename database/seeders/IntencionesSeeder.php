<?php

namespace Database\Seeders;

use App\Models\Intencion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IntencionesSeeder extends Seeder
{
    public function run(): void
    {
        $feligreses = User::where('rol', 'feligres')->get();
        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])->get();
        
        if ($feligreses->isEmpty()) {
            $this->command->info('No hay feligreses. Ejecuta primero UsuariosSeeder.');
            return;
        }

        $intenciones = [
            [
                'titulo' => 'Aniversario luctuoso',
                'descripcion' => 'Intención de misa por aniversario luctuoso',
                'nombre_difunto' => 'Don José Pérez',
                'fecha_misa' => Carbon::now()->addDays(7),
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->subDays(9),
            ],
            [
                'titulo' => 'Descanso eterno',
                'descripcion' => 'Por el descanso eterno de su madre',
                'nombre_difunto' => 'Señora Ana María',
                'fecha_misa' => Carbon::now()->addDays(3),
                'estado' => 'confirmada',
                'fecha' => Carbon::now()->subDays(15),
            ],
            [
                'titulo' => 'Salud de la comunidad',
                'descripcion' => 'Por la salud de toda la comunidad parroquial',
                'nombre_difunto' => null,
                'fecha_misa' => Carbon::now()->addDays(10),
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->subDays(3),
            ],
            [
                'titulo' => 'Por las almas del purgatorio',
                'descripcion' => 'Por el eterno descanso de las almas del purgatorio',
                'nombre_difunto' => 'Fieles difuntos',
                'fecha_misa' => Carbon::now()->addDays(5),
                'estado' => 'confirmada',
                'fecha' => Carbon::now()->subDays(12),
            ],
            [
                'titulo' => 'Acción de gracias',
                'descripcion' => 'Misa de acción de gracias por favores recibidos',
                'nombre_difunto' => null,
                'fecha_misa' => Carbon::now()->addDays(14),
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($intenciones as $i) {
            Intencion::create([
                'feligres_id' => $feligreses->random()->id,
                'sacerdote_id' => $sacerdotes->random()->id,
                'titulo' => $i['titulo'],
                'descripcion' => $i['descripcion'],
                'fecha' => $i['fecha'],
                'nombre_difunto' => $i['nombre_difunto'],
                'fecha_misa' => $i['fecha_misa'],
                'estado' => $i['estado'],
            ]);
        }

        $this->command->info('Se crearon ' . count($intenciones) . ' intenciones de ejemplo.');
    }
}