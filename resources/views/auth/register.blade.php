<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nombres -->
        <div>
            <x-input-label for="name" :value="__('Nombres')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Apellidos -->
        <div class="mt-4">
            <x-input-label for="apellidos" :value="__('Apellidos')" />
            <x-text-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required />
            <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
        </div>

        <!-- Documento -->
        <div class="mt-4">
            <x-input-label for="documento" :value="__('Documento')" />
            <x-text-input id="documento" class="block mt-1 w-full" type="text" name="documento" :value="old('documento')" required />
            <x-input-error :messages="$errors->get('documento')" class="mt-2" />
        </div>

        <!-- Teléfono -->
        <div class="mt-4">
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- Dirección -->
        <div class="mt-4">
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" />
            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
        </div>

        <!-- Fecha de Nacimiento -->
        <div class="mt-4">
            <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
            <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" />
            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
            <small class="text-muted">Opcional</small>
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar Contraseña -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Mensaje informativo -->
        <div class="mt-4 text-sm text-gray-600">
            <p>⚠️ Al registrarte, tu cuenta será creada como <strong>FELIGRÉS</strong>. 
            Si necesitas un rol diferente, contacta a la secretaría parroquial.</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>