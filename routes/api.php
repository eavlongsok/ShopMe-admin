<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/search/{userType}', [APIController::class, 'searchUsers']);
    Route::get('/ban/{userType}', [APIController::class, 'ban']);
    Route::get('/unban/{userType}', [APIController::class, 'unban']);
    Route::get('/listingRequests', [APIController::class, 'listingRequests']);
    Route::get('/approveProduct', [APIController::class, 'approveProduct']);
});