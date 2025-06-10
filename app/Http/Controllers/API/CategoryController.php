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
        $categories = Category::all();
        return $this->successResponse($categories, 'Categories retrieved.');
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->successResponse($category, 'Category created.', 201);
    }

    public function show($id)
    {
        $category = Category::find($id);

        return $category
            ? $this->successResponse($category, 'Category retrieved.')
            : $this->notFoundResponse('Category not found.');
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return $this->notFoundResponse('Category not found.');
        }

        $category->update($request->validated());

        return $this->successResponse($category, 'Category updated.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (! $category) {
            return $this->notFoundResponse('Category not found.');
        }

        $category->delete();
        return $this->successResponse(null, 'Category deleted.');
    }
}
