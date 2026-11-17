@extends('layouts.app')

@section('title', 'Peticiones')

@section('content')
@php
    $esAdmin = $esAdmin ?? Auth::user()->esAdministrador();
@endphp

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">
                    <i class="fas fa-hands-praying text-blue-600"></i>
                    {{ $esAdmin ? 'Peticiones' : 'Mis Peticiones' }}
                </h1>

                <p class="text-gray-600 text-base mt-1">
                    {{ $esAdmin
                        ? 'Gestione las peticiones registradas por los feligreses'
                        : 'Consulta y registra tus peticiones personales' }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('peticiones.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-base transition flex items-center gap-2 font-medium">
                    <i class="fas fa-plus"></i>
                    {{ $esAdmin ? 'Crear Nueva Petición' : 'Enviar Nueva Petición' }}
                </a>
            </div>
        </div>

        <!-- Mensajes -->
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

        <!-- Filtros -->
        <form method="GET" action="{{ route('peticiones.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">
                        <i class="fas fa-search text-gray-500 mr-1"></i> Buscar
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Título, descripción o feligrés..."
                        class="w-full rounded-lg border-gray-300 text-base py-2 px-3"
                    >
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">
                        <i class="fas fa-tag text-gray-500 mr-1"></i> Categoría
                    </label>

                    <select name="categoria_peticion_id" class="w-full rounded-lg border-gray-300 text-base py-2 px-3">
                        <option value="">-- Todas --</option>

                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria_peticion_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-1">
                        <i class="fas fa-info-circle text-gray-500 mr-1"></i> Estado
                    </label>

                    <select name="estado" class="w-full rounded-lg border-gray-300 text-base py-2 px-3">
                        <option value="">-- Todos --</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                            Pendiente
                        </option>
                        <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>
                            Aprobada
                        </option>
                        <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>
                            Completada
                        </option>
                        <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>
                            Rechazada
                        </option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-base transition w-full flex items-center justify-center gap-2 font-medium"
                    >
                        <i class="fas fa-filter"></i>
                        Aplicar Filtros
                    </button>
                </div>

            </div>

            @if(request()->hasAny(['search', 'estado', 'categoria_peticion_id']))
                <div class="mt-3 text-right">
                    <a href="{{ route('peticiones.index') }}"
                       class="text-base text-gray-600 hover:text-gray-800 underline">
                        <i class="fas fa-eraser"></i>
                        Limpiar filtros
                    </a>
                </div>
            @endif
        </form>

        <!-- Contador -->
        <div class="mb-3 text-base text-gray-600">
            <i class="fas fa-list text-gray-500 mr-1"></i>
            Mostrando {{ $items->firstItem() ?? 0 }} a {{ $items->lastItem() ?? 0 }} de {{ $items->total() }} peticiones
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        @if($esAdmin)
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                                Feligrés
                            </th>
                        @endif

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Categoría
                        </th>

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Título
                        </th>

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Descripción
                        </th>

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Fecha
                        </th>

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Sacerdote
                        </th>

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Estado
                        </th>

                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($items as $item)
                        @php
                            $puedeEliminarComoFeligres = !$esAdmin && $item->estado === 'pendiente';
                        @endphp

                        <tr class="hover:bg-gray-50">
                            @if($esAdmin)
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 text-base">
                                        <i class="fas fa-user text-gray-400 mr-1"></i>
                                        {{ $item->feligres->nombre_completo ?? 'Sin feligrés' }}
                                    </div>
                                </td>
                            @endif

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 text-sm rounded-full bg-indigo-100 text-indigo-800 font-medium">
                                    {{ $item->categoria->nombre ?? 'Sin categoría' }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="text-base font-medium text-gray-800">
                                    {{ $item->titulo }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="text-base text-gray-700 max-w-xs truncate" title="{{ $item->descripcion }}">
                                    {{ $item->descripcion }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-base text-gray-700">
                                <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                {{ $item->fecha ? $item->fecha->format('d/m/Y') : 'Sin fecha' }}
                            </td>

                            <td class="px-4 py-3 text-base text-gray-700">
                                {{ $item->sacerdote ? $item->sacerdote->nombre_completo : 'No asignado' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 text-sm rounded-full {{ $item->estado_badge }} font-medium">
                                    {{ ucfirst($item->estado) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($esAdmin)
                                        <a href="{{ route('peticiones.edit', $item->id) }}"
                                           class="text-yellow-700 hover:text-yellow-900 hover:bg-yellow-50 flex items-center gap-1 px-2 py-1 rounded transition text-base font-medium"
                                           title="Editar esta petición">
                                            <i class="fas fa-edit"></i>
                                            <span>Editar</span>
                                        </a>

                                        <form
                                            action="{{ route('peticiones.destroy', $item->id) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('¿Estás seguro de eliminar esta petición?\n\nTítulo: {{ addslashes($item->titulo) }}\nFecha: {{ $item->fecha ? $item->fecha->format('d/m/Y') : 'Sin fecha' }}\n\nEsta acción no se puede deshacer.');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-700 hover:text-red-900 hover:bg-red-50 flex items-center gap-1 px-2 py-1 rounded transition text-base font-medium"
                                                title="Eliminar esta petición"
                                            >
                                                <i class="fas fa-trash"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </form>
                                    @else
                                        @if($puedeEliminarComoFeligres)
                                            <form
                                                action="{{ route('peticiones.destroy', $item->id) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta petición pendiente?\n\nTítulo: {{ addslashes($item->titulo) }}\n\nEsta acción no se puede deshacer.');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-red-700 hover:text-red-900 hover:bg-red-50 flex items-center gap-1 px-2 py-1 rounded transition text-base font-medium"
                                                    title="Eliminar esta petición pendiente"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                    <span>Eliminar</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 flex items-center gap-1 text-base" title="Este registro no puede modificarse">
                                                <i class="fas fa-lock"></i>
                                                <span>Bloqueada</span>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $esAdmin ? 8 : 7 }}" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-3 opacity-50"></i>
                                <p class="text-lg">
                                    {{ $esAdmin
                                        ? 'No hay peticiones registradas'
                                        : 'No tienes peticiones registradas' }}
                                </p>
                                <p class="text-base text-gray-400 mt-1">
                                    Cuando registres una petición aparecerá aquí.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection