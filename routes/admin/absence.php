<?php

use App\Http\Controllers\Admin\Absence\AbsenceController;
use Illuminate\Support\Facades\Route;

Route::controller(AbsenceController::class)->prefix('absence')->name('absence.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/get-detail-recipient', 'getDetailRecipient')->name('get-detail-recipient');
    Route::post('/confirm-absence', 'confirm')->name('confirm-absence');
});
