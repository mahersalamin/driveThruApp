<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ItemSize;
use App\Http\Requests\StoreItemSizeRequest;
use App\Http\Requests\UpdateItemSizeRequest;
use App\Traits\ApiResponseTrait;

class ItemSizeController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $sizes = ItemSize::with('item')->get();
        return $this->successResponse($sizes);
    }

    public function store(StoreItemSizeRequest $request)
    {
        $validated = $request->validated();

        $size = ItemSize::create($validated);

        return $this->successResponse($size, 'Size added.', 201);
    }

    public function show($itemId)
    {
        $sizes = ItemSize::where('item_id', $itemId)->get();

        if ($sizes->isEmpty()) {
            return $this->notFoundResponse('No sizes found for this item.');
        }

        return $this->successResponse($sizes);
    }

    public function update(UpdateItemSizeRequest $request, ItemSize $itemSize)
    {
        if (! $itemSize) {
            return $this->notFoundResponse('Size not found.');
        }

        $validated = $request->validated();

        $itemSize->update($validated);

        return $this->successResponse($itemSize, 'Size updated.');
    }

    public function destroy($id)
    {
        $size = ItemSize::find($id);

        if (! $size) {
            return $this->notFoundResponse('Size not found.');
        }

        $size->delete();

        return $this->successResponse(null, 'Size deleted.');
    }
}
