<?php

use App\Http\Controllers\Admin\Village\VillageController;
use Illuminate\Support\Facades\Route;

Route::controller(VillageController::class)->prefix('village')->name('village.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/get-data', 'getData')->name('get-data');
    Route::post('/store', 'store')->name('store');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/destroy', 'destroy')->name('destroy');
    Route::delete('/destroy-many', 'destroyMany')->name('destroy-many');
});
