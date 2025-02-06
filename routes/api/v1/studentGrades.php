<?php

use Illuminate\Support\Facades\Route;
use management\StudentGradesController;

Route::group(['prefix' => 'v1/studentGrades'], function () {

    Route::get('',[StudentGradesController::class, 'index'])->name('designations.index');

    Route::post('',[StudentGradesController::class, 'store'])->name('designations.store');

    Route::get('{id}',[StudentGradesController::class, 'show'])->name('designations.show');

    Route::post('{id}',[StudentGradesController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[StudentGradesController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[StudentGradesController::class, 'massDelete'])->name('designations.massDestroy');

});
