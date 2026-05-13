@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">

            <!-- Encabezado -->
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </h1>
                <p class="text-gray-600 text-sm mt-1">
                    Actualiza la contraseña de tu cuenta para mantenerla segura.
                </p>
            </div>

            <!-- Mensajes flash -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Información de seguridad -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Para cambiar tu contraseña debes ingresar tu contraseña actual y luego escribir una nueva contraseña segura.
                </p>
            </div>

            <!-- Formulario -->
            <form action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    <!-- Contraseña actual -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña actual *
                        </label>
                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            autocomplete="current-password"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 @error('current_password') border-red-500 @enderror"
                            required
                        >
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nueva contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nueva contraseña *
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            La contraseña debe tener mínimo 8 caracteres.
                        </p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar nueva contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar nueva contraseña *
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                            required
                        >
                    </div>
                </div>

                <!-- Recomendaciones de seguridad -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-shield-alt mr-1"></i> Recomendaciones de seguridad
                    </h3>
                    <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                        <li>Usa una contraseña diferente a las anteriores.</li>
                        <li>Combina letras, números y caracteres especiales.</li>
                        <li>No compartas tu contraseña con otras personas.</li>
                        <li>Cambia tu contraseña periódicamente.</li>
                    </ul>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a href="{{ route('profile.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-save"></i> Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection