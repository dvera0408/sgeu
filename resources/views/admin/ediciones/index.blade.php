{{-- resources/views/admin/ediciones/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Ediciones')
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.ediciones.index') }}" class="text-gray-700 hover:text-uclv-primary">Ediciones</a>
        </span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Ediciones</h1>
            <p class="text-gray-600 mt-1">Administra las ediciones de eventos universitarios</p>
        </div>
        <div>
            <a href="{{ route('admin.ediciones.create') }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nueva Edición
            </a>
        </div>
    </div>

<!-- Filtros y búsqueda simplificados -->
<form method="GET" action="{{ route('admin.ediciones.index') }}" id="filtros-form">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="form-label">Buscar</label>
                <input type="text"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar por nombre, curso o período..."
                       class="form-input">
            </div>
            <div>
                <label for="evento" class="form-label">Evento</label>
                <select id="evento" name="evento" class="form-input">
                    <option value="">Todos</option>
                    @foreach($eventosFiltro as $evento)
                    <option value="{{ $evento->id_evento }}"
                        {{ request('evento') == $evento->id_evento ? 'selected' : '' }}>
                        {{ $evento->nombre }} ({{ $evento->categoria->nombre }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-input">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                    <option value="{{ $estado->value }}"
                        {{ request('estado') == $estado->value ? 'selected' : '' }}>
                        {{ ucfirst($estado->value) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>

    <!-- Tabla de ediciones -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="table-responsive">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-uclv-primary">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Edición
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Evento
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Fechas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
               <tbody class="bg-white divide-y divide-gray-200" id="tabla-ediciones">
    @foreach($ediciones as $edicion)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="shrink-0 h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $edicion->nombre }}</div>
                    <div class="text-sm text-gray-500">{{ $edicion->curso }} • {{ $edicion->periodo }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $edicion->evento->nombre }}</div>
            <div class="text-xs text-gray-500">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-tag mr-1 text-xs"></i>
                    {{ $edicion->evento->categoria->nombre }}
                </span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                <div><i class="fas fa-play text-green-500 mr-1"></i> {{ $edicion->fecha_inicio->format('d/m/Y') }}</div>
                <div><i class="fas fa-flag-checkered text-red-500 mr-1"></i> {{ $edicion->fecha_fin->format('d/m/Y') }}</div>
            </div>
            <div class="text-xs text-gray-500">
                {{ $edicion->fecha_inicio->diffInDays($edicion->fecha_fin) + 1 }} días
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
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
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.ediciones.show', $edicion) }}"
                   class="text-uclv-primary hover:text-uclv-primary-dark" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.ediciones.edit', $edicion) }}"
                   class="text-blue-600 hover:text-blue-900" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
            </table>
        </div>

<!-- Paginación -->
@if($ediciones->hasPages())
<div class="px-6 py-4 border-t border-gray-200">
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Mostrando {{ $ediciones->firstItem() }} a {{ $ediciones->lastItem() }} de {{ $ediciones->total() }} resultados
        </div>
        <div class="flex space-x-2">
            {{ $ediciones->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endif

    </div>

<!-- Resumen de estados -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4">
    @php
        // Obtener estadísticas filtradas
        $estadosCount = [
            'planificada' => $ediciones->where('estado', 'planificada')->count(),
            'activa' => $ediciones->where('estado', 'activa')->count(),
            'pospuesta' => $ediciones->where('estado', 'pospuesta')->count(),
            'finalizada' => $ediciones->where('estado', 'finalizada')->count(),
            'inhabilitada' => $ediciones->where('estado', 'inhabilitada')->count(),
        ];

        $totalFiltrado = $ediciones->total();
    @endphp

    @foreach($estadosCount as $estado => $count)
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">{{ ucfirst($estado) }}</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $count }}</h3>
            </div>
            <div class="p-2 rounded-lg
                @if($estado == 'planificada') bg-blue-50 text-blue-500
                @elseif($estado == 'activa') bg-green-50 text-green-500
                @elseif($estado == 'pospuesta') bg-yellow-50 text-yellow-500
                @elseif($estado == 'finalizada') bg-gray-50 text-gray-500
                @elseif($estado == 'inhabilitada') bg-red-50 text-red-500
                @endif">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        @if($totalFiltrado > 0)
        <div class="mt-3">
            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full
                    @if($estado == 'planificada') bg-blue-500
                    @elseif($estado == 'activa') bg-green-500
                    @elseif($estado == 'pospuesta') bg-yellow-500
                    @elseif($estado == 'finalizada') bg-gray-500
                    @elseif($estado == 'inhabilitada') bg-red-500
                    @endif"
                    style="width: {{ ($count / $totalFiltrado) * 100 }}%">
                </div>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
</div>
@endsection

@section('scripts')
<script>
    // Enviar formulario automáticamente al cambiar filtros (opcional)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filtros-form');
        const searchInput = document.getElementById('search');
        const eventoSelect = document.getElementById('evento');
        const estadoSelect = document.getElementById('estado');

        // Debounce para el campo de búsqueda
        let searchTimeout;
        searchInput?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 1000);
        });

        // Enviar inmediatamente al cambiar selects
        eventoSelect?.addEventListener('change', function() {
            form.submit();
        });

        estadoSelect?.addEventListener('change', function() {
            form.submit();
        });
    });

</script>
@endsection
