<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RollController;
use App\Http\Controllers\BetController;

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

Route::post('login', [AuthController::class, 'authenticate']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/me', [UserController::class, 'show'])->middleware('auth:sanctum');

Route::apiResource('rolls', RollController::class)->only(['index']);

Route::apiResource('bets', BetController::class)->middleware('auth:sanctum');
