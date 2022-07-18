<?php

namespace App\Services\User;

use App\Repositories\Elouquent\UserStoreRepository;
use Exception;

class UserStoreService
{
    public function __construct()
    {
        $this->userStoreRepository = new UserStoreRepository();
    }

    public function store(array $request, int $userId)
    {
        try {
            $request['user_id'] = $userId;
            return $this->userStoreRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        return $this->userStoreRepository->findById($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->userStoreService->findById($id);
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
            return $this->userStoreRepository->update($id, $request);
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
        $user = $this->userStoreRepository->findById($id);

        $this->userStoreRepository->destroy($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUserStoreByUserComission($userId){
        $store = $this->userStoreRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'commission');
        return $store[0];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUserStoreByUser($userId){
        $store = $this->userStoreRepository->findByFieldWhereReturnArray('user_id', '=', $userId, 'id');
        return $store[0]['id'];
    }

}
