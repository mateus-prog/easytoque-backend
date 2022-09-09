<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Services\User\UserBankService;
use App\Services\User\UserCorporateService;
use App\Services\Mail\MailService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class UserBankController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $userService;
    protected $userBankService;
    protected $userCorporateService;
    protected $mailService;
    
    public function __construct(
        UserService $userService, 
        UserBankService $userBankService,
        UserCorporateService $userCorporateService,
        MailService $mailService
    )
    {
        $this->userService = $userService;
        $this->userBankService = $userBankService;
        $this->userCorporateService = $userCorporateService;
        $this->mailService = $mailService;
    }

    public function getUserBankByUser($userId)
    {
        $this->middleware(["auth", "verified"]);
        $userBank = $this->userBankService->getUserBankByUser($userId);

        return $this->success($userBank, HttpStatus::SUCCESS);
    }

    public function update($id, Request $request)
    {
        try {
            $input = $request->only(["bank_id", "agency", "agency_digit", "checking_account", "checking_account_digit", "pix"]);
            $this->userBankService->update($id, $input);

            $userBank = $this->userBankService->findById($id);

            $user = $this->userService->findById($userBank->user_id);
            if(isset($request['password'])){
                $input = $request->only(["phone", "whatsapp", "password"]);
            }else{
                $input = $request->only(["phone", "whatsapp"]);
            }

            $this->userService->update($id, $input);

            if($user->status_user_id != 1){
                $userCorporate = $this->userCorporateService->getUserCorporateByUser($user->id);
                $userCorporate = $userCorporate[0];
                $mailManager = 'parceiros+00@toquecolor.com.br';

                //sendMail complete register user
                $mailRecipient = $mailManager;
                
                //mail welcome
                $mailBody = $this->mailService->createMailCompleteRegisterBody($user->first_name, $userCorporate->corporate_name, $user->email);
                $mailSubject = "[Parceiros Easytoque] - Mais um parceiro finalizou o cadastro";

                $messageLog = "Finalizou Cadastro";

                $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $user->id, $messageLog);
            }

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
