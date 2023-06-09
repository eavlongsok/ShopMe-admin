<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'homePage'])->middleware('checkMainPage');

Route::post('/log-in', [AuthController::class, 'logIn'])->middleware('checkLogin');

Route::get('/login', function () {
    return view('login');
})->middleware('checkLogin');

Route::post('/log-out', [AuthController::class, 'logOut'])->middleware('checkMainPage');
