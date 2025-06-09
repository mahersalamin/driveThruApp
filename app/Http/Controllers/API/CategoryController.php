<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Traits\ApiResponseTrait;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        return $this->successResponse(Category::all(), 'Categories retrieved.');
    }

    public function store(StoreCategoryRequest $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category = Category::create($request->only('name', 'description'));

        return $this->successResponse($category, 'Category created.', 201);
    }

    public function show($id)
    {
        $category = Category::find($id);

        return $category
            ? $this->successResponse($category)
            : $this->notFoundResponse('Category not found.');
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if (!$category) return $this->notFoundResponse();

        $category->update($request->only('name', 'description'));

        return $this->successResponse($category, 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponse(null, 'Category deleted.');
    }
}

