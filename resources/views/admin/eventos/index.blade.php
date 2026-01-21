{{-- resources/views/admin/eventos/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Eventos')
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.eventos.index') }}" class="text-gray-700 hover:text-uclv-primary">Eventos</a>
        </span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Eventos</h1>
            <p class="text-gray-600 mt-1">Administra los eventos universitarios</p>
        </div>
        <div>
            <a href="{{ route('admin.eventos.create') }}"
               class="btn btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Evento
            </a>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <form method="GET" action="{{ route('admin.eventos.index') }}" id="filtros-form">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Buscar eventos..."
                           class="form-input">
                </div>
                <div>
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" name="categoria" class="form-input">
                        <option value="">Todas</option>
                        @foreach($categoriasFiltro as $categoria)
                        <option value="{{ $categoria->id_categoria }}"
                            {{ request('categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-input">
                        <option value="">Todos</option>
                        <option value="habilitado" {{ request('status') == 'habilitado' ? 'selected' : '' }}>
                            Habilitados
                        </option>
                        <option value="inhabilitado" {{ request('status') == 'inhabilitado' ? 'selected' : '' }}>
                            Inhabilitados
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Tabla de eventos -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="table-responsive">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-uclv-primary">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Categoría
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
                    @foreach($eventos as $evento)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-uclv-primary flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $evento->nombre }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $evento->descripcion }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $evento->categoria->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $evento->habilitado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $evento->habilitado ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $evento->habilitado ? 'Habilitado' : 'Inhabilitado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $evento->ediciones_count }}</div>
                            <div class="text-xs text-gray-500">ediciones</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $evento->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.eventos.show', $evento) }}"
                                   class="text-uclv-primary hover:text-uclv-primary-dark" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.eventos.edit', $evento) }}"
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
        @if($eventos->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Mostrando {{ $eventos->firstItem() }} a {{ $eventos->lastItem() }} de {{ $eventos->total() }} resultados
                </div>
                <div class="flex space-x-2">
                    {{ $eventos->appends(request()->query())->links('vendor.pagination.custom') }}
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
        const categoriaSelect = document.getElementById('categoria');
        const statusSelect = document.getElementById('status');

        // Debounce para el campo de búsqueda
        let searchTimeout;
        searchInput?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 1000);
        });

        // Enviar inmediatamente al cambiar selects
        categoriaSelect?.addEventListener('change', function() {
            form.submit();
        });

        statusSelect?.addEventListener('change', function() {
            form.submit();
        });
    });

</script>
@endsection
