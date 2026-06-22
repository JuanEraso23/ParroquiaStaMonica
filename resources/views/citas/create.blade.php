@extends('layouts.app')

@section('title', 'Nueva Cita')

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
                {{ $esAdmin ? 'Nueva Cita' : 'Solicitar Cita' }}
            </h1>

            <p class="text-gray-500 text-sm mb-6">
                {{ $esAdmin
                    ? 'Complete el formulario para agendar una nueva cita.'
                    : 'Complete el formulario para solicitar una nueva cita. La solicitud quedará registrada en estado pendiente.' }}
            </p>

            <form action="{{ route('citas.store') }}" method="POST">
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
                        {{-- Mensaje informativo para feligrés --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                La cita será registrada automáticamente a tu nombre y quedará en estado <strong>pendiente</strong> hasta su revisión.
                            </p>
                        </div>
                    @endif

                    {{-- Sacerdote --}}
                    <div>
                        <label for="sacerdote_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Sacerdote *
                        </label>
                        <select
                            name="sacerdote_id"
                            id="sacerdote_id"
                            class="w-full rounded-lg border-gray-300 @error('sacerdote_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Seleccione un sacerdote</option>
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

                    {{-- Fecha y hora --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">
                                Hora de inicio *
                            </label>
                            <input
                                type="time"
                                name="hora"
                                id="hora"
                                value="{{ old('hora') }}"
                                class="w-full rounded-lg border-gray-300 @error('hora') border-red-500 @enderror"
                                required
                            >
                            @error('hora')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de Cita *
                        </label>
                        <select
                            name="tipo"
                            id="tipo"
                            class="w-full rounded-lg border-gray-300 @error('tipo') border-red-500 @enderror"
                            required
                        >
                            <option value="">Seleccione un tipo</option>
                            <option value="confesion" {{ old('tipo') == 'confesion' ? 'selected' : '' }}>Confesión</option>
                            <option value="bautismo" {{ old('tipo') == 'bautismo' ? 'selected' : '' }}>Bautismo</option>
                            <option value="matrimonio" {{ old('tipo') == 'matrimonio' ? 'selected' : '' }}>Matrimonio</option>
                            <option value="orientacion" {{ old('tipo') == 'orientacion' ? 'selected' : '' }}>Orientación</option>
                        </select>
                        @error('tipo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Duración --}}
                    <div>
                        <label for="duracion_minutos" class="block text-sm font-medium text-gray-700 mb-1">
                            Duración *
                        </label>

                        <select
                            name="duracion_minutos"
                            id="duracion_minutos"
                            class="w-full rounded-lg border-gray-300 @error('duracion_minutos') border-red-500 @enderror"
                            required
                        >
                            <option value="">Seleccione la duración</option>

                            <option value="10" {{ old('duracion_minutos') == '10' ? 'selected' : '' }}>
                                10 minutos - Confesión
                            </option>

                            <option value="15" {{ old('duracion_minutos') == '15' ? 'selected' : '' }}>
                                15 minutos - Cita breve
                            </option>

                            <option value="20" {{ old('duracion_minutos') == '20' ? 'selected' : '' }}>
                                20 minutos - Cita normal
                            </option>

                            <option value="30" {{ old('duracion_minutos') == '30' ? 'selected' : '' }}>
                                30 minutos - Cita extensa
                            </option>
                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            El sistema calculará automáticamente la hora de finalización.
                        </p>

                        @error('duracion_minutos')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 @error('descripcion') border-red-500 @enderror"
                        >{{ old('descripcion') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Información adicional sobre la cita (opcional).
                        </p>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notas internas: SOLO ADMIN --}}
                    @if($esAdmin)
                        <div>
                            <label for="notas_internas" class="block text-sm font-medium text-gray-700 mb-1">
                                Notas Internas
                            </label>
                            <textarea
                                name="notas_internas"
                                id="notas_internas"
                                rows="2"
                                class="w-full rounded-lg border-gray-300 @error('notas_internas') border-red-500 @enderror"
                            >{{ old('notas_internas') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Visible solo para administradores (opcional).
                            </p>
                            @error('notas_internas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a
                        href="{{ route('citas.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition"
                    >
                        <i class="fas fa-save"></i>
                        {{ $esAdmin ? 'Guardar Cita' : 'Enviar Solicitud' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
