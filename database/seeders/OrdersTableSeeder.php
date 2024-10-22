<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Create 10 orders with random data
        Order::factory()->count(10)->create();
    }
}
