<?php

use App\Http\Controllers\StatusRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Status Request Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/statusRequest", [StatusRequestController::class, 'index']);
Route::get("/statusRequest/{id}", [StatusRequestController::class, 'show']);