@extends('layouts.app')

@section('title', 'Editar Petición')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-edit text-yellow-600"></i>
                Editar Petición
            </h1>

            <p class="text-gray-600 text-base mb-6">
                Modifique los datos de la petición, gestione su categoría, estado y registre una respuesta si es necesario.
            </p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-base text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Nota:</strong> Esta vista es exclusiva para personal administrativo.
                </p>
            </div>

            <form action="{{ route('peticiones.update', $peticione) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    {{-- Feligrés --}}
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
                                <option value="{{ $feligres->id }}" {{ old('feligres_id', $peticione->feligres_id) == $feligres->id ? 'selected' : '' }}>
                                    {{ $feligres->nombre_completo }} - {{ $feligres->documento }}
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Feligrés que registró la petición.
                        </p>

                        @error('feligres_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Sacerdote --}}
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
                                <option value="{{ $sacerdote->id }}" {{ old('sacerdote_id', $peticione->sacerdote_id) == $sacerdote->id ? 'selected' : '' }}>
                                    {{ $sacerdote->nombre_completo }} ({{ $sacerdote->cargo ?? $sacerdote->rol_texto }})
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Puede reasignar la petición a otro sacerdote.
                        </p>

                        @error('sacerdote_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Categoría --}}
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
                                <option value="{{ $categoria->id }}" {{ old('categoria_peticion_id', $peticione->categoria_peticion_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Categoría que clasifica la petición.
                        </p>

                        @error('categoria_peticion_id')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Título --}}
                    <div>
                        <label for="titulo" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-heading text-gray-500 mr-1"></i> Título *
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            id="titulo"
                            value="{{ old('titulo', $peticione->titulo) }}"
                            placeholder="Ej: Oración por la salud de mi familia..."
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('titulo') border-red-500 @enderror"
                            required
                        >

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Título breve que resume la petición.
                        </p>

                        @error('titulo')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-comment text-gray-500 mr-1"></i> Descripción *
                        </label>

                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="4"
                            placeholder="Detalle completo de la petición..."
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('descripcion') border-red-500 @enderror"
                            required
                        >{{ old('descripcion', $peticione->descripcion) }}</textarea>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                            Máximo 500 caracteres.
                        </p>

                        @error('descripcion')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="fecha" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar text-gray-500 mr-1"></i> Fecha *
                        </label>

                        <input
                            type="date"
                            name="fecha"
                            id="fecha"
                            value="{{ old('fecha', $peticione->fecha ? $peticione->fecha->format('Y-m-d') : '') }}"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('fecha') border-red-500 @enderror"
                            required
                        >

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Fecha de registro de la petición.
                        </p>

                        @error('fecha')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-info-circle text-gray-500 mr-1"></i> Estado *
                        </label>

                        <select
                            name="estado"
                            id="estado"
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('estado') border-red-500 @enderror"
                            required
                        >
                            <option value="pendiente" {{ old('estado', $peticione->estado) == 'pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>

                            <option value="aprobada" {{ old('estado', $peticione->estado) == 'aprobada' ? 'selected' : '' }}>
                                Aprobada
                            </option>

                            <option value="completada" {{ old('estado', $peticione->estado) == 'completada' ? 'selected' : '' }}>
                                Completada
                            </option>

                            <option value="rechazada" {{ old('estado', $peticione->estado) == 'rechazada' ? 'selected' : '' }}>
                                Rechazada
                            </option>
                        </select>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Pendiente:</strong> sin revisar · <strong>Aprobada:</strong> aceptada · <strong>Completada:</strong> atendida · <strong>Rechazada:</strong> no procede.
                        </p>

                        @error('estado')
                            <p class="text-red-600 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Respuesta --}}
                    <div>
                        <label for="respuesta" class="block text-base font-medium text-gray-700 mb-1">
                            <i class="fas fa-reply text-gray-500 mr-1"></i> Respuesta
                        </label>

                        <textarea
                            name="respuesta"
                            id="respuesta"
                            rows="3"
                            placeholder="Escriba aquí la respuesta al feligrés..."
                            class="w-full rounded-lg border-gray-300 text-base py-2 px-3 @error('respuesta') border-red-500 @enderror"
                        >{{ old('respuesta', $peticione->respuesta) }}</textarea>

                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                            Respuesta del sacerdote o del personal administrativo. El feligrés podrá verla.
                        </p>

                        @error('respuesta')
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
                        Actualizar Petición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
