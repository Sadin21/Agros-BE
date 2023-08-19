<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Route::controller(AuthController::class)->prefix('auth')->group(['role:Admin', 'permission:USER.CREATE'], function () {
    //     Route::post('register', 'register');
    // });
    
Route::controller(CustomerController::class)->prefix('customer')->group(function () {
    Route::get('query', 'query');
    Route::get('me', 'me');
    Route::put('update/{id}', 'update');
    Route::put('update-my-data/{id}', 'selfUpdate');
    Route::delete('delete/{id}', 'delete');
});
