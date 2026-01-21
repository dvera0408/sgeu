<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('ediciones', function (Blueprint $table) {
        $table->id('id_edicion');
        $table->foreignId('evento_id')
              ->constrained('eventos', 'id_evento')
              ->cascadeOnDelete();
        $table->string('nombre'); // Ej: "Festival 2025"
        // Definimos el enum en la BD
        $table->enum('estado', ['planificada', 'activa', 'pospuesta', 'finalizada', 'inhabilitada'])
              ->default('planificada');
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->string('curso'); // Ej: "2024-2025"
        $table->string('periodo'); // Ej: "1er Semestre"
        $table->text('descripcion')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ediciones');
    }
};
