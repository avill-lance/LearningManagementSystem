<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    /* ---- Public auth endpoints ----------------------------------- */
    Route::post('/auth/login',            [AuthController::class, 'login']);
    Route::post('/auth/register/student', [AuthController::class, 'registerStudent']);
    Route::post('/auth/register/teacher', [AuthController::class, 'registerTeacher']);
    Route::get ('/auth/check-username',   [AuthController::class, 'checkUsername']);
    Route::post('/auth/forgot-password',  [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password',   [AuthController::class, 'resetPassword']);

    /* ---- Protected account endpoints (Sanctum) ------------------- */
    Route::middleware('auth:sanctum')->group(function () {
        Route::get   ('/accounts',                 [AccountController::class, 'index']);
        Route::get   ('/accounts/{account}',       [AccountController::class, 'show']);
        Route::post  ('/accounts',                 [AccountController::class, 'store']);
        Route::put   ('/accounts/{account}',       [AccountController::class, 'update']);
        Route::delete('/accounts/{account}',       [AccountController::class, 'destroy']);
        Route::post  ('/accounts/{account}/unlock',[AccountController::class, 'unlock']);
        Route::post  ('/auth/logout',              [AuthController::class, 'logout']);
    });
});