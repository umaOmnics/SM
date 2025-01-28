<?php


use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth\LoginController;
use App\Http\Controllers\UserAuth\AuthenticationController;

Route::group(['prefix' => 'v1'],function(){

    //Authentication APIs

    Route::group(['prefix' => 'user'], function () {

        Route::post('register', [AuthenticationController::class, 'register'])->name('user.register')->middleware('auth:api');

        Route::post('login', [LoginController::class, 'login'])->name('user.login');

    });

    Route::group(['middleware' => ['auth:api']], function () {

        /*BEGIN -- USER CRUD */

        Route::get('users', [UsersController::class, 'index'])->name('user.index');

        Route::get('user/{id}', [UsersController::class, 'show'])->name('user.show');

        Route::delete('users/destroy/{id}', [UsersController::class, 'destroy'])->name('user.destroy');

        Route::delete('users/delete/{id}', [UsersController::class, 'forceDelete'])->name('user.forceDelete');

        Route::post('users/restore', [UsersController::class, 'restore'])->name('user.restore');

        /*END -- USER CRUD */

    });

});
