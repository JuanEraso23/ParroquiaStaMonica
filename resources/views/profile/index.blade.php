@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">

        <!-- Encabezado -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-user-circle"></i> Mi Perfil
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Consulta y administra tu información personal dentro del sistema.
                </p>
            </div>
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

        @if(session('status') === 'profile-updated')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                Perfil actualizado exitosamente.
            </div>
        @endif

        <!-- Perfil del usuario actual -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 flex items-center">
            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                <i class="fas fa-user-circle text-purple-600 text-3xl"></i>
            </div>

            <div>
                <h3 class="font-semibold text-gray-800">
                    {{ $user->nombre_completo }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ $user->email }}
                </p>
                <p class="text-xs text-gray-400">
                    Cuenta personal · {{ $user->rol_texto }}
                </p>
            </div>
        </div>

        <!-- Tarjetas informativas del perfil -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Rol Feligrés -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">
                    Rol: Feligrés
                </h3>
                <p class="text-sm text-gray-600 mb-3">
                    Como feligrés puedes consultar y gestionar únicamente la información asociada a tu cuenta.
                </p>
                <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>Solicitar y consultar tus citas.</li>
                    <li>Registrar tus peticiones e intenciones.</li>
                    <li>Consultar tus horarios y registros personales.</li>
                </ul>
            </div>

            <!-- Seguridad de la cuenta -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">
                    Seguridad de la cuenta
                </h3>
                <p class="text-sm text-gray-600 mb-3">
                    Tu perfil solo permite modificar información personal básica. Los roles, estados y permisos son gestionados por administración.
                </p>
                <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>No puedes cambiar tu rol desde esta sección.</li>
                    <li>No puedes modificar el estado de tu cuenta.</li>
                    <li>No puedes acceder a información de otros usuarios.</li>
                </ul>
            </div>
        </div>

        <!-- Información personal -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                Información Personal
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">
                                Campo
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">
                                Información registrada
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Nombre completo
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $user->nombre_completo }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Documento
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $user->documento ?? 'No registrado' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Teléfono
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $user->telefono ?? 'No registrado' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Correo electrónico
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Dirección
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $user->direccion ?? 'No registrada' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Fecha de nacimiento
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if($user->fecha_nacimiento)
                                    {{ \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') }}
                                @else
                                    No registrada
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Rol
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                    {{ $user->rol_texto }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                Estado de cuenta
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span class="px-2 py-1 text-xs rounded-full {{ $user->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones del perfil -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left"></i> Volver
            </a>

            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>

            <a href="{{ route('profile.password') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-key"></i> Cambiar Contraseña
            </a>
        </div>

    </div>
</div>
@endsection