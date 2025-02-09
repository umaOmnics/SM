<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveTypes\LeaveTypeController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - departments
|--------------------------------------------------------------------------
| CRUD operations for complete departments
|
*/
Route::get('leaveType',[LeaveTypeController::class, 'index'])->name('leaveType.index');
Route::post('leaveType',[LeaveTypeController::class, 'store'])->name('leaveType.store');
Route::get('leaveType/{id}',[LeaveTypeController::class, 'show'])->name('leaveType.show');
Route::post('leaveType/{id}',[LeaveTypeController::class, 'update'])->name('leaveType.update');
// Route::post('sortContactDetails/sorting', [LeaveTypeController::class, 'sorting'])->name('contactDetails.sorting')->middleware(['role:contacts-types,3']);
Route::delete('leaveType/{id}',[LeaveTypeController::class, 'destroy'])->name('leaveType.destroy');
Route::post('leaveType/multi/delete',[LeaveTypeController::class, 'massDelete'])->name('leaveType.massDestroy');
