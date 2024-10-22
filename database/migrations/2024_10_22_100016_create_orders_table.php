<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Order ID
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Foreign key to customers table
            $table->decimal('total_amount', 10, 2); // Total amount of the order
            $table->integer('items_count'); // Number of items in the order
            $table->timestamp('last_added_to_cart')->nullable(); // Last added item to cart
            $table->string('status')->default('pending'); // Order status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
