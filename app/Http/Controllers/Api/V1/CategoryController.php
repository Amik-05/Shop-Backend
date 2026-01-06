<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index()
    {
        return Category::all();
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json([
            'message' => 'Категория создана',
            'data' => $category
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);
        return response()->json([
            'message' => 'Категория обновлена',
            'data' => $category
        ]);
    }

    public function destroy(int $id)
    {
        Category::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Категория удалена'
        ], 204);
    }
}
