<?php

use App\Http\Controllers\Admin\SocialAssistance\SocialAssistanceController;
use Illuminate\Support\Facades\Route;

Route::controller(SocialAssistanceController::class)->prefix('social-assistance')->name('social-assistance.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/get-data', 'getData')->name('get-data');
    Route::post('/store', 'store')->name('store');
    Route::get('/create', 'create')->name('create');
    Route::delete('/destroy-many', 'destroyMany')->name('destroy-many');
    Route::get('/{id}', 'show')->name('show');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::get('/{id}/get-data', 'getDetailData')->name('get-detail-data');
    Route::get('/{id}/get-resident-data', 'getResidentData')->name('get-resident-data');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/{id}/destroy', 'destroy')->name('destroy');
});
