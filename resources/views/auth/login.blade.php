<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="text-4xl mb-3">🏛️</div>

        <h1 class="text-2xl font-semibold text-gray-800">
            Iniciar Sesión
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            Ingresa con tu correo electrónico y contraseña para acceder al sistema de la Parroquia Santa Mónica.
        </p>
    </div>

    <!-- Estado de sesión -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Errores generales -->
    @if($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <p class="font-medium text-sm">
                Revisa tus credenciales e intenta nuevamente.
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Correo electrónico -->
        <div class="mt-4">
            <x-input-label for="email" value="Correo electrónico" />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="ejemplo@correo.com"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Ingresa tu contraseña"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Recuérdame -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500"
                >

                <span class="ms-2 text-sm text-gray-600">
                    Recuérdame
                </span>
            </label>
        </div>

        <!-- Información de seguridad -->
        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-4">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                Usa las credenciales registradas en el sistema. Si olvidaste tu contraseña, puedes solicitar un enlace de recuperación.
            </p>
        </div>

        <!-- Acciones -->
        <div class="mt-6 space-y-3">
            <x-primary-button class="w-full justify-center">
                Iniciar Sesión
            </x-primary-button>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm"
                >
                    <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>