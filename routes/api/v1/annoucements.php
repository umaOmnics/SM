<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementsController;

Route::group(['prefix' => 'v1/announcements'], function () {

    Route::get('',[AnnouncementsController::class, 'index'])->name('designations.index');

    Route::post('',[AnnouncementsController::class, 'store'])->name('designations.store');

    Route::get('{id}',[AnnouncementsController::class, 'show'])->name('designations.show');

    Route::post('{id}',[AnnouncementsController::class, 'update'])->name('designations.update');

    Route::delete('{id}',[AnnouncementsController::class, 'destroy'])->name('designations.destroy');

    Route::post('multi/delete',[AnnouncementsController::class, 'massDelete'])->name('designations.massDestroy');

});
