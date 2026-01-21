@extends('layouts.app')

@section('title', 'Modalidades')
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.modalidades.index') }}" class="text-gray-700 hover:text-uclv-primary">Modalidades</a>
        </span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Modalidades</h1>
            <p class="text-gray-600 mt-1">Administra las modalidades de las ediciones</p>
        </div>
        <div>
            <a href="{{ route('admin.modalidades.create') }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nueva Modalidad
            </a>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <form method="GET" action="{{ route('admin.modalidades.index') }}" id="filtros-form">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Buscar modalidades..."
                           class="form-input">
                </div>
                <div>
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-input">
                        <option value="">Todos</option>
                        <option value="habilitado" {{ request('status') == 'habilitado' ? 'selected' : '' }}>
                            Habilitadas
                        </option>
                        <option value="inhabilitado" {{ request('status') == 'inhabilitado' ? 'selected' : '' }}>
                            Inhabilitadas
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Tabla de modalidades -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="table-responsive">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-uclv-primary">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Ediciones
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Fecha Creación
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($modalidades as $modalidad)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-uclv-primary flex items-center justify-center">
                                    <i class="fas fa-layer-group text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $modalidad->nombre }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $modalidad->descripcion }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $modalidad->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $modalidad->habilitado ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $modalidad->habilitado ? 'Habilitada' : 'Inhabilitada' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $modalidad->ediciones_count }}</div>
                            <div class="text-xs text-gray-500">ediciones asociadas</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $modalidad->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.modalidades.show', $modalidad) }}"
                                   class="text-uclv-primary hover:text-uclv-primary-dark" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.modalidades.edit', $modalidad) }}"
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
        @if($modalidades->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Mostrando {{ $modalidades->firstItem() }} a {{ $modalidades->lastItem() }} de {{ $modalidades->total() }} resultados
                </div>
                <div class="flex space-x-2">
                    {{ $modalidades->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Enviar formulario automáticamente al cambiar filtros
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filtros-form');
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');

        // Debounce para el campo de búsqueda
        let searchTimeout;
        searchInput?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 1000);
        });

        // Enviar inmediatamente al cambiar select
        statusSelect?.addEventListener('change', function() {
            form.submit();
        });
    });
</script>
@endsection
