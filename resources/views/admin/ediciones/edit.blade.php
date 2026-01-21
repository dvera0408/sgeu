{{-- resources/views/admin/ediciones/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Edición: ' . $edicion->nombre)
@section('breadcrumbs')
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="{{ route('admin.ediciones.index') }}" class="text-gray-700 hover:text-uclv-primary">Ediciones</a>
        </span>
    </li>
    <li>
        <span class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <span class="text-gray-500">Editar Edición</span>
        </span>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <!-- Header del formulario -->
        <div class="px-6 py-4 border-b border-gray-200 bg-uclv-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">Editar Edición: {{ $edicion->nombre }}</h2>
                    <p class="text-sm text-white/80 mt-1">Modifique los datos de la edición</p>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.ediciones.update', $edicion) }}" method="POST" id="edicionForm">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="form-label">
                        Nombre de la Edición <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $edicion->nombre) }}"
                           required
                           class="form-input @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Festival Deportivo 2024, Foro Científico 2023...">

                    @error('nombre')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Evento -->
                <div>
                    <label for="evento_id" class="form-label">
                        Evento <span class="text-red-500">*</span>
                    </label>
                    <select id="evento_id"
                            name="evento_id"
                            required
                            class="form-input @error('evento_id') border-red-500 @enderror"
                            onchange="updateEventoInfo(this.value)">
                        <option value="">Seleccione un evento</option>
                        @foreach($eventos as $evento)
                        <option value="{{ $evento->id_evento }}"
                                {{ old('evento_id', $edicion->evento_id) == $evento->id_evento ? 'selected' : '' }}>
                            {{ $evento->nombre }} ({{ $evento->categoria->nombre }})
                        </option>
                        @endforeach
                    </select>

                    @error('evento_id')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror

                    <!-- Información del evento seleccionado -->
                    <div id="evento-info" class="hidden mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <div>
                                <p class="text-sm text-blue-800" id="evento-nombre"></p>
                                <p class="text-xs text-blue-600" id="evento-categoria"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="form-label">
                        Estado <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-500 ml-2">(Estado actual: {{ ucfirst($edicion->estado->value) }})</span>
                    </label>
                    <select id="estado"
                            name="estado"
                            required
                            class="form-input @error('estado') border-red-500 @enderror"
                            onchange="manejarEstadoCambio()">
                        @foreach($estados as $estado)
                        <option value="{{ $estado->value }}"
                                {{ old('estado', $edicion->estado->value) == $estado->value ? 'selected' : '' }}>
                            {{ ucfirst($estado->value) }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Mensaje de transición permitida -->
                    <div id="transicion-mensaje" class="mt-2 text-sm hidden"></div>

                    @error('estado')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Modalidades -->
                <div>
                    <label class="form-label mb-3">
                        Modalidades
                        <span class="text-sm text-gray-500 ml-2">
                            @if(in_array($edicion->estado->value, ['planificada', 'activa']))
                                (Las modalidades se pueden modificar)
                            @else
                                (Las modalidades solo se pueden modificar cuando la edición está Planificada o Activa)
                            @endif
                        </span>
                    </label>

                    <!-- Buscador de modalidades -->
                    <div class="mb-4">
                        <input type="text"
                               id="searchModalidades"
                               placeholder="Buscar modalidades..."
                               class="form-input"
                               @if(!in_array($edicion->estado->value, ['planificada', 'activa'])) disabled @endif>
                    </div>

                    <!-- Contenedor de modalidades -->
                    <div class="border border-gray-300 rounded-lg p-4 max-h-80 overflow-y-auto"
                         id="modalidadesContainer"
                         @if(!in_array($edicion->estado->value, ['planificada', 'activa'])) style="opacity: 0.6; pointer-events: none;" @endif>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($modalidades as $modalidad)
                            @php
                                $isChecked = in_array($modalidad->id_modalidad, old('modalidades', $edicion->modalidades->pluck('id_modalidad')->toArray() ?? []));
                            @endphp
                            <div class="modalidad-item" data-nombre="{{ strtolower($modalidad->nombre) }}">
                                <label class="flex items-start p-3 border border-gray-300 rounded-lg hover:border-uclv-primary hover:bg-blue-50 cursor-pointer transition-all duration-200
                                    {{ $isChecked ? 'border-uclv-primary bg-blue-50' : '' }}">
                                    <input type="checkbox"
                                           name="modalidades[]"
                                           value="{{ $modalidad->id_modalidad }}"
                                           id="modalidad_{{ $modalidad->id_modalidad }}"
                                           class="mt-1 h-4 w-4 text-uclv-primary border-gray-300 rounded focus:ring-uclv-primary focus:ring-2"
                                           {{ $isChecked ? 'checked' : '' }}
                                           @if(!in_array($edicion->estado->value, ['planificada', 'activa'])) disabled @endif>
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">{{ $modalidad->nombre }}</span>
                                                @if($modalidad->descripcion)
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($modalidad->descripcion, 80) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        @if($modalidades->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-layer-group text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">No hay modalidades disponibles</p>
                            <p class="text-sm text-gray-400 mt-1">Primero debes crear modalidades en la sección correspondiente</p>
                        </div>
                        @endif
                    </div>

                    @error('modalidades')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror

                    <!-- Resumen de selección -->
                    <div class="mt-3 flex items-center justify-between text-sm">
                        <div>
                            <span id="modalidadesSeleccionadas">{{ count(old('modalidades', $edicion->modalidades->pluck('id_modalidad')->toArray() ?? [])) }}</span> modalidades seleccionadas
                        </div>
                        <div class="flex space-x-2">
                            <button type="button"
                                    onclick="seleccionarTodas()"
                                    class="text-uclv-primary hover:text-uclv-primary-dark font-medium">
                                <i class="fas fa-check-double mr-1"></i> Seleccionar todas
                            </button>
                            <button type="button"
                                    onclick="deseleccionarTodas()"
                                    class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-times mr-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_inicio" class="form-label">
                            Fecha de Inicio
                            <span class="text-red-500" id="fecha_inicio_required">
                                @if(!in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta']))*@endif
                            </span>
                        </label>
                        <input type="date"
                               id="fecha_inicio"
                               name="fecha_inicio"
                               value="{{ old('fecha_inicio', $edicion->fecha_inicio ? $edicion->fecha_inicio->format('Y-m-d') : '') }}"
                               @if(in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta'])) disabled @endif
                               class="form-input @error('fecha_inicio') border-red-500 @enderror"
                               {{ !in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta']) ? 'required' : '' }}>

                        @if(in_array($edicion->estado->value, ['finalizada', 'inhabilitada']))
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i> Las fechas no se pueden modificar en este estado
                        </p>
                        @elseif($edicion->estado->value == 'pospuesta')
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i> Las fechas se deshabilitan cuando la edición está pospuesta
                        </p>
                        @endif

                        @error('fecha_inicio')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_fin" class="form-label">
                            Fecha de Fin
                            <span class="text-red-500" id="fecha_fin_required">
                                @if(!in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta']))*@endif
                            </span>
                        </label>
                        <input type="date"
                               id="fecha_fin"
                               name="fecha_fin"
                               value="{{ old('fecha_fin', $edicion->fecha_fin ? $edicion->fecha_fin->format('Y-m-d') : '') }}"
                               @if(in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta'])) disabled @endif
                               class="form-input @error('fecha_fin') border-red-500 @enderror"
                               {{ !in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta']) ? 'required' : '' }}>

                        <div id="duracion" class="mt-2 text-sm">
                            @if($edicion->fecha_inicio && $edicion->fecha_fin)
                                @php
                                    $dias = $edicion->fecha_inicio->diffInDays($edicion->fecha_fin) + 1;
                                @endphp
                                <span class="{{ $dias > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $dias }} {{ $dias == 1 ? 'día' : 'días' }}
                                </span>
                            @elseif($edicion->estado->value == 'pospuesta')
                                <span class="text-blue-600">
                                    <i class="fas fa-clock mr-1"></i> Fechas por definir
                                </span>
                            @endif
                        </div>

                        @error('fecha_fin')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Curso y Período -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="curso" class="form-label">
                            Curso Académico <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="curso_select"
                                   name="curso_select"
                                   required
                                   class="form-input @error('curso') border-red-500 @enderror">
                                <option value="">Seleccione un curso</option>
                                @php
                                    $currentYear = date('Y');
                                    $currentCurso = old('curso', $edicion->curso);
                                    $isCustom = true;

                                    // Verificar si el curso actual tiene el formato correcto
                                    if (preg_match('/^\d{4}-\d{4}$/', $currentCurso)) {
                                        $isCustom = false;
                                    }

                                    // Generar opciones para los próximos 5 años
                                    for($i = 0; $i < 5; $i++) {
                                        $year = $currentYear + $i;
                                        $cursoValue = ($year - 1) . '-' . $year;
                                @endphp
                                <option value="{{ $cursoValue }}" {{ $currentCurso == $cursoValue ? 'selected' : '' }}>
                                    {{ $cursoValue }}
                                </option>
                                @php } @endphp
                                <option value="personalizado" {{ $isCustom ? 'selected' : '' }}>Personalizado...</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Campo oculto para el valor real del curso -->
                        <input type="hidden" id="curso" name="curso" value="{{ $currentCurso }}">

                        <div id="curso-personalizado-container" class="{{ $isCustom ? '' : 'hidden' }} mt-2">
                            <input type="text"
                                   id="curso-personalizado"
                                   class="form-input"
                                   placeholder="Ej: 2023-2024, 2024-2025..."
                                   pattern="^\d{4}-\d{4}$"
                                   title="Formato: AAAA-AAAA (ej: 2024-2025)"
                                   value="{{ $isCustom ? $currentCurso : '' }}">
                            <p class="text-xs text-gray-500 mt-1">Formato: AAAA-AAAA (ej: 2024-2025)</p>
                        </div>

                        @error('curso')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="periodo" class="form-label">
                            Período <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="periodo"
                                   name="periodo"
                                   required
                                   class="form-input @error('periodo') border-red-500 @enderror">
                                <option value="">Seleccione un período</option>
                                <option value="1er Semestre" {{ old('periodo', $edicion->periodo) == '1er Semestre' ? 'selected' : '' }}>1er Semestre</option>
                                <option value="2do Semestre" {{ old('periodo', $edicion->periodo) == '2do Semestre' ? 'selected' : '' }}>2do Semestre</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        @error('periodo')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="form-label">
                        Descripción
                    </label>
                    <textarea id="descripcion"
                              name="descripcion"
                              rows="4"
                              class="form-input @error('descripcion') border-red-500 @enderror"
                              placeholder="Describe los detalles de esta edición...">{{ old('descripcion', $edicion->descripcion) }}</textarea>

                    @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror

                    <div class="text-xs text-gray-500 mt-1">
                        <span id="char-count">{{ strlen(old('descripcion', $edicion->descripcion)) }}</span>/1000 caracteres
                    </div>
                </div>

                <!-- Información de la edición -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Información de la Edición</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">ID de Edición</p>
                            <p class="text-sm font-medium">{{ $edicion->id_edicion }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Estado Actual</p>
                            <p class="text-sm font-medium">
                                @php
                                    $estadoColors = [
                                        'planificada' => 'text-yellow-600 bg-yellow-50',
                                        'activa' => 'text-green-600 bg-green-50',
                                        'finalizada' => 'text-blue-600 bg-blue-50',
                                        'inhabilitada' => 'text-red-600 bg-red-50',
                                        'pospuesta' => 'text-orange-600 bg-orange-50'
                                    ];
                                    $colorClass = $estadoColors[$edicion->estado->value] ?? 'text-gray-600 bg-gray-50';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $colorClass }}">
                                    {{ ucfirst($edicion->estado->value) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Fecha de Creación</p>
                            <p class="text-sm font-medium">{{ $edicion->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Última Actualización</p>
                            <p class="text-sm font-medium">{{ $edicion->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Alertas de validación -->
                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Errores de validación</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Acciones del formulario -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <div>
                    <a href="{{ route('admin.ediciones.index') }}"
                       class="btn btn-outline inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                    <a href="{{ route('admin.ediciones.show', $edicion) }}"
                       class="btn btn-outline inline-flex items-center ml-3">
                        <i class="fas fa-eye mr-2"></i>
                        Ver Detalles
                    </a>
                </div>
                <div class="space-x-3">
                    <button type="button"
                            onclick="resetearFormulario()"
                            class="btn btn-outline inline-flex items-center">
                        <i class="fas fa-undo mr-2"></i>
                        Restablecer
                    </button>
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
    // Datos de eventos para JavaScript
    const eventosData = {
        @foreach($eventos as $evento)
        "{{ $evento->id_evento }}": {
            nombre: "{{ $evento->nombre }}",
            categoria: "{{ $evento->categoria->nombre }}"
        },
        @endforeach
    };

    function updateEventoInfo(eventoId) {
        const infoDiv = document.getElementById('evento-info');
        const nombreSpan = document.getElementById('evento-nombre');
        const categoriaSpan = document.getElementById('evento-categoria');

        if (eventoId && eventosData[eventoId]) {
            nombreSpan.textContent = eventosData[eventoId].nombre;
            categoriaSpan.textContent = eventosData[eventoId].categoria;
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    }

    // Calcular duración entre fechas
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');
    const duracionDiv = document.getElementById('duracion');

    function calcularDuracion() {
        const inicio = new Date(fechaInicioInput.value);
        const fin = new Date(fechaFinInput.value);

        if (inicio && fin && inicio <= fin) {
            const diffTime = Math.abs(fin - inicio);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays === 1) {
                duracionDiv.innerHTML = '<span class="text-green-600">1 día</span>';
            } else {
                duracionDiv.innerHTML = `<span class="text-green-600">${diffDays} días</span>`;
            }
        } else if (inicio && fin && inicio > fin) {
            duracionDiv.innerHTML = '<span class="text-red-600">La fecha de fin debe ser posterior a la fecha de inicio</span>';
        } else {
            // Si no hay fechas válidas, mostrar información según estado
            const estado = document.getElementById('estado').value;
            if (estado === 'pospuesta') {
                duracionDiv.innerHTML = '<span class="text-blue-600"><i class="fas fa-clock mr-1"></i> Fechas por definir</span>';
            } else {
                duracionDiv.innerHTML = '<span class="text-gray-500">Ingrese ambas fechas para calcular la duración</span>';
            }
        }
    }

    fechaInicioInput.addEventListener('change', calcularDuracion);
    fechaFinInput.addEventListener('change', calcularDuracion);

    // Contador de caracteres para descripción
    const descripcionTextarea = document.getElementById('descripcion');
    const charCount = document.getElementById('char-count');

    descripcionTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;

        if (length > 1000) {
            this.classList.add('border-red-500');
            charCount.classList.add('text-red-600');
        } else {
            this.classList.remove('border-red-500');
            charCount.classList.remove('text-red-600');
        }
    });

    // Funcionalidad para modalidades
    const searchModalidadesInput = document.getElementById('searchModalidades');
    const modalidadesContainer = document.getElementById('modalidadesContainer');
    const modalidadesSeleccionadasSpan = document.getElementById('modalidadesSeleccionadas');

    function actualizarContadorModalidades() {
        const checkboxes = document.querySelectorAll('input[name="modalidades[]"]:checked');
        modalidadesSeleccionadasSpan.textContent = checkboxes.length;

        // Actualizar estilos de los items seleccionados
        document.querySelectorAll('.modalidad-item').forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            const label = item.querySelector('label');

            if (checkbox.checked) {
                label.classList.add('border-uclv-primary', 'bg-blue-50');
            } else {
                label.classList.remove('border-uclv-primary', 'bg-blue-50');
            }
        });
    }

    function filtrarModalidades() {
        const searchTerm = searchModalidadesInput.value.toLowerCase();

        document.querySelectorAll('.modalidad-item').forEach(item => {
            const nombre = item.getAttribute('data-nombre');
            const textContent = item.textContent.toLowerCase();

            if (nombre.includes(searchTerm) || textContent.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function seleccionarTodas() {
        document.querySelectorAll('.modalidad-item').forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (!checkbox.disabled) {
                checkbox.checked = true;
            }
        });
        actualizarContadorModalidades();
    }

    function deseleccionarTodas() {
        document.querySelectorAll('.modalidad-item').forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (!checkbox.disabled) {
                checkbox.checked = false;
            }
        });
        actualizarContadorModalidades();
    }

    // Event Listeners para modalidades
    searchModalidadesInput.addEventListener('input', filtrarModalidades);

    // Actualizar contador cuando cambian los checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[name="modalidades[]"]')) {
            actualizarContadorModalidades();
        }
    });

    // Manejar el cambio de estado
    const estadoSelect = document.getElementById('estado');
    const fechaInicioRequired = document.getElementById('fecha_inicio_required');
    const fechaFinRequired = document.getElementById('fecha_fin_required');
    const transicionMensaje = document.getElementById('transicion-mensaje');

    // Definir transiciones permitidas
    const transicionesPermitidas = {
        'planificada': ['activa', 'pospuesta'],
        'activa': ['pospuesta', 'finalizada'],
        'finalizada': ['inhabilitada'],
        'inhabilitada': ['finalizada'],
        'pospuesta': ['planificada', 'activa']
    };

    const estadoActual = "{{ $edicion->estado->value }}";

    function mostrarMensajeTransicion(nuevoEstado) {
        if (estadoActual === nuevoEstado) {
            transicionMensaje.classList.add('hidden');
            return;
        }

        const permitidos = transicionesPermitidas[estadoActual] || [];

        if (permitidos.includes(nuevoEstado)) {
            transicionMensaje.innerHTML = `
                <div class="flex items-center text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Transición permitida: ${estadoActual} → ${nuevoEstado}</span>
                </div>
            `;
            transicionMensaje.classList.remove('hidden', 'bg-red-50', 'text-red-600', 'border-red-200');
            transicionMensaje.classList.add('bg-green-50', 'text-green-600', 'border', 'border-green-200', 'p-2', 'rounded');
        } else {
            transicionMensaje.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <div>
                        <span class="font-medium">Transición no permitida</span>
                        <p class="text-sm">No se puede cambiar de "${estadoActual}" a "${nuevoEstado}".</p>
                        <p class="text-sm mt-1">Estados permitidos: ${permitidos.join(', ')}</p>
                    </div>
                </div>
            `;
            transicionMensaje.classList.remove('hidden', 'bg-green-50', 'text-green-600', 'border-green-200');
            transicionMensaje.classList.add('bg-red-50', 'text-red-600', 'border', 'border-red-200', 'p-2', 'rounded');
        }
    }

    function manejarEstadoCambio() {
        const estado = estadoSelect.value;

        // Mostrar mensaje de transición
        mostrarMensajeTransicion(estado);

        // Si el estado es "pospuesta", deshabilitar y limpiar fechas
        if (estado === 'pospuesta') {
            fechaInicioInput.value = '';
            fechaFinInput.value = '';
            fechaInicioInput.disabled = true;
            fechaFinInput.disabled = true;
            fechaInicioInput.removeAttribute('required');
            fechaFinInput.removeAttribute('required');
            fechaInicioRequired.innerHTML = '';
            fechaFinRequired.innerHTML = '';
            duracionDiv.innerHTML = '<span class="text-blue-600"><i class="fas fa-clock mr-1"></i> Fechas por definir</span>';

            // Deshabilitar modalidades
            searchModalidadesInput.disabled = true;
            modalidadesContainer.style.opacity = '0.6';
            modalidadesContainer.style.pointerEvents = 'none';
            document.querySelectorAll('input[name="modalidades[]"]').forEach(checkbox => {
                checkbox.disabled = true;
            });
        }
        // Si el estado es "finalizada" o "inhabilitada", mantener fechas pero deshabilitar
        else if (estado === 'finalizada' || estado === 'inhabilitada') {
            fechaInicioInput.disabled = true;
            fechaFinInput.disabled = true;
            fechaInicioInput.removeAttribute('required');
            fechaFinInput.removeAttribute('required');
            fechaInicioRequired.innerHTML = '';
            fechaFinRequired.innerHTML = '';

            // Deshabilitar modalidades
            searchModalidadesInput.disabled = true;
            modalidadesContainer.style.opacity = '0.6';
            modalidadesContainer.style.pointerEvents = 'none';
            document.querySelectorAll('input[name="modalidades[]"]').forEach(checkbox => {
                checkbox.disabled = true;
            });

            // Recalcular duración con fechas existentes
            if (fechaInicioInput.value && fechaFinInput.value) {
                calcularDuracion();
            }
        }
        // Para estados "planificada" o "activa"
        else {
            // Habilitar fechas
            fechaInicioInput.disabled = false;
            fechaFinInput.disabled = false;
            fechaInicioInput.setAttribute('required', 'required');
            fechaFinInput.setAttribute('required', 'required');
            fechaInicioRequired.innerHTML = '*';
            fechaFinRequired.innerHTML = '*';

            // Habilitar modalidades solo para estados planificada y activa
            if (estado === 'planificada' || estado === 'activa') {
                searchModalidadesInput.disabled = false;
                modalidadesContainer.style.opacity = '1';
                modalidadesContainer.style.pointerEvents = 'auto';
                document.querySelectorAll('input[name="modalidades[]"]').forEach(checkbox => {
                    checkbox.disabled = false;
                });
            } else {
                searchModalidadesInput.disabled = true;
                modalidadesContainer.style.opacity = '0.6';
                modalidadesContainer.style.pointerEvents = 'none';
                document.querySelectorAll('input[name="modalidades[]"]').forEach(checkbox => {
                    checkbox.disabled = true;
                });
            }

            // Recalcular duración si hay fechas
            if (fechaInicioInput.value && fechaFinInput.value) {
                calcularDuracion();
            } else {
                duracionDiv.innerHTML = '<span class="text-gray-500">Ingrese ambas fechas para calcular la duración</span>';
            }
        }
    }

    // Manejar selección de curso
    const cursoSelect = document.getElementById('curso_select');
    const cursoHiddenInput = document.getElementById('curso');
    const cursoPersonalizadoContainer = document.getElementById('curso-personalizado-container');
    const cursoPersonalizadoInput = document.getElementById('curso-personalizado');

    function manejarCursoSeleccionado() {
        const selectedValue = cursoSelect.value;

        if (selectedValue === 'personalizado') {
            cursoPersonalizadoContainer.classList.remove('hidden');
            cursoHiddenInput.value = cursoPersonalizadoInput.value || '';
        } else if (selectedValue) {
            cursoPersonalizadoContainer.classList.add('hidden');
            cursoHiddenInput.value = selectedValue;
        } else {
            cursoPersonalizadoContainer.classList.add('hidden');
            cursoHiddenInput.value = '';
        }
    }

    // Actualizar el campo oculto cuando cambia el input personalizado
    cursoPersonalizadoInput.addEventListener('input', function() {
        if (cursoSelect.value === 'personalizado') {
            cursoHiddenInput.value = this.value;
        }
    });

    cursoSelect.addEventListener('change', manejarCursoSeleccionado);

    // Validación de formulario
    const form = document.getElementById('edicionForm');

    form.addEventListener('submit', function(event) {
        const estado = estadoSelect.value;
        const inicio = new Date(fechaInicioInput.value);
        const fin = new Date(fechaFinInput.value);

        // Validar transición de estado
        if (estadoActual !== estado) {
            const permitidos = transicionesPermitidas[estadoActual] || [];
            if (!permitidos.includes(estado)) {
                event.preventDefault();
                alert(`No se puede cambiar de estado "${estadoActual}" a "${estado}".\n` +
                      `Estados permitidos: ${permitidos.join(', ')}`);
                estadoSelect.focus();
                return;
            }
        }

        // Validar fechas solo si no es pospuesta, finalizada o inhabilitada
        if (!['pospuesta', 'finalizada', 'inhabilitada'].includes(estado)) {
            if (!fechaInicioInput.value || !fechaFinInput.value) {
                event.preventDefault();
                alert('Las fechas de inicio y fin son requeridas para este estado');
                return;
            }

            if (inicio > fin) {
                event.preventDefault();
                alert('La fecha de fin debe ser posterior o igual a la fecha de inicio');
                fechaFinInput.focus();
                return;
            }
        }

        // Validar curso
        const cursoValue = cursoHiddenInput.value;
        if (!cursoValue) {
            event.preventDefault();
            alert('Por favor seleccione o ingrese un curso académico');
            return;
        }

        // Validar formato del curso
        const cursoRegex = /^\d{4}-\d{4}$/;
        if (!cursoRegex.test(cursoValue)) {
            event.preventDefault();
            alert('El curso debe tener el formato AAAA-AAAA (ej: 2024-2025)');
            if (cursoSelect.value === 'personalizado') {
                cursoPersonalizadoInput.focus();
            } else {
                cursoSelect.focus();
            }
            return;
        }

        // Validar período
        const periodoSelect = document.getElementById('periodo');
        if (!periodoSelect.value) {
            event.preventDefault();
            alert('Por favor seleccione un período');
            periodoSelect.focus();
            return;
        }

        // Validar que no se modifiquen modalidades en estados no permitidos
        if (!['planificada', 'activa'].includes(estado)) {
            // Obtener las modalidades originales
            const modalidadesOriginales = JSON.parse('{!! json_encode($edicion->modalidades->pluck("id_modalidad")->toArray()) !!}');
            const modalidadesActuales = Array.from(document.querySelectorAll('input[name="modalidades[]"]:checked'))
                .map(cb => parseInt(cb.value));

            // Ordenar para comparar
            modalidadesOriginales.sort((a, b) => a - b);
            modalidadesActuales.sort((a, b) => a - b);

            // Comparar arrays
            const sonIguales = JSON.stringify(modalidadesOriginales) === JSON.stringify(modalidadesActuales);

            if (!sonIguales) {
                event.preventDefault();
                alert('Las modalidades solo se pueden modificar cuando la edición está en estado Planificada o Activa.');
                return;
            }
        }
    });

    // Función para resetear el formulario
    function resetearFormulario() {
        if (confirm('¿Está seguro de que desea restablecer todos los cambios?')) {
            // Restablecer valores originales
            document.getElementById('nombre').value = "{{ old('nombre', $edicion->nombre) }}";
            document.getElementById('evento_id').value = "{{ old('evento_id', $edicion->evento_id) }}";
            document.getElementById('estado').value = "{{ old('estado', $edicion->estado->value) }}";
            document.getElementById('fecha_inicio').value = "{{ old('fecha_inicio', $edicion->fecha_inicio ? $edicion->fecha_inicio->format('Y-m-d') : '') }}";
            document.getElementById('fecha_fin').value = "{{ old('fecha_fin', $edicion->fecha_fin ? $edicion->fecha_fin->format('Y-m-d') : '') }}";

            // Restablecer curso
            const cursoValue = "{{ old('curso', $edicion->curso) }}";
            const cursoRegex = /^\d{4}-\d{4}$/;
            if (cursoRegex.test(cursoValue)) {
                // Verificar si está en las opciones
                let found = false;
                const options = cursoSelect.options;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value === cursoValue) {
                        cursoSelect.value = cursoValue;
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    cursoSelect.value = 'personalizado';
                }
            } else {
                cursoSelect.value = 'personalizado';
            }
            cursoHiddenInput.value = cursoValue;
            cursoPersonalizadoInput.value = cursoValue;

            // Restablecer período
            document.getElementById('periodo').value = "{{ old('periodo', $edicion->periodo) }}";

            // Restablecer descripción
            document.getElementById('descripcion').value = "{{ old('descripcion', $edicion->descripcion) }}";

            // Restablecer modalidades
            const modalidadesOriginales = JSON.parse('{!! json_encode(old("modalidades", $edicion->modalidades->pluck("id_modalidad")->toArray())) !!}');
            document.querySelectorAll('input[name="modalidades[]"]').forEach(checkbox => {
                checkbox.checked = modalidadesOriginales.includes(parseInt(checkbox.value));
            });

            // Actualizar UI
            manejarEstadoCambio();
            manejarCursoSeleccionado();
            actualizarContadorModalidades();
            calcularDuracion();
            charCount.textContent = document.getElementById('descripcion').value.length;

            alert('Formulario restablecido a los valores originales');
        }
    }

    // Inicializar información del evento si ya hay uno seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const selectedEventoId = document.getElementById('evento_id').value;
        if (selectedEventoId) {
            updateEventoInfo(selectedEventoId);
        }

        // Calcular duración si hay fechas
        calcularDuracion();

        // Inicializar contador de caracteres
        charCount.textContent = descripcionTextarea.value.length;

        // Inicializar funcionalidad de modalidades
        actualizarContadorModalidades();

        // Manejar el estado inicial
        manejarEstadoCambio();

        // Manejar el curso inicial
        manejarCursoSeleccionado();

        // Mostrar mensaje de transición inicial
        mostrarMensajeTransicion(estadoSelect.value);

        // Agregar efecto de hover a las modalidades
        document.querySelectorAll('.modalidad-item').forEach(item => {
            const label = item.querySelector('label');

            label.addEventListener('mouseenter', function() {
                if (!this.classList.contains('border-uclv-primary')) {
                    this.classList.add('border-gray-400');
                }
            });

            label.addEventListener('mouseleave', function() {
                this.classList.remove('border-gray-400');
            });

            // Permitir hacer clic en cualquier parte del label
            label.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    if (!checkbox.disabled) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                }
            });
        });
    });
</script>
@endsection
