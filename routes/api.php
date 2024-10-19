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

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{eventId}', [EventController::class, 'get']);
Route::post('/events', [EventController::class, 'create']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'delete']);
Route::post('/events/user', [EventController::class, 'creatUser']);
Route::delete('/events/user/{id}', [EventController::class, 'deleteUser']);
Route::post('/events/subscribe/{eventId}', [EventController::class, 'subscribe']);
Route::post('/events/emails/{id}', [EventController::class, 'testEmail']);
Route::get('/send-telegram', [EventController::class, 'sendTelegramNotification']);
