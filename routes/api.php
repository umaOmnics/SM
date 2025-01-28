<?php

//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth\LoginController;
use App\Http\Controllers\Client\TokensController;
use App\Http\Controllers\Client\SalutationsController;
use App\Http\Controllers\Client\TitlesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require __DIR__ . '/api/v1/users.php';
require __DIR__ . '/api/v1/rolesAndPermissions.php';
require __DIR__ . '/api/v1/academicYears.php';
require __DIR__ . '/api/v1/categories.php';
require __DIR__ . '/api/v1/departments.php';
require __DIR__ . '/api/v1/designations.php';
require __DIR__ . '/api/v1/students.php';
require __DIR__ . '/api/v1/employees.php';
require __DIR__ . '/api/v1/subjects.php';
require __DIR__ . '/api/v1/studentGrades.php';
require __DIR__ . '/api/v1/subjectFaculties.php';
require __DIR__ . '/api/v1/studentAttendence.php';
require __DIR__ . '/api/v1/employeesAttendence.php';


//Client APIs..

Route::prefix('v1/client')->group(function () {

    Route::post('token', [TokensController::class, 'index'])->name('api.client.token');

    Route::post('refreshToken', [LoginController::class, 'issueToken'])->name('api.client.refreshToken');

    Route::get('salutations', [SalutationsController::class, 'index'])->name('api.salutations.index');

    Route::get('titles', [TitlesController::class, 'index'])->name('api.titles.index');

});

