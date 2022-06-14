<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenusController;

/*
|--------------------------------------------------------------------------
| Menu Routes
|--------------------------------------------------------------------------
|
*/

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::get("/menus/{roleId}", [MenusController::class, 'getMenuByRole']);
});
