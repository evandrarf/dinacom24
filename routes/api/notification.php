<?php

use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Ticket\TicketController;
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

Route::controller(NotificationController::class)->prefix('notification')->group(function () {
    Route::get('/', 'getData');
    Route::post('/{id}/read', 'read');
});
