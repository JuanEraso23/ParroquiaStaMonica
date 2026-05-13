@extends('layouts.app')

@section('title', 'Editar Intención')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-edit"></i> Editar Intención
            </h1>

            <p class="text-gray-500 text-sm mb-6">
                Modifique los datos de la intención, gestione su estado y registre una respuesta si es necesario.
            </p>

            <!-- Alerta informativa agregada -->
            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Esta vista es exclusiva para personal administrativo. Desde aquí puede corregir la información registrada,
                    asignar sacerdote, actualizar el estado de la intención y agregar una respuesta.
                </p>
            </div>

            <form action="{{ route('intenciones.update', $intencione) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="feligres_id" class="block text-sm font-medium text-gray-700 mb-1">Feligrés *</label>
                        <select name="feligres_id" id="feligres_id" class="w-full rounded-lg border-gray-300 @error('feligres_id') border-red-500 @enderror" required>
                            <option value="">Seleccione un feligrés</option>
                            @foreach($feligreses as $feligres)
                                <option value="{{ $feligres->id }}" {{ old('feligres_id', $intencione->feligres_id) == $feligres->id ? 'selected' : '' }}>
                                    {{ $feligres->nombre_completo }} - {{ $feligres->documento }}
                                </option>
                            @endforeach
                        </select>
                        @error('feligres_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sacerdote_id" class="block text-sm font-medium text-gray-700 mb-1">Sacerdote</label>
                        <select name="sacerdote_id" id="sacerdote_id" class="w-full rounded-lg border-gray-300 @error('sacerdote_id') border-red-500 @enderror">
                            <option value="">Seleccione un sacerdote (opcional)</option>
                            @foreach($sacerdotes as $sacerdote)
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id', $intencione->sacerdote_id) == $sacerdote->id ? 'selected' : '' }}>
                                    {{ $sacerdote->nombre_completo }} ({{ $sacerdote->cargo ?? $sacerdote->rol_texto }})
                                </option>
                            @endforeach
                        </select>
                        @error('sacerdote_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $intencione->titulo) }}" class="w-full rounded-lg border-gray-300 @error('titulo') border-red-500 @enderror" required>
                        @error('titulo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                        <textarea name="descripcion" id="descripcion" rows="4" class="w-full rounded-lg border-gray-300 @error('descripcion') border-red-500 @enderror" required>{{ old('descripcion', $intencione->descripcion) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Registro *</label>
                        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $intencione->fecha instanceof \Carbon\Carbon ? $intencione->fecha->format('Y-m-d') : $intencione->fecha) }}" class="w-full rounded-lg border-gray-300 @error('fecha') border-red-500 @enderror" required>
                        @error('fecha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nombre_difunto" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Difunto</label>
                        <input type="text" name="nombre_difunto" id="nombre_difunto" value="{{ old('nombre_difunto', $intencione->nombre_difunto) }}" class="w-full rounded-lg border-gray-300 @error('nombre_difunto') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Si aplica, nombre de la persona fallecida</p>
                        @error('nombre_difunto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_misa" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Misa Deseada</label>
                        <input type="date" name="fecha_misa" id="fecha_misa" value="{{ old('fecha_misa', $intencione->fecha_misa ? ($intencione->fecha_misa instanceof \Carbon\Carbon ? $intencione->fecha_misa->format('Y-m-d') : $intencione->fecha_misa) : '') }}" class="w-full rounded-lg border-gray-300 @error('fecha_misa') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Fecha deseada para celebrar la misa</p>
                        @error('fecha_misa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select name="estado" id="estado" class="w-full rounded-lg border-gray-300 @error('estado') border-red-500 @enderror" required>
                            <option value="pendiente" {{ old('estado', $intencione->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmada" {{ old('estado', $intencione->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="realizada" {{ old('estado', $intencione->estado) == 'realizada' ? 'selected' : '' }}>Realizada</option>
                            <option value="cancelada" {{ old('estado', $intencione->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="respuesta" class="block text-sm font-medium text-gray-700 mb-1">Respuesta</label>
                        <textarea name="respuesta" id="respuesta" rows="3" class="w-full rounded-lg border-gray-300 @error('respuesta') border-red-500 @enderror">{{ old('respuesta', $intencione->respuesta) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Respuesta del sacerdote (opcional)</p>
                        @error('respuesta')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a href="{{ route('peticiones_intenciones.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-save"></i> Actualizar Intención
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection