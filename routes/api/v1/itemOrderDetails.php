<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Items\ItemOrderDetailsController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - itemOrder
|--------------------------------------------------------------------------
| CRUD operations for complete itemOrder
|
*/
Route::get('itemOrder',[ItemOrderDetailsController::class, 'index'])->name('itemOrder.index');
Route::post('itemOrder',[ItemOrderDetailsController::class, 'store'])->name('itemOrder.store');
Route::get('itemOrder/{id}',[ItemOrderDetailsController::class, 'show'])->name('itemOrder.show');
Route::post('itemOrder/{id}',[ItemOrderDetailsController::class, 'update'])->name('itemOrder.update');
// Route::post('sortContactDetails/sorting', [ItemOrderDetailsController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
Route::delete('itemOrder/{id}',[ItemOrderDetailsController::class, 'destroy'])->name('itemOrder.destroy');
Route::post('itemOrder/multi/delete',[ItemOrderDetailsController::class, 'massDelete'])->name('item.massDestroy');
