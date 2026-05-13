@extends('layouts.app')

@section('title', 'Horario del Día')

@section('content')
    @php
        $usuario = Auth::user();
        $esAdmin = $usuario->esAdministrador();
    @endphp

    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">

            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">
                        <i class="fas fa-calendar-day"></i>
                        {{ $esAdmin ? 'Horario del Día' : 'Horario del Día' }}
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ ucfirst($fechaSeleccionada->translatedFormat('l, j \d\e F \d\e Y')) }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('horarios.dia', $fechaAnterior->format('Y-m-d')) }}"
                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>

                    <a href="{{ route('horarios.index', ['anio' => $fechaSeleccionada->year, 'mes' => $fechaSeleccionada->month]) }}"
                    class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-semibold transition hover:bg-purple-100">
                        <i class="fas fa-calendar-alt mr-1"></i> Volver al calendario
                    </a>

                    <a href="{{ route('horarios.dia', $fechaSiguiente->format('Y-m-d')) }}"
                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <!-- Mensaje informativo -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Esta vista es únicamente informativa. Solo se muestran citas en estado
                    <strong>confirmada</strong> o <strong>completada</strong>.
                    @unless($esAdmin)
                        Puedes visualizar todas las citas del día, pero los detalles de feligreses que no eres tú están ocultos.
                    @endunless
                </p>
            </div>

            <!-- Resumen del día - EN UN SOLO RENGLÓN -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 text-center">
                    <p class="text-sm text-gray-500">Total de citas visibles</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $citas->count() }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 text-center">
                    <p class="text-sm text-gray-500">Confirmadas</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $citas->where('estado', 'confirmada')->count() }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 text-center">
                    <p class="text-sm text-gray-500">Completadas</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $citas->where('estado', 'completada')->count() }}
                    </p>
                </div>
            </div>

            <!-- Agenda diaria estilo tabla -->
            <div class="overflow-x-auto">
                <div class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
                    
                    <!-- Encabezado de la agenda -->
                    <div class="grid grid-cols-[100px_1fr] bg-gray-50 border-b border-gray-300">
                        <div class="px-4 py-3 border-r border-gray-300 text-left text-xs font-semibold text-gray-500 uppercase">
                            Hora
                        </div>
                        <div class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            Citas
                        </div>
                    </div>

                    <!-- Horas -->
                    <div class="divide-y divide-gray-200">
                        @foreach($horas as $hora)
                            @php
                                $citasDeLaHora = $citas->filter(function ($cita) use ($hora) {
                                    return \Carbon\Carbon::parse($cita->hora)->hour === $hora['valor_24'];
                                });
                            @endphp

                            <div class="grid grid-cols-[100px_1fr] hover:bg-purple-50 transition min-h-[80px]">
                                <!-- Columna hora -->
                                <div class="px-4 py-4 border-r border-gray-200 bg-gray-50">
                                    <span class="text-sm font-semibold text-gray-600">
                                        {{ $hora['label'] }}
                                    </span>
                                </div>

                                <!-- Columna citas - SIEMPRE con la misma estructura -->
                                <div class="px-4 py-3">
                                    @if($citasDeLaHora->isNotEmpty())
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($citasDeLaHora as $cita)
                                                @php
                                                    $colorBg = match($cita->tipo) {
                                                        'confesion' => '#3B82F6',
                                                        'bautismo' => '#10B981',
                                                        'matrimonio' => '#8B5CF6',
                                                        'orientacion' => '#EAB308',
                                                        default => '#6B7280',
                                                    };
                                                @endphp

                                                <div class="relative flex-1 min-w-[250px]">
                                                    <button type="button"
                                                            onclick="toggleDetalleCita('detalle-cita-{{ $cita->id }}')"
                                                            class="block text-left rounded-lg shadow-sm hover:shadow-md transition w-full"
                                                            style="background-color: {{ $colorBg }};">
                                                        <div class="px-4 py-3 text-white">
                                                            <div class="flex justify-between items-start gap-2 flex-wrap">
                                                                <div>
                                                                    <p class="text-sm font-semibold">
                                                                        {{ $cita->hora_inicio_formateada }} - {{ $cita->hora_fin_formateada }}
                                                                    </p>
                                                                    <p class="text-xs opacity-90">
                                                                        {{ $cita->tipo_texto }} · {{ $cita->duracion_minutos }} min
                                                                    </p>
                                                                </div>

                                                                <span class="text-[10px] bg-white/20 rounded-full px-2 py-0.5">
                                                                    {{ ucfirst($cita->estado) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <!-- Detalle desplegable -->
                                                    <div id="detalle-cita-{{ $cita->id }}"
                                                        class="hidden mt-2 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg z-10 absolute left-0 right-0">
                                                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                                            <h3 class="text-sm font-semibold text-gray-700">Detalles de la cita</h3>
                                                        </div>
                                                        <div class="p-4">
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <!-- Feligrés: visible para admin O si la cita es propia -->
                                                                @if($esAdmin || $cita->es_propia)
                                                                    <div>
                                                                        <p class="text-xs text-gray-400 uppercase tracking-wide">Feligrés</p>
                                                                        <p class="font-medium text-gray-900 mt-1">
                                                                            {{ $cita->feligres_nombre }}
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        <p class="text-xs text-gray-400 uppercase tracking-wide">Feligrés</p>
                                                                        <p class="font-medium text-gray-400 italic mt-1">
                                                                            Información no disponible
                                                                        </p>
                                                                    </div>
                                                                @endif

                                                                <div>
                                                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Sacerdote</p>
                                                                    <p class="font-medium text-gray-900 mt-1">
                                                                        {{ $cita->sacerdote ? ($cita->sacerdote->nombre_completo ?? $cita->sacerdote->name) : 'No asignado' }}
                                                                    </p>
                                                                </div>

                                                                <div>
                                                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Tipo</p>
                                                                    <p class="font-medium text-gray-900 mt-1">
                                                                        {{ $cita->tipo_texto }}
                                                                    </p>
                                                                </div>

                                                                <div>
                                                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Estado</p>
                                                                    <span class="inline-block px-2 py-1 text-xs rounded-full mt-1 {{ $cita->estado_badge }}">
                                                                        {{ ucfirst($cita->estado) }}
                                                                    </span>
                                                                </div>

                                                                @if($cita->descripcion)
                                                                    <div class="md:col-span-2">
                                                                        <p class="text-xs text-gray-400 uppercase tracking-wide">Descripción</p>
                                                                        <p class="text-gray-700 mt-1">
                                                                            {{ $cita->descripcion }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Mensaje consistente para horas sin citas -->
                                        <div class="flex items-center justify-center h-full min-h-[60px]">
                                            <span class="text-sm text-gray-300">Sin citas registradas</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    Confesión
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    Bautismo
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                    Matrimonio
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                    Orientación
                </div>
            </div>

            <!-- Notas -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-sticky-note mr-1"></i>
                    <strong>NOTAS:</strong> Haz clic en cualquier cita para ver los detalles completos.
                    Las franjas de colores indican el tipo de cita.
                    @unless($esAdmin)
                        Como feligrés, puedes ver todas las citas del día, pero solo los detalles de tus propias citas.
                    @endunless
                </p>
            </div>

        </div>
    </div>

    <script>
        function toggleDetalleCita(id) {
            const elemento = document.getElementById(id);
            if (!elemento) return;
            
            // Cerrar otros desplegables abiertos
            document.querySelectorAll('[id^="detalle-cita-"]').forEach(el => {
                if (el.id !== id && !el.classList.contains('hidden')) {
                    el.classList.add('hidden');
                }
            });
            
            elemento.classList.toggle('hidden');
        }
    </script>
@endsection