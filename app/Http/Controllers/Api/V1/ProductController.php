<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getAll($request);
        return ProductResource::collection($products);
    }
    public function show($id)
    {
        $products = $this->productService->getById($id);
        return new ProductResource($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $updatedProduct = $this->productService->update(
            $product,
            $request->validated()
        );
        return response()->json([
            'message' => 'Информация о товаре обновлена',
            'product' => new ProductResource($updatedProduct)
        ]);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return response()->json(['message' => 'Товар удален'], 204);
    }
}
