<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeLeaves\EmployeeLeaveController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - Employee Leaves
|--------------------------------------------------------------------------
| CRUD operations for complete Employee Leaves
|
*/
Route::get('employeeLeave',[EmployeeLeaveController::class, 'index'])->name('employeeLeave.index');
Route::post('employeeLeave',[EmployeeLeaveController::class, 'store'])->name('employeeLeave.store');
Route::get('employeeLeave/{id}',[EmployeeLeaveController::class, 'show'])->name('employeeLeave.show');
Route::post('employeeLeave/{id}',[EmployeeLeaveController::class, 'update'])->name('employeeLeave.update');
// Route::post('sortContactDetails/sorting', [EmployeeLeaveController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
Route::delete('employeeLeave/{id}',[EmployeeLeaveController::class, 'destroy'])->name('employeeLeave.destroy');
Route::post('employeeLeave/multi/delete',[EmployeeLeaveController::class, 'massDelete'])->name('employeeLeave.massDestroy');
