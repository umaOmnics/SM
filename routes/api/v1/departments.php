<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Departments\DepartmentController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - departments
|--------------------------------------------------------------------------
| CRUD operations for complete departments
|
*/
Route::group(['prefix' => 'v1/departments'], function () {

    Route::get('',[DepartmentController::class, 'index'])->name('departments.index');

    Route::post('',[DepartmentController::class, 'store'])->name('departments.store');

    Route::get('{id}',[DepartmentController::class, 'show'])->name('departments.show');

    Route::post('{id}',[DepartmentController::class, 'update'])->name('departments.update');

    Route::delete('{id}',[DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::post('multi/delete',[DepartmentController::class, 'massDelete'])->name('departments.massDestroy');

});

