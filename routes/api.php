<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\FriendsController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::get('/test', static function(): string {
        return 'test';
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', static function (Request $request) {
        return $request->user();
    });

    Route::post('/users/{friend}/friend-requests', [FriendRequestController::class, 'store']);
    Route::post('/friend-requests/{friendRequest}/accept', [FriendRequestController::class, 'accept']);
    Route::get('/friend-requests', [FriendRequestController::class, 'index']);

    Route::get('/friends', [FriendsController::class, 'index']);
});
