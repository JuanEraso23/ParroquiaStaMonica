<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->integer('duracion_minutos')->default(15)->after('hora');
            $table->time('hora_fin')->nullable()->after('duracion_minutos');
        });

        DB::statement("
            UPDATE citas
            SET hora_fin = ADDTIME(hora, SEC_TO_TIME(duracion_minutos * 60))
            WHERE hora_fin IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['duracion_minutos', 'hora_fin']);
        });
    }
};
