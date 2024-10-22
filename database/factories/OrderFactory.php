<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'items_count' => $this->faker->numberBetween(1, 10),
            'last_added_to_cart' => $this->faker->dateTimeThisMonth(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
        ];
    }
}
