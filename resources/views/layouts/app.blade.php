<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Parroquia Santa Mónica') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts y CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-100">

    @php
        $authUser = Auth::user();
        $rol = strtolower($authUser->rol ?? '');

        // Roles con acceso administrativo
        $esAdmin = in_array($rol, ['parroco', 'vicario', 'secretaria']);

        // Usuario final / feligrés
        $esUser = $rol === 'feligres';
    @endphp

    <!-- Overlay del sidebar -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar (menú vertical) -->
    <div id="sidebar" class="sidebar">
        <!-- Perfil del usuario en el sidebar -->
        <div class="sidebar-profile">
            <div class="sidebar-avatar">
                <i class="fas fa-user-circle fa-4x"></i>
            </div>
            <div class="sidebar-user-name">{{ $authUser->nombre_completo }}</div>
            <div class="sidebar-user-role">{{ $authUser->rol_texto }}</div>
        </div>

        <div class="sidebar-nav">
            <!-- Dashboard: visible para admin y user -->
            <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>

            <!-- Citas: visible para admin y user -->
            <a href="{{ route('citas.index') }}" class="sidebar-nav-item {{ request()->routeIs('citas.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Citas
            </a>

            <!-- Peticiones e Intenciones: visible para admin y user -->
            <a href="{{ route('peticiones_intenciones.index') }}" class="sidebar-nav-item {{ request()->routeIs('peticiones_intenciones.*') ? 'active' : '' }}">
                <i class="fas fa-hands-praying"></i> Peticiones e Intenciones
            </a>

            <!-- Horarios: visible para admin y user -->
            @if(Route::has('horarios.index'))
                <a href="{{ route('horarios.index') }}" class="sidebar-nav-item {{ request()->routeIs('horarios.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Horarios
                </a>
            @else
                <a href="#" class="sidebar-nav-item">
                    <i class="fas fa-clock"></i> Horarios
                </a>
            @endif

            <!-- Usuarios: SOLO admin -->
            @if($esAdmin)
                <a href="{{ route('usuarios.index') }}" class="sidebar-nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Usuarios
                </a>
            @endif

            <!-- Mi Perfil: SOLO user -->
            @if($esUser)
                @if(Route::has('profile.index'))
                    <a href="{{ route('profile.index') }}" class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                @else
                    <a href="#" class="sidebar-nav-item">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="min-h-screen flex flex-col">
        <!-- Navbar horizontal -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="px-4 py-3 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="menu-toggle mr-3" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl mr-2">🏛️</span>
                        <span class="font-semibold text-gray-800">Parroquia Santa Mónica</span>
                    </div>
                </div>

                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Contenido dinámico -->
        <main class="flex-1 py-8 px-4">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer-parroquia">
            <div class="container mx-auto px-4 text-center">
                <h4><i class="fas fa-copyright mr-1"></i>2026 - Todos los Derechos Reservados</h4>
                <h6>Diseñado por:</h6>
                <p>
                    Juan Sebastián Coronado Parra |
                    Juan Manuel Eraso Grijalba | 
                    Diego Fernando Escobar Enriquez | 
                    Jaider Andrés Narvaéz Cabrera | 
                    David Esteban Ortiz Ortiz
                </p>
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('open');
        }
    </script>
</body>
</html>