<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserClickSignController;

/*
|--------------------------------------------------------------------------
| Clicksign Routes
|--------------------------------------------------------------------------
|
*/

Route::get("/clickSign/createSigner/{userId}", [UserClickSignController::class, 'createSigner']);
Route::get("/clickSign/createDocumentTemplate/{userId}", [UserClickSignController::class, 'createDocumentTemplate']);
Route::get("/clickSign/createSignerDocument/{userId}", [UserClickSignController::class, 'createSignerDocument']);
Route::get("/clickSign/notificationEmail/{userId}", [UserClickSignController::class, 'notificationEmail']);
