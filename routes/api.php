<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\DeliveryRequestController;
use \App\Http\Controllers\DeliveryTraceController;

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


Route::post('/register', [UserController::class, 'register'])->middleware('auth:sanctum');;

Route::post('/login', [UserController::class, 'login']);


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::prefix('/request')->group(function () {

        Route::put('/{trackingCode}/cancel', [DeliveryRequestController::class, 'cancel']);

        Route::get('/{trackingCode}/detail', [DeliveryRequestController::class, 'detail']);

        Route::post('/submit', [DeliveryRequestController::class, 'submit'])->middleware('access.partner');

        Route::get('/all', [DeliveryRequestController::class, 'index'])->middleware('access.admin');

        Route::put('/{trackingCode}/accept-delivery', [DeliveryRequestController::class, 'setDeliveryMan'])->middleware('access.deliveryman');

        Route::put('/{trackingCode}/{status}', [DeliveryRequestController::class, 'deliveryStatus'])->middleware('access.deliveryman');

        Route::get('/wait-list', [DeliveryRequestController::class, 'waitList'])->middleware('access.deliveryman');

        Route::get('/accepted-list', [DeliveryRequestController::class, 'AcceptedList'])->middleware('access.deliveryman');

        Route::put('/tracing', [DeliveryTraceController::class, 'tracing'])->middleware('access.deliveryman');

    });
});

