<?php

use App\Http\Controllers\EmployeeManagement\EmployeePromotionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - Employee promotions
|--------------------------------------------------------------------------
| CRUD operations for complete Employee promotions
|
*/
Route::group(['prefix' => 'v1/employeePromotions'], function () {

    Route::get('',[EmployeePromotionsController::class, 'index'])->name('promotions.index');

    Route::post('',[EmployeePromotionsController::class, 'store'])->name('promotions.store');

    Route::get('{id}',[EmployeePromotionsController::class, 'show'])->name('promotions.show');

    Route::post('{id}',[EmployeePromotionsController::class, 'update'])->name('promotions.update');

    Route::delete('{id}',[EmployeePromotionsController::class, 'destroy'])->name('promotions.destroy');

    Route::post('multi/delete',[EmployeePromotionsController::class, 'massDelete'])->name('promotions.massDestroy');


});
