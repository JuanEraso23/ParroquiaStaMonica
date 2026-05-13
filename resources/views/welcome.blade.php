<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parroquia Santa Mónica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .btn-custom {
            background: white;
            color: #764ba2;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s;
            display: inline-block;
        }
        .btn-custom:hover {
            transform: scale(1.05);
            color: #667eea;
        }
        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }
        .btn-outline:hover {
            background: white;
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="text-center">
            <h1 class="display-1">🏛️</h1>
            <h1 class="display-3">Parroquia Santa Mónica</h1>
            <p class="lead mt-3">"La comunidad unida en la fe y el servicio"</p>
            
            <div class="mt-5">
                <!-- Botón de Login -->
                <a href="{{ route('login') }}" class="btn-custom me-3">
                    Iniciar Sesión
                </a>
                <!-- Botón de Registro -->
                <a href="{{ route('register') }}" class="btn-custom btn-outline">
                    Registrarse
                </a>
            </div>
        </div>
    </div>
</body>
</html>