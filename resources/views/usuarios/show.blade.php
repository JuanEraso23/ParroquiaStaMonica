@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-user"></i> Detalles del Usuario
                </h1>
                <p class="text-gray-600 mt-1">Información completa del usuario</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nombre completo</p>
                        <p class="font-medium">{{ $usuario->nombre_completo }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Documento</p>
                        <p class="font-medium">{{ $usuario->documento }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Teléfono</p>
                        <p class="font-medium">{{ $usuario->telefono }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Correo Electrónico</p>
                        <p class="font-medium">{{ $usuario->email }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Dirección</p>
                        <p class="font-medium">{{ $usuario->direccion ?: 'No registrada' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Fecha de Nacimiento</p>
                        <p class="font-medium">{{ $usuario->fecha_nacimiento ? $usuario->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Rol</p>
                        <p class="font-medium">
                            <span class="px-2 py-1 rounded text-sm bg-purple-100 text-purple-800">
                                {{ $usuario->rol_texto }}
                            </span>
                        </p>
                    </div>
                    
                    @if($usuario->cargo)
                    <div>
                        <p class="text-sm text-gray-500">Cargo</p>
                        <p class="font-medium">{{ $usuario->cargo }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <p class="text-sm text-gray-500">Estado</p>
                        <p class="font-medium">
                            @if($usuario->activo)
                                <span class="text-green-600">✓ Activo</span>
                            @else
                                <span class="text-red-600">✗ Inactivo</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Fecha de Registro</p>
                        <p class="font-medium">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($usuario->ultimo_acceso)
                    <div>
                        <p class="text-sm text-gray-500">Último Acceso</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($usuario->ultimo_acceso)->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    @if(Auth::user()->esAdministrador())
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection