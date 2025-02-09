<?php

use App\Http\Controllers\EmployeeManagement\ResignationsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - Resignations
|--------------------------------------------------------------------------
| CRUD operations for complete Resignations
|
*/
Route::group(['prefix' => 'v1/resignations'], function () {

    Route::get('',[ResignationsController::class, 'index'])->name('resignations.index');

    Route::post('',[ResignationsController::class, 'store'])->name('resignations.store');

    Route::get('{id}',[ResignationsController::class, 'show'])->name('resignations.show');

    Route::post('{id}',[ResignationsController::class, 'update'])->name('resignations.update');

    Route::delete('{id}',[ResignationsController::class, 'destroy'])->name('resignations.destroy');

    Route::post('multi/delete',[ResignationsController::class, 'massDelete'])->name('resignations.massDestroy');


});
