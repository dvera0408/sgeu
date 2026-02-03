<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\EstadoEdicion;


class EdicionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
    {
        $inicio = $this->faker->dateTimeBetween('-24 months', '+12 months');
        $duracionDias = $this->faker->numberBetween(3, 90);

        $fin = (clone $inicio)->modify("+{$duracionDias} days");

        $año = $inicio->format('Y');
        $mes = (int) $inicio->format('n');

        $curso = $mes <= 6
            ? "{$año}-" . ($año + 1)
            : ($año + 1) . "-{$año}";

        return [
            'nombre'       => $this->faker->catchPhrase() . ' ' . $año,
            'estado'       => $this->faker->randomElement(EstadoEdicion::cases())->value,
            'fecha_inicio' => $inicio,
            'fecha_fin'    => $fin,
            'curso'        => $curso,
            'periodo'      => match (true) {
                $mes <= 6   => '1er Semestre',
                $mes <= 12  => '2do Semestre',
                default     => 'Verano / Intensivo',
            },
            'descripcion'  => $this->faker->realTextBetween(80, 250),
            // evento_id se asigna en seeder
        ];
    }
}
