<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Designations\DesignationController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - designations
|--------------------------------------------------------------------------
| CRUD operations for complete designations
|
*/
Route::group(['prefix' => 'v1/designations'], function () {

    Route::get('',[DesignationController::class, 'index'])->name('designations.index');

    Route::post('',[DesignationController::class, 'store'])->name('designations.store');

    Route::get('{id}',[DesignationController::class, 'show'])->name('designations.show');

    Route::post('{id}',[DesignationController::class, 'update'])->name('designations.update');

// Route::post('sortContactDetails/sorting', [DesignationController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
    Route::delete('{id}',[DesignationController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[DesignationController::class, 'massDelete'])->name('designations.massDestroy');


});
