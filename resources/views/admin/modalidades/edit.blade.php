{{-- resources/views/admin/modalidades/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Modalidad: ' . $modalidad->nombre)
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
            <span class="text-gray-500">Editar Modalidad</span>
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
                    <h2 class="text-lg font-semibold text-white">Editar Modalidad</h2>
                    <p class="text-sm text-white/80 mt-1">Modifique los datos de la modalidad</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.modalidades.update', $modalidad) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="form-label">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $modalidad->nombre) }}"
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
                              placeholder="Describe la modalidad...">{{ old('descripcion', $modalidad->descripcion) }}</textarea>

                    @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label class="form-label">Estado</label>
                    <div class="mt-2 space-y-3">
                        <div class="flex items-center">
                            <input type="radio"
                                   id="habilitado_true"
                                   name="habilitado"
                                   value="1"
                                   {{ old('habilitado', $modalidad->habilitado) ? 'checked' : '' }}
                                   class="h-4 w-4 text-uclv-primary focus:ring-uclv-primary border-gray-300">
                            <label for="habilitado_true" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="inline-flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    Habilitada
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    La modalidad estará disponible para asignar a ediciones
                                </span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio"
                                   id="habilitado_false"
                                   name="habilitado"
                                   value="0"
                                   {{ !old('habilitado', $modalidad->habilitado) ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="habilitado_false" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="inline-flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                    Inhabilitada
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    No se podrá asignar a nuevas ediciones
                                </span>
                            </label>
                        </div>
                    </div>

                    @error('habilitado')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Información de la modalidad -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Información de la Modalidad</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">ID de Modalidad</p>
                            <p class="text-sm font-medium">{{ $modalidad->id_modalidad }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Ediciones Asociadas</p>
                            <p class="text-sm font-medium">{{ $modalidad->ediciones->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fecha de Creación</p>
                            <p class="text-sm font-medium">{{ $modalidad->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Última Actualización</p>
                            <p class="text-sm font-medium">{{ $modalidad->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.modalidades.index') }}"
                   class="btn btn-outline inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <div class="space-x-3">
                    <a href="{{ route('admin.modalidades.show', $modalidad) }}"
                       class="btn btn-outline inline-flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        Ver Detalles
                    </a>
                    <button type="submit"
                            class="btn btn-primary inline-flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
