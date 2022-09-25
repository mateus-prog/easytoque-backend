<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\UserCorporateController;
use App\Http\Controllers\SoapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/env', function () {

    dump(env('APP_NAME'));
    dump(config('app.url'));
    //dd(config('app.url'));
});

Route::get('/teste', [SoapController::class, 'index']);