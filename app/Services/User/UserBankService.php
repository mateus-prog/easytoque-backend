<?php

namespace App\Services\User;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\UserBankRepository;
use App\Repositories\Elouquent\BankRepository;
use Exception;

class UserBankService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userBankRepository = new UserBankRepository();
        $this->bankRepository = new BankRepository();
    }

    public function store(int $userId)
    {
        try {
            $request['user_id'] = $userId;
            return $this->userBankRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function getUserBankByUser($userId)
    {
        $user = $this->userRepository->findById($userId);
        $bankUser = $this->userBankRepository->findByFieldWhereReturnObject('user_id', '=', $userId);
        
        $bank = $this->bankRepository->findById($bankUser[0]->bank_id);
        $bankUser[0]->bank_id = $bank->name . ' (cód: '. $bank->code.')';
        $bankUser[0]->hash_id = $user['hash_id'];
        
        return $bankUser;
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function getUserBankEditByUser($userId)
    {
        return $this->userBankRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'bank_id, agency, agency_digit, checking_account, checking_account_digit, pix');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        return $this->userBankRepository->findById($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->userBankRepository->findById($id);
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
        $userBank = $this->userBankRepository->findByFieldWhereReturnArray('user_id', '=', $id, 'id');
        $id = $userBank[0]['id'];

        try {
            return $this->userBankRepository->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userBankRepository->delete($id);
    }

}
