<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
