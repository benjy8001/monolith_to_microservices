<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_title' => $this->faker->text(30),
            'price' => $this->faker->numberBetween(10, 100),
            'influencer_revenue' => $this->faker->numberBetween(10, 100),
            'admin_revenue' => $this->faker->numberBetween(10, 100),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
