{{-- resources/views/admin/categorias/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalles: ' . $categoria->nombre)
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.categorias.index') }}" class="text-gray-700 hover:text-uclv-primary">Categorías</a>
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
            <div class="p-3 rounded-lg uclv-bg-primary">
                <i class="fas fa-tags text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $categoria->nombre }}</h1>
                <p class="text-gray-600">ID: {{ $categoria->id_categoria }} • Creada el {{ $categoria->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categorias.edit', $categoria) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('admin.categorias.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información General</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estado</p>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $categoria->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $categoria->habilitado ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $categoria->habilitado ? 'Habilitada' : 'Inhabilitada' }}
                            </span>

                            <form action="{{ route('admin.categorias.toggle', $categoria) }}" method="POST" class="inline ml-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="text-xs text-{{ $categoria->habilitado ? 'yellow' : 'green' }}-600 hover:text-{{ $categoria->habilitado ? 'yellow' : 'green' }}-800">
                                    {{ $categoria->habilitado ? 'Inhabilitar' : 'Habilitar' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Eventos Asociados</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $categoria->eventos->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha de Creación</p>
                        <p class="text-gray-900">{{ $categoria->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Última Actualización</p>
                        <p class="text-gray-900">{{ $categoria->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Eventos asociados -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Eventos de esta Categoría</h3>
                    <p class="text-sm text-gray-600 mt-1">Eventos que pertenecen a esta categoría</p>
                </div>

                @if($categoria->eventos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ediciones
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categoria->eventos as $evento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="shrink-0 h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $evento->nombre }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $evento->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $evento->habilitado ? 'Habilitado' : 'Inhabilitado' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $evento->ediciones_count ?? $evento->ediciones->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.eventos.show', $evento) }}" class="text-uclv-primary hover:text-uclv-primary-dark">
                                        <i class="fas fa-eye"></i>
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
                    <h3 class="text-sm font-medium text-gray-900 mb-1">No hay eventos asociados</h3>
                    <p class="text-sm text-gray-500">Esta categoría no tiene eventos asignados todavía.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.eventos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white uclv-bg-primary hover:uclv-bg-primary-dark">
                            <i class="fas fa-plus mr-2"></i>
                            Crear Evento
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar con acciones y estadísticas -->
        <div class="space-y-6">
            <!-- Acciones rápidas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.categorias.edit', $categoria) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Categoría
                    </a>

                    <form action="{{ route('admin.categorias.toggle', $categoria) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
                            <i class="fas fa-{{ $categoria->habilitado ? 'ban' : 'check-circle' }} mr-2"></i>
                            {{ $categoria->habilitado ? 'Inhabilitar' : 'Habilitar' }} Categoría
                        </button>
                    </form>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total de Eventos</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $categoria->eventos->count() }}</p>
                    </div>
                    @php
                        $eventosHabilitados = $categoria->eventos->where('habilitado', true)->count();
                        $porcentajeHabilitados = $categoria->eventos->count() > 0 ? round(($eventosHabilitados / $categoria->eventos->count()) * 100) : 0;
                    @endphp
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Eventos Habilitados</p>
                        <div class="flex items-center">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ $porcentajeHabilitados }}%"></div>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ $porcentajeHabilitados }}%</span>
                        </div>
                    </div>
                    @php
                        $edicionesTotales = 0;
                        foreach($categoria->eventos as $evento) {
                            $edicionesTotales += $evento->ediciones->count();
                        }
                    @endphp
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total de Ediciones</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $edicionesTotales }}</p>
                    </div>
                </div>
            </div>

            <!-- Información del sistema -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Información del Sistema</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Categoría ID:</span>
                        <span class="font-medium">{{ $categoria->id_categoria }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Creada:</span>
                        <span class="font-medium">{{ $categoria->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Actualizada:</span>
                        <span class="font-medium">{{ $categoria->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
