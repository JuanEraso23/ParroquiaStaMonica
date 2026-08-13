@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $usuario = Auth::user();
    $esAdmin = $usuario->esAdministrador();
@endphp

<!-- Título de la página -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        {{ $esAdmin ? 'Sistema de Gestión Parroquial' : 'Mi Panel' }}
    </h1>

    <p class="text-gray-500 text-sm mt-1">
        Parroquia Santa Mónica · {{ now()->format('d/m/Y') }}
    </p>
</div>

<!-- Cuadros de estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="stat-card">
        <div class="text-blue-600 text-3xl mb-2">
            <i class="fas fa-calendar-day"></i>
        </div>

        <div class="stat-number text-blue-600">
            {{ $citasHoy }}
        </div>

        <div class="text-sm text-gray-500">
            {{ $esAdmin ? 'Citas Hoy' : 'Mis Citas Hoy' }}
        </div>
    </div>

    <div class="stat-card">
        <div class="text-green-600 text-3xl mb-2">
            <i class="fas fa-hands-praying"></i>
        </div>

        <div class="stat-number text-green-600">
            {{ $totalPeticiones }}
        </div>

        <div class="text-sm text-gray-500">
            {{ $esAdmin ? 'Peticiones Registradas' : 'Mis Peticiones' }}
        </div>
    </div>

    <div class="stat-card">
        <div class="text-purple-600 text-3xl mb-2">
            <i class="fas fa-check-circle"></i>
        </div>

        <div class="stat-number text-purple-600">
            {{ $citasConfirmadas }}
        </div>

        <div class="text-sm text-gray-500">
            {{ $esAdmin ? 'Citas Confirmadas' : 'Mis Citas Confirmadas' }}
        </div>
    </div>
</div>

<!-- Listados -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Próximas Citas -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">
                <i class="fas fa-calendar-alt mr-1"></i>
                {{ $esAdmin ? 'Próximas Citas' : 'Mis Próximas Citas' }}
            </h2>

            <a href="{{ route('citas.index') }}" class="text-sm text-purple-600 hover:text-purple-800">
                Ver todas →
            </a>
        </div>

        <div class="p-4">
            @forelse($proximasCitas->take(4) as $cita)
                <div class="border-b border-gray-100 pb-3 mb-3 last:border-0 last:mb-0 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            @if($esAdmin)
                                <p class="font-medium text-gray-900">
                                    {{ $cita->feligres->nombre_completo ?? 'Sin feligrés' }}
                                </p>
                            @endif

                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                -
                                {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}
                            </p>

                            <p class="text-sm text-gray-500">
                                <span class="font-medium">
                                    {{ $cita->tipo_texto }}
                                </span>

                                @if($cita->descripcion)
                                    <br>
                                    <span class="text-xs text-gray-400">
                                        {{ $cita->descripcion }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <span class="px-2 py-1 text-xs rounded-full {{ $cita->estado_badge }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-calendar-alt text-2xl mb-2 opacity-50"></i>
                    <p>No hay citas programadas</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Peticiones Recientes -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">
                <i class="fas fa-hands-praying mr-1"></i>
                {{ $esAdmin ? 'Peticiones Recientes' : 'Mis Peticiones Recientes' }}
            </h2>

            <a href="{{ route('peticiones.index') }}" class="text-sm text-purple-600 hover:text-purple-800">
                Ver más →
            </a>
        </div>

        <div class="p-4">
            @forelse($peticionesRecientes->take(4) as $peticion)
                <div class="border-b border-gray-100 pb-3 mb-3 last:border-0 last:mb-0 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">
                                    Petición
                                </span>

                                <span class="text-xs text-gray-400">
                                    {{ $peticion->fecha ? $peticion->fecha->format('d/m/Y') : 'Sin fecha' }}
                                </span>
                            </div>

                            @if($esAdmin)
                                <p class="font-medium text-gray-900">
                                    {{ $peticion->feligres->nombre_completo ?? 'Sin feligrés' }}
                                </p>
                            @endif

                            <p class="text-sm text-gray-600">
                                {{ $peticion->titulo }}
                            </p>

                            <p class="text-xs text-gray-400 line-clamp-1">
                                {{ $peticion->descripcion }}
                            </p>

                            @if($peticion->categoria)
                                <p class="text-xs text-indigo-600 mt-1">
                                    Categoría: {{ $peticion->categoria->nombre }}
                                </p>
                            @endif
                        </div>

                        <span class="px-2 py-1 text-xs rounded-full {{ $peticion->estado_badge }}">
                            {{ ucfirst($peticion->estado) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-hands-praying text-4xl mb-2 opacity-50"></i>
                    <p>No hay peticiones recientes</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection