{{-- resources/views/admin/eventos/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalles: ' . $evento->nombre)
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.eventos.index') }}" class="text-gray-700 hover:text-uclv-primary">Eventos</a>
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
                <i class="fas fa-calendar-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $evento->nombre }}</h1>
                <p class="text-gray-600">ID: {{ $evento->id_evento }} • Creado el {{ $evento->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.eventos.edit', $evento) }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('admin.eventos.index') }}"
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $evento->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $evento->habilitado ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $evento->habilitado ? 'Habilitado' : 'Inhabilitado' }}
                            </span>

                            <form action="{{ route('admin.eventos.toggle', $evento) }}" method="POST" class="inline ml-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="text-xs text-{{ $evento->habilitado ? 'yellow' : 'green' }}-600 hover:text-{{ $evento->habilitado ? 'yellow' : 'green' }}-800">
                                    {{ $evento->habilitado ? 'Inhabilitar' : 'Habilitar' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Categoría</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $evento->categoria->nombre }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ediciones</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $evento->ediciones->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Creación</p>
                        <p class="text-gray-900">{{ $evento->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Descripción</p>
                        <p class="text-gray-900">{{ $evento->descripcion ?: 'Sin descripción' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Última Actualización</p>
                        <p class="text-gray-900">{{ $evento->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Ediciones asociadas -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Ediciones de este Evento</h3>
                        <p class="text-sm text-gray-600 mt-1">Todas las ediciones programadas para este evento</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.ediciones.create') }}" class="btn btn-primary btn-sm inline-flex items-center">
                            <i class="fas fa-plus mr-1"></i>
                            Nueva Edición
                        </a>
                    </div>
                </div>

                @if($evento->ediciones->count() > 0)
                <div class="table-responsive">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fechas
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($evento->ediciones->sortByDesc('fecha_inicio') as $edicion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-day text-purple-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $edicion->nombre }}</div>
                                            <div class="text-sm text-gray-500">{{ $edicion->curso }} • {{ $edicion->periodo }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div>{{ $edicion->fecha_inicio->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">al {{ $edicion->fecha_fin->format('d/m/Y') }}</div>
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
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estadoColors[$edicion->estado->value] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($edicion->estado->value) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.ediciones.show', $edicion) }}" class="text-uclv-primary hover:text-[#269aa6] mr-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.ediciones.edit', $edicion) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">No hay ediciones asociadas</h3>
                    <p class="text-sm text-gray-500">Este evento no tiene ediciones programadas todavía.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.ediciones.create') }}" class="btn btn-primary inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Crear Edición
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar con acciones y estadísticas -->
        <div class="space-y-6">
            <!-- Acciones rápidas -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.eventos.edit', $evento) }}"
                       class="w-full btn btn-primary inline-flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Evento
                    </a>

                    <form action="{{ route('admin.eventos.toggle', $evento) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full btn btn-outline inline-flex items-center justify-center">
                            <i class="fas fa-{{ $evento->habilitado ? 'ban' : 'check-circle' }} mr-2"></i>
                            {{ $evento->habilitado ? 'Inhabilitar' : 'Habilitar' }} Evento
                        </button>
                    </form>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total de Ediciones</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $evento->ediciones->count() }}</p>
                    </div>
                    @php
                        $edicionesActivas = $evento->ediciones->where('estado', 'activa')->count();
                        $edicionesPlanificadas = $evento->ediciones->where('estado', 'planificada')->count();
                        $edicionesFinalizadas = $evento->ediciones->where('estado', 'finalizada')->count();
                    @endphp
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ediciones Activas</p>
                        <p class="text-2xl font-bold text-green-600">{{ $edicionesActivas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ediciones Planificadas</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $edicionesPlanificadas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ediciones Finalizadas</p>
                        <p class="text-2xl font-bold text-gray-600">{{ $edicionesFinalizadas }}</p>
                    </div>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Información del Sistema</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Evento ID:</span>
                        <span class="font-medium">{{ $evento->id_evento }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Creado:</span>
                        <span class="font-medium">{{ $evento->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Actualizado:</span>
                        <span class="font-medium">{{ $evento->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Categoría ID:</span>
                        <span class="font-medium">{{ $evento->categoria_id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
