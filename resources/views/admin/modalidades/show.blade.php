{{-- resources/views/admin/modalidades/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalles: ' . $modalidad->nombre)
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.modalidades.index') }}" class="text-gray-700 hover:text-uclv-primary">Modalidades</a>
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
                <i class="fas fa-layer-group text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $modalidad->nombre }}</h1>
                <p class="text-gray-600">ID: {{ $modalidad->id_modalidad }} • Creada el {{ $modalidad->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.modalidades.edit', $modalidad) }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('admin.modalidades.index') }}"
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $modalidad->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $modalidad->habilitado ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $modalidad->habilitado ? 'Habilitada' : 'Inhabilitada' }}
                            </span>

                            <form action="{{ route('admin.modalidades.toggle', $modalidad) }}" method="POST" class="inline ml-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="text-xs text-{{ $modalidad->habilitado ? 'yellow' : 'green' }}-600 hover:text-{{ $modalidad->habilitado ? 'yellow' : 'green' }}-800">
                                    {{ $modalidad->habilitado ? 'Inhabilitar' : 'Habilitar' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ediciones Asociadas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $modalidad->ediciones->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Creación</p>
                        <p class="text-gray-900">{{ $modalidad->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Descripción</p>
                        <p class="text-gray-900">{{ $modalidad->descripcion ?: 'Sin descripción' }}</p>
                    </div>
                </div>
            </div>

            <!-- Ediciones asociadas -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Ediciones con esta Modalidad</h3>
                    <p class="text-sm text-gray-600 mt-1">Ediciones que utilizan esta modalidad</p>
                </div>

                @if($modalidad->ediciones->count() > 0)
                <div class="table-responsive">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Edición
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Evento
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fechas
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($modalidad->ediciones as $edicion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.ediciones.show', $edicion) }}" class="text-uclv-primary hover:text-uclv-primary-dark font-medium">
                                        {{ $edicion->nombre }}
                                    </a>
                                    <div class="text-sm text-gray-500">{{ $edicion->curso }} • {{ $edicion->periodo }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $edicion->evento->nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ $edicion->evento->categoria->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $edicion->fecha_inicio->format('d/m/Y') }} - {{ $edicion->fecha_fin->format('d/m/Y') }}
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
                    <p class="text-sm text-gray-500">Esta modalidad no ha sido asignada a ninguna edición.</p>
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
                    <a href="{{ route('admin.modalidades.edit', $modalidad) }}"
                       class="w-full btn btn-primary inline-flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Modalidad
                    </a>

                    <form action="{{ route('admin.modalidades.toggle', $modalidad) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full btn btn-outline inline-flex items-center justify-center">
                            <i class="fas fa-{{ $modalidad->habilitado ? 'ban' : 'check-circle' }} mr-2"></i>
                            {{ $modalidad->habilitado ? 'Inhabilitar' : 'Habilitar' }} Modalidad
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Información del Sistema</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Modalidad ID:</span>
                        <span class="font-medium">{{ $modalidad->id_modalidad }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Creada:</span>
                        <span class="font-medium">{{ $modalidad->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Actualizada:</span>
                        <span class="font-medium">{{ $modalidad->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
