<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectsController;

Route::group(['prefix' => 'v1/subjects'], function () {

    Route::get('',[SubjectsController::class, 'index'])->name('designations.index');

    Route::post('',[SubjectsController::class, 'store'])->name('designations.store');

    Route::get('{id}',[SubjectsController::class, 'show'])->name('designations.show');

    Route::post('{id}',[SubjectsController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[SubjectsController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[SubjectsController::class, 'massDelete'])->name('designations.massDestroy');

});
