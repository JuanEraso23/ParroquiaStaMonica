<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearSessions extends Command
{
    protected $signature = 'sessions:clear';
    protected $description = 'Clear all session files';

    public function handle()
    {
        $path = storage_path('framework/sessions');
        if (File::exists($path)) {
            File::cleanDirectory($path);
            $this->info('Sesiones limpiadas correctamente.');
        } else {
            $this->error('Directorio de sesiones no encontrado.');
        }
    }
}