<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderItem>
 */
class PurchaseOrderItemFactory extends Factory
{
    use HasFactory;
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
            'quantity' => $this->faker->numberBetween(5, 100),
            'price' => $this->faker->randomFloat(2, 20, 2000),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),];


    }
}
