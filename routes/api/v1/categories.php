<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - Categories
|--------------------------------------------------------------------------
| CRUD operations for complete Categories
|
*/

Route::group(['prefix' => 'v1/categories'], function () {

    Route::get('',[CategoryController::class, 'index'])->name('categories.index');

    Route::post('',[CategoryController::class, 'store'])->name('categories.store');

    Route::get('{id}',[CategoryController::class, 'show'])->name('categories.show');

    Route::post('{id}',[CategoryController::class, 'update'])->name('categories.update');

    Route::delete('{id}',[CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('multi/delete',[CategoryController::class, 'massDelete'])->name('categories.massDestroy');

});
