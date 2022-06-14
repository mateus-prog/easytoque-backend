<?php

use App\Http\Controllers\StatesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| States Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/states", [StatesController::class, 'index']);
Route::get("/states/uf/{uf}", [StatesController::class, 'showByUf']);