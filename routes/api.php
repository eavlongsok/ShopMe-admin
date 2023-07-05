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
    Route::post('/create/admin', [APIController::class, 'createNewAdmin']);
    Route::get('/admin/info', [APIController::class, 'getAdminInformation']); // get the logged in admin info
    Route::post('/admin/editAccount', [APIController::class, 'editAccount']); // edit the logged in admin account
    Route::post('/admin/editLogo', [APIController::class, 'editLogo']);

    Route::get('/search/{userType}', [APIController::class, 'searchUsers']);
    Route::get('/ban/user/{userType}', [APIController::class, 'ban']);
    Route::get('/unban/user/{userType}', [APIController::class, 'unban']);
    Route::get('/listingRequests', [APIController::class, 'listingRequests']);
    Route::get('/approveProduct', [APIController::class, 'approveProduct']);
    Route::get('/unapproveProduct', [APIController::class, 'unapproveProduct']);
    Route::get('/verification', [APIController::class, 'getPendingVerificationAccounts']);
    Route::get('/verify', [APIController::class, 'verifyAccount']);
    Route::get('/unverify', [APIController::class, 'unverifyAccount']);
    Route::get('/rejectVerification', [APIController::class, 'rejectVerification']);
    Route::get('/getCategories', [APIController::class, 'getCategories']);
    Route::get('/getProducts', [APIController::class, 'getProducts']);
    Route::get('/ban/product', [APIController::class, 'banProduct']);
    Route::get('/unban/product', [APIController::class, 'unbanProduct']);
    Route::get('/remove/product', [APIController::class, 'removeProduct']);
    Route::get('/getBannedProducts', [APIController::class, 'getBannedProducts']);
    Route::get('/info/monthlyaccounts', [APIController::class, 'getAllAccountInformation']);
    Route::get('/info/monthlyproducts', [APIController::class, 'getAllProductInformation']);
});