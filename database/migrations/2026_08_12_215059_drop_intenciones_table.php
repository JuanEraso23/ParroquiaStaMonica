<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Elimina definitivamente la tabla de intenciones.
     */
    public function up(): void
    {
        Schema::dropIfExists('intenciones');
    }

    /**
     * El módulo de intenciones fue retirado definitivamente.
     */
    public function down(): void
    {
        // No se reconstruye la tabla de intenciones.
    }
};