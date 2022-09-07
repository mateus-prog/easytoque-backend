<?php

namespace App\Services\User;

use App\Repositories\Elouquent\UserRepository;
use App\Repositories\Elouquent\StatusUserRepository;
use App\Repositories\Elouquent\RolesRepository;
use App\Repositories\Elouquent\UserStoreRepository;
use App\Repositories\Elouquent\UserCorporateRepository;

use Illuminate\Support\Facades\Hash;
use Exception;

class UserService
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->statusUserRepository = new StatusUserRepository();
        $this->rolesRepository = new RolesRepository();
        $this->userStoreRepository = new UserStoreRepository();
        $this->userCorporateRepository = new UserCorporateRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->userRepository->all();
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function getUsersByRole($roleId)
    {

        $users = $this->userRepository->findByFieldWhereReturnObject('role_id', '=', $roleId);
        
        foreach($users as $user){
            $user->name = $user->first_name . ' ' . $user->last_name;
            $user->status_user_id = $this->statusUserRepository->findById($user->status_user_id);
            $user->status = $user->status_user_id->status;
            $user->status_user_id = $user->status_user_id->name;

            $user->role_id = $this->rolesRepository->findById($roleId);
            $user->role_id = $user->role_id->display_name;

            if($roleId == 4){
                $store = $this->userStoreRepository->findByFieldWhereReturnArray('user_id', '=', $user->id, 'commission');
                $user->commission = str_replace('.', ',', $store[0]['commission']);
                
                $corporate = $this->userCorporateRepository->findByFieldWhereReturnArray('user_id', '=', $user->id, 'cnpj, state_id');
                $user->cnpj = $corporate[0]['cnpj'];
                //$user->state_id = $corporate[0]['state_id'];
            }
        }

        return $users;
    }

    public function store(array $request)
    {
        try { 
            $request['password'] = Hash::make($request['password']);
            $request['hash_id'] = str_replace('/', '', Hash::make($request['email']));
            return $this->userRepository->store($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function findByHash(string $hash)
    {
        return $this->userRepository->findByFieldWhereReturnArray('hash_id', '=', $hash, 'id');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getUserByUser(int $id)
    {
        return $this->userRepository->findByFieldWhereReturnArray('id', '=', $id, 'id, first_name, last_name, email, phone, whatsapp');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        return $this->userRepository->findById($id);
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
            if(isset($request['password'])){ $request['password'] = Hash::make($request['password']); }

            return $this->userRepository->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserActive($id, $request)
    {
        try {
            $request['status_user_id'] = 1;

            return $this->userRepository->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserBlocked($id, $request)
    {
        try {
            $request['status_user_id'] = 2;

            return $this->userRepository->update($id, $request);
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
        $user = $this->userRepository->findById($id);

        $this->userRepository->destroy($user);
    }

}
