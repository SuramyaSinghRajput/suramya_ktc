<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Lots;
use App\Models\Warehouse;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exports\Excel\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{

    // public function index(Request $request)
    // {
    //     $query = Product::query();

    //     if ($request->search) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('sku', 'LIKE', "%$search%")
    //                 ->orWhere('full_name', 'LIKE', "%$search%")
    //                 ->orWhere('common_name', 'LIKE', "%$search%");
    //         });
    //     }

    //     $sort_by = $request->get('sort_by', 'id');
    //     $sort_dir = $request->get('sort_dir', 'desc');
    //     $query->orderBy($sort_by, $sort_dir);

    //     $query->withSum('lots as stock_quantity', 'quantity_bags');

    //     $products = $query->paginate(100);

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'data' => $products->items(),
    //             'next_page_url' => $products->nextPageUrl()
    //         ]);
    //     }
    //     return view('admin.pages.product.list');
    // }
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('common_names', 'LIKE', "%$search%");
            });
        }

        $sort_by = $request->get('sort_by', 'id');
        $sort_dir = $request->get('sort_dir', 'desc');
        $query->orderBy($sort_by, $sort_dir);

        // If related to lots, keep this
        $query->withSum('lots as stock_quantity', 'quantity_bags');

        $products = $query->paginate(100);

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'next_page_url' => $products->nextPageUrl()
            ]);
        }

        return view('admin.pages.product.list');
    }


    public function create()
    {
        return view('admin.pages.product.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category' => 'required|in:Root,Stem,Seed,Flower,Other',

            // Optional fields
            'english_name'     => 'nullable|string|max:255',
            'common_names'     => 'nullable|string|max:255',
            'botanical_name'   => 'nullable|string|max:255',
            'harvest_season'   => 'nullable|string|max:255',
            'location_found'   => 'nullable|string|max:255',
            'suppliers'        => 'nullable|string|max:255',
            'volume'           => 'nullable|string|max:100',
            'track_price'      => 'nullable|boolean',
        ]);

        // Save product
        $product = Product::create([
            'name'            => $request->name,
            'sku'             => $request->sku,
            'category'        => $request->category,
            'english_name'    => $request->english_name,
            'common_names'    => $request->common_names,
            'botanical_name'  => $request->botanical_name,
            'harvest_season'  => $request->harvest_season,
            'location_found'  => $request->location_found,
            'suppliers'       => $request->suppliers,
            'volume'          => $request->volume,
            'track_price'     => $request->has('track_price'),
        ]);

        // Return AJAX response
        if ($request->ajax()) {
            return response()->json(['message' => 'Product added successfully']);
        }

        // Fallback if normal form (not AJAX)
        return redirect()->route('admin.product.list')->with('success', 'Product added successfully.');
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.pages.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'category' => 'required|string|max:255', // string, not integer
            'english_name' => 'nullable|string|max:255',
            'common_names' => 'nullable|string|max:255',
            'botanical_name' => 'nullable|string|max:255',
            'harvest_season' => 'nullable|string|max:255',
            'location_found' => 'nullable|string|max:255',
            'suppliers' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'track_price' => 'nullable|boolean',
        ]);

        $product = Product::findOrFail($id);

        $data = $request->all();
        $data['track_price'] = $request->has('track_price');

        $product->update($data);

        return response()->json(['message' => 'Product updated successfully']);
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

        $warehouse = Product::findOrFail($id);
        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted successfully!']);
    }
    
    public function filterLotsByWarehouse($warehouseId, $productId)
    {
        $lots = Lots::with(['warehouse', 'media'])
            ->where('product_id', $productId)
            ->when($warehouseId != 0, function ($query) use ($warehouseId) {
                return $query->where('warehouse_id', $warehouseId);
            })
            ->get();

        $mappedLots = $lots->map(function ($lot) {
            return [
                'id' => $lot->id,
                'warehouse_name' => $lot->warehouse->store ?? 'N/A',
                'lot_number' => $lot->lot_number,
                'quantity_bags' => $lot->quantity_bags,
                'remaining_quantity_bags_after_deduction' => $lot->remaining_quantity_bags_after_deduction,
                'quality_description' => $lot->quality_description,
                'status' => $lot->status,
                'media' => $lot->media->map(fn($media) => [
                    'filename' => $media->media_URL,
                ]),
            ];
        });

        return response()->json([
            'lots' => $mappedLots,
            'totals' => [
                'quantity_bags' => $lots->sum('quantity_bags'),
                'remaining_quantity_bags_after_deduction' => $lots->sum('remaining_quantity_bags_after_deduction'),
            ],
        ]);
    }

    public function productLot($id)
    {
        $product = Product::findOrFail($id);

        $lots = Lots::with(['warehouse', 'media'])
            ->where('product_id', $id)
            ->get();

        // Get unique warehouses used in these lots
        $warehouses = $lots->pluck('warehouse')->filter()->unique('id')->values();

        return view('admin.pages.product.product-lot', compact('product', 'lots', 'warehouses'));
    }

}
