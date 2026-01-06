<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function createFromCart(int $userId) : Order
    {
        return DB::transaction(function () use ($userId)
        {
            $cartItems = CartItem::where('user_id', $userId)
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                abort(400, 'Cart is empty');
            }

            $order = Order::create([
                'user_id' => $userId,
                'status' => 'new',
                'total_price' => 0,
            ]);

            $total = 0;

            foreach ($cartItems as $item) {
                $product = $item->product;

                if ($product->stock < $item->quantity) {
                    abort(409, 'Not enough stock');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id'=> $product->id,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                ]);

                $product->decrement('stock', $item->quantity);

                $total += $product->price * $item->quantity;
            }

            $order->update(['total_price' => $total]);

            CartItem::where('user_id', $userId)->delete();

            return $order;
        });
    }

}
