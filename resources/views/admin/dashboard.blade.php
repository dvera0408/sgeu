@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <span class="text-gray-500">Dashboard</span>
        </span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Cards de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Categorías -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Categorías</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\Categoria::count() }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-tags text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.categorias.index') }}" class="text-uclv-primary hover:text-uclv-primary-dark text-sm font-medium flex items-center">
                    Ver todas
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Total Eventos -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Eventos</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\Evento::count() }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <i class="fas fa-calendar-alt text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.eventos.index') }}" class="text-uclv-primary hover:text-uclv-primary-dark text-sm font-medium flex items-center">
                    Ver todos
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Total Ediciones -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Ediciones</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\Edicion::count() }}</h3>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <i class="fas fa-calendar-day text-2xl text-purple-500"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.ediciones.index') }}" class="text-uclv-primary hover:text-uclv-primary-dark text-sm font-medium flex items-center">
                    Ver todas
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!--Total Modalidades -->

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Modalidades</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\Modalidad::count() }}</h3>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <i class="fas fa-calendar-day text-2xl text-purple-500"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.modalidades.index') }}" class="text-uclv-primary hover:text-uclv-primary-dark text-sm font-medium flex items-center">
                    Ver todas
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Ediciones Próximas -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Eventos UCLV</h2>
            <p class="text-sm text-gray-600 mt-1">Ediciones planificadas y activas de eventos</p>
        </div>

        <div class="p-6">
            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Edición
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Evento
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Inicio
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $proximasEdiciones = App\Models\Edicion::with('evento')
                                ->whereIn('estado', ['activa', 'planificada'])
                                ->orderBy('fecha_inicio', 'asc')
                                ->take(5)
                                ->get();
                        @endphp

                        @forelse($proximasEdiciones as $edicion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $edicion->nombre }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-gray-900">{{ $edicion->evento->nombre ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-gray-900">{{ $edicion->fecha_inicio->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $estadoColors = [
                                        'planificada' => 'badge-primary',
                                        'activa' => 'badge-success',
                                    ];
                                @endphp
                                <span class="{{ $estadoColors[$edicion->estado->value] ?? 'badge-info' }}">
                                    {{ ucfirst($edicion->estado->value) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                                <p>No hay ediciones próximas programadas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('admin.ediciones.index') }}" class="btn btn-primary inline-flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Ver todas las ediciones
                </a>
            </div>
        </div>
    </div>

    <!-- Resumen Rápido -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Categorías más populares -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Categorías con más eventos</h3>
            <div class="space-y-4">
                @php
                    $categoriasPopulares = App\Models\Categoria::withCount('eventos')
                        ->orderByDesc('eventos_count')
                        ->take(5)
                        ->get();
                @endphp

                @foreach($categoriasPopulares as $categoria)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-uclv-primary flex items-center justify-center text-white mr-3">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $categoria->nombre }}</p>
                            <p class="text-sm text-gray-500">{{ $categoria->eventos_count }} eventos</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-medium {{ $categoria->habilitado ? 'text-green-600' : 'text-red-600' }}">
                            {{ $categoria->habilitado ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.categorias.create') }}" class="p-4 border border-gray-200 rounded-lg hover:border-uclv-primary hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-uclv-primary text-white mr-3">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Nueva Categoría</p>
                            <p class="text-sm text-gray-500">Crear nueva categoría</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.eventos.create') }}" class="p-4 border border-gray-200 rounded-lg hover:border-uclv-primary hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-uclv-primary text-white mr-3">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Nuevo Evento</p>
                            <p class="text-sm text-gray-500">Crear nuevo evento</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.ediciones.create') }}" class="p-4 border border-gray-200 rounded-lg hover:border-uclv-primary hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-uclv-primary text-white mr-3">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Nueva Edición</p>
                            <p class="text-sm text-gray-500">Crear nueva edición</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.modalidades.create') }}" class="p-4 border border-gray-200 rounded-lg hover:border-uclv-primary hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-uclv-primary text-white mr-3">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Nueva Modalidad</p>
                            <p class="text-sm text-gray-500">Crear nueva modalidad</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
