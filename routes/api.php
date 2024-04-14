<?php

use App\Http\Controllers\Admin\Product\CategoryController;
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

/********************* Auth *********************/
Route::prefix('auth')->group(function ($router) {
  Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/login-ecommerce', 'loginEcommerce')->name('loginEcommerce');
    Route::post('/login/verify-email/', 'verifyEmail')->name('verifyEmail');
    Route::post('/forgot-password/', 'forgotPassword')->name('forgotPassword');
    Route::post('/verify-set-password/', 'verifySetPassword')->name('verifySetPassword');
    Route::post('/set-new-password/', 'setNewPassword')->name('setNewPassword');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/refresh', 'refresh')->name('refresh');
    Route::post('/me', 'me')->name('me');
  });
});

/********************* Auth *********************/
Route::group(
  [
    'middleware' => 'auth:api',
    'prefix' => 'admin',
  ],
  function($router) {
    Route::get('category/list-departments-categories', [CategoryController::class, 'listCategories']);
    Route::resource('category', CategoryController::class);
    Route::post('category/{id}', [CategoryController::class, 'update']);
  }
);
