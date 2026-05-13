<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="text-4xl mb-3">🏛️</div>

        <h1 class="text-2xl font-semibold text-gray-800">
            Recuperar Contraseña
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            Ingresa el correo electrónico asociado a tu cuenta y te enviaremos un enlace para restablecer tu contraseña.
        </p>
    </div>

    <!-- Mensaje de estado -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <p class="font-medium text-sm">
                Revisa la información ingresada.
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div>
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

        <!-- Información -->
        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-4">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                Si el correo está registrado en el sistema, recibirás un enlace para crear una nueva contraseña.
            </p>
        </div>

        <!-- Acciones -->
        <div class="mt-6 space-y-3">
            <x-primary-button class="w-full justify-center">
                Enviar enlace de recuperación
            </x-primary-button>

            <a href="{{ route('login') }}"
               class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm">
                <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>