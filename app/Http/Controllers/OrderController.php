<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'cartItems.product'])
            ->get()
            ->map(function ($order) {
                $totalAmount = $order->cartItems->sum(function ($item) {
                    return $item->price * $item->quantity;
                });

                $itemsCount = $order->cartItems->count();
                $lastAddedToCart = $order->cartItems()->orderByDesc('created_at')->first()?->created_at;
                $completedOrderExists = $order->status === 'completed';

                return [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer->name,
                    'total_amount' => $totalAmount,
                    'items_count' => $itemsCount,
                    'last_added_to_cart' => $lastAddedToCart,
                    'completed_order_exists' => $completedOrderExists,
                    'created_at' => $order->created_at,
                ];
            });

        $orders = $orders->sortByDesc(function ($order) {
            return $order['completed_order_exists'] ? Order::where('id', $order['order_id'])->value('completed_at') : null;
        });

        return view('orders.index', ['orders' => $orders->values()->all()]);
    }
}
