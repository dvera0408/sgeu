<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Evento;
use App\Models\Edicion;
use App\Models\Modalidad;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
    // Llamar al seeder de usuario admin
        $this->call(AdminUserSeeder::class);
        // Crear modalidades
        $modalidades = Modalidad::factory(4)->create();

        // Crear 3 Categorías
        Categoria::factory(3)->create()->each(function ($categoria) use ($modalidades) {

            // Por cada categoría, crear 3 Eventos
            Evento::factory(3)->create(['categoria_id' => $categoria->id_categoria])
                ->each(function ($evento) use ($modalidades) {

                    // Por cada evento, crear 2 Ediciones
                    Edicion::factory(2)->create(['evento_id' => $evento->id_evento])
                        ->each(function ($edicion) use ($modalidades) {
                            // Asignar 1-2 modalidades aleatorias a cada edición
                            $edicion->modalidades()->attach(
                                $modalidades->random(rand(1, 2))->pluck('id_modalidad')->toArray()
                            );
                        });
                });
        });
    }
}
