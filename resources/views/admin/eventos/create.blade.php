{{-- resources/views/admin/eventos/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nuevo Evento')
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
            <span class="text-gray-500">Nuevo Evento</span>
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
                    <h2 class="text-lg font-semibold text-white">Crear Nuevo Evento</h2>
                    <p class="text-sm text-white/80 mt-1">Complete el formulario para registrar un nuevo evento</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-calendar-plus text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.eventos.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="form-label">
                        Nombre del Evento <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre') }}"
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
                              placeholder="Describe brevemente el evento...">{{ old('descripcion') }}</textarea>

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
                        <option value="{{ $categoria->id_categoria }}" {{ old('categoria_id') == $categoria->id_categoria ? 'selected' : '' }}>
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

                <!-- Estado (siempre habilitado al crear) -->
                <div class="hidden">
                    <input type="checkbox" id="habilitado" name="habilitado" value="1" checked>
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
                                    <li>Los eventos deben estar asociados a una categoría</li>
                                    <li>Un evento puede tener múltiples ediciones (ej: Festival 2023, Festival 2024)</li>
                                    <li>No se puede inhabilitar un evento que tenga ediciones activas o planificadas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.eventos.index') }}"
                   class="btn btn-outline inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="btn btn-primary inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Evento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validación en tiempo real
    document.getElementById('nombre')?.addEventListener('input', function(e) {
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

    // Contador de caracteres para descripción
    const descripcionTextarea = document.getElementById('descripcion');
    const charCount = document.createElement('div');
    charCount.className = 'text-xs text-gray-500 mt-1';
    charCount.textContent = '0/500 caracteres';
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
