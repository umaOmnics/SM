<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;

Route::group(['prefix' => 'v1/students'], function () {

    Route::get('',[StudentsController::class, 'index'])->name('designations.index');

    Route::post('',[StudentsController::class, 'store'])->name('designations.store');

    Route::get('{id}',[StudentsController::class, 'show'])->name('designations.show');

    Route::post('{id}',[StudentsController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[StudentsController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[StudentsController::class, 'massDelete'])->name('designations.massDestroy');

});
