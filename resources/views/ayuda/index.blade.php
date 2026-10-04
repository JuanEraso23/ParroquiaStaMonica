@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     CENTRO DE AYUDA - Parroquia Santa Mónica
     Guías para feligreses, secretaría, párroco y vicario
     ═══════════════════════════════════════════════════════════ --}}

<div class="ayuda-container">

    {{-- Encabezado --}}
    <div class="ayuda-header">
        <h1>
            <i class="fas fa-circle-info"></i> Centro de Ayuda
        </h1>
        <p class="ayuda-subtitle">
            Encuentra aquí las guías para usar el sistema parroquial.
            Si tienes dudas, consulta la sección que corresponda a tu rol.
        </p>
    </div>

    {{-- Índice de acceso rápido --}}
    <div class="ayuda-indice">
        <h2><i class="fas fa-list"></i> ¿Qué necesitas hacer?</h2>
        <div class="ayuda-indice-grid">
            <a href="#feligreses" class="ayuda-indice-card">
                <i class="fas fa-user"></i>
                <span>Soy Feligrés</span>
                <small>Agendar citas y enviar peticiones</small>
            </a>
            <a href="#secretaria" class="ayuda-indice-card">
                <i class="fas fa-user-tie"></i>
                <span>Soy Secretaría</span>
                <small>Gestionar citas, peticiones y usuarios</small>
            </a>
            <a href="#parroco" class="ayuda-indice-card">
                <i class="fas fa-church"></i>
                <span>Soy Párroco o Vicario</span>
                <small>Revisar y responder peticiones</small>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- GUÍA 1: FELIGRESES --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <section id="feligreses" class="ayuda-section">
        <h2>
            <i class="fas fa-user"></i>
            Guía para Feligreses
        </h2>
        <p class="ayuda-intro">
            Esta guía te explica paso a paso cómo usar el sistema para agendar
            una cita con el sacerdote o enviar una petición.
        </p>

        <div class="ayuda-card">
            <h3><i class="fas fa-calendar-check"></i> ¿Cómo agendar una cita?</h3>
            <ol>
                <li>En el menú lateral, haz clic en <strong>Citas</strong>.</li>
                <li>Presiona el botón verde <strong>"Nueva Cita"</strong>.</li>
                <li>Selecciona la <strong>fecha</strong> y la <strong>hora</strong> que prefieras.</li>
                <li>Escribe el <strong>motivo</strong> de tu cita (bautizo, confesión, consejo, etc.).</li>
                <li>Haz clic en <strong>"Guardar"</strong>.</li>
                <li>¡Listo! Recibirás un mensaje de confirmación en pantalla.</li>
            </ol>
            <div class="ayuda-tip">
                💡 <strong>Consejo:</strong> Si tu cita no aparece, revisa que hayas
                iniciado sesión con tu cuenta de feligrés.
            </div>
        </div>

        <div class="ayuda-card">
            <h3><i class="fas fa-hands-praying"></i> ¿Cómo enviar una petición?</h3>
            <ol>
                <li>En el menú lateral, haz clic en <strong>Peticiones</strong>.</li>
                <li>Presiona el botón <strong>"Nueva Petición"</strong>.</li>
                <li>Selecciona la <strong>categoría</strong> (oración, ayuda, sugerencia, etc.).</li>
                <li>Escribe tu petición con claridad.</li>
                <li>Haz clic en <strong>"Enviar"</strong>.</li>
                <li>Podrás ver el estado de tu petición: <em>pendiente</em>, <em>en revisión</em> o <em>respondida</em>.</li>
            </ol>
        </div>

        <div class="ayuda-card">
            <h3><i class="fas fa-user-circle"></i> ¿Cómo actualizar mis datos?</h3>
            <ol>
                <li>En el menú lateral, haz clic en <strong>Mi Perfil</strong>.</li>
                <li>Presiona <strong>"Editar"</strong>.</li>
                <li>Actualiza tus datos (teléfono, correo, dirección).</li>
                <li>Haz clic en <strong>"Guardar cambios"</strong>.</li>
            </ol>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- GUÍA 2: SECRETARÍA --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <section id="secretaria" class="ayuda-section">
        <h2>
            <i class="fas fa-user-tie"></i>
            Guía para Secretaría
        </h2>
        <p class="ayuda-intro">
            Como secretaría, tu labor es coordinar las citas, gestionar las
            peticiones y mantener actualizados los usuarios del sistema.
        </p>

        <div class="ayuda-card">
            <h3><i class="fas fa-calendar-check"></i> Gestión de citas</h3>
            <ul>
                <li>En <strong>Citas</strong> puedes ver todas las citas agendadas.</li>
                <li>Usa el botón <strong>"Editar"</strong> para cambiar fecha, hora o motivo.</li>
                <li>Usa <strong>"Cambiar estado"</strong> para marcar como confirmada, cancelada o atendida.</li>
                <li>Para cancelar una cita, haz clic en el ícono de basura 🗑️.</li>
            </ul>
            <div class="ayuda-tip">
                ⚠️ <strong>Importante:</strong> antes de eliminar una cita,
                verifica con el feligrés para evitar confusiones.
            </div>
        </div>

        <div class="ayuda-card">
            <h3><i class="fas fa-hands-praying"></i> Gestión de peticiones</h3>
            <ul>
                <li>En <strong>Peticiones</strong> verás todas las enviadas por los feligreses.</li>
                <li>Puedes <strong>filtrar</strong> por categoría o por estado.</li>
                <li>Al abrir una petición, puedes <strong>responderla</strong> y cambiar su estado.</li>
                <li>Los feligreses verán tu respuesta en su panel.</li>
            </ul>
        </div>

        <div class="ayuda-card">
            <h3><i class="fas fa-users"></i> Gestión de usuarios</h3>
            <ul>
                <li>En <strong>Usuarios</strong> puedes ver todos los registrados.</li>
                <li>Usa <strong>"Nuevo usuario"</strong> para registrar feligreses.</li>
                <li>Puedes <strong>activar o desactivar</strong> cuentas según sea necesario.</li>
                <li>Si alguien olvidó su contraseña, puede recuperarla desde la pantalla de inicio.</li>
            </ul>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- GUÍA 3: PÁRROCO Y VICARIO --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <section id="parroco" class="ayuda-section">
        <h2>
            <i class="fas fa-church"></i>
            Guía para Párroco y Vicario
        </h2>
        <p class="ayuda-intro">
            Como responsable pastoral, tu rol en el sistema es revisar las
            peticiones, acompañar a los feligreses y supervisar la agenda.
        </p>

        <div class="ayuda-card">
            <h3><i class="fas fa-hands-praying"></i> Revisar peticiones</h3>
            <ul>
                <li>Accede a <strong>Peticiones</strong> desde el menú lateral.</li>
                <li>Verás las peticiones ordenadas por fecha (las más recientes arriba).</li>
                <li>Puedes <strong>responder</strong> cada una con un mensaje pastoral.</li>
                <li>El feligrés verá tu respuesta en su perfil.</li>
            </ul>
        </div>

        <div class="ayuda-card">
            <h3><i class="fas fa-calendar-check"></i> Supervisar la agenda</h3>
            <ul>
                <li>En <strong>Citas</strong> puedes ver todas las programadas.</li>
                <li>Consulta la sección <strong>Horarios</strong> para ver la disponibilidad.</li>
                <li>Si necesitas bloquear un día, avisa a secretaría.</li>
            </ul>
        </div>

        <div class="ayuda-card">
            <h3><i class="fas fa-chart-line"></i> Panel de control</h3>
            <ul>
                <li>El <strong>Dashboard</strong> muestra un resumen general.</li>
                <li>Verás estadísticas de citas y peticiones del mes.</li>
                <li>Próximamente habrá reportes en PDF descargables.</li>
            </ul>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- CONTACTO / SOPORTE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <section class="ayuda-contacto">
        <h2><i class="fas fa-headset"></i> ¿Necesitas más ayuda?</h2>
        <p>
            Si no encuentras la respuesta a tu pregunta, puedes contactar a
            la secretaría parroquial directamente.
        </p>
        <ul>
            <li><i class="fas fa-phone"></i> Teléfono: <strong>Próximamente</strong></li>
            <li><i class="fas fa-envelope"></i> Correo: <strong>Próximamente</strong></li>
            <li><i class="fas fa-map-marker-alt"></i> Dirección: <strong>Próximamente</strong></li>
        </ul>
    </section>

</div>

{{-- ═══════════════════════════════════════════════════════════
     ESTILOS ESPECÍFICOS DE LA PÁGINA DE AYUDA
     ═══════════════════════════════════════════════════════════ --}}
<style>
    .ayuda-container {
        max-width: 960px;
        margin: 0 auto;
        padding: 1rem;
        color: #1f2937;
        line-height: 1.7;
        font-size: 1.05rem;
    }

    .ayuda-header {
        text-align: center;
        padding: 2rem 1rem;
        background: linear-gradient(135deg, #6d28d9, #9333ea);
        color: white;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .ayuda-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .ayuda-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        max-width: 640px;
        margin: 0 auto;
    }

    .ayuda-indice {
        margin-bottom: 3rem;
    }

    .ayuda-indice h2 {
        font-size: 1.4rem;
        margin-bottom: 1rem;
        color: #6d28d9;
    }

    .ayuda-indice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .ayuda-indice-card {
        display: block;
        padding: 1.5rem 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        color: #1f2937;
        transition: all 0.2s;
    }

    .ayuda-indice-card:hover {
        border-color: #9333ea;
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(147, 51, 234, 0.15);
    }

    .ayuda-indice-card i {
        font-size: 2rem;
        color: #9333ea;
        display: block;
        margin-bottom: 0.5rem;
    }

    .ayuda-indice-card span {
        display: block;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .ayuda-indice-card small {
        display: block;
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .ayuda-section {
        margin-bottom: 3rem;
        scroll-margin-top: 80px;
    }

    .ayuda-section h2 {
        font-size: 1.6rem;
        color: #6d28d9;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 1rem;
    }

    .ayuda-section h2 i {
        margin-right: 0.5rem;
    }

    .ayuda-intro {
        font-size: 1.05rem;
        color: #4b5563;
        margin-bottom: 1.5rem;
    }

    .ayuda-card {
        background: white;
        border-left: 4px solid #9333ea;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .ayuda-card h3 {
        font-size: 1.2rem;
        color: #4c1d95;
        margin-bottom: 0.75rem;
    }

    .ayuda-card h3 i {
        margin-right: 0.4rem;
    }

    .ayuda-card ol,
    .ayuda-card ul {
        padding-left: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .ayuda-card li {
        margin-bottom: 0.5rem;
    }

    .ayuda-tip {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-top: 1rem;
        font-size: 0.98rem;
    }

    .ayuda-contacto {
        background: #ede9fe;
        padding: 2rem;
        border-radius: 12px;
        text-align: center;
    }

    .ayuda-contacto h2 {
        color: #6d28d9;
        margin-bottom: 0.75rem;
    }

    .ayuda-contacto ul {
        list-style: none;
        padding: 0;
        margin-top: 1rem;
    }

    .ayuda-contacto li {
        margin-bottom: 0.5rem;
        font-size: 1.05rem;
    }

    .ayuda-contacto li i {
        color: #9333ea;
        margin-right: 0.5rem;
    }

    /* ─── Responsive para celular ─── */
    @media (max-width: 640px) {
        .ayuda-container {
            font-size: 1rem;
        }

        .ayuda-header h1 {
            font-size: 1.5rem;
        }

        .ayuda-header {
            padding: 1.5rem 1rem;
        }

        .ayuda-section h2 {
            font-size: 1.35rem;
        }

        .ayuda-card {
            padding: 1rem;
        }
    }
</style>

@endsection