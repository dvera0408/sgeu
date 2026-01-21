<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)  // Agregar Request $request
    {
        // Consulta base
        $query = Categoria::withCount('eventos');

        // Aplicar filtros
        $this->aplicarFiltros($query, $request);

        // Paginar con parámetros de filtro
        $categorias = $query->paginate(10)
            ->appends($request->query());

        return view('admin.categorias.index', compact('categorias'));
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias',
        ]);

        Categoria::create([
            'nombre' => $validated['nombre'],
            'habilitado' => true
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $categoria->load(['eventos' => function($query) {
            $query->withCount('ediciones');
        }]);

        return view('admin.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id_categoria . ',id_categoria',
            'habilitado' => 'boolean'
        ]);

        try {
            $categoria->update($validated);

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría actualizada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        // Verificar si tiene eventos antes de eliminar
        if ($categoria->eventos()->count() > 0) {
            return redirect()->route('admin.categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene eventos asociados.');
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    /**
     * Cambiar estado de la categoría (habilitar/deshabilitar)
     */
    public function toggleStatus(Categoria $categoria)
    {
        try {
            $categoria->update([
                'habilitado' => !$categoria->habilitado
            ]);

            $message = $categoria->habilitado
                ? 'Categoría habilitada exitosamente.'
                : 'Categoría inhabilitada exitosamente.';

            return redirect()->route('admin.categorias.index')
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.categorias.index')
                ->with('error', $e->getMessage());
        }
    }
}
