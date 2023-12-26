<?php

use App\Http\Controllers\Admin\Resident\ResidentController;
use Illuminate\Support\Facades\Route;

Route::controller(ResidentController::class)->prefix('resident')->name('resident.')->group(function () {
    Route::get('/', 'index')->name('index');
});
