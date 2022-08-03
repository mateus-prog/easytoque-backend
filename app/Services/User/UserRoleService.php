<?php

namespace App\Services\User;

use App\Repositories\Elouquent\UserRoleRepository;
use Exception;

class UserRoleService
{
    public function __construct()
    {
        $this->userRoleRepository = new UserRoleRepository();
    }

    public function store(array $request)
    {
        try { 
            return $this->userRoleRepository->store($request);
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
            return $this->userRoleRepository->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function getUser($userId)
    {
        return $this->userRoleRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'id');
    }

}
