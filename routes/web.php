<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);
Route::get('/getdata', [PostController::class, 'getdata']);
Route::post('/store', [PostController::class, 'store']);
Route::post('/update/{id}', [PostController::class, 'update']);
Route::get('/destroy/{id}', [PostController::class, 'destroy']);

