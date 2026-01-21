<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modalidad;
use Illuminate\Http\Request;

class ModalidadController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base
        $query = Modalidad::withCount('ediciones');

        // Aplicar filtros si existen
        $this->aplicarFiltros($query, $request);

        // Paginar con parámetros de filtro
        $modalidades = $query->paginate(10)
            ->appends($request->query());

        return view('admin.modalidades.index', compact('modalidades'));
    }

    /**
     * Aplica filtros a la consulta
     */
    private function aplicarFiltros($query, Request $request)
    {
        // Filtro por búsqueda (nombre, descripción)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%")
                  ->orWhere('descripcion', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'habilitado') {
                $query->where('habilitado', true);
            } elseif ($status === 'inhabilitado') {
                $query->where('habilitado', false);
            }
        }
    }

    public function create()
    {
        return view('admin.modalidades.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:modalidades',
            'descripcion' => 'nullable|string',
        ]);

        Modalidad::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'habilitado' => true,
        ]);

        return redirect()->route('admin.modalidades.index')
            ->with('success', 'Modalidad creada exitosamente.');
    }

    public function show(Modalidad $modalidad)
    {
        $modalidad->load(['ediciones' => function($query) {
            $query->with('evento.categoria');
        }]);
        return view('admin.modalidades.show', compact('modalidad'));
    }

    public function edit(Modalidad $modalidad)
    {
        return view('admin.modalidades.edit', compact('modalidad'));
    }

    public function update(Request $request, Modalidad $modalidad)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:modalidades,nombre,' . $modalidad->id_modalidad . ',id_modalidad',
            'descripcion' => 'nullable|string',
            'habilitado' => 'boolean'
        ]);

        $modalidad->update($validated);

        return redirect()->route('admin.modalidades.index')
            ->with('success', 'Modalidad actualizada exitosamente.');
    }

    public function destroy(Modalidad $modalidad)
    {
        if ($modalidad->ediciones()->count() > 0) {
            return redirect()->route('admin.modalidades.index')
                ->with('error', 'No se puede eliminar la modalidad porque tiene ediciones asociadas.');
        }

        $modalidad->delete();

        return redirect()->route('admin.modalidades.index')
            ->with('success', 'Modalidad eliminada exitosamente.');
    }

    public function toggleStatus(Modalidad $modalidad)
    {
        try {
            $modalidad->update([
                'habilitado' => !$modalidad->habilitado
            ]);

            $message = $modalidad->habilitado
                ? 'Modalidad habilitada exitosamente.'
                : 'Modalidad inhabilitada exitosamente.';

            return redirect()->route('admin.modalidades.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.modalidades.index')
                ->with('error', 'Error al cambiar el estado de la modalidad.');
        }
    }
}
