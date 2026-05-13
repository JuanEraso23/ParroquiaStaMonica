@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <!-- Encabezado con botón Nuevo Usuario -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-users"></i> Gestión de Usuarios
                </h1>
                <p class="text-gray-500 text-sm mt-1">Administra los usuarios y roles del sistema</p>
            </div>
            <a href="{{ route('usuarios.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
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

        <!-- Perfil del usuario actual (estilo Figma) -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                <i class="fas fa-user-circle text-purple-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">{{ Auth::user()->nombre_completo }}</h3>
                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                <p class="text-xs text-gray-400">Sesión actual · {{ Auth::user()->rol_texto }}</p>
            </div>
        </div>

        <!-- Tarjetas de roles (Administrador y Secretaria) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Rol Administrador -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">Rol: Administrador</h3>
                <p class="text-sm text-gray-600 mb-3">
                    Los padres y personal administrativo tienen acceso completo para gestionar la página cuando sea necesario.
                </p>
                <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>Ver, editar y aprobar todos los registros</li>
                    <li>Gestionar citas, peticiones e intenciones</li>
                    <li>Administrar usuarios del sistema</li>
                </ul>
            </div>

            <!-- Rol Secretaria -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">Rol: Secretaria</h3>
                <p class="text-sm text-gray-600 mb-3">
                    Personal de secretaría con permisos limitados.
                </p>
                <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>Registrar nuevas citas</li>
                    <li>Registrar peticiones e intenciones</li>
                    <li>Consultar horarios disponibles</li>
                </ul>
            </div>
        </div>

        <!-- Tabla: Administradores -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                Administradores
                <span class="text-sm text-gray-500">({{ $administradores->count() }} usuarios)</span>
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo Electrónico</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($administradores as $admin)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $admin->nombre_completo }}</div>
                                @if($admin->cargo)
                                    <div class="text-xs text-gray-500">{{ $admin->cargo }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $admin->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $admin->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $admin->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    @if($admin->id !== Auth::id())
                                        <form action="{{ route('usuarios.toggle-activo', $admin) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-500 hover:text-gray-700" title="{{ $admin->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas {{ $admin->activo ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('usuarios.show', $admin) }}" class="text-blue-600 hover:text-blue-800" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('usuarios.edit', $admin) }}" class="text-yellow-600 hover:text-yellow-800" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($admin->id !== Auth::id())
                                        <form action="{{ route('usuarios.destroy', $admin) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">No hay administradores registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla: Secretarias -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                Secretarias
                <span class="text-sm text-gray-500">({{ $secretarias->count() }} usuarios)</span>
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo Electrónico</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($secretarias as $secretaria)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $secretaria->nombre_completo }}</div>
                                @if($secretaria->cargo)
                                    <div class="text-xs text-gray-500">{{ $secretaria->cargo }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $secretaria->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $secretaria->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $secretaria->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    @if($secretaria->id !== Auth::id())
                                        <form action="{{ route('usuarios.toggle-activo', $secretaria) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-500 hover:text-gray-700" title="{{ $secretaria->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas {{ $secretaria->activo ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('usuarios.show', $secretaria) }}" class="text-blue-600 hover:text-blue-800" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('usuarios.edit', $secretaria) }}" class="text-yellow-600 hover:text-yellow-800" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($secretaria->id !== Auth::id())
                                        <form action="{{ route('usuarios.destroy', $secretaria) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">No hay secretarias registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla: Feligreses (usuarios comunes) -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">
                Feligreses
                <span class="text-sm text-gray-500">{{ $feligreses->count() }}</span>
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo Electrónico</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($feligreses as $feligres)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $feligres->nombre_completo }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $feligres->documento }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $feligres->telefono }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $feligres->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $feligres->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $feligres->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    @if($feligres->id !== Auth::id())
                                        <form action="{{ route('usuarios.toggle-activo', $feligres) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-500 hover:text-gray-700" title="{{ $feligres->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas {{ $feligres->activo ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('usuarios.show', $feligres) }}" class="text-blue-600 hover:text-blue-800" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('usuarios.edit', $feligres) }}" class="text-yellow-600 hover:text-yellow-800" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($feligres->id !== Auth::id())
                                        <form action="{{ route('usuarios.destroy', $feligres) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">No hay feligreses registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection