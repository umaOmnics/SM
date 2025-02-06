<?php

use App\Http\Controllers\Items\InventoryStockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendors\VendorController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - departments
|--------------------------------------------------------------------------
| CRUD operations for complete departments
|
*/
Route::get('inventoryStock',[InventoryStockController::class, 'index'])->name('inventoryStock.index');
Route::post('inventoryStock',[InventoryStockController::class, 'store'])->name('inventoryStock.store');
Route::get('inventoryStock/{id}',[InventoryStockController::class, 'show'])->name('inventoryStock.show');
Route::post('inventoryStock/{id}',[InventoryStockController::class, 'update'])->name('inventoryStock.update');
// Route::post('sortContactDetails/sorting', [VendorController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
Route::delete('inventoryStock/{id}',[InventoryStockController::class, 'destroy'])->name('inventoryStock.destroy');
Route::post('inventoryStock/multi/delete',[InventoryStockController::class, 'massDelete'])->name('inventoryStock.massDestroy');
