<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteHandler;

Route::post('login', [RouteHandler::class, 'login']);
Route::post('contact-us', [RouteHandler::class, 'contact']);
Route::get('setting', [RouteHandler::class, 'getPublicSetting']);


Route::middleware('auth:sanctum')->group(function(){

    Route::get('/user', [RouteHandler::class, 'profile']);

    Route::get('/get-setting', [RouteHandler::class, 'getSetting']);
    Route::post('/update-setting', [RouteHandler::class, 'updateSetting']);

});
