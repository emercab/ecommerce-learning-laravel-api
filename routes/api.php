<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

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

Route::group([
  // 'middleware' => 'api',
  'prefix' => 'auth'
], function ($router) {
  Route::post('/register', [AuthController::class, 'register'])->name('register');
  Route::post('/login', [AuthController::class, 'login'])->name('login');
  Route::post('/login-ecommerce', [AuthController::class, 'loginEcommerce'])->name('login.ecommerce');
  Route::post('/login/verify-email/', [AuthController::class, 'verifyEmail'])->name('verify.email');
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
  Route::post('/me', [AuthController::class, 'me'])->name('me');
});
