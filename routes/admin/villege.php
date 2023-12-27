<?php

use App\Http\Controllers\Admin\Villege\VillegeController;
use Illuminate\Support\Facades\Route;

Route::controller(VillegeController::class)->prefix('villege')->name('villege.')->group(function () {
    Route::get('/', 'index')->name('index');
});
