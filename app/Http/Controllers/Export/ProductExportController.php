<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportController extends Controller
{

    public function showForm()
    {
        $fields = [
            'name',
            'sku',
            'category',
            'english_name',
            'common_names',
            'botanical_name',
            'harvest_season',
            'location_found',
            'suppliers',
            'volume',
            'track_price',
            'created_at',
            'updated_at',
        ];

        return view('export.product-form', compact('fields'));
    }

    // public function export(Request $request)
    // {
    //     $fields = $request->input('fields', []);
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     if (empty($fields)) {
    //         return back()->with('error', 'Please select at least one field.');
    //     }

    //     $query = DB::table('products')
    //         ->select($fields)
    //         ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
    //             $q->whereBetween('created_at', [$startDate, $endDate]);
    //         });

    //     $data = $query->get();

    //     $filename = 'products_' . now()->format('Ymd_His') . '.csv';

    //     return response()->streamDownload(function () use ($data, $fields) {
    //         $handle = fopen('php://output', 'w');

    //         // Optional: Write UTF-8 BOM for Excel
    //         echo "\xEF\xBB\xBF";

    //         // Header row
    //         fputcsv($handle, $fields);

    //         // Data rows
    //         foreach ($data as $row) {
    //             fputcsv($handle, array_map(fn($field) => $row->$field ?? '', $fields));
    //         }

    //         fclose($handle);
    //     }, $filename, [
    //         'Content-Type' => 'text/csv',
    //     ]);
    // }
    public function export(Request $request)
    {
        $fields = $request->input('fields', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (empty($fields)) {
            return back()->with('error', 'Please select at least one field.');
        }

        $query = DB::table('products')
            ->select($fields)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });

        $data = $query->get();

        $filename = 'products_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($data, $fields) {
            $handle = fopen('php://output', 'w');

            // Write BOM for Excel compatibility
            echo "\xEF\xBB\xBF";

            // Header row
            fputcsv($handle, $fields);

            // Data rows
            foreach ($data as $row) {
                fputcsv($handle, array_map(fn($field) => $row->$field ?? '', $fields));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
