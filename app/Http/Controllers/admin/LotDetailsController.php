<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lots;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\LotDeduction;
use App\Models\LotsMedia;
use App\Models\Product;
use DateTime;
use Illuminate\Support\Facades\DB;

class LotDetailsController extends Controller
{

    public function index(Request $request)
    {
        $query = Lots::query();

        // Fuzzy search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lot_number', 'LIKE', "%$search%");
                // ->orWhere('location', 'LIKE', "%$search%");
            });
        }
        $query->orderBy('id', 'desc');

        $lots = $query->paginate(100);

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json($lots);
        }

        // Else return the Blade view
        return view('admin.pages.lots.lotdetails'); // adjust view name if different
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

        Lots::create($validated);

        return response()->json(['message' => 'Warehouse added successfully!'], 200);
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

    public function activeList($id)
    {
        $lots = Lots::where('status', 1)->where('id', $id)->first();
        $lotImages = LotsMedia::where('lot_id', $id)->get();
        $deductionList = LotDeduction::where('lot_id', $id)->get();
        $deduction = LotDeduction::where('lot_id', $id)->first();
        $deductionCreatedAt = $deduction ? $deduction->created_at : $lots->created_at;

        $products = Product::all();
        $totalRent = 0;
        foreach ($deductionList as $deduction) {
            $lot = $lots; // already loaded
            $lotDate = new \DateTime($lot->date);
            $deductionDate = new \DateTime($deduction->deduction_date);

            $months = ($deductionDate->format('Y') - $lotDate->format('Y')) * 12 +
                    ($deductionDate->format('m') - $lotDate->format('m'));

            if ($deductionDate->format('d') >= $lotDate->format('d')) {
                $months += 1;
            }

            $rent = $months * $lot->warehouse_rate * $deduction->qty_bag;
            $totalRent += $rent;
        }
        $permissionsJson = auth()->user()->role->permission ?? '{}';
        $permission = json_decode($permissionsJson, true);
        return view('admin.pages.lots.activelotdetail', compact('lots','permission', 'lotImages', 'products', 'deductionList', 'totalRent', 'deductionCreatedAt'));      
    }

    public function completeList($id)
    {
        $lots = Lots::where('status', 0)->where('id', $id)->first();
        $lotImages = LotsMedia::where('lot_id', $id)->get();
        $deductionList = LotDeduction::where('lot_id', $id)->get();
        $deduction = LotDeduction::where('lot_id', $id)->first();
        $deductionCreatedAt = $deduction ? $deduction->created_at : $lots->created_at;

        $products = Product::all();
        $totalRent = 0;
        foreach ($deductionList as $deduction) {
            $lot = $lots; // already loaded
            $lotDate = new \DateTime($lot->date);
            $deductionDate = new \DateTime($deduction->deduction_date);

            $months = ($deductionDate->format('Y') - $lotDate->format('Y')) * 12 +
                    ($deductionDate->format('m') - $lotDate->format('m'));

            if ($deductionDate->format('d') >= $lotDate->format('d')) {
                $months += 1;
            }

            $rent = $months * $lot->warehouse_rate * $deduction->qty_bag;
            $totalRent += $rent;
        }
        $permissionsJson = auth()->user()->role->permission ?? '{}';
        $permission = json_decode($permissionsJson, true);
        return view('admin.pages.lots.completelotdetail', compact('lots','permission', 'lotImages', 'products', 'deductionList', 'totalRent', 'deductionCreatedAt'));
    }

    public function markAsComplete($id)
    {
        $lot = Lots::findOrFail($id);
        $lot->status = '0'; // or 1 if it's a boolean
        $lot->updated_at = now(); // optional
        $lot->save();

        return response()->json([
            'status' => true,
            'message' => 'Lot marked as complete successfully.'
        ]);
    }

    public function markAsActive($id)
    {
        $lot = Lots::findOrFail($id);
        $lot->status = '1'; // or 1 if it's a boolean
        $lot->updated_at = now(); // optional
        $lot->save();

        return response()->json([
            'status' => true,
            'message' => 'Lot marked as active successfully.'
        ]);
    }
    
    public function updates(Request $request, $id)
    {
        $lot = Lots::findOrFail($id);
        
        $validated = $request->validate([
            'lot_number' => 'required|unique:lots,lot_number,' . $id . ',id',
            'labour_rate' => 'required|numeric',
            'qty' => 'required|integer|min:1',
            'date' => 'required|date',
            'rent' => 'required|numeric',
            'quality_description' => 'required|string',
            'packaging_remark' => 'nullable|string',
            'each_bag_weight' => 'nullable|numeric',
            'media.*' => 'nullable|file', // 20MB
        ]);

        // Fetch product name
        $product = Product::findOrFail($request->product_id);
        $itemName = $product->common_name;

        // Update lot fields
        $lot->lot_number = $request->lot_number;
        $lot->labour_rate = $request->labour_rate;
        $lot->product_rate = 300; // Hardcoded rate
        $lot->item = $itemName;
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

        // Handle new media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('lots', 'public');
                $lot->media()->create([
                    'media_URL' => $path,
                    'media_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Lot details updated successfully!');
    }

    public function destroyImage($id)
    {
        $media = LotsMedia::findOrFail($id);
        Storage::delete('public/' . $media->media_URL);
        $media->delete();

        return response()->json(['message' => 'Media deleted']);
    }

    public function deductionstore(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $lot = Lots::where('id', $request->lot_id)->lockForUpdate()->firstOrFail();
    
            $existing = LotDeduction::where('lot_id', $request->lot_id)
                ->where('gate_pass', $request->gate_pass)
                ->first();
    
            // If already exists, stop and show message
            if ($existing) {
                DB::rollBack();
                $message = 'A deduction with this Gate Pass already exists.';
            }else{
    
            // Determine max deductible
            $maxDeductible = $lot->remaining_quantity_bags_after_deduction;
    
            $validated = $request->validate([
                'deduct_qty' => ['required', 'integer', 'min:1', 'max:' . $maxDeductible],
                'gate_pass' => 'required|string|max:100',
                'lot_id' => 'required|integer|exists:lots,id',
                'ddd' => 'required|date',
            ]);
    
            // Create new deduction
            LotDeduction::create([
                'qty_bag' => $request->deduct_qty,
                'gate_pass' => $request->gate_pass,
                'lot_id' => $request->lot_id,
                'deduction_date' => $request->ddd,
            ]);
    
            // Recalculate remaining quantity
            $totalDeducted = LotDeduction::where('lot_id', $request->lot_id)->sum('qty_bag');
            $lot->remaining_quantity_bags_after_deduction = max(0, $lot->quantity_bags - $totalDeducted);
            $lot->save();
    
            DB::commit();
    
            $message = 'Deduction saved successfully!';
            if ($lot->remaining_quantity_bags_after_deduction == 0) {
                $message .= ' All bags are deducted.';
            }
        }
    
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function destroyDeduction(Request $request, $id)
    {
        $request->validate([
            'admin_password' => 'required|string',
        ]);

        $admin = Auth::user(); // assuming admin is authenticated user

        if (!Hash::check($request->admin_password, $admin->password)) {
            return redirect()->back()->with('error', 'Incorrect password. Deletion not permitted.');
        }

        $deduction = LotDeduction::findOrFail($id);
        $lot = Lots::findOrFail($deduction->lot_id);
        $lot->remaining_quantity_bags_after_deduction += $deduction->qty_bag;
        $lot->save();
        $deduction->delete();

        return redirect()->back()->with('success', 'Deduction deleted successfully!');
    }


    public function editDeduction(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $deduction = LotDeduction::findOrFail($id);
            $lot = Lots::where('id', $deduction->lot_id)->lockForUpdate()->firstOrFail();

            // Calculate max deductible
            $maxDeductible = $lot->remaining_quantity_bags_after_deduction + $deduction->qty_bag;

            // Validate inputs
            $validated = $request->validate([
                'deduct_qty' => ['required', 'integer', 'min:1', 'max:' . $maxDeductible],
                'gate_pass' => 'required|string|max:100',
                'ddd' => 'required|date',
            ]);

            // Update the deduction
            $deduction->update([
                'qty_bag' => $validated['deduct_qty'],
                'gate_pass' => $validated['gate_pass'],
                'deduction_date' => $validated['ddd'],
            ]);

            // Recalculate lot's remaining quantity
            $totalDeducted = LotDeduction::where('lot_id', $lot->id)->sum('qty_bag');
            $lot->remaining_quantity_bags_after_deduction = max(0, $lot->quantity_bags - $totalDeducted);
            $lot->save();

            DB::commit();

            return redirect()->back()->with('success', 'Deduction updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }
}
