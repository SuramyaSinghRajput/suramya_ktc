<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lots;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;
use App\Exports\Excel\CategoryExport;
use App\Models\Product;
use App\Models\Warehouse;
use Maatwebsite\Excel\Facades\Excel;

class LotsController extends Controller
{

    public function index(Request $request , $id)
    {
        $warehouses = Warehouse::where('id',$id)->first();
        $products = \App\Models\Product::all();
        $active = Lots::where('warehouse_id', $id)->where('status','1')->get();
        $complete = Lots::where('warehouse_id', $id)->where('status','0')->get();

        return view('admin.pages.lots.list')->with(['activelots'=> $active,'warehouses'=> $warehouses,'products'=> $products,'completelots'=> $complete,'warehouses' => $warehouses]); // adjust view name if different
    }

    public function create()
    {
        return view('admin.pages.warehouse.add');
    }
    
    public function store(Request $request)
    {
        $request->validate([
      
            'lot_number' => 'required|unique:lots,lot_number',
            'labour_rate' => 'required|numeric',
            'rent' => 'required|numeric',
            'qty' => 'required|numeric',
            'date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'media.*' => 'nullable',
            'quality_description' =>'required|string'
        ]);
    
        try {
            DB::beginTransaction();
            $item = Product::find($request->product_id)?->full_name ?? null;
            $lot = new Lots();
            $lot->lot_number = $request->lot_number;
            $lot->item = $item;
            $lot->labour_rate = $request->labour_rate;
            $lot->product_rate = '300';
            $lot->quantity_bags = $request->qty;
            $lot->total_value = $request->qty * 300;
            $lot->remaining_quantity_bags_after_deduction = $request->qty;
            $lot->date = $request->date;
            $lot->warehouse_id = $request->warehouse_id;
            $lot->warehouse_rate = $request->rent;
            $lot->product_id = $request->product_id;
            $lot->quality_description = $request->quality_description;
            $lot->packaging_remark = $request->packaging_remark;
            $lot->each_bag_weight = $request->each_bag_weight;
            $lot->status = 1;
            $lot->save();
    
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('lots/media', $fileName, 'public');
        
                        DB::table('lot_media')->insert([
                            'lot_id' => $lot->id,
                            'media_type' => $file->getMimeType(), // image/jpeg, video/mp4, etc.
                            'media_URL' => $filePath,
                            'uploaded_at' => now(),
                        ]);
                    }
                }
            }
    
            DB::commit();
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $lots = Lots::findOrFail($id);
        return view('admin.pages.lots.edit', compact('lots'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'store' => 'required|string|max:255',
            'about' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $lots = Lots::findOrFail($id);
        $lots->update($request->all());

        return response()->json(['message' => 'Lots updated successfully']);
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

        $lots = Lots::findOrFail($id);
        $lots->delete();

        return response()->json(['message' => 'Loys deleted successfully!']);
    }

    public function deductList()
    {

    }
    
}
