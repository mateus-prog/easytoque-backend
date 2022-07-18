<?php

use App\Http\Controllers\StatusUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Status Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/status", [StatusUserController::class, 'index']);
Route::get("/status/{id}", [StatusUserController::class, 'edit']);
