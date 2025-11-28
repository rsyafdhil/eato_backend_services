<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return response()->json([
            'message' => 'Daftar Tenants Berhasil Diperoleh',
            'data' => $tenants
        ]);
    }

    public function getTenants()
    {
        $tenants = Tenant::get();
        return view('tenants.index', compact('tenants'));
    }

    public function storeTenants(Request $request)
    {
        // dd($request->all());
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'preview_image' => 'nullable|string|max:255',
            'owner_id' => 'required|integer|exists:users,id'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validation->errors()
            ], 422);
        }

        $tenant = Tenant::create([
            'name' => $request->name,
            'preview_image' => $request->preview_image,
            'owner_id' => $request->owner_id
        ]);

        return response()->json([
            'message' => 'Tenant Created Successfully',
            'data' => $tenant
        ], 200);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'owner_id' => 'required|integer|exists:users,id'
        ]);
        // dd($request->all());

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $imagePath = $request->file('preview_image')->store('tenants', 'public');
        $tenant = Tenant::create([
            'name' => $request->name,
            'preview_image' => $imagePath,
            'description' => $request->description,
            'owner_id' => $request->owner_id
        ]);

        // dd($tenant);

        return redirect()->route('fe.tenants.index')->with('success', 'tenant created successfully.');
    }

    public function createPage()
    {
        $owners = $owners = User::where('role_id', 3)->get(); // contoh: role 3 = Owner
        return view('tenants.create', compact('owners'));
    }

    public function destroyFe(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('fe.tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    public function editPage($id)
    {
        $tenant = Tenant::findOrFail($id);
        $owners = User::where('role_id', 3)->get(); // contoh: role 3 = Owner
        return view('tenants.edit', compact('tenant', 'owners'));
    }
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'owner_id' => 'required|integer|exists:users,id'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        if ($request->hasFile('preview_image')) {
            $imagePath = $request->file('preview_image')->store('tenants', 'public');
            $tenant->preview_image = $imagePath;
        }

        $tenant->name = $request->name;
        $tenant->description = $request->description;
        $tenant->owner_id = $request->owner_id;
        $tenant->save();

        return redirect()->route('fe.tenants.index')->with('success', 'Tenant updated successfully.');
    }
}
