<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;


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

Route::prefix('auth')->group( function (){

    Route::post('/register',  [AuthController::class, 'register']);
    Route::post('/login',  [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function (){
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::get('/user',  [UserController::class, 'user']);
        Route::put('/user/change-password',  [UserController::class, 'changeUserPassword']);
        Route::put('/user/update',  [UserController::class, 'updateUser']);
        Route::delete('/user/delete',  [UserController::class, 'destroyUser']);

        Route::get('/users',[UserController::class, 'allUsers']);
        Route::put('/users/update/{user}',[UserController::class, 'update']);
        Route::delete('/users/delete/{user}',[UserController::class, 'destroy']);
        Route::put('/users/change-password/{user}',  [UserController::class, 'changeUsersPassword']);
    });

});

