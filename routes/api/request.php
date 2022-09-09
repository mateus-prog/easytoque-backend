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
    Route::get("/requests/store/{type}", [RequestController::class, 'requestStore']);
    Route::get("/requests/client", [RequestController::class, 'getClient']);
    Route::get("/requests/{id}", [RequestController::class, 'show']);
    Route::put("/requests/{id}", [RequestController::class, 'update']);
    Route::post("/requests/upload/proof", [RequestController::class, 'uploadFileProof']);
    Route::post("/requests/upload/invoice", [RequestController::class, 'uploadFileInvoice']);
});
