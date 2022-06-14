<?php

namespace App\Services\Request;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\RequestRepository;
use App\Repositories\Elouquent\StatusRequestRepository;
use App\Repositories\Elouquent\UserCorporateRepository;
use App\Repositories\Elouquent\UserBankRepository;
use App\Repositories\Elouquent\BankRepository;
use App\Repositories\Elouquent\ReasonRepository;
use App\Helpers\Format;
use Exception;

class RequestService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->statusRequestRepository = new StatusRequestRepository();
        $this->requestRepository = new RequestRepository();
        $this->userCorporateRepository = new UserCorporateRepository();
        $this->userBankRepository = new UserBankRepository();
        $this->bankRepository = new BankRepository();
        $this->reasonRepository = new ReasonRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        $requests = $this->requestRepository->all();

        foreach($requests as $request){
            $user = $this->userRepository->findById($request->user_id);

            $statusRequest = $this->statusRequestRepository->findById($request->status_request_id);
            $request->status_request_id = $statusRequest->name;
            $request->color = $statusRequest->color;

            $userCorporate = $this->userCorporateRepository->findByFieldWhereReturnObject('user_id', '=', $request->user_id);
            $request->cnpj = $userCorporate[0]->cnpj;

            $userBank = $this->userBankRepository->findByFieldWhereReturnObject('user_id', '=', $request->user_id);
        
            $bank = $this->bankRepository->findById($userBank[0]->bank_id);
            $request->bank_id = $bank->name . ' (cód: '. $bank->code.')';

            $request->agency = $userBank[0]->agency;
            $request->checking_account = $userBank[0]->checking_account;
            
            $request->hash_id = $user->hash_id;

            $request->user_id = $user->first_name . ' ' . $user->last_name;
        }

        return $requests;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        $request = $this->requestRepository->findById($id);
        $request->value = Format::valueBR($request->value);

        $user = $this->userRepository->findById($request->user_id);

        $statusRequest = $this->statusRequestRepository->findById($request->status_request_id);
        $request->status_request_id = $statusRequest->name;
        $request->color = $statusRequest->color;

        $userCorporate = $this->userCorporateRepository->findByFieldWhereReturnObject('user_id', '=', $request->user_id);
        $request->cnpj = $userCorporate[0]->cnpj;

        $userBank = $this->userBankRepository->findByFieldWhereReturnObject('user_id', '=', $request->user_id);
    
        $bank = $this->bankRepository->findById($userBank[0]->bank_id);
        $request->bank_id = $bank->name . ' (cód: '. $bank->code.')';

        $request->agency = $userBank[0]->agency;
        $request->checking_account = $userBank[0]->checking_account;
        
        $request->hash_id = $user->hash_id;

        $request->user_id = $user->first_name . ' ' . $user->last_name;

        /*$reason = $this->reasonRepository->findByFieldWhereReturnObject('request_id', '=', $id);
        if(empty($reason)){
            $request->reason = '';
        }else{
            $request->reason = $reason[0]->reason;
        }*/

        return $request;
    }
}