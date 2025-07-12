<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LotDetailsController;
use App\Http\Controllers\admin\LotsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\WarehouseController;
use App\Http\Controllers\admin\BillController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Export\ProductExportController;
use App\Http\Controllers\Export\LotExportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'auth'], function () {
    Route::get('/', [LoginController::class, "index"]);
    Route::get('/login', [LoginController::class, "index"]);
    Route::get('/admin', [LoginController::class, "index"]);
    Route::get('/admin/login', [LoginController::class, "index"]);
    Route::post('/admin/auth/login', [LoginController::class, "login"]);
    Route::get('/logout', [LoginController::class, "logout"]);
});

Route::group(['namespace' => 'admin'], function () {
    // 
});
Route::post('/admin/user/toggle-status', [DashboardController::class, 'toggleStatus'])->name('user.toggleStatus');



Route::group(['prefix' => 'admin', 'namespace' => 'admin', 'middleware' => 'Checksession'], function () {
    // Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function () {

    /**
     *  admin/dashboard
     */
    Route::get('dashboard', [DashboardController::class, "dashboard"]);

    /** Product */
    Route::get('/product/list', [ProductController::class, "index"])->name('admin.product.index');
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']);
    Route::any('/product/add', [ProductController::class, "create"]);
    Route::post('/product/store', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::any('/product/update-status/{id}/{status}', [ProductController::class, "updateStatus"]);
    Route::get('/product/export-form', [ProductExportController::class, 'showForm'])->name('admin.export.form');
    Route::any('/product/export-data', [ProductExportController::class, 'export'])->name('admin.export.products');
    Route::get('/product/lot/list/{id}', [ProductController::class, "productLot"])->name('admin.product.lot.list');
    Route::get('/warehouse/filter/{warehouseId}/{productId}', [ProductController::class, 'filterLotsByWarehouse']);

    /** Warehouse */
    Route::get('/warehouse/list', [WarehouseController::class, "index"])->name('admin.warehouse.index');
    Route::delete('/warehouse/delete/{id}', [WarehouseController::class, 'destroy'])->name('admin.warehouse.destroy');
    Route::any('/warehouse/add', [WarehouseController::class, "create"]);
    Route::post('/warehouse/store', [WarehouseController::class, 'store'])->name('admin.warehouse.store');
    Route::get('/warehouse/edit/{id}', [WarehouseController::class, 'edit'])->name('admin.warehouse.edit');
    Route::post('/warehouse/update/{id}', [WarehouseController::class, 'update'])->name('admin.warehouse.update');
    Route::any('/warehouse/update-status/{id}/{status}', [WarehouseController::class, "updateStatus"]);

    Route::prefix('warehouse')->group(function () {
        Route::get('/bills/ajax', [BillController::class, 'ajaxList']);
        Route::get('/bills/{id}', [BillController::class, 'index']);
        Route::get('/bills/view/{id}', [BillController::class, 'view'])->name('admin.warehouse.bills.view');
        Route::post('/bills/store', [BillController::class, 'store'])->name('admin.warehouse.bills.store');
        Route::get('/bills/edit/{id}', [BillController::class, 'edit'])->name('admin.warehouse.bills.edit');
        Route::post('/bills/update/{id}', [BillController::class, 'update'])->name('admin.warehouse.bills.update');
        Route::any('/bills/delete/{id}', [BillController::class, 'destroy'])->name('admin.warehouse.bills.destroy');
    });    
    /**Lots*/
    
    Route::get('/lots/list/warehouse/{id}', [LotsController::class, "index"])->name('admin.lots.index');
    Route::delete('/lots/delete/{id}', [LotsController::class, 'destroy'])->name('lot.delete');
    Route::post('/lots/store', [LotsController::class, 'store'])->name('create.lot');
    Route::get('/lots/edit/{id}', [LotsController::class, 'edit'])->name('admin.lots.edit');
    Route::post('/lots/update/{id}', [LotsController::class, 'update'])->name('admin.lots.update');
    Route::any('/lots/update-status/{id}/{status}', [LotsController::class, "updateStatus"]);
    Route::get('/lots/{lot_id}/deduct-list', [LotsController::class, 'deductList'])->name('lots.deduct.list');
    Route::get('/lots/export-form', [LotExportController::class, 'showExportForm'])->name('lots.export.form');
    Route::any('/lots/export-data', [LotExportController::class, 'export'])->name('admin.lots.export');

    /** Lots Details */
    Route::get('/lots/details/list', [LotDetailsController::class, "index"])->name('admin.lots.details.index');
    Route::get('/lots/details/active/list/{id}', [LotDetailsController::class, "activeList"])->name('admin.lots.active.list');
    Route::get('/lots/details/complete/list/{id}', [LotDetailsController::class, "completeList"])->name('admin.lots.complete.list');
    Route::post('/lots/complete/{id}', [LotDetailsController::class, 'markAsComplete'])->name('lots.complete');
    Route::post('/lots/active/{id}', [LotDetailsController::class, 'markAsActive'])->name('lots.active');
    Route::post('/lots/deduction/store', [LotDetailsController::class, 'deductionstore'])->name('deduction.store');
    Route::post('/lots/details/edit/{id}', [LotDetailsController::class, 'updates'])->name('admin.lots.details.updates');
    Route::delete('/lots/deduction/destroy/{id}', [LotDetailsController::class, 'destroyDeduction'])->name('admin.lots.deduction.destroy');
    Route::post('/lots/deduction/edit/{id}', [LotDetailsController::class, 'editDeduction'])->name('admin.lots.deduction.update');
    
    /* * User Management
     */
    // Route::get('/', 'UserController@index');
    Route::get('/users/list', [UserController::class, "index"]);
    Route::any('/users/add-user', [UserController::class, "addUser"]);
    Route::any('/users/edit/{id}', [UserController::class, "editUser"]);
    Route::any('/users/delete/{id}', [UserController::class, "delete"]);
    Route::any('/users/update-status/{id}/{status}', [UserController::class, "updateStatus"]);

    /**
     * Roles
     */
    Route::get('/roles/list', [RoleController::class, "index"]);
    Route::any('/roles/permissions/{role_id}', [RoleController::class, "permissions"]);
    Route::any('/roles/edit/{role_id}', [RoleController::class, "edit"]);
    Route::any('/roles/add', [RoleController::class, "add"]);

    /**
     * Settings
     */
    Route::any('/settings', [SettingsController::class, "index"]);

    /**
     * JD Lead
     */

});
