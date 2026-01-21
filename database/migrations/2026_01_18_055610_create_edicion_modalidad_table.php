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
        Schema::create('edicion_modalidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edicion_id')
                  ->constrained('ediciones', 'id_edicion')
                  ->onDelete('cascade');
            $table->foreignId('modalidad_id')
                  ->constrained('modalidades', 'id_modalidad')
                  ->onDelete('cascade');
            $table->timestamps();

            // Asegurar que no haya duplicados
            $table->unique(['edicion_id', 'modalidad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edicion_modalidad');
    }
};
