<?php

namespace App\Models;

use App\Enums\EstadoEdicion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edicion extends Model
{
    use HasFactory;

    protected $table = 'ediciones';
    protected $primaryKey = 'id_edicion';

    protected $fillable = [
        'nombre', 'estado', 'fecha_inicio', 'fecha_fin',
        'curso', 'periodo', 'descripcion', 'evento_id'
    ];

    protected $casts = [
        'estado' => EstadoEdicion::class,
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id', 'id_evento');
    }

    // Relación muchos a muchos con Modalidad
    public function modalidades()
    {
        return $this->belongsToMany(Modalidad::class, 'edicion_modalidad', 'edicion_id', 'modalidad_id');
    }
}
