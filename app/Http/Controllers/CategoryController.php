<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|in:0,1'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        Category::create([
            'category_name' => $request->input('category_name'),
            'slug' => $request->input('slug'),
            'status' => $request->input('status')
        ]);

        return redirect()->route('fe.category.store')->with('success', 'category created successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|in:0,1'
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'slug' => $request->slug,
            'status' => $request->status
        ]);

        return redirect()->route('fe.category.index')->with('success', 'category edited successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('fe.category.index')->with('success', 'category deleted successfully');
    }

    public function getSubCategory()
    {
        $subCategories = SubCategory::get();
        return view('sub_categories.index', compact('subCategories'));
    }

    public function createSubCat()
    {
        $categories = Category::all();
        return view('sub_categories.create', compact('categories'));
    }

    public function storeSubCat(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sub_category_name' => 'string|max:255|required',
            'slug' => 'required|string|max:255',
            'parent_category_id' => 'required|integer|exists:categories,id',
            'status' => 'required|in:0,1'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        SubCategory::create([
            'sub_category_name' => $request->input('sub_category_name'),
            'slug' => $request->input('slug'),
            'parent_category_id' => $request->input('parent_category_id'),
            'status' => $request->input('status')
        ]);


        return redirect()->route('fe.subcat.index')->with('success', 'sub category created successfully');
    }

    public function editSubCat($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::all();

        return view('sub_categories.edit', compact([
            'subCategory',
            'categories'
        ]));
    }

    public function updateSubCat(Request $request, $id)
    {
        $subcat = SubCategory::findOrFail($id);

        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'parent_category_id' => 'required|integer|exists:categories,id',
            'status' => 'required|boolean',
        ]);
        // dd($request->all());

        $subcat->update([
            'sub_category_name' => $request->sub_category_name,
            'slug' => $request->slug,
            'parent_category_id' => $request->parent_category_id,
            'status' => $request->status,
        ]);

        return redirect()->route('fe.subcat.index')->with('success', 'sub category edited successfully');
    }

    public function destroySubCat($id)
    {
        $subcat = SubCategory::findOrFail($id);
        $subcat->delete();

        return redirect()->route('fe.subcat.index')->with('success', 'sub category deleted successfully');
    }
}
