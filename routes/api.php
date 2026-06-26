<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsTokenController;
use App\Http\Controllers\SendSmsController;

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

Route::post('/getAccessToken', [SmsTokenController::class, 'getAccessToken'])->name('getAccessToken');
Route::post('/sendSms', [SendSmsController::class, 'sendSms'])->name('sendSms');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});