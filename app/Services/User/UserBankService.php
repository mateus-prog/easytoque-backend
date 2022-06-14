<?php

namespace App\Services\User;

use App\Repositories\Elouquent\userRepository;
use App\Repositories\Elouquent\userBankRepository;
use App\Repositories\Elouquent\bankRepository;
use Exception;

class UserBankService
{
    public function __construct()
    {
        $this->userRepository = new userRepository();
        $this->userBankRepository = new userBankRepository();
        $this->bankRepository = new bankRepository();
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
        $bankUser = $this->userBankRepository->findByFieldWhereReturnObject('user_id', '=', $userId);
        
        $bank = $this->bankRepository->findById($bankUser[0]->bank_id);
        $bankUser[0]->bank_id = $bank->name . ' (cód: '. $bank->code.')';
        
        return $bankUser;
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
        $user = $this->userBankRepository->findById($id);

        $this->userBankRepository->destroy($user);
    }

}
