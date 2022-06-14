<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserService;
use App\Services\User\UserBankService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class UserBankController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $userService;
    protected $userBankService;
    
    public function __construct(
        UserService $userService, 
        UserBankService $userBankService
    )
    {
        $this->userService = $userService;
        $this->userBankService = $userBankService;
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

            $request['password'] = Hash::make($request['password']);

            $input = $request->only(["phone", "whatsapp", "password"]);
            $this->userService->update($id, $input);

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
