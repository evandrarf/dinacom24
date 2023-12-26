<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('admin.dashboard.index'));
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect(route('admin.dashboard'));
    });

    Route::controller(LoginController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login-form');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    });

    Route::middleware('auth')->group(function () {
        require __DIR__ . '/admin/dashboard.php';
        require __DIR__ . '/admin/resident.php';
    });
});
