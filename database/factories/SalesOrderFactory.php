<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class SalesOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->value('id')
                ?? Customer::factory()->create()->id,

            'status' => fake()->randomElement(['Pending', 'Approved', 'Completed']),
            'total' => 0,

            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
