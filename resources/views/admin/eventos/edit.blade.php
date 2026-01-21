{{-- resources/views/admin/eventos/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Evento: ' . $evento->nombre)
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
            <span class="text-gray-500">Editar Evento</span>
        </span>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <!-- Header del formulario -->
        <div class="px-6 py-4 border-b border-gray-200 bg-uclv-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Editar Evento</h2>
                    <p class="text-sm text-white/80 mt-1">Modifique los datos del evento</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.eventos.update', $evento) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="form-label">
                        Nombre del Evento <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $evento->nombre) }}"
                           required
                           class="form-input @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Festival Deportivo, Foro Científico...">

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
                              placeholder="Describe brevemente el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>

                    @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria_id" class="form-label">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select id="categoria_id"
                            name="categoria_id"
                            required
                            class="form-input @error('categoria_id') border-red-500 @enderror">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}"
                                {{ (old('categoria_id', $evento->categoria_id) == $categoria->id_categoria) ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>

                    @error('categoria_id')
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
                                   {{ old('habilitado', $evento->habilitado) ? 'checked' : '' }}
                                   class="h-4 w-4 text-uclv-primary focus:ring-uclv-primary border-gray-300">
                            <label for="habilitado_true" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="inline-flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    Habilitado
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    El evento estará disponible para crear nuevas ediciones
                                </span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio"
                                   id="habilitado_false"
                                   name="habilitado"
                                   value="0"
                                   {{ !old('habilitado', $evento->habilitado) ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="habilitado_false" class="ml-3 block text-sm font-medium text-gray-700">
                                <span class="inline-flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                    Inhabilitado
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    No se podrán crear nuevas ediciones para este evento
                                </span>
                            </label>
                        </div>
                    </div>

                    @error('habilitado')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror

                    @php
                        $tieneEdicionesActivas = $evento->ediciones()
                            ->whereNotIn('estado', ['finalizada', 'inhabilitada'])
                            ->exists();
                    @endphp

                    @if($tieneEdicionesActivas && !$evento->habilitado)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    Este evento tiene ediciones activas o planificadas. Si lo inhabilita, también se inhabilitarán todas sus ediciones.
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Información del evento -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Información del Evento</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">ID del Evento</p>
                            <p class="text-sm font-medium">{{ $evento->id_evento }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Ediciones</p>
                            <p class="text-sm font-medium">{{ $evento->ediciones->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fecha de Creación</p>
                            <p class="text-sm font-medium">{{ $evento->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Última Actualización</p>
                            <p class="text-sm font-medium">{{ $evento->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.eventos.index') }}"
                   class="btn btn-outline inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <div class="space-x-3">
                    <a href="{{ route('admin.eventos.show', $evento) }}"
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

@section('scripts')
<script>
    // Contador de caracteres para descripción
    const descripcionTextarea = document.getElementById('descripcion');
    const charCount = document.createElement('div');
    charCount.className = 'text-xs text-gray-500 mt-1';
    charCount.textContent = `${descripcionTextarea.value.length}/500 caracteres`;
    descripcionTextarea.parentNode.appendChild(charCount);

    descripcionTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/500 caracteres`;

        if (length > 500) {
            this.classList.add('border-red-500');
            charCount.classList.add('text-red-600');
        } else {
            this.classList.remove('border-red-500');
            charCount.classList.remove('text-red-600');
        }
    });
</script>
@endsection
