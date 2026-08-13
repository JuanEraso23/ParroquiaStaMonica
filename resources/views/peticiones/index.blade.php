@extends('layouts.app')

@section('title', 'Peticiones')

@section('content')
@php
    $esAdmin = $esAdmin ?? Auth::user()->esAdministrador();
@endphp

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ $esAdmin ? 'Peticiones' : 'Mis Peticiones' }}
                </h1>

                <p class="text-gray-500 text-sm mt-1">
                    {{ $esAdmin
                        ? 'Gestione las peticiones registradas por los feligreses'
                        : 'Consulta y registra tus peticiones personales' }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('peticiones.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-plus"></i>
                    {{ $esAdmin ? 'Nueva Petición' : 'Crear Petición' }}
                </a>
            </div>
        </div>

        <!-- Mensajes -->
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

        <!-- Filtros -->
        <form method="GET" action="{{ route('peticiones.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Buscar
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por título, descripción o feligrés..."
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Categoría
                    </label>

                    <select name="categoria_peticion_id" class="w-full rounded-lg border-gray-300">
                        <option value="">Todas</option>

                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria_peticion_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>

                    <select name="estado" class="w-full rounded-lg border-gray-300">
                        <option value="">Todos</option>
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
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition w-full"
                    >
                        <i class="fas fa-search"></i>
                        Filtrar
                    </button>
                </div>

            </div>

            @if(request()->hasAny(['search', 'estado', 'categoria_peticion_id']))
                <div class="mt-3 text-right">
                    <a href="{{ route('peticiones.index') }}"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-eraser"></i>
                        Limpiar filtros
                    </a>
                </div>
            @endif
        </form>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <div class="mb-3 text-sm text-gray-500">
                Mostrando {{ $items->firstItem() ?? 0 }} a {{ $items->lastItem() ?? 0 }} de {{ $items->total() }} peticiones
            </div>

            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        @if($esAdmin)
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Feligrés
                            </th>
                        @endif

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Categoría
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Título
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Descripción
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Fecha
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Sacerdote
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Estado
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
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
                                    <div class="font-medium text-gray-900">
                                        {{ $item->feligres->nombre_completo ?? 'Sin feligrés' }}
                                    </div>
                                </td>
                            @endif

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                    {{ $item->categoria->nombre ?? 'Sin categoría' }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-700">
                                    {{ $item->titulo }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 max-w-xs truncate">
                                    {{ $item->descripcion }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $item->fecha ? $item->fecha->format('d/m/Y') : 'Sin fecha' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $item->sacerdote ? $item->sacerdote->nombre_completo : 'No asignado' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $item->estado_badge }}">
                                    {{ ucfirst($item->estado) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    @if($esAdmin)
                                        <a href="{{ route('peticiones.edit', $item->id) }}"
                                           class="text-yellow-600 hover:text-yellow-800"
                                           title="Editar petición">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form
                                            action="{{ route('peticiones.destroy', $item->id) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('¿Eliminar esta petición?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-600 hover:text-red-800"
                                                title="Eliminar petición"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        @if($puedeEliminarComoFeligres)
                                            <form
                                                action="{{ route('peticiones.destroy', $item->id) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Eliminar esta petición pendiente?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Eliminar petición pendiente"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400" title="Este registro no puede modificarse">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $esAdmin ? 8 : 7 }}" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 opacity-50"></i>
                                <p>
                                    {{ $esAdmin
                                        ? 'No hay peticiones registradas'
                                        : 'No tienes peticiones registradas' }}
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