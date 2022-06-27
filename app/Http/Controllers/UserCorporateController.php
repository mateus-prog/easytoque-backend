<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use App\Services\User\UserService;
use App\Services\User\UserBankService;
use App\Services\User\UserCorporateService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;

class UserCorporateController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $userService;
    protected $userBankService;
    protected $userCorporateService;
    
    public function __construct(
        UserService $userService,
        UserBankService $userBankService,
        UserCorporateService $userCorporateService
    )
    {
        //$this->middleware(["auth", "verified"]);
        $this->userService = $userService;
        $this->userBankService = $userBankService;
        $this->userCorporateService = $userCorporateService;
    }

    public function getUserCorporateByUser($userId)
    {
        $userCorporate = $this->userCorporateService->getUserCorporateByUser($userId);

        return $this->success($userCorporate, HttpStatus::SUCCESS);
    }

    public function getUserCorporateEditByUser($userId)
    {
        $user = $this->userService->findByHash($userId);
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
        
        return $this->success($userCorporateBank, HttpStatus::SUCCESS);
    }
}
