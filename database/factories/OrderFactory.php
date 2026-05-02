<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => \App\Models\Client::factory(),
            'employee_id' => \App\Models\Employee::factory(),
            'status' => 'Pending',
            'order_date' => fake()->date(),
            'delivery_date' => fake()->date(),
        ];
    }
}
