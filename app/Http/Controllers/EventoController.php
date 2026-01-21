<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Categoria;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base con relaciones
        $query = Evento::with(['categoria'])
            ->withCount('ediciones');

        // Aplicar filtros si existen
        $this->aplicarFiltros($query, $request);

        // Ordenar y paginar
        $eventos = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // Obtener categorías para el filtro
        $categoriasFiltro = Categoria::all();

        return view('admin.eventos.index', compact('eventos', 'categoriasFiltro'));
    }

    /**
     * Aplica filtros a la consulta
     */
    private function aplicarFiltros($query, Request $request)
    {
        // Filtro por búsqueda (nombre)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('nombre', 'like', "%{$searchTerm}%");
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->input('categoria'));
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

    // Los demás métodos permanecen igual...
    public function create()
    {
        $categorias = Categoria::where('habilitado', true)->get();
        return view('admin.eventos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:eventos',
            'descripcion' => 'nullable|string|max:500',
            'categoria_id' => 'required|exists:categorias,id_categoria'
        ]);

        Evento::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'categoria_id' => $validated['categoria_id'],
            'habilitado' => true
        ]);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento creado exitosamente.');
    }

    public function show(Evento $evento)
    {
        $evento->load(['categoria', 'ediciones']);
        return view('admin.eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $categorias = Categoria::where('habilitado', true)->get();
        return view('admin.eventos.edit', compact('evento', 'categorias'));
    }

    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:eventos,nombre,' . $evento->id_evento . ',id_evento',
            'descripcion' => 'nullable|string|max:500',
            'categoria_id' => 'required|exists:categorias,id_categoria',
            'habilitado' => 'boolean'
        ]);

        try {
            $evento->update($validated);

            return redirect()->route('admin.eventos.index')
                ->with('success', 'Evento actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function destroy(Evento $evento)
    {
        if ($evento->ediciones()->count() > 0) {
            return redirect()->route('admin.eventos.index')
                ->with('error', 'No se puede eliminar el evento porque tiene ediciones asociadas.');
        }

        $evento->delete();

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento eliminado exitosamente.');
    }

    public function toggleStatus(Evento $evento)
    {
        try {
            $evento->update([
                'habilitado' => !$evento->habilitado
            ]);

            $message = $evento->habilitado
                ? 'Evento habilitado exitosamente.'
                : 'Evento inhabilitado exitosamente.';

            return redirect()->route('admin.eventos.index')
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.eventos.index')
                ->with('error', $e->getMessage());
        }
    }
}
