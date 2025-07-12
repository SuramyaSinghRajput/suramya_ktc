<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Lots;
use App\Models\Bill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    public function index($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $bills = Bill::where('warehouse_id', $warehouseId)->get();
        $lots = Lots::where('warehouse_id', $warehouseId)->get();

        return view('admin.pages.bills.list', compact('warehouse', 'bills', 'lots'));
    }

    public function view($id)
    {
        $bill = Bill::findOrFail($id);

        $lotIds = json_decode($bill->lot_id, true) ?? [];
        $lotNames = Lots::whereIn('id', $lotIds)->pluck('lot_number')->toArray();

        return response()->json([
            'id' => $bill->id,
            'bill_number' => $bill->bill_number,
            'bill_date' => $bill->bill_date,
            'clear_date' => $bill->clear_date,
            'payment_method' => $bill->payment_method,
            'remark' => $bill->remark,
            'lot_ids' => $lotIds,
            'lot_names' => $lotNames,
            'items' => Lots::whereIn('id', $lotIds)->pluck('item')->toArray(),
            'bill_file_url' => $bill->bill_file ? url('storage/app/public/' . $bill->bill_file) : null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,id',
            'bill_number' => 'required|string|max:255',
            'bill_date' => 'required|date',
            'clear_date' => 'nullable|date|after_or_equal:bill_date',
            'lot_id' => 'required|array',
            'lot_id.*' => 'exists:lots,id',
            'payment_method' => 'required|in:cash,bank,upi,cheque,online',
            'bill_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'remark' => 'nullable|string|max:1000',
        ]);

        // Check if selected lots are eligible (remaining_quantity == 0)
        $invalidLots = Lots::whereIn('id', $validated['lot_id'])
            ->where('remaining_quantity_bags_after_deduction', '!=', 0)
            ->pluck('lot_number')
            ->toArray();

        if (count($invalidLots)) {
            return back()
                ->withInput()
                ->withErrors([
                    'lot_id' => 'The following lots have non-zero remaining quantity: ' . implode(', ', $invalidLots),
                ]);
        }

        $bill = new Bill();
        $bill->warehouse_id = $validated['warehouse_id'];
        $bill->bill_number = $validated['bill_number'];
        $bill->bill_date = $validated['bill_date'];
        $bill->clear_date = $validated['clear_date'] ?? null;
        $bill->payment_method = $validated['payment_method'];
        $bill->remark = $validated['remark'] ?? null;
        $bill->lot_id = json_encode($validated['lot_id']);

        if ($request->hasFile('bill_file')) {
            $file = $request->file('bill_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bill_files', $filename, 'public');
            $bill->bill_file = $path;
        }

        $bill->save();

        // Update lot statuses
        Lots::where('warehouse_id', $validated['warehouse_id'])
            ->whereIn('id', $validated['lot_id'])
            ->update(['status' => '0']);

        return redirect()->back()->with('success', 'Bill Stored successfully!');
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bill_number' => 'required|string|max:255',
            'bill_date' => 'required|date',
            'clear_date' => 'nullable|date',
            'payment_method' => 'required|string|max:100',
            'bill_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'remark' => 'nullable|string|max:1000',
            'lot_id' => 'required|array',
            'lot_id.*' => 'exists:lots,id',
        ]);

        $bill = Bill::findOrFail($id);

        $bill->bill_number = $validated['bill_number'];
        $bill->bill_date = $validated['bill_date'];
        $bill->clear_date = $validated['clear_date'] ?? null;
        $bill->payment_method = $validated['payment_method'];
        $bill->remark = $validated['remark'] ?? null;
        $bill->lot_id = json_encode($validated['lot_id']);

        if ($request->hasFile('bill_file')) {
            if ($bill->bill_file && Storage::disk('public')->exists($bill->bill_file)) {
                Storage::disk('public')->delete($bill->bill_file);
            }

            $bill->bill_file = $request->file('bill_file')->store('bill_files', 'public');
        }

        $bill->save();

        Lots::where('warehouse_id', $bill->warehouse_id)
            ->whereIn('id', $validated['lot_id'])
            ->update(['status' => '0']);

        return response()->json(['message' => 'Bill Updated successfully!']);
    }

    public function edit($id)
    {
        $bill = Bill::findOrFail($id);
        $lotIds = json_decode($bill->lot_id);

        return response()->json([
            'id' => $bill->id,
            'bill_number' => $bill->bill_number,
            'bill_date' => $bill->bill_date,
            'clear_date' => $bill->clear_date,
            'payment_method' => $bill->payment_method,
            'remark' => $bill->remark,
            'lot_ids' => $lotIds
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'admin_password' => 'required|string'
        ]);

        // Password check
        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return response()->json(['message' => 'Invalid admin password'], 403);
        }

        $bill = Bill::findOrFail($id);
        $bill->delete();

        return response()->json(['message' => 'Bill deleted successfully']);
    }
}
