<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
*/

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::group(["middleware" => ["role:partner"]], function () {
        Route::get('/logos', [LogoController::class, 'getByUser'])->middleware("permission:read-logos");
        Route::post("/logos/updateLogo", [LogoController::class, 'updateLogo'])->middleware("permission:update-logos");
        Route::delete("/logos/deleteLogo", [LogoController::class, 'destroyLogo'])->middleware("permission:delete-logos");
    });
});