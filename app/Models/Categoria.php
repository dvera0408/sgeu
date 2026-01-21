<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Categoria extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_categoria';
    protected $fillable = ['nombre', 'habilitado'];

    // Relación: Una categoría tiene muchos eventos
    public function eventos()
    {
        return $this->hasMany(Evento::class, 'categoria_id', 'id_categoria');
    }

    // LÓGICA DE NEGOCIO Y REGLAS
    protected static function booted()
    {
        static::updating(function ($categoria) {
            // Regla: Si se intenta inhabilitar (habilitado pasa a false)
            if ($categoria->isDirty('habilitado') && $categoria->habilitado == false) {

                // Verificar si hay ediciones NO finalizadas en sus eventos hijos
                $tieneEdicionesActivas = $categoria->eventos()->whereHas('ediciones', function($query){
                    $query->whereNotIn('estado', ['finalizada', 'inhabilitada']);
                })->exists();

                if ($tieneEdicionesActivas) {
                    throw ValidationException::withMessages([
                        'habilitado' => 'No se puede inhabilitar la categoría porque tiene eventos con ediciones activas o pendientes.'
                    ]);
                }

                // Cascada: Si pasa la validación, inhabilitar eventos hijos
                $categoria->eventos()->update(['habilitado' => false]);
                // Opcional: Inhabilitar también las ediciones de esos eventos
                foreach($categoria->eventos as $evento) {
                    $evento->ediciones()->update(['estado' => 'inhabilitada']);
                }
            }
        });
    }
}
