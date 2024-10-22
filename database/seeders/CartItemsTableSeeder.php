<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CartItem;

class CartItemsTableSeeder extends Seeder
{
    public function run()
    {
        // Create 20 cart items with random data, linking to orders and products
        CartItem::factory()->count(20)->create();
    }
}
