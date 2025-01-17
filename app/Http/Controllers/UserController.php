<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Services\User\UserCorporateService;
use App\Services\User\UserStoreService;
use App\Services\User\UserBankService;
use App\Services\User\UserRoleService;
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
    protected $userRoleService;
    protected $statusUserService;
    protected $roleService;
    protected $logService;
    protected $mailService;

    public function __construct(
        UserService $userService, 
        UserCorporateService $userCorporateService, 
        UserStoreService $userStoreService,
        UserBankService $userBankService,
        UserRoleService $userRoleService,
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
        $this->userRoleService = $userRoleService;
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

            $this->userRoleService->store(['role_id' => $request['role_id'], 'user_id' => $user->id]);

            return $this->success($user, HttpStatus::CREATED);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function sendMailWelcome($id)
    {
        try {  

            $user = $this->userService->findById($id);
            
            $mailRecipient = $user->email;
            $name = $user->first_name;
            
            //mail welcome
            $mailBody = $this->mailService->createMailWelcomeBody($name);
            $mailSubject = utf8_decode($name) . ", bem vindo parceiro Easytoque";

            $messageLog = "Bem vindo ao Easytoque";

            $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $id, $messageLog);

            //mail complete Data Bank
            $link = env('EXTERNAL_APP_URL').'/partners/edit-bank-data/'.$user->hash_id;
            $mailBody = $this->mailService->createMailDataBankUserBody($name, $link);
            $mailSubject = utf8_decode($name) . ", complete seu cadastro como parceiro Easytoque";

            $messageLog = "Complete seu cadastro como parceiro Easytoque";                

            $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $id, $messageLog);

            return $this->success($user, HttpStatus::SUCCESS);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function sendMailPartnerFinish($id)
    {
        try {  

            $user = $this->userService->findById($id);
            
            //sendMail complete register user
            $mailRecipient = $user->email;
            $name = $user->first_name;
            
            $clientId  = $this->userStoreService->getUserStoreByUserClientId($id);

            $linkStore = 'https://loja.easytoque.com.br/?___store=loja_'.$clientId;
            
            //mail welcome
            $mailBody = $this->mailService->createMailPartnerAddFinish($name, $mailRecipient, $linkStore);
            $mailSubject = "[Parceiros Easytoque] - Seus dados de acesso e sua loja!";

            $messageLog = "Dados da loja";
            
            $this->mailService->sendMail($mailRecipient, $mailSubject, $mailBody, $user->id, $messageLog);

            return $this->success($user, HttpStatus::SUCCESS);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->userService->findById($id);

            if($user->role_id == 4)
            {
                $user = $this->userService->getUserByUser($id);

                $userCorporate = $this->userCorporateService->getUserCorporateEditByUser($id);
                $userStore = $this->userStoreService->getUserStoreByUserComission($id);
                $userStore['commission'] = intval($userStore['commission']);

                $user = array_merge($userCorporate[0], $user[0]); 
                $user = array_merge($userStore, $user);
            } 

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
            $role = $this->roleService->findById($user->role_id);
            
            $messageLog = $role['display_name'];
            $actionId = 2;
            $idUserLog = $id;
            $userId = $id;

            $log = Log::createLog($idUserLog, $messageLog, $actionId);

            //verifica se no request vem o campo role_id e faz o update
            if($user->role_id == 4){

                $input = $request->only(["first_name", "last_name", "email", "phone", "whatsapp", "cpf", "hash_id"]);
                $this->userService->update($userId, $input);

                $userCorporate = $this->userCorporateService->getUserCorporateByUser($userId);
                $userCorporate = $userCorporate[0];

                $input = $request->only(["corporate_name", "cnpj", "address", "number", "complement", "district", "city", "cep", "state_id"]);
                $userCorporate = $this->userCorporateService->update($userCorporate->id, $input);

                $id = $this->userStoreService->getUserStoreByUser($userId);

                $input = $request->only(["commission"]);
                $userStore = $this->userStoreService->update($id, $input);

            }else{

                $input = $request->only(["first_name", "last_name", "password", "role_id", "email"]);
                $this->userService->update($userId, $input);

                $idUserRole = $this->userRoleService->getUser($userId)[0]['id'];
                $this->userRoleService->update($idUserRole, ['role_id' => $request['role_id']]);
            
            }

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

    public function checkCnpj(Request $request)
    {
        try {
            //Gate::authorize('update', User::findOrFail($id));
            $userCnpj = $this->userCorporateService->checkCnpj($request['cnpj']);

            $userId = isset($userCnpj[0]) ? $userCnpj[0]['user_id'] : 0;
            
            return $this->success($userId, HttpStatus::SUCCESS);
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
