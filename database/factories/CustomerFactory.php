<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'birth_date' => fake()->date(),
            'vat_number' => fake()->unique()->numerify('###########'),
            'segment' => fake()->randomElement(['Bronze', 'Silver', 'Gold']),
            'loyalty_points' => fake()->numberBetween(0, 5000),
            'status' => fake()->randomElement(['Active', 'Passive']),
            'last_order_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
