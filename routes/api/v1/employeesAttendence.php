<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeAttendenceController;

Route::group(['prefix' => 'v1/employeeAttendence'], function () {

    Route::get('',[EmployeeAttendenceController::class, 'index'])->name('designations.index');

    Route::post('',[EmployeeAttendenceController::class, 'store'])->name('designations.store');

    Route::get('{id}',[EmployeeAttendenceController::class, 'show'])->name('designations.show');

    Route::post('{id}',[EmployeeAttendenceController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[EmployeeAttendenceController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[EmployeeAttendenceController::class, 'massDelete'])->name('designations.massDestroy');

});
