<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('sessions:clean', function () {
    $path = storage_path('framework/sessions');
    if (File::exists($path)) {
        File::cleanDirectory($path);
        $this->info('Sesiones limpiadas correctamente.');
    }
})->purpose('Limpiar todas las sesiones almacenadas');