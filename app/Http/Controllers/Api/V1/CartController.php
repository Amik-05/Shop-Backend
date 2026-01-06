<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer| min:1'
        ]);

        $cartItem = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $data['product_id'])
            ->with('product')
            ->first();


        if ($cartItem)
        {
            $cartItem->quantity += $data['quantity'];
            if ($cartItem->product->stock >= $cartItem->quantity )
            {
                $cartItem->save();
                return response()->json([
                    'message' => 'Товар добавлен в корзину',
                    'data' => $cartItem
                ]);
            }
        }
        else
        {
            $product = Product::where('id',$data['product_id'])->first();
            /*return response()->json([
                'message' => 'Товар добавлен в корзину',
                'data' => $product
            ]);*/
            if ($product->stock >= $data['quantity'])
            {
                $cartItem = CartItem::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity']
                ]);
                return response()->json([
                    'message' => 'Товар добавлен в корзину',
                    'data' => $cartItem
                ]);
            }
        }

        return response()->json([
            'message' => 'Недостаточно товара на складе'
        ], 409);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user->id !== $request->user()->id)
        {
            abort(403);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cartItem->product->stock >= $data['quantity'] )
        {
            $cartItem->update($data);
            return response()->json([
                'message' => 'Данные обновлены',
                'data' => $cartItem
            ]);
        }
        return response()->json([
            'message' => 'Недостаточно товара на складе'
        ], 409);

    }


    public function remove(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user->id !== $request->user()->id)
        {
            abort(403);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Товар удален из корзины'
        ]);

    }

}
