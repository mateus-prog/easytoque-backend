<?php

namespace App\Services\Role;

use App\Repositories\Elouquent\RolesRepository;
use Exception;

class RoleService
{
    public function __construct()
    {
        $this->rolesRepository = new RolesRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all()
    {
        return $this->rolesRepository->all();
    }

    /**
     * Selecione os usuarios conforme o role
     * @param  int  $id
     * @return array
    */
    public function findById($roleId)
    {
        return $this->rolesRepository->findById($roleId);
    }
}