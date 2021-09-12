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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// public routes

Route::post('register', [AuthController::class, 'register']);

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);

    Route::get('/profile', [UserController::class, 'profile']);

    Route::put('/profile/update', [UserController::class, 'update']);

    Route::get('/{slug}', [UserController::class, 'show']);

    Route::put('/delete/{slug}', [UserController::class, 'delete']);
});


