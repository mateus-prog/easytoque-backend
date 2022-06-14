<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BanksController;

/*
|--------------------------------------------------------------------------
| Bank Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/banks", [BanksController::class, 'index']);