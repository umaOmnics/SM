<?php

use Illuminate\Support\Facades\Route;
use management\EmployeesController;

Route::group(['prefix' => 'v1/employees'], function () {

    Route::get('',[EmployeesController::class, 'index'])->name('designations.index');

    Route::post('',[EmployeesController::class, 'store'])->name('designations.store');

    Route::get('{id}',[EmployeesController::class, 'show'])->name('designations.show');

    Route::post('{id}',[EmployeesController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[EmployeesController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[EmployeesController::class, 'massDelete'])->name('designations.massDestroy');

});
