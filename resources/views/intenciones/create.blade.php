@extends('layouts.app')

@section('title', 'Nueva Intención')

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
                {{ $esAdmin ? 'Nueva Intención de Misa' : 'Registrar Intención de Misa' }}
            </h1>

            <p class="text-gray-500 text-sm mb-6">
                {{ $esAdmin
                    ? 'Complete el formulario para registrar una nueva intención de misa.'
                    : 'Complete el formulario para registrar una intención de misa. La solicitud quedará en estado pendiente hasta su revisión.' }}
            </p>

            <form action="{{ route('intenciones.store') }}" method="POST">
                @csrf

                <div class="space-y-4">

                    {{-- Campo FELIGRÉS: SOLO ADMIN --}}
                    @if($esAdmin)
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
                    @else
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                La intención será registrada automáticamente a tu nombre y quedará en estado
                                <strong>pendiente</strong> hasta su revisión.
                            </p>
                        </div>
                    @endif

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

                    {{-- Fecha de registro --}}
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de Registro *
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

                    {{-- Nombre del difunto --}}
                    <div>
                        <label for="nombre_difunto" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre del Difunto
                        </label>
                        <input
                            type="text"
                            name="nombre_difunto"
                            id="nombre_difunto"
                            value="{{ old('nombre_difunto') }}"
                            class="w-full rounded-lg border-gray-300 @error('nombre_difunto') border-red-500 @enderror"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Si aplica, nombre de la persona fallecida.
                        </p>
                        @error('nombre_difunto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha de misa deseada --}}
                    <div>
                        <label for="fecha_misa" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de Misa Deseada
                        </label>
                        <input
                            type="date"
                            name="fecha_misa"
                            id="fecha_misa"
                            value="{{ old('fecha_misa') }}"
                            class="w-full rounded-lg border-gray-300 @error('fecha_misa') border-red-500 @enderror"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Fecha deseada para celebrar la misa.
                        </p>
                        @error('fecha_misa')
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
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition"
                    >
                        <i class="fas fa-save"></i>
                        {{ $esAdmin ? 'Guardar Intención' : 'Enviar Intención' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection