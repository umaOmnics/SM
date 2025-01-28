<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassTimeTableController;

Route::group(['prefix' => 'v1/classTimeTable'], function () {

    Route::get('',[ClassTimeTableController::class, 'index'])->name('designations.index');

    Route::post('',[ClassTimeTableController::class, 'store'])->name('designations.store');

    Route::get('{id}',[ClassTimeTableController::class, 'show'])->name('designations.show');

    Route::post('{id}',[ClassTimeTableController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[ClassTimeTableController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[ClassTimeTableController::class, 'massDelete'])->name('designations.massDestroy');

});
