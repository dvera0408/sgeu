<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModalidadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'Baile', 'Ponencia', 'Futbol',
                'Baloncesto', 'Canto', 'Obra'
            ]),
            'descripcion' => fake()->sentence(),
            'habilitado' => true,
        ];
    }
}
