<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesOrderItem>
 */
class SalesOrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $product = Product::inRandomOrder()->first();
        return [
            'order_id' => null,
            'product_id' => $product?->id,
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $product ? $this->faker->randomFloat(2, 50, 4000) : 0,
        ];
    }
}
