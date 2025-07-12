<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use Image;
use File;
use App\Exports\Excel\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('store', 'LIKE', "%$search%")
                ->orWhere('location', 'LIKE', "%$search%");
            });
        }

        $query->orderBy('id', 'desc');
        $warehouses = $query->paginate(12); // or any per-page count

        foreach ($warehouses as $warehouse) {
            $lots = \App\Models\Lots::where('warehouse_id', $warehouse->id)->get();

            $warehouse->lots_count = $lots->count();
            $warehouse->products_count = $lots->pluck('product_id')->unique()->count();
            $warehouse->bag_quantity = $lots->sum('quantity_bags');
        }

        if ($request->ajax()) {
            return response()->json($warehouses);
        }

        return view('admin.pages.warehouse.list', compact('warehouses'));
    }

    public function create()
    {
        return view('admin.pages.warehouse.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store' => 'required|string|max:255',
            'about' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        Warehouse::create($validated);

        return response()->json(['message' => 'Warehouse added successfully!'], 200);
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.pages.warehouse.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'store' => 'required|string|max:255',
            'about' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->all());

        return response()->json(['message' => 'Warehouse updated successfully']);
    }


    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password incorrect!'], 403);
        }

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted successfully!']);
    }
}
