@extends('layouts.app')

@section('title', 'Gestión de Citas')

@section('content')
@php
    $usuario = Auth::user();
    $esAdmin = $usuario->esAdministrador();
@endphp

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <!-- Encabezado -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-calendar-check"></i>
                    {{ $esAdmin ? 'Gestión de Citas' : 'Mis Citas' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $esAdmin ? 'Administración de las citas de la parroquia' : 'Consulta y gestión de tus citas registradas en la parroquia' }}
                </p>
            </div>

            <a href="{{ route('citas.create') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus"></i>
                {{ $esAdmin ? 'Nueva Cita' : 'Solicitar Cita' }}
            </a>
        </div>

        <!-- Mensajes flash -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Horario de Atención -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-100">
            <h3 class="font-semibold text-gray-800 mb-2">🕘 Horario de Atención para Citas</h3>
            <p class="text-sm text-gray-600 mb-3">
                Ambos sacerdotes están disponibles para gestionar las citas según el horario establecido.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-3">
                    <p class="font-semibold text-gray-800">Padre Edison</p>
                    <p class="text-sm text-gray-500">Párroco • Disponible 8:00 - 9:00 AM</p>
                </div>
                <div class="bg-white rounded-lg p-3">
                    <p class="font-semibold text-gray-800">Padre Felipe</p>
                    <p class="text-sm text-gray-500">Vicario • Disponible 8:00 - 9:00 AM</p>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <form method="GET" action="{{ route('citas.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ $esAdmin ? 'Nombre, teléfono, tipo...' : 'Tipo, descripción o sacerdote...' }}"
                           class="w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sacerdote</label>
                    <select name="sacerdote_id" class="w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        @foreach($sacerdotes as $sacerdote)
                            <option value="{{ $sacerdote->id }}" {{ request('sacerdote_id') == $sacerdote->id ? 'selected' : '' }}>
                                {{ $sacerdote->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date"
                           name="fecha"
                           value="{{ request('fecha') }}"
                           class="w-full rounded-lg border-gray-300">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition w-full">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['search', 'sacerdote_id', 'fecha']))
                <div class="mt-3 text-right">
                    <a href="{{ route('citas.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-eraser"></i> Limpiar filtros
                    </a>
                </div>
            @endif
        </form>

        <!-- Listado de citas -->
        <div class="space-y-6">
            @forelse($citas as $cita)
                <div class="border rounded-lg p-4 bg-white hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg font-semibold text-gray-800">
                                    {{ $cita->fecha->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $cita->estado_badge }}">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </div>

                            {{-- Información del feligrés solo para admin --}}
                            @if($esAdmin)
                                <p class="font-medium text-gray-900">{{ $cita->feligres->nombre_completo }}</p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-phone mr-1"></i> {{ $cita->feligres->telefono }}
                                </p>
                            @endif

                            <p class="text-sm text-gray-500">
                                <i class="fas fa-user mr-1"></i> {{ $cita->sacerdote->nombre_completo }}
                            </p>

                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-medium">{{ $cita->tipo_texto }}</span>
                                @if($cita->descripcion)
                                    <br><span class="text-xs text-gray-400">{{ $cita->descripcion }}</span>
                                @endif
                            </p>

                            {{-- Mensaje orientativo para feligrés --}}
                            @unless($esAdmin)
                                @if($cita->estado === 'pendiente')
                                    <p class="text-xs text-yellow-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Puedes eliminar esta cita si deseas corregirla o registrarla nuevamente.
                                    </p>
                                @elseif(in_array($cita->estado, ['confirmada', 'completada', 'cancelada']))
                                    <p class="text-xs text-gray-400 mt-2">
                                        <i class="fas fa-lock mr-1"></i>
                                        Esta cita ya no puede modificarse ni eliminarse.
                                    </p>
                                @endif
                            @endunless
                        </div>

                        <div class="flex items-center space-x-2">
                            {{-- Botones solo para admin --}}
                            @if($esAdmin)
                                <a href="{{ route('citas.edit', $cita) }}"
                                   class="text-yellow-600 hover:text-yellow-800"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('citas.destroy', $cita) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('¿Eliminar esta cita?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                {{-- Feligres: solo puede eliminar si está pendiente --}}
                                @if($cita->estado === 'pendiente')
                                    <form action="{{ route('citas.destroy', $cita) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('¿Eliminar esta cita pendiente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800"
                                                title="Eliminar cita pendiente">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar-alt text-4xl mb-2 opacity-50"></i>
                    <p>{{ $esAdmin ? 'No hay citas registradas' : 'No tienes citas registradas' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $citas->links() }}
        </div>
    </div>
</div>
@endsection
