<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\SocialAssistance\SocialAssistanceController;
use App\Models\SocialAssistance;
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

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('logout', 'logout');
        Route::post('login', 'login');
        Route::post('refresh', 'refresh');
        Route::get('me', 'me');
        Route::put('change-password', 'changePassword');
        Route::post('upload-family-card', 'uploadFamilyCard');
        Route::get('summary', 'summary');
    });

    Route::middleware(['JwtMiddleware'])->group(function () {
        Route::controller(SocialAssistanceController::class)->prefix('social-assistance')->group(function () {
            Route::get('/', 'getData');
        });
    });
});
