<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request, OrderService $orderService)
    {
        try
        {
            $order = $orderService->createFromCart($request->user()->id);
            return response()->json($order->load('items.product'), 201);
        }
        catch (\RangeException $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }

    public function index(Request $request)
    {
        return Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->get();
    }


}
