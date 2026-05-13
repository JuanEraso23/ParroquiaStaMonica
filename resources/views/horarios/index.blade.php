@extends('layouts.app')

@section('title', 'Horarios')

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
                    <i class="fas fa-clock"></i>
                    {{ $esAdmin ? 'Horarios de la Parroquia' : 'Mis Horarios' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $esAdmin
                        ? 'Calendario informativo de citas confirmadas y completadas.'
                        : 'Consulta tus citas confirmadas y completadas en el calendario.' }}
                </p>
            </div>

            <!-- Navegación de meses -->
            <div class="flex items-center gap-2">
                <a href="{{ route('horarios.index', ['anio' => $mesAnterior->year, 'mes' => $mesAnterior->month]) }}"
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                    <i class="fas fa-chevron-left"></i>
                </a>

                <div class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-semibold min-w-[180px] text-center">
                    {{ ucfirst($fechaBase->translatedFormat('F Y')) }}
                </div>

                <a href="{{ route('horarios.index', ['anio' => $mesSiguiente->year, 'mes' => $mesSiguiente->month]) }}"
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Mensaje informativo -->
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                Este módulo es únicamente informativo. Solo se muestran citas en estado
                <strong>confirmada</strong> o <strong>completada</strong>.
                @unless($esAdmin)
                    Solo puedes visualizar información relacionada con tus propias citas.
                @endunless
            </p>
        </div>

        <!-- Tabla de calendario estilo semana -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <!-- Cabecera con números de semana -->
                <thead>
                    <tr>
                        <th class="px-3 py-2 bg-gray-100 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            SEMANA
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Lunes
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Martes
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Miércoles
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Jueves
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Viernes
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Sábado
                        </th>
                        <th class="px-3 py-2 bg-gray-50 border border-gray-200 text-center text-xs font-semibold text-gray-500 uppercase">
                            Domingo
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($semanas as $indice => $semana)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Número de semana -->
                            <td class="px-3 py-2 bg-gray-100 border border-gray-200 text-center text-sm font-semibold text-gray-600">
                                {{ $indice + 1 }}
                            </td>

                            <!-- Días de la semana -->
                            @foreach($semana as $dia)
                                @php
                                    $esHoy = $dia['es_hoy'] ?? false;
                                @endphp

                                <td class="px-2 py-3 border border-gray-200 align-top min-w-[100px] h-[80px] 
                                    {{ $esHoy ? 'bg-purple-100' : ($dia['es_mes_actual'] ? 'bg-white' : 'bg-gray-50') }}">
                                    <a href="{{ route('horarios.dia', $dia['fecha_key']) }}"
                                       class="block h-full">
                                        <!-- Número del día - SIN círculo, solo texto -->
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="inline-flex items-center justify-center w-7 h-7 text-sm font-semibold 
                                                {{ $esHoy ? 'text-purple-800' : 'text-gray-700' }}">
                                                {{ $dia['fecha']->day }}
                                            </span>

                                            <!-- Contador de citas -->
                                            @if($dia['total_citas'] > 0)
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-purple-200 text-purple-800 font-medium">
                                                    {{ $dia['total_citas'] }} cita{{ $dia['total_citas'] != 1 ? 's' : '' }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Mensaje simple cuando hay citas -->
                                        @if($dia['total_citas'] > 0)
                                            <div class="mt-2 text-center">
                                                <span class="text-xs {{ $esHoy ? 'text-purple-800' : 'text-purple-600' }}">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    {{ $dia['total_citas'] }} agendada{{ $dia['total_citas'] != 1 ? 's' : '' }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="mt-2 text-center">
                                                <span class="text-xs {{ $esHoy ? 'text-purple-400' : 'text-gray-300' }}">
                                                    Sin citas
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                <strong>NOTAS:</strong> Los días con fondo gris claro pertenecen a otros meses.
                El fondo morado pastel indica el día actual.
                El número indica la cantidad de citas agendadas para ese día.
                Haz clic en cualquier día para ver el horario detallado.
            </p>
        </div>

    </div>
</div>
@endsection