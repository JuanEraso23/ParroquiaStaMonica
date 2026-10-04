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
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-plus-circle text-purple-600"></i>
                {{ $esAdmin ? 'Nueva Cita' : 'Solicitar Cita' }}
            </h1>

            <p class="text-gray-600 text-base mb-6">
                {{ $esAdmin
                    ? 'Complete el formulario para agendar una nueva cita.'
                    : 'Complete el formulario para solicitar una nueva cita. La solicitud quedará registrada en estado pendiente.' }}
            </p>

            <form action="{{ route('citas.store') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    {{-- Campo FELIGRÉS: SOLO ADMIN --}}
                    @if($esAdmin)
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
                                Seleccione el feligrés para quien se agenda la cita.
                            </p>
                            @error('feligres_id')
                                <p class="text-red-600 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    @else
                        {{-- Mensaje informativo para feligrés --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-base text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                La cita será registrada automáticamente a tu nombre y quedará en estado <strong>pendiente</strong> hasta su revisión.
                            </p>
                        </div>
                    @endif

                    {{-- Sacerdote --}}
                    <div>
                        <label for="sacerdote_id" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-user-tie text-gray-500 mr-1"></i> Sacerdote *
                        </label>
                        <select
                            name="sacerdote_id"
                            id="sacerdote_id"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('sacerdote_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">-- Seleccione un sacerdote --</option>
                            @foreach($sacerdotes as $sacerdote)
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id') == $sacerdote->id ? 'selected' : '' }}>
                                    {{ $sacerdote->nombre_completo }} ({{ $sacerdote->cargo ?? $sacerdote->rol_texto }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Elige con qué sacerdote deseas agendar tu cita.
                        </p>
                        @error('sacerdote_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Fecha y hora --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fecha" class="block text-base font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar text-gray-500 mr-1"></i> Fecha *
                            </label>
                            <input
                                type="date"
                                name="fecha"
                                id="fecha"
                                value="{{ old('fecha') }}"
                                class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('fecha') border-red-500 @enderror"
                                required
                            >
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Selecciona el día de tu cita.
                            </p>
                            @error('fecha')
                                <p class="text-red-600 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="hora" class="block text-base font-medium text-gray-700 mb-1">
                                <i class="fas fa-clock text-gray-500 mr-1"></i> Hora *
                            </label>
                            <input
                                type="time"
                                name="hora"
                                id="hora"
                                value="{{ old('hora') }}"
                                class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('hora') border-red-500 @enderror"
                                required
                            >
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Horario disponible: 8:00 AM a 9:00 AM.
                            </p>
                            @error('hora')
                                <p class="text-red-600 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label for="tipo" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag text-gray-500 mr-1"></i> Tipo de Cita *
                        </label>
                        <select
                            name="tipo"
                            id="tipo"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('tipo') border-red-500 @enderror"
                            required
                        >
                            <option value="">-- Seleccione un tipo --</option>
                            <option value="confesion" {{ old('tipo') == 'confesion' ? 'selected' : '' }}>Confesión</option>
                            <option value="bautismo" {{ old('tipo') == 'bautismo' ? 'selected' : '' }}>Bautismo</option>
                            <option value="matrimonio" {{ old('tipo') == 'matrimonio' ? 'selected' : '' }}>Matrimonio</option>
                            <option value="orientacion" {{ old('tipo') == 'orientacion' ? 'selected' : '' }}>Orientación</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Elige el motivo de tu cita (bautizo, confesión, etc.).
                        </p>
                        @error('tipo')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-comment text-gray-500 mr-1"></i> Descripción
                        </label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="3"
                            placeholder="Ej: Deseo bautizar a mi hijo, preferiblemente en la misa del domingo..."
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('descripcion') border-red-500 @enderror"
                        >{{ old('descripcion') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                            Información adicional sobre la cita (opcional). Sé específico para que el sacerdote pueda prepararse mejor.
                        </p>
                        @error('descripcion')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Notas internas: SOLO ADMIN --}}
                    @if($esAdmin)
                        <div>
                            <label for="notas_internas" class="block text-base font-medium text-gray-700 mb-1">
                                <i class="fas fa-lock text-gray-500 mr-1"></i> Notas Internas
                            </label>
                            <textarea
                                name="notas_internas"
                                id="notas_internas"
                                rows="2"
                                placeholder="Observaciones solo visibles para administradores..."
                                class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('notas_internas') border-red-500 @enderror"
                            >{{ old('notas_internas') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-eye-slash text-gray-400 mr-1"></i>
                                Visible solo para administradores (opcional).
                            </p>
                            @error('notas_internas')
                                <p class="text-red-600 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-4 border-t">
                    <a
                        href="{{ route('citas.index') }}"
                        class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-base font-medium flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-base font-medium flex items-center justify-center gap-2"
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