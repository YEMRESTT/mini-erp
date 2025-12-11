<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PurchaseOrder;
use App\Models\Product;

class PurchaseOrderItemFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'purchase_order_id' => \App\Models\PurchaseOrder::factory(),
            'product_id'        => $product->id,
            'quantity'          => fake()->numberBetween(1, 50),
            'price'             => $product->price, // 🟢 fiyat artık gerçek ürün fiyatı
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }
}
