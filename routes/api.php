<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZbraController;
use App\Models\Zbra;
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

    // Friends
    Route::post('/users/{friend}/friend-requests', [FriendRequestController::class, 'store']);
    Route::post('/friend-requests/{friendRequest}/accept', [FriendRequestController::class, 'accept']);
    Route::get('/friend-requests', [FriendRequestController::class, 'index']);

    Route::get('/users', [UserController::class, 'index']);

    Route::get('/friends', [FriendsController::class, 'index']);
    Route::get('/friends/{friend}', [FriendsController::class, 'view']);
    Route::get('/friends/{friend}/zbras', [FriendsController::class, 'zbras']);

    // Zbras
    Route::get('/zbras/{zbra}', [ZbraController::class, 'show']);
    Route::post('/zbras', [ZbraController::class, 'store']);

    Route::get('/feeds', [FeedController::class, 'index']);
});
