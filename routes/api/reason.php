<?php

use App\Http\Controllers\ReasonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reason Routes
|--------------------------------------------------------------------------
|
*/

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::get("/reasons", [ReasonController::class, 'index']);
    Route::post("/reasons", [ReasonController::class, 'store']);
});
