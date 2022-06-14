<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailerController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
*/

Route::post("/mails/send-mail", [MailerController::class, "composeEmail"]);