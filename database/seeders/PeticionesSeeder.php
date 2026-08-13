<?php

namespace Database\Seeders;

use App\Models\CategoriaPeticion;

use App\Models\Peticion;

use App\Models\User;

use Carbon\Carbon;

use Illuminate\Database\Seeder;

class PeticionesSeeder extends Seeder

{

 public function run(): void

 {

 $feligreses = User::where('rol', 'feligres')->get();

 $sacerdotes = User::whereIn('rol', ['parroco', 'vicario'])->get();

 if ($feligreses->isEmpty()) {

 $this->command->error('No hay feligreses. Ejecuta primero UsuariosSeeder.');

 return;

 }

 if ($sacerdotes->isEmpty()) {

 $this->command->error('No hay sacerdotes. Ejecuta primero UsuariosSeeder.');

 return;

 }

 $categorias = CategoriaPeticion::pluck('id', 'nombre');

 if ($categorias->isEmpty()) {

 $this->command->error('No hay categorías. Ejecuta primero CategoriaPeticionSeeder.');

 return;

 }

 $peticiones = [

 [

 'titulo' => 'Salud de familiar enfermo',

 'descripcion' => 'Por la salud de un familiar enfermo',

 'categoria' => 'Salud',

 'estado' => 'aprobada',

 'fecha' => Carbon::now()->subDays(10),

 ],

 [

 'titulo' => 'Trabajo y sustento',

 'descripcion' => 'Por el trabajo y sustento diario',

 'categoria' => 'Trabajo',

 'estado' => 'pendiente',

 'fecha' => Carbon::now()->subDays(5),

 ],

 [

 'titulo' => 'Paz en el hogar',

 'descripcion' => 'Por la paz y armonía en el hogar',

 'categoria' => 'Familia',

 'estado' => 'completada',

 'fecha' => Carbon::now()->subDays(15),

 ],

 [

 'titulo' => 'Por los enfermos',

 'descripcion' => 'Por todos los enfermos de la comunidad',

 'categoria' => 'Salud',

 'estado' => 'aprobada',

 'fecha' => Carbon::now()->subDays(7),

 ],

 [

 'titulo' => 'Por los jóvenes',

 'descripcion' => 'Por la guía y protección de los jóvenes',

 'categoria' => 'Orientación espiritual',

 'estado' => 'pendiente',

 'fecha' => Carbon::now()->subDays(3),

 ],

 ];

 foreach ($peticiones as $peticion) {

 Peticion::create([

 'feligres_id' => $feligreses->random()->id,

 'sacerdote_id' => $sacerdotes->random()->id,

 'categoria_peticion_id' => $categorias[$peticion['categoria']],

 'titulo' => $peticion['titulo'],

 'descripcion' => $peticion['descripcion'],

 'fecha' => $peticion['fecha'],

 'estado' => $peticion['estado'],

 'respuesta' => $peticion['estado'] === 'aprobada'

 ? 'Que Dios los bendiga. Su petición ha sido escuchada.'

 : null,

 ]);

 }

 $this->command->info('Se crearon ' . count($peticiones) . ' peticiones con categoría.');

 }

}