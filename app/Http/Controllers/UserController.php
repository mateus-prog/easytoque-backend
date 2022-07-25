<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserService;
use App\Services\User\UserCorporateService;
use App\Services\User\UserStoreService;
use App\Services\User\UserBankService;
use App\Services\StatusUser\StatusUserService;
use App\Services\Role\RoleService;
use App\Services\Log\LogService;
use App\Services\Mail\MailService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use App\Helpers\Log;

class UserController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $userService;
    protected $userCorporateService;
    protected $userStoreService;
    protected $userBankService;
    protected $statusUserService;
    protected $roleService;
    protected $logService;
    protected $mailService;

    public function __construct(
        UserService $userService, 
        UserCorporateService $userCorporateService, 
        UserStoreService $userStoreService,
        UserBankService $userBankService,
        StatusUserService $statusUserService,
        RoleService $roleService,
        LogService $logService,
        MailService $mailService
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->userService = $userService;
        $this->userCorporateService = $userCorporateService;
        $this->userStoreService = $userStoreService;
        $this->userBankService = $userBankService;
        $this->statusUserService = $statusUserService;
        $this->roleService = $roleService;
        $this->logService = $logService;
        $this->mailService = $mailService;
    }

    public function index()
    {
        $users = $this->userService->all();

        return $this->success($users, HttpStatus::SUCCESS);
    }

    public function getUsersByRole($roleId)
    {
        $users = $this->userService->getUsersByRole($roleId);
        
        return $this->success($users, HttpStatus::SUCCESS);
    }

    public function store(Request $request)
    {
        try {  
            if($request['role_id'] == 4)
            {
                $input = $request->only(["first_name", "last_name", "status_user_id", "role_id", "email", "password", "phone", "whatsapp", "cpf", "hash_id"]);
                $user = $this->userService->store($input);

                $input = $request->only(["corporate_name", "cnpj", "address", "number", "complement", "district", "city", "cep", "state_id", "user_id"]);
                $userCorporate = $this->userCorporateService->store($input, $user->id);

                $input = $request->only(["commission", "user_id"]);
                $userStore = $this->userStoreService->store($input, $user->id);

                $input = $request->only(["user_id"]);
                $userBankData = $this->userBankService->store($user->id);

            }else{
                $input = $request->only(["first_name", "last_name", "status_user_id", "role_id", "email", "password", "hash_id"]);
                $user = $this->userService->store($input);
            }
            
            $status = $this->statusUserService->findById($request['status_user_id']);
            $role = $this->roleService->findById($request['role_id']);
            
            $messageLog = $role['display_name'];
            $actionId = 1;
            $idUserLog = $user->id;

            $log = Log::createLog($idUserLog, $messageLog, $actionId);
            $this->logService->store($log);

            if($request['role_id'] == 4)
            {
                $mailRecipient = $user->email;
                $name = $user->first_name;
                
                //mail welcome
                $mailBody = $this->mailService->createMailWelcomeBody($name);
                $mailSubject = utf8_decode($name) . ", bem vindo parceiro Easytoque";

                $messageLog = "Bem vindo ao Easytoque";

                $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $user->id, $messageLog);

                //mail complete Data Bank
                $link = env('EXTERNAL_APP_URL').'/partners/edit-bank-data/'.$user->hash_id;
                $mailBody = $this->mailService->createMailDataBankUserBody($name, $link);
                $mailSubject = utf8_decode($name) . ", complete seu cadastro como parceiro Easytoque";

                $messageLog = "Complete seu cadastro como parceiro Easytoque";                

                $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $user->id, $messageLog);
            }

            return $this->success($user, HttpStatus::CREATED);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->userService->findById($id);
            
            return $this->success($user, HttpStatus::SUCCESS);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update($id, Request $request)
    {
        try {
            //Gate::authorize('update', User::findOrFail($id));

            $user = $this->userService->findById($id);
            $status = $this->statusUserService->findById($user->status_user_id);
            $role = $this->roleService->findById($user->role_id);
            
            $messageLog = $role['display_name'];
            $actionId = 2;
            $idUserLog = $id;

            $log = Log::createLog($idUserLog, $messageLog, $actionId);

            $input = $request->only(["first_name", "last_name", "password", "role_id", "email"]);
            $this->userService->update($id, $input);

            $this->logService->store($log);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function activeUser($id, Request $request)
    {
        try {
            //Gate::authorize('update', User::findOrFail($id));
            $input = $request->only(["status_user_id"]);
            
            $this->userService->updateUserActive($id, $input);

            $messageLog = '';
            $actionId = 5;
            $idUserLog = $id;

            $log = Log::createLog($idUserLog, $messageLog, $actionId);
            $this->logService->store($log);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function blockedUser($id, Request $request)
    {
        try {
            //Gate::authorize('update', User::findOrFail($id));
            $input = $request->only(["status_user_id"]);
            
            $this->userService->updateUserBlocked($id, $input);

            $messageLog = $request['reason'];
            $actionId = 4;
            $idUserLog = $id;

            $log = Log::createLog($idUserLog, $messageLog, $actionId);
            $this->logService->store($log);

            return response()->noContent();
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->destroy($id);

            return response()->noContent();
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
