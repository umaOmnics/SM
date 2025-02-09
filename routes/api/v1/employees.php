<?php

use App\Http\Controllers\EmployeeManagement\EmployeeAssetsController;
use App\Http\Controllers\EmployeeManagement\EmployeeEmergencyContactsController;
use App\Http\Controllers\EmployeeManagement\EmployeesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/employees'], function () {

    Route::get('',[EmployeesController::class, 'index'])->name('designations.index');

    Route::post('',[EmployeesController::class, 'store'])->name('designations.store');

    Route::get('{id}',[EmployeesController::class, 'show'])->name('designations.show');

    Route::post('{id}',[EmployeesController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[EmployeesController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[EmployeesController::class, 'massDelete'])->name('designations.massDestroy');

});

Route::group(['prefix' => 'v1/employeeEmergencyContacts'], function () {

    Route::get('', [EmployeeEmergencyContactsController::class, 'index'])->name('employeeEmergencyContacts.index');

    Route::post('', [EmployeeEmergencyContactsController::class, 'store'])->name('employeeEmergencyContacts.store');

    Route::get('{id}', [EmployeeEmergencyContactsController::class, 'show'])->name('employeeEmergencyContacts.show');

    Route::post('{id}', [EmployeeEmergencyContactsController::class, 'update'])->name('employeeEmergencyContacts.update');

    Route::delete('{id}', [EmployeeEmergencyContactsController::class, 'destroy'])->name('employeeEmergencyContacts.destroy');

    Route::post('multi/delete', [EmployeeEmergencyContactsController::class, 'massDelete'])->name('employeeEmergencyContacts.massDestroy');


});


Route::group(['prefix' => 'v1/employeeAssets'], function () {

    Route::get('',[EmployeeAssetsController::class, 'index'])->name('assets.index');

    Route::post('',[EmployeeAssetsController::class, 'store'])->name('assets.store');

    Route::get('{id}',[EmployeeAssetsController::class, 'show'])->name('assets.show');

    Route::post('{id}',[EmployeeAssetsController::class, 'update'])->name('assets.update');

    Route::delete('{id}',[EmployeeAssetsController::class, 'destroy'])->name('assets.destroy');

    Route::post('multi/delete',[EmployeeAssetsController::class, 'massDelete'])->name('assets.massDestroy');


});
