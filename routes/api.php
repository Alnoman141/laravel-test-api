<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleHasPermissionController;
use App\Http\Controllers\Api\ModelHasRoleController;
use App\Http\Controllers\Api\ModelHasPermissionController;
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

    // role routes
    Route::prefix('role')->group(function () {

        Route::get('/', [RoleController::class, 'index']);

        Route::post('/', [RoleController::class, 'store']);

        Route::post('/update/{id}', [RoleController::class, 'update']);

    });

    // permission routes
    Route::prefix('permission')->group(function () {

        Route::get('/', [PermissionController::class, 'index']);

        Route::post('/', [PermissionController::class, 'store']);

        Route::post('/update/{id}', [PermissionController::class, 'update']);

    });

    // role has permission routes
    Route::prefix('role-has-permission')->group(function () {

        Route::post('/', [RoleHasPermissionController::class, 'store']);

        Route::post('/update/{role_id}', [RoleHasPermissionController::class, 'update']);

    });

    // model has role routes
    Route::prefix('model-has-role')->group(function () {

        Route::post('/', [ModelHasRoleController::class, 'store']);

        Route::post('/update/{model_id}', [ModelHasRoleController::class, 'update']);

    });

    // model has permission routes
    Route::prefix('model-has-permission')->group(function () {

        Route::post('/', [ModelHasPermissionController::class, 'store']);

        Route::post('/update/{model_id}', [ModelHasPermissionController::class, 'update']);

    });

    // user routes
    Route::prefix('user')->group(function () {

        Route::get('profile', [UserController::class, 'profile']);

        Route::post('profile/update', [UserController::class, 'update']);

        Route::get('{slug}', [UserController::class, 'show']);

        Route::put('delete/{slug}', [UserController::class, 'delete']);

        Route::put('logout', [AuthController::class, 'logout']);

        Route::post('change-password/{slug}', [AuthController::class, 'changePassword']);
    });

    // product routes
    Route::prefix('product')->group(function () {

        Route::post('store', [ProductController::class, 'store']);

        Route::post('upload-image', [ProductController::class, 'uploadImage']);

        Route::get('show/{slug}', [ProductController::class, 'show']);

        Route::post('update/{slug}', [ProductController::class, 'update']);

        Route::put('delete/{slug}', [ProductController::class, 'delete']);

        Route::post('delete/image', [ProductController::class, 'deleteImage']);
    });
});


