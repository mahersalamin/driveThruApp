<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $items = Item::with('category', 'sizes')->get();
        return $this->successResponse($items);
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);
        return $this->successResponse($item, 'Item created.', 201);
    }

    public function show($id)
    {
        try {
            $item = Item::with('category', 'sizes')->findOrFail($id);
            return $this->successResponse($item, 'Item fetched successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Item not found.');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving item.', 500, ['exception' => $e->getMessage()]);
        }
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::find($id);

        if (! $item) {
            return $this->notFoundResponse('Item not found.');
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Optional: delete the old image if needed
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }

            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return $this->successResponse($item, 'Item updated.');
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (! $item) {
            return $this->notFoundResponse('Item not found.');
        }

        // Optional: delete image file if exists
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return $this->successResponse(null, 'Item deleted.');
    }
}
