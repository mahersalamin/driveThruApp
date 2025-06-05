<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ItemSize;
use App\Http\Requests\StoreItemSizeRequest;
use App\Http\Requests\UpdateItemSizeRequest;

class ItemSizeController extends Controller
{
    public function index()
    {
        return ItemSize::with('item')->get();
    }

    public function store(StoreItemSizeRequest $request)
    {
        return ItemSize::create($request->validated());
    }

    public function show(ItemSize $itemSize)
    {
        return $itemSize->load('item');
    }

    public function update(UpdateItemSizeRequest $request, ItemSize $itemSize)
    {
        $itemSize->update($request->validated());
        return $itemSize;
    }

    public function destroy(ItemSize $itemSize)
    {
        $itemSize->delete();
        return response()->noContent();
    }
}

