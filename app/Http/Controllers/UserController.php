<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserService;
use App\Services\User\UserCorporateService;
use App\Services\User\UserStoreService;
use App\Services\User\UserBankService;
use App\Services\Store\StoreService;
use App\Services\StatusUser\StatusUserService;
use App\Services\Role\RoleService;
use App\Services\Log\LogService;
use App\Http\Requests\User\UserRequest;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $userService;
    protected $userCorporateService;
    protected $userStoreService;
    protected $userBankService;
    protected $storeService;
    protected $statusUserService;
    protected $roleService;
    protected $logService;

    public function __construct(
        UserService $userService, 
        UserCorporateService $userCorporateService, 
        UserStoreService $userStoreService,
        UserBankService $userBankService,
        StoreService $storeService,
        StatusUserService $statusUserService,
        RoleService $roleService,
        LogService $logService
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->userService = $userService;
        $this->userCorporateService = $userCorporateService;
        $this->userStoreService = $userStoreService;
        $this->userBankService = $userBankService;
        $this->storeService = $storeService;
        $this->statusUserService = $statusUserService;
        $this->roleService = $roleService;
        $this->logService = $logService;
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

    public function store(UserRequest $request)
    {
        try {  
            if($request['role_id'] == 4)
            {
                $input = $request->only(["first_name", "last_name", "status_user_id", "role_id", "email", "password", "phone", "whatsapp", "cpf", "hash_id"]);
                $user = $this->userService->store($input);

                $input = $request->only(["corporate_name", "cnpj", "address", "number", "complement", "district", "city", "cep", "state_id", "user_id"]);
                $userCorporate = $this->userCorporateService->store($input, $user->id);
                
                //$response = $this->storeService->storeMagento($user->id);
                //$storeId = $response['loja_id'];
                $storeId = 1;
                
                $input = $request->only(["commission", "store_id", "user_id"]);
                $userStore = $this->userStoreService->store($input, $storeId, $user->id);

                $input = $request->only(["user_id"]);
                $userBankData = $this->userBankService->store($user->id);

            }else{
                $input = $request->only(["first_name", "last_name", "status_user_id", "role_id", "email", "password", "hash_id"]);
                $user = $this->userService->store($input);
            }
            
            $status = $this->statusUserService->findById($request['status_user_id']);
            $role = $this->roleService->findById($request['role_id']);
            
            $message = 'Adicionou o '.$role['display_name'].' '.$user->first_name . ' ' . $user->last_name. '  com o status ' . $status['name'];

            $data = array(
                'user_id' => Auth::user()->id,
                'message' => $message
            );

            $log = $this->logService->store($data);

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

    public function update($id, UserRequest $request)
    {
        try {
            //Gate::authorize('update', User::findOrFail($id));

            $user = $this->userService->findById($id);
            $status = $this->statusUserService->findById($user->status_user_id);
            $role = $this->roleService->findById($user->role_id);
            
            $message = 'Editou o '.$role['display_name'].' '.$user->first_name . ' ' . $user->last_name. '  com o status ' . $status['name'];
            
            $data = array(
                'user_id' => Auth::user()->id,
                'message' => $message
            );

            $input = $request->only(["first_name", "last_name", "password", "role_id", "email"]);
            $this->userService->update($id, $input);

            $log = $this->logService->store($data);

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
