@extends('layouts.app')

@section('title', 'Nueva Modalidad')
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
            <span class="text-gray-500">Nueva Modalidad</span>
        </span>
    </li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <!-- Header del formulario -->
        <div class="px-6 py-4 border-b border-gray-200 bg-uclv-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Crear Nueva Modalidad</h2>
                    <p class="text-sm text-white/80 mt-1">Complete el formulario para registrar una nueva modalidad</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-layer-group text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.modalidades.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="form-label">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre') }}"
                           required
                           class="form-input @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Ponencia, Baloncesto, Fútbol...">

                    @error('nombre')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="form-label">
                        Descripción
                    </label>
                    <textarea id="descripcion"
                              name="descripcion"
                              rows="3"
                              class="form-input @error('descripcion') border-red-500 @enderror"
                              placeholder="Describe la modalidad...">{{ old('descripcion') }}</textarea>

                    @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Información adicional -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Información importante</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Las modalidades definen el formato de participación en las ediciones</li>
                                    <li>Una edición puede tener una o varias modalidades asignadas</li>
                                    <li>Ejemplos: Ponencia, Baloncesto, Fútbol, etc.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.modalidades.index') }}"
                   class="btn btn-outline inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="btn btn-primary inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Modalidad
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
