<?php

use Illuminate\Support\Facades\Route;
use management\StudentAttendenceController;

Route::group(['prefix' => 'v1/studentAttendence'], function () {

    Route::get('',[StudentAttendenceController::class, 'index'])->name('designations.index');

    Route::post('',[StudentAttendenceController::class, 'store'])->name('designations.store');

    Route::get('{id}',[StudentAttendenceController::class, 'show'])->name('designations.show');

    Route::post('{id}',[StudentAttendenceController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[StudentAttendenceController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[StudentAttendenceController::class, 'massDelete'])->name('designations.massDestroy');

});
