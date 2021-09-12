<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// public routes

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('users/', [UserController::class, 'index']);

    Route::get('user/profile', [UserController::class, 'profile']);

    Route::post('user/profile/update', [UserController::class, 'update']);

    Route::get('user/{slug}', [UserController::class, 'show']);

    Route::put('/delete/{slug}', [UserController::class, 'delete']);

    Route::put('user/logout', [AuthController::class, 'logout']);
});


