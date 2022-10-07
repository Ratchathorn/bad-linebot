<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::webhooks('https://6310450662-linebot.loca.lt');

Route::get('/', function () {
    return [
        'version' => '1.0.0'
    ];
});

Route::post('/types/post', [\App\Http\Controllers\GoldTypeController::class, 'replyMessage']);

Route::get('/types/view', [\App\Http\Controllers\GoldTypeController::class, 'viewLiff'])
    ->name('goldtype.view');


Route::post('/types/view/pushmessage', [\App\Http\Controllers\GoldTypeController::class, 'pushM'])
    ->name('goldtype.pushmessage');
Route::post('/types/view/pushgoldtype', [\App\Http\Controllers\GoldTypeController::class, 'pushG'])
    ->name('goldtype.pushgoldtype');
Route::post('/types/view/pushpostman', [\App\Http\Controllers\GoldTypeController::class, 'pushPM'])
    ->name('goldtype.pushpostman');

Route::get('/types/view/pushmessage', [\App\Http\Controllers\GoldTypeController::class, 'viewLiff']);
Route::get('/types/view/pushgoldtype', [\App\Http\Controllers\GoldTypeController::class, 'viewLiff']);
Route::get('/types/view/pushpostman', [\App\Http\Controllers\GoldTypeController::class, 'viewLiff']);
