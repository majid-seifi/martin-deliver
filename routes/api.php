<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeliveryLogController;
use App\Http\Controllers\RequestController;
use App\Models\Request as RequestModel;
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
        Route::group(['middleware' => ['role:Delivery']], function () {
            Route::get('requests', array(RequestController::class, 'requests'));
            Route::patch('request/accept/{requestModel}', [RequestController::class, 'accept'])
                ->middleware('request.status:'.RequestModel::STATUS_REGISTERED);
            Route::group(['middleware' => ['request.owner:delivery_id']], function () {
                Route::patch('request/sent/{requestModel}', [RequestController::class, 'sent'])
                    ->middleware('request.status:' . RequestModel::STATUS_ACCEPTED);
                Route::patch('request/delivered/{requestModel}', [RequestController::class, 'delivered'])
                    ->middleware('request.status:' . RequestModel::STATUS_SENT);
            });

            Route::post('delivery/log', array(DeliveryLogController::class, 'store'));
        });
    });
});
