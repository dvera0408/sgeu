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
            'nombre'      => $this->faker->unique()->catchPhrase(),
            'descripcion' => $this->faker->realTextBetween(60, 180),
            'habilitado'  => $this->faker->boolean(95),
            // categoria_id se asigna en seeder
        ];
    }
}
