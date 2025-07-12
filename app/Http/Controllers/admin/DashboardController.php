<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AppUsers;
use DB;
use Validator;
use Image;
use File;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $page_title = 'Dashboard';
            $page_description = '';
            $breadcrumbs = [
                // [
                //     'title' => 'Dashboard',
                //     'url' => '',
                // ],
            ];
            $status = request('status');
            if ($status == '0') {
                $status = '2';
            }
            // $user = AppUsers::orderBy('id', 'desc')->get();
           
            return view('admin.pages.dashboard.list', compact('page_title', 'page_description', 'breadcrumbs'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
 public function toggleStatus(Request $request)
{
    $user = AppUsers::findOrFail($request->id);
    $user->status = $user->status == 1 ? 0 : 1;
    $user->save();

    return response()->json([
        'status' => true,
        'new_status' => $user->status
    ]);
}


}
