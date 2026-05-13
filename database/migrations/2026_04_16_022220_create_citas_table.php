<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feligres_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sacerdote_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora');
            $table->string('tipo');
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->text('notas_internas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};