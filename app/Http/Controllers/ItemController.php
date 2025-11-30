<?php

namespace App\Http\Controllers;

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
}
