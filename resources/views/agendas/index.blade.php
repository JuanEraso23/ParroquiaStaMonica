@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Agendas de sacerdotes</h1>
            <p class="text-sm text-gray-500 mt-1">Apertura y cierre diario de la agenda de cada sacerdote.</p>
        </div>

        <form method="GET" action="{{ route('agendas.index') }}" class="flex items-center gap-2">
            <label for="fecha" class="text-sm font-medium text-gray-700">Fecha:</label>
            <input
                type="date"
                id="fecha"
                name="fecha"
                value="{{ $fecha }}"
                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                onchange="this.form.submit()"
            >
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($pasoElPlazoHoy)
        <div class="mb-4 rounded-md bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 text-sm">
            Ya pasaron las 12:00 PM. Cualquier apertura de agenda para hoy será considerada
            <strong>extraordinaria</strong> y requiere un motivo.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($sacerdotes as $sacerdote)
            @php
                $agenda = $agendas->get($sacerdote->id);
                $totalCitas = $citasPorSacerdote[$sacerdote->id] ?? 0;
                $abierta = $agenda && $agenda->estaAbierta();
            @endphp

            <div class="bg-white shadow rounded-lg p-5 border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $sacerdote->nombre_completo }}</h2>
                        <p class="text-sm text-gray-500">{{ $sacerdote->rol_texto }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $abierta ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $abierta ? 'Abierta' : 'Cerrada' }}
                    </span>
                </div>

                <div class="mt-4 text-sm text-gray-700 space-y-1">
                    <p><strong>Citas activas ese día:</strong> {{ $totalCitas }}</p>

                    @if ($agenda)
                        <p>
                            <strong>Jornada:</strong>
                            {{ \Carbon\Carbon::parse($agenda->hora_inicio)->format('g:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($agenda->hora_fin)->format('g:i A') }}
                        </p>

                        @if ($agenda->abierta_en)
                            <p>
                                <strong>Abierta por:</strong>
                                {{ $agenda->usuarioApertura?->nombre_completo ?? '—' }}
                                el {{ $agenda->abierta_en->format('d/m/Y g:i A') }}
                            </p>
                        @endif

                        @if ($agenda->fueAbiertaFueraDePlazo() && $agenda->motivo_apertura_extraordinaria)
                            <p class="text-yellow-700">
                                <strong>Apertura extraordinaria:</strong>
                                {{ $agenda->motivo_apertura_extraordinaria }}
                            </p>
                        @endif

                        @if ($agenda->cerrada_en)
                            <p>
                                <strong>Cerrada por:</strong>
                                {{ $agenda->usuarioCierre?->nombre_completo ?? '—' }}
                                el {{ $agenda->cerrada_en->format('d/m/Y g:i A') }}
                            </p>
                        @endif
                    @else
                        <p class="text-gray-500">Sin agenda registrada para esta fecha.</p>
                    @endif
                </div>

                @if ($abierta)
                    <form method="POST"
                          action="{{ route('agendas.cerrar', $agenda) }}"
                          class="mt-4"
                          onsubmit="return confirm('¿Cerrar la agenda? Las citas existentes se conservarán.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-700">
                            Cerrar agenda
                        </button>
                    </form>
                @else
                    <form method="POST"
                          action="{{ route('agendas.abrir') }}"
                          class="mt-4 space-y-3">
                        @csrf
                        <input type="hidden" name="sacerdote_id" value="{{ $sacerdote->id }}">
                        <input type="hidden" name="fecha" value="{{ $fecha }}">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Hora inicio</label>
                                <input type="time" name="hora_inicio" value="15:00" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Hora fin</label>
                                <input type="time" name="hora_fin" value="18:00" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </div>
                        </div>

                        @if ($pasoElPlazoHoy)
                            <div>
                                <label class="block text-xs font-medium text-yellow-700">
                                    Motivo (apertura extraordinaria)
                                </label>
                                <textarea name="motivo_apertura_extraordinaria" rows="2" required
                                          class="mt-1 block w-full rounded-md border-yellow-300 shadow-sm text-sm"
                                          placeholder="Indique el motivo de la apertura después de las 12:00 PM"></textarea>
                            </div>
                        @endif

                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-green-700">
                            Abrir agenda
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
