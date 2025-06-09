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
        return $this->successResponse(Item::with('category','sizes')->get());
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

    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        if (! $item) return $this->notFoundResponse();

        $item->update($request->only('name', 'category_id', 'image_path'));
        return $this->successResponse($item, 'Item updated.');
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        if (! $item) return $this->notFoundResponse();

        $item->delete();
        return $this->successResponse(null, 'Item deleted.');
    }
}
