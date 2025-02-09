<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Allowances\AllowancesController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - Allowances
|--------------------------------------------------------------------------
| CRUD operations for complete Allowances
|
*/

Route::group(['prefix' => 'v1/allowances'], function () {

    Route::get('',[AllowancesController::class, 'index'])->name('allowances.index');

    Route::post('',[AllowancesController::class, 'store'])->name('allowances.store');

    Route::get('{id}',[AllowancesController::class, 'show'])->name('allowances.show');

    Route::post('{id}',[AllowancesController::class, 'update'])->name('allowances.update');

    Route::delete('{id}',[AllowancesController::class, 'destroy'])->name('allowances.destroy');

    Route::post('multi/delete',[AllowancesController::class, 'massDelete'])->name('allowances.massDestroy');

});
