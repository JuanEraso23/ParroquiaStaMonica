<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la configuración diaria de agenda para cada sacerdote.
     */
    public function up(): void
    {
        Schema::create('agendas_sacerdotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sacerdote_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->date('fecha');

            $table->time('hora_inicio')
                ->default('15:00:00');

            $table->time('hora_fin')
                ->default('18:00:00');

            $table->enum('estado', ['abierta', 'cerrada'])
                ->default('cerrada');

            $table->foreignId('abierta_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('abierta_en')
                ->nullable();

            $table->boolean('fuera_de_plazo')
                ->default(false);

            $table->string(
                'motivo_apertura_extraordinaria',
                500
            )->nullable();

            $table->foreignId('cerrada_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('cerrada_en')
                ->nullable();

            $table->timestamps();

            /*
             * Un sacerdote solo puede tener una configuración
             * de agenda para una fecha determinada.
             */
            $table->unique(
                ['sacerdote_id', 'fecha'],
                'agenda_sacerdote_fecha_unique'
            );
        });
    }

    /**
     * Elimina la tabla al revertir esta migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas_sacerdotes');
    }
};
