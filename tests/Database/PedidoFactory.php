<?php

namespace Tests\Database;

use App\Models\Pedido;
use App\Models\User;
use App\Models\Tercero;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tercero_id' => Tercero::factory(),
            'direccion' => $this->faker->address(),
            'comentario' => $this->faker->optional()->sentence(),
            'contacto_id' => null,
            'maquina_id' => null,
            'fabricante_id' => null,
            'estado' => $this->faker->randomElement(['Borrador', 'En Proceso', 'Completado', 'Rechazado']),
            'motivo_rechazo' => null,
        ];
    }

    /**
     * Pedido en estado borrador
     */
    public function borrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Borrador',
            'motivo_rechazo' => null,
        ]);
    }

    /**
     * Pedido en proceso
     */
    public function enProceso(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'En Proceso',
            'motivo_rechazo' => null,
        ]);
    }

    /**
     * Pedido completado
     */
    public function completado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Completado',
            'motivo_rechazo' => null,
        ]);
    }

    /**
     * Pedido rechazado
     */
    public function rechazado(string $motivo = null): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Rechazado',
            'motivo_rechazo' => $motivo ?? $this->faker->sentence(),
        ]);
    }

    /**
     * Pedido con contacto
     */
    public function conContacto(): static
    {
        return $this->state(fn (array $attributes) => [
            'contacto_id' => 1,
        ]);
    }

    /**
     * Pedido con máquina
     */
    public function conMaquina(): static
    {
        return $this->state(fn (array $attributes) => [
            'maquina_id' => 1,
        ]);
    }

    /**
     * Pedido con fabricante
     */
    public function conFabricante(): static
    {
        return $this->state(fn (array $attributes) => [
            'fabricante_id' => 1,
        ]);
    }

    /**
     * Pedido urgente (con comentario de urgencia)
     */
    public function urgente(): static
    {
        return $this->state(fn (array $attributes) => [
            'comentario' => 'Pedido urgente - Entrega inmediata requerida',
            'estado' => 'En Proceso',
        ]);
    }

    /**
     * Pedido con dirección específica
     */
    public function conDireccion(string $direccion): static
    {
        return $this->state(fn (array $attributes) => [
            'direccion' => $direccion,
        ]);
    }

    /**
     * Pedido con comentario específico
     */
    public function conComentario(string $comentario): static
    {
        return $this->state(fn (array $attributes) => [
            'comentario' => $comentario,
        ]);
    }
}
