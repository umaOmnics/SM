<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendors\VendorController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - departments
|--------------------------------------------------------------------------
| CRUD operations for complete departments
|
*/
Route::get('vendor',[VendorController::class, 'index'])->name('vendor.index');
Route::post('vendor',[VendorController::class, 'store'])->name('vendor.store');
Route::get('vendor/{id}',[VendorController::class, 'show'])->name('vendor.show');
Route::post('vendor/{id}',[VendorController::class, 'update'])->name('vendor.update');
// Route::post('sortContactDetails/sorting', [VendorController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
Route::delete('vendor/{id}',[VendorController::class, 'destroy'])->name('vendor.destroy');
Route::post('vendor/multi/delete',[VendorController::class, 'massDelete'])->name('vendor.massDestroy');
