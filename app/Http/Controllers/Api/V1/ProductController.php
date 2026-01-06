<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        return Product::all();
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    public function show(int $id)
    {
        return Product::findOrFail($id);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if (isset($data['name'])){
            $data['slug'] = Str::slug($data['name']);
        }
        $product->update($data);
        return response()->json([
            'message' => 'Информация о товаре обновлена',
            'product' => $product
        ]);
    }

    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();

        return response()->json(['message' => 'Товар удален'], 204);
    }
}
