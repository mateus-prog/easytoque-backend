<?php

namespace App\Services\User;

use App\Repositories\Elouquent\UserCorporateRepository;
use App\Services\State\StateService;
use Exception;

class UserCorporateService
{
    public function __construct()
    {
        $this->userCorporateRepository = new UserCorporateRepository();
        $this->stateService = new StateService();
    }

    public function store(array $request, int $userId)
    {
        try {
            $request['user_id'] = $userId;
            return $this->userCorporateRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function getUserCorporateByUser($userId)
    {
        $userCorporate = $this->userCorporateRepository->findByFieldWhereReturnObject('user_id', '=', $userId);
        
        $state = $this->stateService->findById($userCorporate[0]->state_id);
        $userCorporate[0]->state_id = $state->name;

        return $userCorporate;
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function getUserCorporateEditByUser($userId)
    {
        return $this->userCorporateRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'corporate_name, cnpj, id');
    }

    /**
     * Selecione o cnpj para saber o usuario
     * @param  int  $cnpj
     * @return array
    */
    public function checkCnpj($cnpj)
    {
        return $this->userCorporateRepository->findByFieldWhereReturnArray('cnpj', '=', $cnpj, 'user_id');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        return $this->userCorporateRepository->findById($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->findById($id);
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
            return $this->userCorporateRepository->update($id, $request);
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
        $this->userCorporateRepository->delete($id);
    }

}
