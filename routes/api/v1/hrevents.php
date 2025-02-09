<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HrEventsController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - HR Events
|--------------------------------------------------------------------------
| CRUD operations for complete HR Events
|
*/
Route::group(['prefix' => 'v1/hrevents'], function () {

    Route::get('',[HrEventsController::class, 'index'])->name('hrevents.index');

    Route::post('',[HrEventsController::class, 'store'])->name('hrevents.store');

    Route::get('{id}',[HrEventsController::class, 'show'])->name('hrevents.show');

    Route::post('{id}',[HrEventsController::class, 'update'])->name('hrevents.update');

    Route::delete('{id}',[HrEventsController::class, 'destroy'])->name('hrevents.destroy');

    Route::post('multi/delete',[HrEventsController::class, 'massDelete'])->name('hrevents.massDestroy');


});
