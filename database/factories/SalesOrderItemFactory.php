<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SalesOrder;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesOrderItem>
 */
class SalesOrderItemFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'sales_order_id' => SalesOrder::factory()->create()->id, // 🔥 Burayı düzelttik!
            'product_id' => $product->id,
            'quantity' => fake()->numberBetween(1, 5),
            'price' => $product->price ?? fake()->randomFloat(2, 50, 500),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
