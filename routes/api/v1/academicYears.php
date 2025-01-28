<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicYears\AcademicYearController;

/*
|--------------------------------------------------------------------------
| API Routes for MasterData - Academic Years
|--------------------------------------------------------------------------
| CRUD operations for complete Academic Years
|
*/

Route::group(['prefix' => 'v1/academicYears'], function () {

    Route::get('',[AcademicYearController::class, 'index'])->name('academicYears.index');

    Route::post('',[AcademicYearController::class, 'store'])->name('academicYears.store');

    Route::get('{id}',[AcademicYearController::class, 'show'])->name('academicYears.show');

    Route::post('{id}',[AcademicYearController::class, 'update'])->name('academicYears.update');

    Route::delete('{id}',[AcademicYearController::class, 'destroy'])->name('academicYears.destroy');

    Route::post('multi/delete',[AcademicYearController::class, 'massDelete'])->name('academicYears.massDestroy');

});
