<?php

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

Route::controller(TicketController::class)->prefix('ticket')->group(function () {
    Route::get('/{id}', 'show');
});
