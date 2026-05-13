<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peticiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feligres_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sacerdote_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('titulo');
            $table->text('descripcion');
            $table->date('fecha');
            $table->enum('estado', ['pendiente', 'aprobada', 'completada', 'rechazada'])->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->text('notas_internas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peticiones');
    }
};