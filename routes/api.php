<?php

use App\Http\Controllers\EventController;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

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

Route::get('/hello', [EventController::class, 'hello']);

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('{eventId}', [EventController::class, 'get']);
    Route::post('/', [EventController::class, 'create']);
    Route::put('{id}', [EventController::class, 'update']);
    Route::delete('{id}', [EventController::class, 'delete']);
    Route::post('user', [EventController::class, 'creatUser']);
    Route::delete('user/{id}', [EventController::class, 'deleteUser']);
    Route::post('subscribe/{eventId}', [EventController::class, 'subscribe']);
    Route::post('emails/{id}', [EventController::class, 'testEmail']);
});

Route::get('/send-telegram', [EventController::class, 'sendTelegramNotification']);
Route::get('/send-discord', [EventController::class, 'sendDiscordNotification']);
Route::get('/test', [EventController::class, 'testEventDispatch']);
