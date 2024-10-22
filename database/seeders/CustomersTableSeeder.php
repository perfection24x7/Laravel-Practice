<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        // Create 10 customers with random data
        Customer::factory()->count(10)->create();
    }
}
