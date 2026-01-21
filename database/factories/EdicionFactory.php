<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\EstadoEdicion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Edicion>
 */
class EdicionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $fechaInicio = fake()->dateTimeBetween('-1 year', '+1 year');

    // Sumar entre 1 y 30 días a la fecha de inicio
    $diasDuracion = fake()->numberBetween(1, 30);
    $fechaFin = (clone $fechaInicio)->modify("+{$diasDuracion} days");

    return [
        'nombre' => fake()->words(2, true) . ' ' . date('Y'),
        'estado' => fake()->randomElement(EstadoEdicion::cases())->value,
        'fecha_inicio' => $fechaInicio,
        'fecha_fin' => $fechaFin,
        'curso' => '2025-2026',
        'periodo' => fake()->randomElement(['1er Periodo', '2do Periodo']),
        'descripcion' => fake()->paragraph(),
        // evento_id se asignará en el seeder
    ];
}
}
