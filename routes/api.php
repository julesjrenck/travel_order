<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/travel-orders', [TravelOrderController::class, 'index']);
    Route::get('/travel-orders/{id}', [TravelOrderController::class, 'show']);
    Route::post('/travel-orders', [TravelOrderController::class, 'store']);
    Route::put('/travel-orders/{id}', [TravelOrderController::class, 'update']); 
    Route::patch('/travel-orders/{id}', [TravelOrderController::class, 'update']); 
    Route::post('/travel-orders/{id}/notify', [TravelOrderController::class, 'notify']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});