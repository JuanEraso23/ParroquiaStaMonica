@extends('layouts.app')

@section('title', 'Nueva Petición')

@section('content')
@php
    $usuario = Auth::user();
    $esAdmin = $usuario->esAdministrador();
@endphp

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-plus-circle text-blue-600"></i>
                Nueva Petición
            </h1>

            <p class="text-gray-600 text-base mb-6">
                Complete el formulario para registrar una nueva petición.
            </p>

            <form action="{{ route('peticiones.store') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="feligres_id" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-user text-gray-500 mr-1"></i> Feligrés *
                        </label>

                        <select
                            name="feligres_id"
                            id="feligres_id"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('feligres_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">-- Seleccione un feligrés --</option>

                            @foreach($feligreses as $feligres)
                                <option value="{{ $feligres->id }}" {{ old('feligres_id') == $feligres->id ? 'selected' : '' }}>
                                    {{ $feligres->nombre_completo }} - {{ $feligres->documento }}
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Feligrés que registra la petición.
                        </p>

                        @error('feligres_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="sacerdote_id" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-user-tie text-gray-500 mr-1"></i> Sacerdote (opcional)
                        </label>

                        <select
                            name="sacerdote_id"
                            id="sacerdote_id"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('sacerdote_id') border-red-500 @enderror"
                        >
                            <option value="">-- Sin asignar --</option>

                            @foreach($sacerdotes as $sacerdote)
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id') == $sacerdote->id ? 'selected' : '' }}>
                                    {{ $sacerdote->nombre_completo }} ({{ $sacerdote->cargo ?? $sacerdote->rol_texto }})
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Puedes dejar este campo vacío y la secretaría lo asignará después.
                        </p>

                        @error('sacerdote_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="categoria_peticion_id" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag text-gray-500 mr-1"></i> Categoría *
                        </label>

                        <select
                            name="categoria_peticion_id"
                            id="categoria_peticion_id"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('categoria_peticion_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">-- Seleccione una categoría --</option>

                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_peticion_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Elige la categoría que mejor describa tu petición.
                        </p>

                        @error('categoria_peticion_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="titulo" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-heading text-gray-500 mr-1"></i> Título *
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            id="titulo"
                            value="{{ old('titulo') }}"
                            placeholder="Ej: Oración por la salud de mi familia..."
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('titulo') border-red-500 @enderror"
                            required
                        >

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Un título corto que resuma tu petición.
                        </p>

                        @error('titulo')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcion" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-comment text-gray-500 mr-1"></i> Descripción *
                        </label>

                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="4"
                            placeholder="Describe tu petición con el mayor detalle posible..."
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('descripcion') border-red-500 @enderror"
                            required
                        >{{ old('descripcion') }}</textarea>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                            Sé específico para que podamos atender tu petición de la mejor manera. Máximo 500 caracteres.
                        </p>

                        @error('descripcion')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar text-gray-500 mr-1"></i> Fecha *
                        </label>

                        <input
                            type="date"
                            name="fecha"
                            id="fecha"
                            value="{{ old('fecha', now()->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('fecha') border-red-500 @enderror"
                            required
                        >

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Fecha en que se registra la petición.
                        </p>

                        @error('fecha')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-4 border-t">
                    <a
                        href="{{ route('peticiones.index') }}"
                        class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-base font-medium flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-base font-medium flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-save"></i>
                        Guardar Petición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection