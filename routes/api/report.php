<?php

use App\Http\Controllers\Api\Report\ReportController;
use Illuminate\Support\Facades\Route;

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

Route::controller(ReportController::class)->prefix('report')->group(function () {
    Route::get('/', 'getData');
    Route::get('/{id}', 'show');
});
