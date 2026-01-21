<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    use HasFactory;
    protected $table = 'modalidades';
    protected $primaryKey = 'id_modalidad';
    protected $fillable = ['nombre', 'descripcion', 'habilitado'];

    // Relación muchos a muchos con Edicion
    public function ediciones()
    {
        return $this->belongsToMany(Edicion::class, 'edicion_modalidad', 'modalidad_id', 'edicion_id');
    }

    // Scope para modalidades habilitadas
    public function scopeHabilitadas($query)
    {
        return $query->where('habilitado', true);
    }
}
