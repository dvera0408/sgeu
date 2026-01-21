{{-- resources/views/admin/categorias/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Categoría: ' . $categoria->nombre)
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
            <span class="text-gray-500">Editar Categoría</span>
        </span>
    </li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header del formulario -->
        <div class="px-6 py-4 border-b border-gray-200 uclv-bg-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Editar Categoría</h2>
                    <p class="text-sm text-white/80 mt-1">Modifique los datos de la categoría</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $categoria->nombre) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-uclv-primary focus:border-transparent transition duration-200 @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Deportivo, Cultural, Científico...">

                    @error('nombre')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <div class="mt-2 space-y-3">
                        <div class="flex items-center">
                            <input type="radio"
                                   id="habilitado_true"
                                   name="habilitado"
                                   value="1"
                                   {{ $categoria->habilitado ? 'checked' : '' }}
                                   class="h-4 w-4 text-uclv-primary focus:ring-uclv-primary border-gray-300">
                            <label for="habilitado_true" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="inline-flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    Habilitada
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    La categoría estará disponible para asignar nuevos eventos
                                </span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio"
                                   id="habilitado_false"
                                   name="habilitado"
                                   value="0"
                                   {{ !$categoria->habilitado ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="habilitado_false" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="inline-flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                    Inhabilitada
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    No se podrán asignar nuevos eventos a esta categoría
                                </span>
                            </label>
                        </div>
                    </div>

                    @error('habilitado')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror

                    @if($categoria->eventos()->whereHas('ediciones', function($q) {
                        $q->whereNotIn('estado', ['finalizada', 'inhabilitada']);
                    })->exists() && !$categoria->habilitado)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    Esta categoría tiene eventos con ediciones activas. Si la inhabilita, también se inhabilitarán todos sus eventos y ediciones.
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Información de la categoría -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Información de la Categoría</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">ID de Categoría</p>
                            <p class="text-sm font-medium">{{ $categoria->id_categoria }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Eventos Asociados</p>
                            <p class="text-sm font-medium">{{ $categoria->eventos_count }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fecha de Creación</p>
                            <p class="text-sm font-medium">{{ $categoria->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Última Actualización</p>
                            <p class="text-sm font-medium">{{ $categoria->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.categorias.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <div class="space-x-3">
                    <a href="{{ route('admin.categorias.show', $categoria) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
                        <i class="fas fa-eye mr-2"></i>
                        Ver Detalles
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white uclv-bg-primary hover:uclv-bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
