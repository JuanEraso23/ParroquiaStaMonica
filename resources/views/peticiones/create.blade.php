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
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-plus-circle"></i>
                Nueva Petición
            </h1>

            <p class="text-gray-500 text-sm mb-6">
                Complete el formulario para registrar una nueva petición.
            </p>

            <form action="{{ route('peticiones.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Feligrés: visible para todos (comportamiento original) --}}
                    <div>
                        <label for="feligres_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Feligrés *
                        </label>
                        <select
                            name="feligres_id"
                            id="feligres_id"
                            class="w-full rounded-lg border-gray-300 @error('feligres_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Seleccione un feligrés</option>
                            @foreach($feligreses as $feligres)
                                <option value="{{ $feligres->id }}" {{ old('feligres_id') == $feligres->id ? 'selected' : '' }}>
                                    {{ $feligres->nombre_completo }} - {{ $feligres->documento }}
                                </option>
                            @endforeach
                        </select>
                        @error('feligres_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sacerdote --}}
                    <div>
                        <label for="sacerdote_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Sacerdote
                        </label>
                        <select
                            name="sacerdote_id"
                            id="sacerdote_id"
                            class="w-full rounded-lg border-gray-300 @error('sacerdote_id') border-red-500 @enderror"
                        >
                            <option value="">Seleccione un sacerdote (opcional)</option>
                            @foreach($sacerdotes as $sacerdote)
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id') == $sacerdote->id ? 'selected' : '' }}>
                                    {{ $sacerdote->nombre_completo }} ({{ $sacerdote->cargo ?? $sacerdote->rol_texto }})
                                </option>
                            @endforeach
                        </select>
                        @error('sacerdote_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Título --}}
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">
                            Título *
                        </label>
                        <input
                            type="text"
                            name="titulo"
                            id="titulo"
                            value="{{ old('titulo') }}"
                            class="w-full rounded-lg border-gray-300 @error('titulo') border-red-500 @enderror"
                            required
                        >
                        @error('titulo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción *
                        </label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="4"
                            class="w-full rounded-lg border-gray-300 @error('descripcion') border-red-500 @enderror"
                            required
                        >{{ old('descripcion') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha *
                        </label>
                        <input
                            type="date"
                            name="fecha"
                            id="fecha"
                            value="{{ old('fecha') }}"
                            class="w-full rounded-lg border-gray-300 @error('fecha') border-red-500 @enderror"
                            required
                        >
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a
                        href="{{ route('peticiones_intenciones.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
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