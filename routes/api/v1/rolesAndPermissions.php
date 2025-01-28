<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesPermissions\ResourceController;
use App\Http\Controllers\RolesPermissions\RoleController;
use App\Http\Controllers\RolesPermissions\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes for User - Roles and Permissions ..
|--------------------------------------------------------------------------
|
*/

Route::group(['middleware' => ['auth:api'],'prefix'=>'v1'], function () {

    Route::group(['prefix' => 'roles'], function (){

        Route::get('', [RoleController::class, 'index'])->name('roles.index');

        Route::post('', [RoleController::class, 'store'])->name('roles.store');

        Route::get('{id}', [RoleController::class, 'show'])->name('roles.show');

        Route::post('{id}', [RoleController::class, 'update'])->name('roles.update');

        Route::post('resources/{id}', [RoleController::class, 'updateRolesResources'])->name('roles.resources.update');

        Route::delete('{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::post('resources/delete/{id}',[RoleController::class,'deleteRolesResources'])->name('roles.resources.delete');
    });

    Route::group(['prefix' => 'permissions'], function () {

        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');

    });

    Route::group(['prefix' => 'resources'], function () {

        Route::get('', [ResourceController::class, 'index'])->name('resources.index');

    });

});
