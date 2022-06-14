<?php

use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Roles Routes
|--------------------------------------------------------------------------
|
*/

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::get("/requests", [RequestController::class, 'index']);
    Route::get("/requests/{id}", [RequestController::class, 'show']);
});
