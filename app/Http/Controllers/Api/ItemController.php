<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function get($id): JsonResponse
    {
        $ownerId = $this->getOwnerId();
        $item = Item::with('supplier')->where('owner_id', $ownerId)->find($id);
        
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json($item);
    }
}
