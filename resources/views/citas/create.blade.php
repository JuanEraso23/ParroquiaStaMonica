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

                <div class="space-y-4">
                    {{-- Aviso: agenda cerrada --}}
                    @if(!($agendaAbierta ?? true))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-700">
                                <i class="fas fa-lock mr-1"></i>
                                La agenda del sacerdote seleccionado está <strong>cerrada</strong> para la fecha indicada.
                                No es posible registrar nuevas citas. Contacte a la secretaría parroquial para solicitar la apertura.
                            </p>
                        </div>
                    @endif

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
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id', $sacerdoteSeleccionado ?? '') == $sacerdote->id ? 'selected' : '' }}>
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
                                value="{{ old('fecha', $fechaSeleccionada ?? '') }}"
                                class="w-full rounded-lg border-gray-300 @error('fecha') border-red-500 @enderror"
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

                            <option value="10" {{ old('duracion_minutos', $duracionSeleccionada ?? '') == '10' ? 'selected' : '' }}>
                                10 minutos - Confesión
                            </option>

                            <option value="15" {{ old('duracion_minutos', $duracionSeleccionada ?? '') == '15' ? 'selected' : '' }}>
                                15 minutos - Cita breve
                            </option>

                            <option value="20" {{ old('duracion_minutos', $duracionSeleccionada ?? '') == '20' ? 'selected' : '' }}>
                                20 minutos - Cita normal
                            </option>

                            <option value="30" {{ old('duracion_minutos', $duracionSeleccionada ?? '') == '30' ? 'selected' : '' }}>
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

                    {{-- Horarios sugeridos (solo si la agenda está abierta) --}}
                    @if(($agendaAbierta ?? false) && !empty($horarios))
                        <div class="mt-6 pt-4 border-t">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock mr-1"></i>
                                Horarios disponibles
                            </h3>

                            <p class="text-xs text-gray-500 mb-3">
                                Disponibilidad calculada para citas de {{ $duracionSeleccionada ?? 20 }} minutos.
                                Los horarios ocupados aparecen en rojo.
                            </p>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($horarios as $h)
                                    <button
                                        type="button"
                                        data-hora="{{ \Carbon\Carbon::parse($h['hora_inicio'])->format('H:i') }}"
                                        class="slot-horario px-3 py-2 rounded-lg text-xs text-center transition
                                            {{ $h['ocupado']
                                                ? 'bg-red-100 text-red-600 cursor-not-allowed opacity-70'
                                                : 'bg-green-100 text-green-700 hover:bg-green-200 cursor-pointer' }}"
                                        {{ $h['ocupado'] ? 'disabled' : '' }}
                                    >
                                        <div class="font-semibold">
                                            {{ \Carbon\Carbon::parse($h['hora_inicio'])->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($h['hora_fin'])->format('g:i A') }}
                                        </div>

                                        <div>
                                            {{ $h['ocupado'] ? 'Ocupado' : 'Disponible' }}
                                        </div>
                                        @if($h['ocupado'] && $esAdmin && !empty($h['feligres_nombre']))
                                            <div class="mt-1 text-[11px] font-medium">
                                                {{ $h['feligres_nombre'] }}
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const botonesHorario = document.querySelectorAll('.slot-horario');
        const inputHora = document.getElementById('hora');
        const selectSacerdote = document.getElementById('sacerdote_id');
        const inputFecha = document.getElementById('fecha');
        const selectDuracion = document.getElementById('duracion_minutos');

        botonesHorario.forEach(function (boton) {
            boton.addEventListener('click', function () {
                if (boton.disabled) {
                    return;
                }

                const hora = boton.getAttribute('data-hora');

                if (inputHora) {
                    inputHora.value = hora;
                }

                botonesHorario.forEach(function (b) {
                    b.classList.remove('ring-2', 'ring-green-500', 'bg-green-200');
                });

                boton.classList.add('ring-2', 'ring-green-500', 'bg-green-200');
            });
        });

        function actualizarHorarios() {
            const sacerdoteId = selectSacerdote ? selectSacerdote.value : '';
            const fecha = inputFecha ? inputFecha.value : '';
            const duracion = selectDuracion ? selectDuracion.value : '';

            if (sacerdoteId && fecha && duracion) {
                const url = "{{ route('citas.create') }}"
                    + "?sacerdote_id=" + encodeURIComponent(sacerdoteId)
                    + "&fecha=" + encodeURIComponent(fecha)
                    + "&duracion_minutos=" + encodeURIComponent(duracion);

                window.location.href = url;
            }
        }

        if (selectSacerdote) {
            selectSacerdote.addEventListener('change', actualizarHorarios);
        }

        if (inputFecha) {
            inputFecha.addEventListener('change', actualizarHorarios);
        }

        if (selectDuracion) {
            selectDuracion.addEventListener('change', actualizarHorarios);
        }
    });
</script>
@endsection
