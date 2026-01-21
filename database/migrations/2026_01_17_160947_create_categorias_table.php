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
    Schema::create('categorias', function (Blueprint $table) {
        $table->id('id_categoria'); // Clave primaria personalizada
        $table->string('nombre');
        $table->boolean('habilitado')->default(true); // true: Habilitada, false: Inhabilitada
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
