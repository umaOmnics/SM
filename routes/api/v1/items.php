<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Items\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - departments
|--------------------------------------------------------------------------
| CRUD operations for complete departments
|
*/
Route::get('item',[ItemController::class, 'index'])->name('item.index');
Route::post('item',[ItemController::class, 'store'])->name('item.store');
Route::get('item/{id}',[ItemController::class, 'show'])->name('item.show');
Route::post('item/{id}',[ItemController::class, 'update'])->name('item.update');
// Route::post('sortContactDetails/sorting', [ItemController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
Route::delete('item/{id}',[ItemController::class, 'destroy'])->name('item.destroy');
Route::post('item/multi/delete',[ItemController::class, 'massDelete'])->name('item.massDestroy');
