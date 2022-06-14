<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\UserCorporateController;

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

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/reset-password', function (Request $request) {
    return Inertia::render('Auth/ResetPassword', [
        'email' => $request->query('email'),
        'token' => $request->query('token'),
    ]);
});

Route::get('/email/verify/success', function (Request $request) {
    return Inertia::render('Auth/EmailVerified');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
*/

Route::get('/teste', function (Request $request) {
    $urlStore = 'https://loja.easytoque.com.br/createStore.php';

    $response = Http::asForm()->post($urlStore, [
        'store_name' => 'Mateus Teste',
        'rev_id' => 202,
    ]);

    dump(time());
    dd($response->json());
});