<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'type' => $this->faker->randomElement([
                'critical_stock', 'new_order', 'invoice_late',
                'purchase_delayed', 'system_cron'
            ]),
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->sentence(),
            'is_read' => false,
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
