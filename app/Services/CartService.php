<?php

namespace App\Services;
use App\Models\Product;
use App\Models\CartItem;

class CartService
{

    public function getAll($perPage = 10)
    {
        return Product::paginate($perPage);
    }

    public function getById($id)
    {
        return Product::findOrFail($id);
    }

    public function checkStock($productId, $quantity)
    {
        $product = $this->getById($productId);

        if ($product->stock < $quantity)
        {
            throw new \Exception("Product {$product->name} is out of stock");
        }

        return true;
    }

    public function decrementStock($productId, $quantity)
    {
        $product = $this->getById($productId);

        $productId->decrement('stock', $quantity);

        return $product;
    }


}


