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
    Route::get("/requests/user", [RequestController::class, 'getByUser']);
    Route::get("/requests/{id}", [RequestController::class, 'show']);
    Route::put("/requests/{id}", [RequestController::class, 'update']);
    Route::post("/requests/upload", [RequestController::class, 'upload']);
});
