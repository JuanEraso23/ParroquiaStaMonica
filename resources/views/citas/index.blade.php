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
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">
                    <i class="fas fa-calendar-check text-purple-600"></i>
                    {{ $esAdmin ? 'Gestión de Citas' : 'Mis Citas' }}
                </h1>
                <p class="text-gray-600 text-base mt-1">
                    {{ $esAdmin ? 'Administración de las citas de la parroquia' : 'Consulta y gestión de tus citas registradas en la parroquia' }}
                </p>
            </div>

            <a href="{{ route('citas.create') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-lg text-base transition flex items-center gap-2 font-medium">
                <i class="fas fa-plus"></i>
                {{ $esAdmin ? 'Crear Nueva Cita' : 'Solicitar Nueva Cita' }}
            </a>
        </div>

        <!-- Mensajes flash -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded text-base">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded text-base">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Horario de Atención -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-100">
            <h3 class="font-semibold text-gray-800 mb-2 text-lg">🕘 Horario de Atención para Citas</h3>
            <p class="text-base text-gray-700 mb-3">
                Ambos sacerdotes están disponibles para gestionar las citas según el horario establecido.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-3">
                    <p class="font-semibold text-gray-800 text-base">Padre Edison</p>
                    <p class="text-base text-gray-600">Párroco • Disponible 8:00 - 9:00 AM</p>
                </div>
                <div class="bg-white rounded-lg p-3">
                    <p class="font-semibold text-gray-800 text-base">Padre Felipe</p>
                    <p class="text-base text-gray-600">Vicario • Disponible 8:00 - 9:00 AM</p>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <form method="GET" action="{{ route('citas.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">
                        <i class="fas fa-search text-gray-500 mr-1"></i> Buscar
                    </label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ $esAdmin ? 'Nombre, teléfono, tipo...' : 'Tipo, descripción o sacerdote...' }}"
                           class="w-full rounded-lg border-gray-300 text-base py-2 px-3">
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">
                        <i class="fas fa-user-tie text-gray-500 mr-1"></i> Sacerdote
                    </label>
                    <select name="sacerdote_id" class="w-full rounded-lg border-gray-300 text-base py-2 px-3">
                        <option value="">-- Todos --</option>
                        @foreach($sacerdotes as $sacerdote)
                            <option value="{{ $sacerdote->id }}" {{ request('sacerdote_id') == $sacerdote->id ? 'selected' : '' }}>
                                {{ $sacerdote->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar text-gray-500 mr-1"></i> Fecha
                    </label>
                    <input type="date"
                           name="fecha"
                           value="{{ request('fecha') }}"
                           class="w-full rounded-lg border-gray-300 text-base py-2 px-3">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-base transition w-full flex items-center justify-center gap-2 font-medium">
                        <i class="fas fa-filter"></i> Aplicar Filtros
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['search', 'sacerdote_id', 'fecha']))
                <div class="mt-3 text-right">
                    <a href="{{ route('citas.index') }}"
                       class="text-base text-gray-600 hover:text-gray-800 underline">
                        <i class="fas fa-eraser"></i> Limpiar filtros
                    </a>
                </div>
            @endif
        </form>

        <!-- Listado de citas -->
        <div class="space-y-6">
            @forelse($citas as $cita)
                <div class="border rounded-lg p-4 bg-white hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-calendar-day text-purple-600 mr-1"></i>
                                    {{ $cita->fecha->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}
                                </span>
                                <span class="px-3 py-1 text-sm rounded-full {{ $cita->estado_badge }} font-medium">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </div>

                            @if($esAdmin)
                                <p class="font-medium text-gray-900 text-base">
                                    <i class="fas fa-user text-gray-500 mr-1"></i>
                                    {{ $cita->feligres->nombre_completo }}
                                </p>
                                <p class="text-base text-gray-600">
                                    <i class="fas fa-phone text-gray-500 mr-1"></i> {{ $cita->feligres->telefono }}
                                </p>
                            @endif

                            <p class="text-base text-gray-700 mt-1">
                                <i class="fas fa-user-tie text-gray-500 mr-1"></i> {{ $cita->sacerdote->nombre_completo }}
                            </p>

                            <p class="text-base text-gray-700 mt-2">
                                <span class="font-medium">{{ $cita->tipo_texto }}</span>
                                @if($cita->descripcion)
                                    <br><span class="text-sm text-gray-500">{{ $cita->descripcion }}</span>
                                @endif
                            </p>

                            @unless($esAdmin)
                                @if($cita->estado === 'pendiente')
                                    <p class="text-sm text-yellow-700 mt-2 bg-yellow-50 p-2 rounded border-l-4 border-yellow-400">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Nota:</strong> Puedes eliminar esta cita si deseas corregirla o registrarla nuevamente.
                                    </p>
                                @elseif(in_array($cita->estado, ['confirmada', 'completada', 'cancelada']))
                                    <p class="text-sm text-gray-500 mt-2 bg-gray-50 p-2 rounded border-l-4 border-gray-300">
                                        <i class="fas fa-lock mr-1"></i>
                                        <strong>Nota:</strong> Esta cita ya no puede modificarse ni eliminarse. Contacta a la secretaría si necesitas cambios.
                                    </p>
                                @endif
                            @endunless
                        </div>

                        <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                            @if($esAdmin)
                                <a href="{{ route('citas.edit', $cita) }}"
                                   class="text-yellow-700 hover:text-yellow-900 hover:bg-yellow-50 flex items-center gap-1 px-3 py-2 rounded transition text-base font-medium"
                                   title="Editar esta cita">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>

                                <form action="{{ route('citas.destroy', $cita) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta cita?\n\nFecha: {{ $cita->fecha->format('d/m/Y') }}\nHora: {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}\n\nEsta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-700 hover:text-red-900 hover:bg-red-50 flex items-center gap-1 px-3 py-2 rounded transition text-base font-medium"
                                            title="Eliminar esta cita">
                                        <i class="fas fa-trash"></i>
                                        <span>Eliminar</span>
                                    </button>
                                </form>
                            @else
                                @if($cita->estado === 'pendiente')
                                    <form action="{{ route('citas.destroy', $cita) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta cita pendiente?\n\nFecha: {{ $cita->fecha->format('d/m/Y') }}\nHora: {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}\n\nEsta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-700 hover:text-red-900 hover:bg-red-50 flex items-center gap-1 px-3 py-2 rounded transition text-base font-medium"
                                                title="Eliminar esta cita pendiente">
                                            <i class="fas fa-trash"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar-alt text-5xl mb-3 opacity-50"></i>
                    <p class="text-lg">{{ $esAdmin ? 'No hay citas registradas' : 'No tienes citas registradas' }}</p>
                    <p class="text-base text-gray-400 mt-1">Cuando registres una cita aparecerá aquí.</p>
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