<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Evento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evento';
    protected $fillable = ['nombre', 'descripcion', 'habilitado', 'categoria_id'];

    // Relación Inversa
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id_categoria');
    }

    // Relación: Un evento tiene muchas ediciones
    public function ediciones()
    {
        return $this->hasMany(Edicion::class, 'evento_id', 'id_evento');
    }

    // LÓGICA DE NEGOCIO
    protected static function booted()
    {
        static::updating(function ($evento) {
            // Regla: Inhabilitar Evento
            if ($evento->isDirty('habilitado') && $evento->habilitado == false) {

                // Verificar ediciones activas
                $tieneEdicionesActivas = $evento->ediciones()
                    ->whereNotIn('estado', ['finalizada', 'inhabilitada'])
                    ->exists();

                if ($tieneEdicionesActivas) {
                    throw ValidationException::withMessages([
                        'habilitado' => 'No se puede inhabilitar el evento porque tiene ediciones en curso o planificadas.'
                    ]);
                }

                // Cascada: Inhabilitar ediciones hijas
                $evento->ediciones()->update(['estado' => 'inhabilitada']);
            }
        });
    }
}
