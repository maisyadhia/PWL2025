<?php

use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;

Route::get('/level', [LevelController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});
