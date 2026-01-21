{{-- resources/views/admin/categorias/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nueva Categoría')
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
            <span class="text-gray-500">Nueva Categoría</span>
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
                    <h2 class="text-lg font-semibold text-white">Crear Nueva Categoría</h2>
                    <p class="text-sm text-white/80 mt-1">Complete el formulario para registrar una nueva categoría de eventos</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-tags text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-uclv-primary focus:border-transparent transition duration-200 @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Deportivo, Cultural, Científico...">

                    @error('nombre')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror

                    <p class="mt-2 text-sm text-gray-500">
                        El nombre debe ser único y descriptivo del tipo de eventos que agrupará.
                    </p>
                </div>

                <!-- Estado (siempre habilitada al crear) -->
                <div class="hidden">
                    <input type="checkbox" id="habilitado" name="habilitado" value="1" checked>
                </div>

                <!-- Información adicional -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Información importante</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Las categorías sirven para organizar los eventos por tipo</li>
                                    <li>Una categoría puede estar habilitada o inhabilitada</li>
                                    <li>No se puede inhabilitar una categoría que tenga eventos con ediciones activas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.categorias.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white uclv-bg-primary hover:uclv-bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uclv-primary">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validación en tiempo real
    document.getElementById('nombre').addEventListener('input', function(e) {
        const input = e.target;
        const value = input.value.trim();

        if (value.length < 3) {
            input.classList.add('border-yellow-500');
            input.classList.remove('border-green-500');
        } else {
            input.classList.remove('border-yellow-500');
            input.classList.add('border-green-500');
        }
    });
</script>
@endsection
