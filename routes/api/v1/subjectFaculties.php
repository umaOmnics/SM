<?php

use Illuminate\Support\Facades\Route;
use management\SubjectFacultiesController;

Route::group(['prefix' => 'v1/subjectFaculties'], function () {

    Route::get('',[SubjectFacultiesController::class, 'index'])->name('designations.index');

    Route::post('',[SubjectFacultiesController::class, 'store'])->name('designations.store');

    Route::get('{id}',[SubjectFacultiesController::class, 'show'])->name('designations.show');

    Route::post('{id}',[SubjectFacultiesController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[SubjectFacultiesController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[SubjectFacultiesController::class, 'massDelete'])->name('designations.massDestroy');

});
