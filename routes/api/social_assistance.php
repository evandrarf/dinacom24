<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\SocialAssistance\SocialAssistanceController;
use Illuminate\Http\Request;
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

Route::controller(SocialAssistanceController::class)->prefix('social-assistance')->group(function () {
    Route::get('/', 'getData');
});
