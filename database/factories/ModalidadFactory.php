<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModalidadFactory extends Factory
{
public function definition(): array
    {
        return [
            'nombre'      => $this->faker->unique()->catchPhrase(),
            'descripcion' => $this->faker->realTextBetween(50, 140),
            'habilitado'  => true,
        ];
    }
}
