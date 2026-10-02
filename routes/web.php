<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PeticionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/**
 * Página de inicio pública.
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Rutas protegidas generales.
 */
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * Dashboard.
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /**
     * Perfil del usuario.
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
     * Cambio de contraseña.
     */
    Route::get('/perfil/password', [ProfileController::class, 'password'])
        ->name('profile.password');

    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

    /**
     * Citas - rutas compartidas.
     */
    Route::resource('citas', CitaController::class)->only([
        'index',
        'create',
        'store',
        'destroy',
    ]);

    /**
     * Peticiones - rutas compartidas.
     *
     * El método index ahora muestra directamente el listado,
     * filtros y categorías de las peticiones.
     */
    Route::resource('peticiones', PeticionController::class)->only([
        'index',
        'create',
        'store',
        'destroy',
    ]);

    /**
     * Horarios - agenda informativa.
     */
    Route::get('/horarios', [HorarioController::class, 'index'])
        ->name('horarios.index');

    Route::get('/horarios/{fecha}', [HorarioController::class, 'dia'])
        ->name('horarios.dia');
});

/**
 * Rutas exclusivas para administradores.
 */
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    /**
     * Usuarios.
     */
    Route::resource('usuarios', UsuarioController::class);

    Route::patch(
        'usuarios/{usuario}/toggle-activo',
        [UsuarioController::class, 'toggleActivo']
    )->name('usuarios.toggle-activo');

    /**
     * Citas - acciones administrativas.
     */
    Route::get('citas/{cita}/edit', [CitaController::class, 'edit'])
        ->name('citas.edit');

    Route::put('citas/{cita}', [CitaController::class, 'update'])
        ->name('citas.update');

    Route::patch(
        'citas/{cita}/estado',
        [CitaController::class, 'cambiarEstado']
    )->name('citas.estado');

    /**
     * Peticiones - acciones administrativas.
     */
    Route::get(
        'peticiones/{peticione}/edit',
        [PeticionController::class, 'edit']
    )->name('peticiones.edit');

    Route::put(
        'peticiones/{peticione}',
        [PeticionController::class, 'update']
    )->name('peticiones.update');

    Route::patch(
        'peticiones/{peticione}/estado',
        [PeticionController::class, 'cambiarEstado']
    )->name('peticiones.estado');
});

/**
 * Rutas de autenticación.
 */
require __DIR__ . '/auth.php';