<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api;" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/', [SiteController::class, 'index']);

    Route::post('/auth/login', [AuthController::class, 'login']); //->middleware(['signature']);
    Route::post('/auth/logout', [AuthController::class, 'logout']); //->middleware(['signature']);
    Route::get('/auth/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);

    Route::get('/users', [UserController::class, 'index']); //->middleware(['auth.api', 'role:user.view']);
    Route::get('/users/{id}', [UserController::class, 'show']); //->middleware(['auth.api', 'role:user.view']);
    Route::post('/users', [UserController::class, 'store']); //->middleware(['auth.api', 'role:user.create|roles.view']);
    Route::put('/users/{id}', [UserController::class, 'update']); //->middleware(['auth.api', 'role:user.update||roles.view']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']); //->middleware(['auth.api', 'role:user.delete']);

    Route::get('/roles', [RoleController::class, 'index']); //->middleware(['auth.api', 'role:roles.view']);
    Route::get('/roles/{id}', [RoleController::class, 'show']); //->middleware(['auth.api', 'role:roles.view']);
    Route::post('/roles', [RoleController::class, 'store']); //->middleware(['auth.api', 'role:roles.create']);
    Route::put('/roles', [RoleController::class, 'update']); //->middleware(['auth.api', 'role:roles.update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']); //->middleware(['auth.api', 'role:roles.delete']);


    Route::get('/posts', [PostController::class, 'index']); //->middleware(['auth.api', 'role:posts.view']);
    Route::get('/posts/{id}', [PostController::class, 'show']); //->middleware(['auth.api', 'role:posts.view']);
    Route::post('/posts', [PostController::class, 'store']); //->middleware(['auth.api', 'role:posts.create']);
    Route::put('/posts/{id}', [PostController::class, 'update']); //->middleware(['auth.api', 'role:posts.update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); //->middleware(['auth.api', 'role:posts.delete']);
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
