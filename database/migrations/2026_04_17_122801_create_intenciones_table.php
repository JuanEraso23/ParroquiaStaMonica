<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feligres_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sacerdote_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('titulo');
            $table->text('descripcion');
            $table->date('fecha');
            $table->string('nombre_difunto')->nullable();
            $table->date('fecha_misa')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'realizada', 'cancelada'])->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->text('notas_internas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intenciones');
    }
};