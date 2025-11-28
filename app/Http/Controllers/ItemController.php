<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\SubCategory;
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
        return view('items.create', compact([
            'categories',
            'subCategories'
        ]));
    }

    public function store(Request $request)
    {
        $imagePath = null;
        if ($request->hasFile('preview_image')) {
            $imagePath = $request->file('preview_image')->store('items', 'public');
        }

        Item::create([
            'item_name' => $request->input('item_name'),
            'description' => $request->input('description'),
            'slug' => $request->input('slug'),
            'category_item_id' => $request->input('category_item_id'),
            'sub_category_item_id' => $request->input('sub_category_item_id'),
            'price' => $request->input('price'),
            'preview_image' => $imagePath,
        ]);

        return redirect()->route('fe.items.index')->with(['success' => 'Item created successfully']);
    }
}
