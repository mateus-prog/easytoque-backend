<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Status Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/store", [StoreController::class, 'index']);
//Route::get("/status/{id}", [StoreController::class, 'edit']);
