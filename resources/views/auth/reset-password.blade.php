<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="text-4xl mb-3">🏛️</div>

        <h1 class="text-2xl font-semibold text-gray-800">
            Restablecer Contraseña
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            Ingresa tu correo electrónico y define una nueva contraseña para acceder nuevamente al sistema.
        </p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <p class="font-medium text-sm">
                Revisa la información ingresada.
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->token }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo electrónico" />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email', $request->email)"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Nueva contraseña" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />

            <p class="text-xs text-gray-500 mt-1">
                La contraseña debe tener mínimo 8 caracteres.
            </p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar nueva contraseña" />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 space-y-3">
            <x-primary-button class="w-full justify-center">
                Restablecer Contraseña
            </x-primary-button>

            <a href="{{ route('login') }}"
               class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm">
                <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>