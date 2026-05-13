@extends('layouts.app')

@section('title', 'Editar Petición')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-edit"></i> Editar Petición
            </h1>

            <p class="text-gray-500 text-sm mb-6">
                Modifique los datos de la petición, gestione su estado y registre una respuesta si es necesario.
            </p>

            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Esta vista es exclusiva para personal administrativo.
                </p>
            </div>

            <form action="{{ route('peticiones.update', $peticione) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Feligrés --}}
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
                                <option value="{{ $feligres->id }}" {{ old('feligres_id', $peticione->feligres_id) == $feligres->id ? 'selected' : '' }}>
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
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id', $peticione->sacerdote_id) == $sacerdote->id ? 'selected' : '' }}>
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
                            value="{{ old('titulo', $peticione->titulo) }}"
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
                        >{{ old('descripcion', $peticione->descripcion) }}</textarea>
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
                            value="{{ old('fecha', $peticione->fecha->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 @error('fecha') border-red-500 @enderror"
                            required
                        >
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">
                            Estado *
                        </label>
                        <select
                            name="estado"
                            id="estado"
                            class="w-full rounded-lg border-gray-300 @error('estado') border-red-500 @enderror"
                            required
                        >
                            <option value="pendiente" {{ old('estado', $peticione->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="aprobada" {{ old('estado', $peticione->estado) == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                            <option value="completada" {{ old('estado', $peticione->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="rechazada" {{ old('estado', $peticione->estado) == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Respuesta --}}
                    <div>
                        <label for="respuesta" class="block text-sm font-medium text-gray-700 mb-1">
                            Respuesta
                        </label>
                        <textarea
                            name="respuesta"
                            id="respuesta"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 @error('respuesta') border-red-500 @enderror"
                        >{{ old('respuesta', $peticione->respuesta) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Respuesta del sacerdote o del personal administrativo (opcional).
                        </p>
                        @error('respuesta')
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
                        <i class="fas fa-save"></i> Actualizar Petición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection