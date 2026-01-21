<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'nombre' => fake()->unique()->words(3, true), // Ej: "Juegos Criollos 2024"
        'descripcion' => fake()->sentence(),
        'habilitado' => true,
        // categoria_id se asignará en el seeder
    ];
}
}
