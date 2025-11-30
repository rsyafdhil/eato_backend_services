<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Item;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Get all favorites for a user
public function index($user_id)
{
    try {
        $favorites = Favorite::where('user_id', $user_id)
            ->with('item.tenant')
            ->get();

        $items = $favorites->map(function ($favorite) {
            // Check if item exists
            if (!$favorite->item) {
                return null;
            }

            $previewImage = $favorite->item->preview_image;
            
            // Convert local path to URL
            if ($previewImage && !str_starts_with($previewImage, 'http')) {
                // Remove the full local path and keep only the filename
                $filename = basename($previewImage);
                $previewImage = url("storage/items/{$filename}");
            }
            
            return [
                'id' => $favorite->item->id,
                'item_name' => $favorite->item->item_name,
                'price' => $favorite->item->price,
                'preview_image' => $previewImage,
                'tenant_id' => $favorite->item->tenant_id,
                'tenant_name' => optional($favorite->item->tenant)->name ?? 'Unknown',
            ];
        })->filter(); // Remove null values

        return response()->json($items->values()); // Re-index array
    } catch (\Exception $e) {
        \Log::error('Error getting favorites: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return response()->json([
            'error' => 'Failed to get favorites',
            'message' => $e->getMessage()
        ], 500);
    }
}

    // Add item to favorites
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'item_id' => 'required|exists:items,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
        ]);

        return response()->json([
            'message' => 'Item added to favorites',
            'favorite' => $favorite
        ], 201);
    }

    // Remove item from favorites
    public function destroy($user_id, $item_id)
    {
        $deleted = Favorite::where('user_id', $user_id)
            ->where('item_id', $item_id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Item removed from favorites']);
        }

        return response()->json(['message' => 'Favorite not found'], 404);
    }
}