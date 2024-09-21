<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


##--------------------------------------AUTH MODULE
Route::controller(AuthController::class)->group(function(){
    Route::post('register','register');
    Route::post('login','login');
    Route::post('logout','logout')->middleware('auth:sanctum');});

##--------------------------------------PROFILE MODULE
Route::post('updateProfile',[ProfileController::class,'updateProfile'])->middleware('auth:sanctum');
Route::get('Profile',[ProfileController::class,'getProfile'])->middleware('auth:sanctum');
