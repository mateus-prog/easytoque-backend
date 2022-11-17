<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Http\Controllers\SoapController;

use Illuminate\Support\Facades\Hash;

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

Route::get('/hash', function(){
    $users = User::all();
    foreach($users as $user)
    {
        //verifica se o usuario é Parceiro e se o status esta pendente
        if($user->role_id == 4 && $user->senha_hash == '0')
        {
            $hash_id = str_replace('/', '', Hash::make($user->email));

            if($user->status_user_id == 1){

                $data = array(
                    'password' => $user->password,
                    'hash_id' => $hash_id,
                    'senha_hash' => '1',
                );
            }else{
                $data = array(
                    'hash_id' => $hash_id,
                    'senha_hash' => '1',
                );
            }

            $user->update($user->id, $data);
        }
    }
});