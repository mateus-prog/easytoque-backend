<?php

namespace App\Services\Request;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\RequestRepository;
use App\Repositories\Elouquent\StatusRequestRepository;
use App\Repositories\Elouquent\UserCorporateRepository;
use App\Repositories\Elouquent\UserBankRepository;
use App\Repositories\Elouquent\BankRepository;
use App\Repositories\Elouquent\ReasonRepository;
use App\Services\Upload\UploadService;
use App\Helpers\Format;

use Illuminate\Support\Facades\Auth;

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
        $this->uploadService = new UploadService();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        $requests = $this->requestRepository->all();
        foreach($requests as $request){
            $request = $this->traitReturnDisplay($request, $request->user_id);
        }
        return $requests;
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function getByUser()
    {
        $user = Auth::user();
        $requests = $this->requestRepository->findByFieldWhereReturnObject('user_id', '=', $user->id, 'id, value, user_id, status_request_id, created_at, url_proof, url_invoice');
        
        foreach($requests as $request){
            $request = $this->traitReturnDisplay($request, $request->user_id);
        }

        return $requests;
    }

    public function traitReturnDisplay($request, $userId)
    {
        $user = $this->userRepository->findById($userId);

        $statusRequest = $this->statusRequestRepository->findById($request->status_request_id);
        $request->status_name = $statusRequest->name;
        $request->color = $statusRequest->color;

        $userCorporate = $this->userCorporateRepository->findByFieldWhereReturnObject('user_id', '=', $request->user_id);
        $request->cnpj = $userCorporate[0]->cnpj;

        $userBank = $this->userBankRepository->findByFieldWhereReturnObject('user_id', '=', $request->user_id);
    
        $bank = $this->bankRepository->findById($userBank[0]->bank_id);
        $request->bank_id = $bank->name . ' (cód: '. $bank->code.')';

        $request->agency = $userBank[0]->agency;
        $request->pix = $userBank[0]->pix;
        $request->checking_account = $userBank[0]->checking_account;
        
        $request->hash_id = $user->hash_id;

        $request->user_id = $userId;
        $request->user_name = $user->first_name . ' ' . $user->last_name;
        
        $reason = $this->reasonRepository->findByFieldWhereReturnObject('request_id', '=', $request->id);
        $request->reason = empty($reason[0]) ? '' : $reason[0]->reason;

        $request->url_invoice = $request->url_invoice != '' ? $this->uploadService->pathFile('storage/'.$request->url_invoice) : '';  
        $request->url_proof = $request->url_proof != '' ? $this->uploadService->pathFile('storage/'.$request->url_proof) : '';  
        
        return $request;
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

        $request = $this->traitReturnDisplay($request, $request->user_id);

        return $request;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->requestRepository->findById($id);
    }

    public function store(array $request)
    {
        try { 
            return $this->requestRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, $request)
    {
        try {
            return $this->requestRepository->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}