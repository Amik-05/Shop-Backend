<?php

namespace App\Services;
use App\Filters\ProductFilter;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductService
{

    public function getAll(Request $request)
    {
        $filter = new ProductFilter($request);
        $query = $filter->apply(Product::query());
        return $query->paginate(10);
    }

    public function getById($id) : Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        if (!isset($data['name']))
        {
            $data['slug'] = Str::slug($data['name']);
        }
        return Product::create($data);
    }

    public function update(Product $product, array $data):Product
    {
        if (!isset($data['name']))
        {
            $data['slug'] = Str::slug($data['name']);
        }
        $product->update($data);
        return $product;

    }

    public function delete(Product $product) : void
    {
        $product->delete();
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


