{{-- resources/views/admin/ediciones/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalles: ' . $edicion->nombre)
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.ediciones.index') }}" class="text-gray-700 hover:text-uclv-primary">Ediciones</a>
        </span>
    </li>
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <span class="text-gray-500">Detalles</span>
        </span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-lg bg-uclv-primary">
                <i class="fas fa-calendar-day text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $edicion->nombre }}</h1>
                <p class="text-gray-600">ID: {{ $edicion->id_edicion }} • Creada el {{ $edicion->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.ediciones.edit', $edicion) }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('admin.ediciones.index') }}"
               class="btn btn-outline inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Grid de información -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Estado y detalles -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información General</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estado</p>
                        <div class="flex items-center">
                            @php
                                $estadoColors = [
                                    'planificada' => 'bg-blue-100 text-blue-800',
                                    'activa' => 'bg-green-100 text-green-800',
                                    'pospuesta' => 'bg-yellow-100 text-yellow-800',
                                    'finalizada' => 'bg-gray-100 text-gray-800',
                                    'inhabilitada' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $estadoColors[$edicion->estado->value] ?? 'bg-gray-100 text-gray-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2
                                    @if($edicion->estado->value == 'planificada') bg-blue-500
                                    @elseif($edicion->estado->value == 'activa') bg-green-500
                                    @elseif($edicion->estado->value == 'pospuesta') bg-yellow-500
                                    @elseif($edicion->estado->value == 'finalizada') bg-gray-500
                                    @elseif($edicion->estado->value == 'inhabilitada') bg-red-500
                                    @endif">
                                </span>
                                {{ ucfirst($edicion->estado->value) }}
                            </span>

                            <!-- Dropdown para cambiar estado -->
                            <div class="relative inline-block ml-3">
                                <button type="button"
                                        class="text-xs text-gray-600 hover:text-gray-900 focus:outline-none"
                                        onclick="toggleEstadoDropdown()">
                                    <i class="fas fa-exchange-alt"></i> Cambiar
                                </button>
                                <div id="estado-dropdown"
                                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                    <div class="py-1">
                                        @foreach($estados as $estado)
                                        <form action="{{ route('admin.ediciones.cambiar-estado', $edicion) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="{{ $estado->value }}">
                                            <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $edicion->estado->value == $estado->value ? 'bg-gray-50 font-medium' : '' }}">
                                                <span class="inline-flex items-center">
                                                    <span class="w-2 h-2 rounded-full mr-2
                                                        @if($estado->value == 'planificada') bg-blue-500
                                                        @elseif($estado->value == 'activa') bg-green-500
                                                        @elseif($estado->value == 'pospuesta') bg-yellow-500
                                                        @elseif($estado->value == 'finalizada') bg-gray-500
                                                        @elseif($estado->value == 'inhabilitada') bg-red-500
                                                        @endif">
                                                    </span>
                                                    {{ ucfirst($estado->value) }}
                                                </span>
                                            </button>
                                        </form>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Evento</p>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $edicion->evento->nombre }}
                            </span>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $edicion->evento->categoria->nombre }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Curso Académico</p>
                        <p class="text-gray-900 font-medium">{{ $edicion->curso }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Período</p>
                        <p class="text-gray-900 font-medium">{{ $edicion->periodo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Inicio</p>
                        <p class="text-gray-900 font-medium">{{ $edicion->fecha_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Fin</p>
                        <p class="text-gray-900 font-medium">{{ $edicion->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Duración</p>
                        <p class="text-gray-900 font-medium">
                            @php
                                $dias = $edicion->fecha_inicio->diffInDays($edicion->fecha_fin) + 1;
                            @endphp
                            {{ $dias }} {{ $dias == 1 ? 'día' : 'días' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Creación</p>
                        <p class="text-gray-900">{{ $edicion->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($edicion->descripcion)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Descripción</p>
                    <p class="text-gray-700">{{ $edicion->descripcion }}</p>
                </div>
                @endif
            </div>

<div class="mt-6">
    <p class="text-sm text-gray-500 mb-2">Modalidades</p>
    <div class="flex flex-wrap gap-2">
        @forelse($edicion->modalidades as $modalidad)
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
            <i class="fas fa-layer-group mr-1"></i>
            {{ $modalidad->nombre }}
        </span>
        @empty
        <span class="text-sm text-gray-500">No hay modalidades asignadas</span>
        @endforelse
    </div>
</div>

            <!-- Progreso del tiempo -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Progreso del Tiempo</h3>
                <div class="space-y-4">
                    @php
                        $hoy = now();
                        $inicio = $edicion->fecha_inicio;
                        $fin = $edicion->fecha_fin;
                        $totalDias = $inicio->diffInDays($fin) + 1;

                        if ($hoy < $inicio) {
                            $porcentaje = 0;
                            $estado = 'Pendiente';
                            $diasRestantes = $hoy->diffInDays($inicio);
                            $mensaje = "Comienza en $diasRestantes días";
                        } elseif ($hoy > $fin) {
                            $porcentaje = 100;
                            $estado = 'Finalizado';
                            $diasTranscurridos = $inicio->diffInDays($fin) + 1;
                            $mensaje = "Finalizó hace " . $hoy->diffInDays($fin) . " días";
                        } else {
                            $diasTranscurridos = $inicio->diffInDays($hoy) + 1;
                            $porcentaje = min(100, round(($diasTranscurridos / $totalDias) * 100));
                            $estado = 'En curso';
                            $diasRestantes = $hoy->diffInDays($fin);
                            $mensaje = "Día $diasTranscurridos de $totalDias";
                        }
                    @endphp

                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ $estado }}</span>
                        <span class="text-sm text-gray-500">{{ $mensaje }}</span>
                    </div>

                    <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-uclv-primary rounded-full transition-all duration-500"
                             style="width: {{ $porcentaje }}%"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-center mt-4">
                        <div>
                            <p class="text-xs text-gray-500">Inicio</p>
                            <p class="text-sm font-medium">{{ $inicio->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Hoy</p>
                            <p class="text-sm font-medium">{{ $hoy->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fin</p>
                            <p class="text-sm font-medium">{{ $fin->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar con acciones y estadísticas -->
        <div class="space-y-6">
            <!-- Acciones rápidas -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.ediciones.edit', $edicion) }}"
                       class="w-full btn btn-primary inline-flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Edición
                    </a>

                    <!-- Dropdown para cambiar estado -->
                    <div class="relative">
                        <button type="button"
                                onclick="toggleEstadoDropdown()"
                                class="w-full btn btn-outline inline-flex items-center justify-center">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Cambiar Estado
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información del evento padre -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Evento</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nombre</p>
                        <p class="font-medium text-gray-900">{{ $edicion->evento->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Categoría</p>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $edicion->evento->categoria->nombre }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estado del Evento</p>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $edicion->evento->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $edicion->evento->habilitado ? 'Habilitado' : 'Inhabilitado' }}
                        </span>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="{{ route('admin.eventos.show', $edicion->evento) }}"
                           class="w-full btn btn-outline btn-sm inline-flex items-center justify-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Ver Evento
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Información del Sistema</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Edición ID:</span>
                        <span class="font-medium">{{ $edicion->id_edicion }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Evento ID:</span>
                        <span class="font-medium">{{ $edicion->evento_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Creada:</span>
                        <span class="font-medium">{{ $edicion->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Actualizada:</span>
                        <span class="font-medium">{{ $edicion->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleEstadoDropdown() {
        const dropdown = document.getElementById('estado-dropdown');
        dropdown.classList.toggle('hidden');
    }

    function confirmDelete(nombre, estado) {
        const estadosPermitidos = ['finalizada', 'inhabilitada', 'pospuesta'];
        if (!estadosPermitidos.includes(estado)) {
            alert(`No se puede eliminar una edición que está ${estado}. Solo se pueden eliminar ediciones finalizadas, inhabilitadas o pospuestas.`);
            return false;
        }

        return confirm(`¿Está seguro de eliminar la edición "${nombre}"? Esta acción no se puede deshacer.`);
    }

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('estado-dropdown');
        if (!event.target.closest('#estado-dropdown') && !event.target.closest('[onclick="toggleEstadoDropdown()"]')) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endsection
