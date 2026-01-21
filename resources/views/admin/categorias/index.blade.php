@extends('layouts.app')

@section('title', 'Categorías')
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.categorias.index') }}" class="text-gray-700 hover:text-uclv-primary">Categorías</a>
        </span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Categorías</h1>
            <p class="text-gray-600 mt-1">Administra las categorías de eventos universitarios</p>
        </div>
        <div>
            <a href="{{ route('admin.categorias.create') }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nueva Categoría
            </a>
        </div>
    </div>

<!-- Filtros y búsqueda -->
<form method="GET" action="{{ route('admin.categorias.index') }}" id="filtros-form">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="search" class="form-label">Buscar</label>
                <input type="text"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar categorías..."
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

    <!-- Tabla de categorías -->
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
                            Eventos
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
    @foreach($categorias as $categoria)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="shrink-0 h-10 w-10 rounded-lg bg-uclv-primary flex items-center justify-center">
                    <i class="fas fa-tag text-white"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $categoria->nombre }}</div>
                    <div class="text-sm text-gray-500">ID: {{ $categoria->id_categoria }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $categoria->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <span class="w-2 h-2 rounded-full mr-2 {{ $categoria->habilitado ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $categoria->habilitado ? 'Habilitada' : 'Inhabilitada' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $categoria->eventos_count }}</div>
            <div class="text-xs text-gray-500">eventos asociados</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $categoria->created_at->format('d/m/Y') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.categorias.show', $categoria) }}"
                   class="text-uclv-primary hover:text-[#269aa6]" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.categorias.edit', $categoria) }}"
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
@if($categorias->hasPages())
<div class="px-6 py-4 border-t border-gray-200">
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Mostrando {{ $categorias->firstItem() }} a {{ $categorias->lastItem() }} de {{ $categorias->total() }} resultados
        </div>
        <div class="flex space-x-2">
            {{ $categorias->appends(request()->query())->links('vendor.pagination.custom') }}
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
