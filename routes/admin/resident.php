<?php

use App\Http\Controllers\Admin\Resident\ResidentController;
use Illuminate\Support\Facades\Route;

Route::controller(ResidentController::class)->prefix('resident')->name('resident.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/get-data', 'getData')->name('get-data');
    Route::get('/create', 'create')->name('create');
    Route::delete('/destroy-many', 'destroyMany')->name('destroy-many');
    Route::get('/{id}', 'show')->name('show');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::get('/{id}/get-data', 'getDetailData')->name('get-detail-data');
    Route::post('/store', 'store')->name('store');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/destroy', 'destroy')->name('destroy');
});
