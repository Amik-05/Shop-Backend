<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Mockery\Exception;

class AdminOrderController extends Controller
{
    public function updateStatus(UpdateStatusRequest $request, Order $order)
    {

        $order->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Статус заказа обновлен',
            'data' => $order
        ]);

    }

    public function all()
    {
        return Order::with('items.product', 'user')->get();
    }


}
