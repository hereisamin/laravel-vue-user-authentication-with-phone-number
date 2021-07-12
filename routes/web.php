<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\phoneRegisterController;
use App\Http\Controllers\Auth\phoneLoginController;
use App\Http\Controllers\Auth\phonePasswordResetController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

//Route::post('/sendCode', [sendCode::class, 'sendCode'])->name('sendCode');
Route::get('/phoneRegister', [phoneRegisterController::class, 'create'])->name('phoneRegister');
Route::post('/phoneRegister', [phoneRegisterController::class, 'sendCode'])->name('phoneRegister');
Route::post('/registration', [phoneRegisterController::class, 'store'])->name('registration');
Route::get('/phoneLogin', [phoneLoginController::class, 'create'])->name('phoneLogin');
Route::post('/phoneLogin', [phoneLoginController::class, 'setLogin'])->name('phoneLogin');
Route::get('/resetPassword', [phonePasswordResetController::class, 'create'])->name('resetPassword');
Route::post('/resetPassword', [phonePasswordResetController::class, 'sendCode'])->name('resetPassword');
Route::post('/setPassword', [phonePasswordResetController::class, 'setPassword'])->name('setPassword');
