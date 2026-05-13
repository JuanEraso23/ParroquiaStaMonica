<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeticionController;
use App\Http\Controllers\IntencionController;
use App\Http\Controllers\PeticionIntencionController;
use App\Http\Controllers\HorarioController;
use Illuminate\Support\Facades\Route;

/**
 * Página de inicio pública
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Rutas protegidas generales
 */
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * Dashboard
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /**
     * Perfil del usuario
     */
    Route::get('/perfil', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/perfil/editar', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/perfil', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/perfil', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * Cambio de contraseña
     */
    Route::get('/perfil/password', [ProfileController::class, 'password'])
        ->name('profile.password');

    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

    /**
     * Citas - rutas compartidas
     */
    Route::resource('citas', CitaController::class)->only([
        'index',
        'create',
        'store',
        'destroy',
    ]);

    /**
     * Peticiones - rutas compartidas
     */
    Route::resource('peticiones', PeticionController::class)->only([
        'index',
        'create',
        'store',
        'destroy',
    ]);

    /**
     * Intenciones - rutas compartidas
     */
    Route::resource('intenciones', IntencionController::class)->only([
        'index',
        'create',
        'store',
        'destroy',
    ]);

    /**
     * Vista unificada de peticiones e intenciones
     */
    Route::get('/peticiones_intenciones', [PeticionIntencionController::class, 'index'])
        ->name('peticiones_intenciones.index');

    /**
     * Horarios - agenda informativa
     */
    Route::get('/horarios', [HorarioController::class, 'index'])
        ->name('horarios.index');

    Route::get('/horarios/{fecha}', [HorarioController::class, 'dia'])
        ->name('horarios.dia');
});

/**
 * Rutas exclusivas para administradores
 */
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    /**
     * Usuarios
     */
    Route::resource('usuarios', UsuarioController::class);

    Route::patch('usuarios/{usuario}/toggle-activo', [UsuarioController::class, 'toggleActivo'])
        ->name('usuarios.toggle-activo');

    /**
     * Citas - acciones administrativas
     */
    Route::get('citas/{cita}/edit', [CitaController::class, 'edit'])
        ->name('citas.edit');

    Route::put('citas/{cita}', [CitaController::class, 'update'])
        ->name('citas.update');

    Route::patch('citas/{cita}/estado', [CitaController::class, 'cambiarEstado'])
        ->name('citas.estado');

    /**
     * Peticiones - acciones administrativas
     */
    Route::get('peticiones/{peticione}/edit', [PeticionController::class, 'edit'])
        ->name('peticiones.edit');

    Route::put('peticiones/{peticione}', [PeticionController::class, 'update'])
        ->name('peticiones.update');

    Route::patch('peticiones/{peticione}/estado', [PeticionController::class, 'cambiarEstado'])
        ->name('peticiones.estado');

    /**
     * Intenciones - acciones administrativas
     */
    Route::get('intenciones/{intencione}/edit', [IntencionController::class, 'edit'])
        ->name('intenciones.edit');

    Route::put('intenciones/{intencione}', [IntencionController::class, 'update'])
        ->name('intenciones.update');

    Route::patch('intenciones/{intencione}/estado', [IntencionController::class, 'cambiarEstado'])
        ->name('intenciones.estado');
});

/**
 * Rutas de autenticación
 */
require __DIR__ . '/auth.php';