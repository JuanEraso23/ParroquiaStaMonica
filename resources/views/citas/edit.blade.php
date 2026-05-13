@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-edit"></i> Editar Cita
            </h1>

            <p class="text-gray-500 text-sm mb-6">
                Modifique los datos de la cita, gestione su estado y registre observaciones internas si es necesario.
            </p>

            <!-- Mensaje informativo -->
            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Esta vista es exclusiva para personal administrativo. Desde aquí puede corregir la información registrada,
                    reasignar sacerdote, actualizar el estado de la cita y agregar notas internas.
                </p>
            </div>

            <form action="{{ route('citas.update', $cita) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Feligrés -->
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
                                <option value="{{ $feligres->id }}" {{ old('feligres_id', $cita->feligres_id) == $feligres->id ? 'selected' : '' }}>
                                    {{ $feligres->nombre_completo }} - {{ $feligres->documento }}
                                </option>
                            @endforeach
                        </select>
                        @error('feligres_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sacerdote -->
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
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id', $cita->sacerdote_id) == $sacerdote->id ? 'selected' : '' }}>
                                    {{ $sacerdote->nombre_completo }} ({{ $sacerdote->cargo ?? $sacerdote->rol_texto }})
                                </option>
                            @endforeach
                        </select>
                        @error('sacerdote_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha y hora -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha *
                            </label>
                            <input
                                type="date"
                                name="fecha"
                                id="fecha"
                                value="{{ old('fecha', $cita->fecha->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 @error('fecha') border-red-500 @enderror"
                                required
                            >
                            @error('fecha')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">
                                Hora *
                            </label>
                            <input
                                type="time"
                                name="hora"
                                id="hora"
                                value="{{ old('hora', \Carbon\Carbon::parse($cita->hora)->format('H:i')) }}"
                                class="w-full rounded-lg border-gray-300 @error('hora') border-red-500 @enderror"
                                required
                            >
                            @error('hora')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tipo -->
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
                            <option value="confesion" {{ old('tipo', $cita->tipo) == 'confesion' ? 'selected' : '' }}>
                                Confesión
                            </option>
                            <option value="bautismo" {{ old('tipo', $cita->tipo) == 'bautismo' ? 'selected' : '' }}>
                                Bautismo
                            </option>
                            <option value="matrimonio" {{ old('tipo', $cita->tipo) == 'matrimonio' ? 'selected' : '' }}>
                                Matrimonio
                            </option>
                            <option value="orientacion" {{ old('tipo', $cita->tipo) == 'orientacion' ? 'selected' : '' }}>
                                Orientación
                            </option>
                        </select>
                        @error('tipo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
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
                            <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>
                            <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>
                                Confirmada
                            </option>
                            <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>
                                Cancelada
                            </option>
                            <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>
                                Completada
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Use el estado para reflejar el seguimiento real de la cita.
                        </p>
                        @error('estado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 @error('descripcion') border-red-500 @enderror"
                        >{{ old('descripcion', $cita->descripcion) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Información adicional visible en el registro general de la cita.
                        </p>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notas internas -->
                    <div>
                        <label for="notas_internas" class="block text-sm font-medium text-gray-700 mb-1">
                            Notas Internas
                        </label>
                        <textarea
                            name="notas_internas"
                            id="notas_internas"
                            rows="2"
                            class="w-full rounded-lg border-gray-300 @error('notas_internas') border-red-500 @enderror"
                        >{{ old('notas_internas', $cita->notas_internas) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Visible solo para administradores. Úselo para observaciones o seguimiento interno.
                        </p>
                        @error('notas_internas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
                        <i class="fas fa-save"></i> Actualizar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection