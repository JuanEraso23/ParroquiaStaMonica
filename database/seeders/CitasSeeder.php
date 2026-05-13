<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CitasSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener los feligreses existentes
        $feligreses = User::where('rol', 'feligres')->get();
        
        // Obtener los sacerdotes (párroco y vicario)
        $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])->get();
        
        // Si no hay feligreses o sacerdotes, no continuar
        if ($feligreses->isEmpty() || $sacerdotes->isEmpty()) {
            $this->command->info('No hay feligreses o sacerdotes disponibles. Ejecuta primero UsuariosSeeder.');
            return;
        }

        // Citas de ejemplo
        $citas = [
            [
                'tipo' => 'confesion',
                'descripcion' => 'Confesión general',
                'estado' => 'confirmada',
                'fecha' => Carbon::now()->addDays(2),
                'hora' => '09:00:00',
            ],
            [
                'tipo' => 'bautismo',
                'descripcion' => 'Bautizo de Santiago Pérez',
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->addDays(5),
                'hora' => '10:30:00',
            ],
            [
                'tipo' => 'matrimonio',
                'descripcion' => 'Primera entrevista prematrimonial',
                'estado' => 'confirmada',
                'fecha' => Carbon::now()->addDays(7),
                'hora' => '14:00:00',
            ],
            [
                'tipo' => 'orientacion',
                'descripcion' => 'Orientación pastoral familiar',
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->addDays(3),
                'hora' => '11:00:00',
            ],
            [
                'tipo' => 'confesion',
                'descripcion' => 'Confesión de primera comunión',
                'estado' => 'completada',
                'fecha' => Carbon::now()->subDays(2),
                'hora' => '08:30:00',
            ],
            [
                'tipo' => 'bautismo',
                'descripcion' => 'Bautizo de Valentina López',
                'estado' => 'cancelada',
                'fecha' => Carbon::now()->addDays(10),
                'hora' => '15:00:00',
            ],
            [
                'tipo' => 'matrimonio',
                'descripcion' => 'Segunda entrevista prematrimonial',
                'estado' => 'pendiente',
                'fecha' => Carbon::now()->addDays(12),
                'hora' => '09:30:00',
            ],
            [
                'tipo' => 'orientacion',
                'descripcion' => 'Curso de preparación para bautizos',
                'estado' => 'confirmada',
                'fecha' => Carbon::now()->addDays(8),
                'hora' => '16:00:00',
            ],
        ];

        // Asignar feligreses y sacerdotes aleatoriamente
        foreach ($citas as $citaData) {
            $feligres = $feligreses->random();
            $sacerdote = $sacerdotes->random();

            Cita::create([
                'feligres_id' => $feligres->id,
                'sacerdote_id' => $sacerdote->id,
                'fecha' => $citaData['fecha'],
                'hora' => $citaData['hora'],
                'tipo' => $citaData['tipo'],
                'descripcion' => $citaData['descripcion'],
                'estado' => $citaData['estado'],
                'notas_internas' => 'Cita generada automáticamente por el seeder',
            ]);
        }

        // Crear citas adicionales para los próximos días (para pruebas)
        $tipos = ['confesion', 'bautismo', 'matrimonio', 'orientacion'];
        $estados = ['pendiente', 'confirmada', 'completada', 'cancelada'];
        
        for ($i = 1; $i <= 15; $i++) {
            $feligres = $feligreses->random();
            $sacerdote = $sacerdotes->random();
            $dias = rand(1, 30);
            
            Cita::create([
                'feligres_id' => $feligres->id,
                'sacerdote_id' => $sacerdote->id,
                'fecha' => Carbon::now()->addDays($dias),
                'hora' => sprintf('%02d:00:00', rand(8, 17)),
                'tipo' => $tipos[array_rand($tipos)],
                'descripcion' => 'Cita de prueba ' . $i,
                'estado' => $estados[array_rand($estados)],
                'notas_internas' => 'Cita generada automáticamente para pruebas',
            ]);
        }

        $this->command->info('Se crearon ' . (count($citas) + 15) . ' citas de ejemplo.');
    }
}