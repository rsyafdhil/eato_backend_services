<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getTenants()
    {
        try {
            $tenants = Tenant::all()->map(function($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'description' => $tenant->description,
                    'preview_image' => $tenant->preview_image 
                        ? url('storage/' . $tenant->preview_image) 
                        : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Tenants retrieved successfully',
                'data' => $tenants
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tenants',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTenantById($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Tenant retrieved successfully',
                'data' => [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'description' => $tenant->description,
                    'preview_image' => $tenant->preview_image 
                        ? url('storage/' . $tenant->preview_image) 
                        : null,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getItemsByTenant($tenantId)
    {
        try {
            $items = Item::where('tenant_id', $tenantId)
                ->with(['category', 'sub_category'])
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'description' => $item->description,
                        'price' => $item->price,
                        'tenant_id' => $item->tenant_id,
                        'preview_image' => $item->preview_image 
                            ? url('storage/' . $item->preview_image) 
                            : null,
                        'category' => $item->category,
                        'sub_category' => $item->sub_category,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'message' => 'Items retrieved successfully',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}