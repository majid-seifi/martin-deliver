<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
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

Route::middleware('api')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['middleware' => ['role:Intermediary']], function () {
            Route::post('request/register', [RequestController::class, 'register']);
            Route::delete('request/{requestModel}', [RequestController::class, 'cancel'])
                ->middleware(['request.owner', 'request.cancelable']);
        });
    });
});
