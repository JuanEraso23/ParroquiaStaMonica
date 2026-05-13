@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">

            <!-- Encabezado -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-user-edit"></i> Editar Mi Perfil
                </h1>
                <p class="text-gray-600 mt-1">
                    Modifica únicamente tu información personal.
                </p>
            </div>

            <!-- Formulario -->
            <form action="{{ route('profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Nombres -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombres *
                        </label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500
                                      @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Apellidos -->
                    <div>
                        <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                            Apellidos *
                        </label>
                        <input type="text" name="apellidos" id="apellidos"
                               value="{{ old('apellidos', $user->apellidos) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500
                                      @error('apellidos') border-red-500 @enderror"
                               required>
                        @error('apellidos')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Documento -->
                    <div>
                        <label for="documento" class="block text-sm font-medium text-gray-700 mb-1">
                            Documento *
                        </label>
                        <input type="text" name="documento" id="documento"
                               value="{{ old('documento', $user->documento) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500
                                      @error('documento') border-red-500 @enderror"
                               required>
                        @error('documento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                            Teléfono *
                        </label>
                        <input type="text" name="telefono" id="telefono"
                               value="{{ old('telefono', $user->telefono) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500
                                      @error('telefono') border-red-500 @enderror"
                               required>
                        @error('telefono')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">
                            Dirección
                        </label>
                        <input type="text" name="direccion" id="direccion"
                               value="{{ old('direccion', $user->direccion) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500">
                        @error('direccion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de Nacimiento
                        </label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->format('Y-m-d')) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500">
                        @error('fecha_nacimiento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo Electrónico *
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-purple-500 focus:ring-purple-500
                                      @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a href="{{ route('profile.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md
                              hover:bg-gray-400 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md
                                   hover:bg-purple-700 transition">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
