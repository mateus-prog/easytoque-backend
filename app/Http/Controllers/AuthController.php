<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UserWrongCredentials;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\SignInRequest;
use App\Http\Requests\Authentication\SignUpRequest;
use App\Services\Authentication\SignInUserService;
use App\Services\Authentication\SignUpUserService;
use App\Services\User\UserService;
use App\Services\Mail\MailService;
use Exception;

class AuthController extends Controller
{
    use ApiResponser;

    protected $userService;
    protected $mailService;
    
    public function __construct(
        UserService $userService,
        MailService $mailService
    )
    {
        $this->userService = $userService;
        $this->mailService = $mailService;
    }

    public function register(SignUpRequest $request)
    {
        try {
            $user = (new SignUpUserService())->execute($request->only(['name', 'email', 'password']));

            $user->sendEmailVerificationNotification();

            return $this->success([
                'user' => $user->format(),
                'token' => $user->createToken('API Token')->plainTextToken
            ]);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function login(SignInRequest $request)
    {
        try {
            $request['status_user_id'] = 1;
            (new SignInUserService())->execute($request->only(['email', 'password', 'status_user_id']));

            return $this->success([
                'user' => auth()->user()->format(),
                'token' => auth()->user()->createToken('API Token')->plainTextToken
            ]);
        } catch (UserWrongCredentials $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => __('generic.tokensRevoked')
        ];
    }

    public function change(Request $request)
    {
        try {
            $input = $request->all();
            $this->userService->update(Auth::user()->id, $input);
    
            return $this->success('sucesso', HttpStatus::SUCCESS);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function reset(Request $request)
    {
        //try {
            $password = $this->generatePassword();
            $user = $this->userService->findByMail($request['email']);

            if(!empty($user)){
                $userId = $user[0]['id'];
                $user = $this->userService->findById($userId);
                
                //sendMail complete register user
                $mailRecipient = $user->email;
                
                //mail welcome
                $mailBody = $this->mailService->createMailPasswordPartner($user->first_name, $password, $mailRecipient);
                $mailSubject = "[Parceiros Easytoque] - Seus dados de acesso!";

                $messageLog = "Dados do usuário";
                
                $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $userId, $messageLog);

                //update de senha
                $this->userService->update($userId, ['password' => $password]);
        
                return $this->success('sucesso', HttpStatus::SUCCESS);
            }else{
                return response()->noContent();
            }
        /*} catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }*/
    }

    function generatePassword($qtyCaraceters = 8)
    {
        //Letras minúsculas embaralhadas
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');
    
        //Letras maiúsculas embaralhadas
        $capitalLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    
        //Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;
    
        //Caracteres Especiais
        $specialCharacters = str_shuffle('!@#$%*-');
    
        //Junta tudo
        $characters = $capitalLetters.$smallLetters.$numbers.$specialCharacters;
    
        //Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, $qtyCaraceters);
    
        //Retorna a senha
        return $password;
    }
}
