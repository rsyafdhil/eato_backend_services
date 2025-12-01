<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Item;
use App\Models\SubCategory;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category', 'sub_category')->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $tenants = Tenant::all();
        return view('items.create', compact([
            'categories',
            'subCategories',
            'tenants'
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'tenant_id' => 'required|integer|exists:tenants,id',
            'category_item_id' => 'nullable|integer|exists:categories,id',
            'sub_category_item_id' => 'nullable|integer|exists:sub_categories,id',
            'price' => 'required|integer',
            'preview_image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('preview_image')) {
            $imagePath = $request->file('preview_image')->store('items', 'public');
        }

        Item::create([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'slug' => $request->slug,
            'tenant_id' => $request->tenant_id,
            'category_item_id' => $request->category_item_id,
            'sub_category_item_id' => $request->sub_category_item_id,
            'price' => $request->price,
            'preview_image' => $imagePath,
        ]);

        return redirect()->route('fe.items.index')
            ->with('success', 'Item created successfully');
    }
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $subCategories = SubCategory::where('status', 1)->get();

        return view('items.edit', compact('item', 'categories', 'subCategories'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:items,slug,' . $id,
            'category_item_id' => 'required|exists:categories,id',
            'sub_category_item_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric|min:0',
            'preview_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('preview_image')) {
            // Delete old image if exists
            if ($item->preview_image && Storage::exists('public/' . $item->preview_image)) {
                Storage::delete('public/' . $item->preview_image);
            }

            $validated['preview_image'] = $request->file('preview_image')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()->route('fe.items.index')->with('success', 'Item berhasil diupdate');
    }

    /**
     * Display the specified item (API endpoint)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $item = Item::with(['category', 'sub_category'])->findOrFail($id);

            // Transform preview_image to full URL if it exists
            if ($item->preview_image) {
                $item->preview_image = url('storage/' . $item->preview_image);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item retrieved successfully',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all items (API endpoint for search)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $items = Item::with(['category', 'sub_category'])->get();

            // Transform preview_image to full URL for all items
            $items->transform(function ($item) {
                if ($item->preview_image) {
                    $item->preview_image = url('storage/' . $item->preview_image);
                }
                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
