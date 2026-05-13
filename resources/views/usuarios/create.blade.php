@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-user-plus"></i> Crear Usuario
                </h1>
                <p class="text-gray-600 mt-1">Registrar nuevo usuario en el sistema (administradores y feligreses)</p>
            </div>
            
            <form action="{{ route('usuarios.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombres *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
                        <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('apellidos') border-red-500 @enderror"
                               required>
                        @error('apellidos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="documento" class="block text-sm font-medium text-gray-700 mb-1">Documento *</label>
                        <input type="text" name="documento" id="documento" value="{{ old('documento') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('documento') border-red-500 @enderror"
                               required>
                        @error('documento')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('telefono') border-red-500 @enderror"
                               required>
                        @error('telefono')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                        <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    
                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
                        <select name="rol" id="rol" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('rol') border-red-500 @enderror"
                                required>
                            <option value="feligres" {{ old('rol') == 'feligres' ? 'selected' : '' }}>Feligrés</option>
                            <option value="secretaria" {{ old('rol') == 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                            <option value="parroco" {{ old('rol') == 'parroco' ? 'selected' : '' }}>Párroco</option>
                            <option value="vicario" {{ old('rol') == 'vicario' ? 'selected' : '' }}>Vicario</option>
                        </select>
                        @error('rol')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div id="cargo_field" style="display: none;">
                        <label for="cargo" class="block text-sm font-medium text-gray-700 mb-1">Cargo específico</label>
                        <input type="text" name="cargo" id="cargo" value="{{ old('cargo') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               placeholder="Ej: Secretaria Principal, Párroco Titular">
                        @error('cargo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                        <input type="password" name="password" id="password" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                               required>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                               required>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-info-circle"></i> 
                        Esta sección es solo para administradores. Puedes crear usuarios con cualquier rol.
                    </p>
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                        <i class="fas fa-save"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('rol').addEventListener('change', function() {
        const cargoField = document.getElementById('cargo_field');
        cargoField.style.display = this.value !== 'feligres' ? 'block' : 'none';
    });
    
    // Ejecutar al cargar
    if (document.getElementById('rol').value !== 'feligres') {
        document.getElementById('cargo_field').style.display = 'block';
    }
</script>
@endsection