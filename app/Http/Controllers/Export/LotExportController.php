<?php
namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use App\Models\Lots;
use App\Models\Product;
use App\Models\Warehouse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LotExportController extends Controller
{
    // public function showExportForm()
    // {
    //     $fields = [
    //         'id', 'product_id', 'warehouse_id', 'lot_number', 'item',
    //         'grade', 'labour_rate', 'warehouse_rate', 'product_rate',
    //         'total_value', 'quantity_bags', 'quantity_kgs',
    //         'remaining_quantity_bags_after_deduction', 'each_bag_weight',
    //         'date', 'status', 'bill_number', 'bill_date', 'pay_by',
    //         'clear_date', 'packaging_remark', 'quality_description',
    //         'created_at', 'updated_at'
    //     ];

    //     return view('export.lot-form', compact('fields'));
    // }

    public function showExportForm()
    {
        $fields = [
            'id', 'product_id', 'warehouse_id', 'lot_number', 'item',
            'grade', 'labour_rate', 'warehouse_rate', 'product_rate',
            'total_value', 'quantity_bags', 'quantity_kgs',
            'remaining_quantity_bags_after_deduction', 'each_bag_weight',
            'date', 'status', 'bill_number', 'bill_date', 'pay_by',
            'clear_date', 'packaging_remark', 'quality_description',
            'created_at', 'updated_at'
        ];

        $statuses = Lots::distinct()->pluck('status')->filter()->values();
        $warehouses = Warehouse::select('id', 'store')->get();
        $products = Product::select('id', 'common_name')->get();

        return view('export.lot-form', compact('fields', 'statuses', 'warehouses', 'products'));
    }

    // public function export(Request $request)
    // {
    //     $fields = $request->input('fields');
    //     $fromDate = $request->input('from_date');
    //     $toDate = $request->input('to_date');

    //     if (!$fields || !$fromDate || !$toDate) {
    //         return redirect()->back()->with('error', 'Please select all required fields and dates.');
    //     }

    //     $lots = Lots::select($fields)
    //         ->whereBetween('date', [$fromDate, $toDate])
    //         ->get();

    //     // Export as CSV
    //     $filename = 'lot_export_' . now()->format('Ymd_His') . '.csv';

    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => "attachment; filename=\"$filename\"",
    //     ];

    //     $callback = function () use ($lots, $fields) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, $fields); // Header row

    //         foreach ($lots as $lot) {
    //             fputcsv($file, $lot->only($fields));
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    public function export(Request $request)
    {
        $fields = $request->input('fields');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$fields || !$fromDate || !$toDate) {
            return redirect()->back()->with('error', 'Please select all required fields and dates.');
        }

        $query = Lots::select($fields)
            ->whereBetween('date', [$fromDate, $toDate]);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        $lots = $query->get();

        $filename = 'lot_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($lots, $fields) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $fields);
            foreach ($lots as $lot) {
                fputcsv($file, $lot->only($fields));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
