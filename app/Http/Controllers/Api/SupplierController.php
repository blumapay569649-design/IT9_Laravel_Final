<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function get($id): JsonResponse
    {
        $ownerId = $this->getOwnerId();
        $supplier = Supplier::where('owner_id', $ownerId)->find($id);
        
        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }
}
