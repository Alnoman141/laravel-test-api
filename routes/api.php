<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
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

Route::post('forget-password/send-otp', [AuthController::class, 'sendOTP']);

Route::post('change-password-by-otp', [AuthController::class, 'changePasswordByOTP']);

Route::get('products', [ProductController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('users/', [UserController::class, 'index']);
    Route::get('brands/', [BrandController::class, 'index']);
    Route::get('categories/', [CategoryController::class, 'index']);

    Route::prefix('user')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);

        Route::post('profile/update', [UserController::class, 'update']);

        Route::get('{slug}', [UserController::class, 'show']);

        Route::put('delete/{slug}', [UserController::class, 'delete']);

        Route::put('logout', [AuthController::class, 'logout']);

        Route::post('change-password/{slug}', [AuthController::class, 'changePassword']);
    });
    Route::prefix('product')->group(function () {

        Route::post('store', [ProductController::class, 'store']);

        Route::post('upload-image', [ProductController::class, 'uploadImage']);

        Route::get('show/{slug}', [ProductController::class, 'show']);

        Route::post('update/{slug}', [ProductController::class, 'update']);

        Route::put('delete/{slug}', [ProductController::class, 'delete']);

        Route::post('delete/image', [ProductController::class, 'deleteImage']);
    });
});


