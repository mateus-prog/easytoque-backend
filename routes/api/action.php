<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActionsController;

/*
|--------------------------------------------------------------------------
| Action Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/actions", [ActionsController::class, 'index']);