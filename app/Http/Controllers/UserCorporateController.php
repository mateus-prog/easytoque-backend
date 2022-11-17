<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use App\Services\User\UserService;
use App\Services\User\UserBankService;
use App\Services\User\UserCorporateService;
use App\Services\User\UserStoreService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

use Illuminate\Support\Facades\Hash;

class UserCorporateController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $userService;
    protected $userBankService;
    protected $userCorporateService;
    protected $userStoreService;
    
    public function __construct(
        UserService $userService,
        UserBankService $userBankService,
        UserCorporateService $userCorporateService,
        UserStoreService $userStoreService,
    )
    {
        //$this->middleware(["auth", "verified"]);
        $this->userService = $userService;
        $this->userBankService = $userBankService;
        $this->userCorporateService = $userCorporateService;
        $this->userStoreService = $userStoreService;
    }

    public function getUserCorporateByUser($userId)
    {
        $userCorporate = $this->userCorporateService->getUserCorporateByUser($userId);

        return $this->success($userCorporate, HttpStatus::SUCCESS);
    }

    public function getUserCorporateEditByUser($hash)
    {
        $user = $this->userService->findByHash($hash);
        if(empty($user)){
            return response()->noContent();
        }   
        $userId = $user[0]['id'];

        $user = $this->userService->getUserByUser($userId);
        
        $userCorporate = $this->userCorporateService->getUserCorporateEditByUser($userId);
        $userCorporate = array_merge($userCorporate[0], $user[0]);

        $userBank = $this->userBankService->getUserBankEditByUser($userId);

        if(!empty($userBank)){
            $userCorporateBank = array_merge($userCorporate, $userBank[0]);
        }else{
            $userCorporateBank = $userCorporate;
        }

        $clientId = $this->userStoreService->getUserStoreByUserClientId($userId);
        $userCorporateBank['client_id'] = $clientId;
        $userCorporateBank['user_id'] = $userId;

        return $this->success($userCorporateBank, HttpStatus::SUCCESS);
    }

    
    //retirar esse metodo
    public function getUserHash(){
        $users = $this->userService->all();
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

                    $this->userService->update($user->id, $data);
                }else{
                    $data = array(
                        'hash_id' => $hash_id,
                        'senha_hash' => '1',
                    );

                    $this->userService->update($user->id, $data);
                }
            }
        }
    }
}
