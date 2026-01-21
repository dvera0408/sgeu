<?php

namespace App\Http\Controllers;

use App\Models\Edicion;
use App\Models\Evento;
use App\Enums\EstadoEdicion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EdicionController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base con relaciones
        $query = Edicion::with(['evento.categoria'])
            ->orderBy('fecha_inicio', 'desc');

        // Aplicar filtros si existen
        $this->aplicarFiltros($query, $request);

        // Paginar resultados
        $ediciones = $query->paginate(10)
            ->appends($request->query()); // Mantener parámetros en paginación

        $estados = EstadoEdicion::cases();

        // Obtener eventos para el select de filtro
        $eventosFiltro = Evento::where('habilitado', true)
            ->with('categoria')
            ->get();

        return view('admin.ediciones.index', compact('ediciones', 'estados', 'eventosFiltro'));
    }

    /**
     * Aplica filtros a la consulta
     */
    private function aplicarFiltros($query, Request $request)
    {
        // Filtro por búsqueda (nombre, curso, período)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%")
                  ->orWhere('curso', 'like', "%{$searchTerm}%")
                  ->orWhere('periodo', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por evento
        if ($request->filled('evento')) {
            $query->where('evento_id', $request->input('evento'));
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }
    }

    public function create()
    {
        $eventos = Evento::where('habilitado', true)
            ->with('categoria')
            ->get();

        $estados = EstadoEdicion::cases();
        $modalidades = \App\Models\Modalidad::where('habilitado', true)->get();

        return view('admin.ediciones.create', compact('eventos', 'estados', 'modalidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'evento_id' => 'required|exists:eventos,id_evento',
            'estado' => 'required|in:' . implode(',', array_column(EstadoEdicion::cases(), 'value')),
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'curso' => 'required|string|max:50|regex:/^\d{4}-\d{4}$/',
            'periodo' => 'required|in:1er Semestre,2do Semestre',
            'descripcion' => 'nullable|string|max:1000',
            'modalidades' => 'nullable|array',
            'modalidades.*' => 'exists:modalidades,id_modalidad'
        ]);

        // Verificar que el evento esté habilitado
        $evento = Evento::find($validated['evento_id']);
        if (!$evento->habilitado) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se puede crear edición para un evento inhabilitado.');
        }

        // Si el estado es pospuesta, establecer fechas como null
        if ($validated['estado'] == 'pospuesta') {
            $validated['fecha_inicio'] = null;
            $validated['fecha_fin'] = null;
        }

        $edicion = Edicion::create($validated);

        // Sincronizar modalidades solo si el estado lo permite
        if (in_array($validated['estado'], ['planificada', 'activa'])) {
            if (isset($validated['modalidades'])) {
                $edicion->modalidades()->sync($validated['modalidades']);
            }
        }

        return redirect()->route('admin.ediciones.index')
            ->with('success', 'Edición creada exitosamente.');
    }

    public function show(Edicion $edicion)
    {
        $edicion->load('evento.categoria');
        $estados = EstadoEdicion::cases();

        return view('admin.ediciones.show', compact('edicion', 'estados'));
    }

    public function edit(Edicion $edicion)
    {
        $eventos = Evento::where('habilitado', true)->get();
        $estados = EstadoEdicion::cases();
        $modalidades = \App\Models\Modalidad::where('habilitado', true)->get();

        return view('admin.ediciones.edit', compact('edicion', 'eventos', 'estados', 'modalidades'));
    }

    public function update(Request $request, Edicion $edicion)
    {
        // Definir reglas de validación
        $rules = [
            'nombre' => 'required|string|max:255',
            'evento_id' => 'required|exists:eventos,id_evento',
            'descripcion' => 'nullable|string|max:1000',
            'curso' => 'required|string|max:50|regex:/^\d{4}-\d{4}$/',
            'periodo' => 'required|in:1er Semestre,2do Semestre',
            'modalidades' => 'nullable|array',
            'modalidades.*' => 'exists:modalidades,id_modalidad'
        ];

        // Validar transiciones de estado permitidas
        $rules['estado'] = [
            'required',
            Rule::in(array_column(EstadoEdicion::cases(), 'value')),
            function ($attribute, $value, $fail) use ($edicion) {
                $estadoActual = $edicion->estado->value;
                $transicionesPermitidas = $this->getTransicionesPermitidas($estadoActual);

                if ($estadoActual !== $value && !in_array($value, $transicionesPermitidas)) {
                    $fail("No se puede cambiar de estado '$estadoActual' a '$value'. Estados permitidos: " .
                          implode(', ', $transicionesPermitidas));
                }
            }
        ];

        // Reglas para fechas según estado
        if (in_array($edicion->estado->value, ['finalizada', 'inhabilitada'])) {
            // No se pueden modificar fechas en estos estados
            $rules['fecha_inicio'] = 'nullable';
            $rules['fecha_fin'] = 'nullable';
        } elseif ($request->input('estado') == 'pospuesta' || $edicion->estado->value == 'pospuesta') {
            // Para estado pospuesta, fechas son null
            $rules['fecha_inicio'] = 'nullable|date';
            $rules['fecha_fin'] = 'nullable|date|after_or_equal:fecha_inicio';
        } else {
            // Para otros estados, fechas normales
            $rules['fecha_inicio'] = 'required|date';
            $rules['fecha_fin'] = 'required|date|after_or_equal:fecha_inicio';
        }

        $validated = $request->validate($rules);

        // Verificar que el evento esté habilitado
        $evento = Evento::find($validated['evento_id']);
        if (!$evento->habilitado && $edicion->evento_id != $validated['evento_id']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se puede asignar a un evento inhabilitado.');
        }

        // Manejar fechas según estado
        if ($validated['estado'] == 'pospuesta') {
            $validated['fecha_inicio'] = null;
            $validated['fecha_fin'] = null;
        } elseif (in_array($edicion->estado->value, ['finalizada', 'inhabilitada'])) {
            // Mantener las fechas originales en estos estados
            $validated['fecha_inicio'] = $edicion->fecha_inicio;
            $validated['fecha_fin'] = $edicion->fecha_fin;
        }

        $edicion->update($validated);

        // Sincronizar modalidades solo si el estado lo permite
        if (in_array($edicion->estado->value, ['planificada', 'activa'])) {
            $edicion->modalidades()->sync($validated['modalidades'] ?? []);
        } else {
            // Si no está en estado que permite modificar modalidades, mantener las existentes
            // No hacemos sync para evitar modificar las modalidades
        }

        return redirect()->route('admin.ediciones.index')
            ->with('success', 'Edición actualizada exitosamente.');
    }

    public function destroy(Edicion $edicion)
    {
        if (!in_array($edicion->estado->value, ['finalizada', 'inhabilitada', 'pospuesta'])) {
            return redirect()->route('admin.ediciones.index')
                ->with('error', 'No se puede eliminar una edición que está activa o planificada.');
        }

        $edicion->delete();

        return redirect()->route('admin.ediciones.index')
            ->with('success', 'Edición eliminada exitosamente.');
    }

    public function cambiarEstado(Request $request, Edicion $edicion)
    {
        $request->validate([
            'estado' => [
                'required',
                Rule::in(array_column(EstadoEdicion::cases(), 'value')),
                function ($attribute, $value, $fail) use ($edicion) {
                    $estadoActual = $edicion->estado->value;
                    $transicionesPermitidas = $this->getTransicionesPermitidas($estadoActual);

                    if (!in_array($value, $transicionesPermitidas)) {
                        $fail("No se puede cambiar de estado '$estadoActual' a '$value'. Estados permitidos: " .
                              implode(', ', $transicionesPermitidas));
                    }
                }
            ]
        ]);

        $datos = ['estado' => $request->estado];

        // Si se cambia a pospuesta, establecer fechas como null
        if ($request->estado == 'pospuesta') {
            $datos['fecha_inicio'] = null;
            $datos['fecha_fin'] = null;
        }
        // Si se reactiva desde pospuesta, solicitar nuevas fechas si no las tiene
        elseif ($edicion->estado->value == 'pospuesta' && in_array($request->estado, ['planificada', 'activa'])) {
            // Aquí podrías agregar lógica para solicitar nuevas fechas
            // Por ahora, si no tiene fechas, mantenemos null
            if (!$edicion->fecha_inicio || !$edicion->fecha_fin) {
                // No actualizamos fechas, se mantienen null
                // En una implementación real, podrías redirigir a un formulario para establecer fechas
            }
        }

        $edicion->update($datos);

        return redirect()->route('admin.ediciones.show', $edicion)
            ->with('success', 'Estado de edición actualizado exitosamente.');
    }

    /**
     * Obtener transiciones de estado permitidas según estado actual
     */
    private function getTransicionesPermitidas($estadoActual)
    {
        $transiciones = [
            'planificada' => ['activa', 'pospuesta'],
            'activa' => ['pospuesta', 'finalizada'],
            'finalizada' => ['inhabilitada'],
            'inhabilitada' => ['finalizada'],
            'pospuesta' => ['planificada', 'activa'],
        ];

        return $transiciones[$estadoActual] ?? [];
    }
}
